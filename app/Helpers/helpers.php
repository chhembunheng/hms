<?php

use App\Models\RoomStatus;
use Carbon\Carbon;
use Spatie\Image\Image;
use Illuminate\Support\Str;
use Spatie\Image\Enums\Fit;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Imagick\Driver;

if (!function_exists('webp_variants')) {
    function webp_variants(
        string $path,
        string|array|null $presetOrWidths = null,
        ?int $height = null,
        int $quality = 80,
        bool $exactHeight = false
    ): array {
        Log::info("webp_variants: start path={$path}");

        try {
            if (trim($path) === '') {
                Log::warning("webp_variants: empty path received");
                return ['srcset' => '', 'variants' => [], 'fallback' => '', 'width' => 0];
            }
            if (trim(strtolower(substr($path, 0, 7))) === 'uploads') {
                $path = 'storage/' . ltrim($path, '/');
            }

            $presets = [
                'xs' => [320, 480],
                'sm' => [320, 480, 640, 768],
                'md' => [768, 1024, 1280],
                'lg' => [1024, 1280, 1440],
                'xl' => [1280, 1600, 1920],
                '2xl' => [1600, 1920, 2560],
                'square' => [320, 480, 640, 768, 1024],
                'portrait' => [320, 480, 640, 768],
                'banner' => [640, 1024, 1440, 1920, 2560],
                'product' => [480, 768, 1080, 1440],
                'portfolio' => [768, 1280, 1600, 1920, 2560],
                'b' => [64, 128, 256, 512, 1024],
                'bxs' => [64, 128, 256, 342],
            ];

            if (is_array($presetOrWidths)) {
                $widths = array_values(array_filter($presetOrWidths, fn($w) => is_int($w) && $w > 0));
            } elseif (is_string($presetOrWidths) && isset($presets[$presetOrWidths])) {
                $widths = $presets[$presetOrWidths];
            } else {
                $widths = [480, 768, 1024, 1280, 1920];
            }

            $disk = Storage::disk('public');
            $cacheBase = 'webp-cache';
            $isRemote = filter_var($path, FILTER_VALIDATE_URL) !== false;
            $fallback = $isRemote ? $path : asset(ltrim($path, '/'));
            $sourceKey = $isRemote ? md5($path) : md5(public_path(ltrim($path, '/')));
            $ext = strtolower(pathinfo(parse_url($path, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg');

            if (!$disk->exists($cacheBase)) {
                $disk->makeDirectory($cacheBase);
                Log::info("webp_variants: created cache dir {$cacheBase}");
            }

            if ($isRemote) {
                $tmpName = "{$cacheBase}/tmp-{$sourceKey}.{$ext}";
                $tmpPath = $disk->path($tmpName);

                if (!is_file($tmpPath)) {
                    Log::info("webp_variants: downloading remote file {$path}");
                    $ch = curl_init($path);
                    curl_setopt_array($ch, [
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_CONNECTTIMEOUT => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_SSL_VERIFYPEER => true,
                        CURLOPT_SSL_VERIFYHOST => 2,
                        CURLOPT_USERAGENT => 'WebVariantBot/1.0',
                    ]);
                    $file = curl_exec($ch);
                    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    $err = curl_error($ch);
                    curl_close($ch);

                    if ($file === false || $code >= 400) {
                        Log::error("webp_variants: remote fetch failed code={$code} err={$err} url={$path}");
                        return ['srcset' => '', 'variants' => [], 'fallback' => $fallback, 'width' => 0];
                    }

                    $disk->put($tmpName, $file);
                    Log::info("webp_variants: downloaded remote file saved to {$tmpName}");
                }

                $localSource = $disk->path($tmpName);
            } else {
                $localSource = public_path(ltrim($path, '/'));
                if (!is_file($localSource)) {
                    Log::error("webp_variants: source file missing at {$localSource}");
                    return ['srcset' => '', 'variants' => [], 'fallback' => $fallback, 'width' => 0];
                }
            }

            $targetFormat = 'webp';

            $variantUrls = [];
            $srcsetParts = [];

            foreach ($widths as $w) {
                $variantRel = "{$cacheBase}/{$sourceKey}-w{$w}" . ($height ? "-h{$height}" : '') . ".{$targetFormat}";
                $variantAbs = $disk->path($variantRel);

                if (!is_file($variantAbs)) {
                    try {
                        Log::info("webp_variants: generating {$variantRel}");
                        @mkdir(dirname($variantAbs), 0777, true);

                        $img = Image::load($localSource)
                            ->format($targetFormat)
                            ->quality($quality);

                        if ($height) {
                            $fit = $exactHeight ? Fit::Crop : Fit::FillMax;
                            $img->fit($fit, $w, $height);
                        } else {
                            $img->width($w);
                        }

                        $tmpOut = $variantAbs . '.tmp';
                        $img->save($tmpOut);
                        @rename($tmpOut, $variantAbs);

                        Log::info("webp_variants: saved variant {$variantRel}");
                    } catch (\Throwable $e) {
                        Log::error("webp_variants: generation failed {$path} @{$w}px => {$targetFormat}: " . $e->getMessage());
                        continue;
                    }
                }

                if (is_file($variantAbs)) {
                    $url = asset('storage/' . $variantRel);
                    $variantUrls[] = $url;
                    $srcsetParts[] = $url . " {$w}w";
                }
            }

            $srcset = implode(', ', $srcsetParts);
            Log::info("webp_variants: done path={$path}, generated " . count($variantUrls) . " variants");

            return [
                'srcset'   => $srcset,
                'variants' => $variantUrls,
                'fallback' => $fallback,
                'width'    => max($widths),
            ];
        } catch (\Throwable $e) {
            Log::error("webp_variants fatal error: " . $e->getMessage());
            return ['srcset' => '', 'variants' => [], 'fallback' => $path, 'width' => 0];
        }
    }
}

if (!function_exists('webpasset')) {
    function webpasset($path = '', $height = null, $absoluteWebpPath = null, $quality = 80)
    {
        if (empty($path)) {
            return '';
        }

        if ($absoluteWebpPath && file_exists($absoluteWebpPath)) {
            return asset(str_replace(public_path(), '', $absoluteWebpPath));
        }

        $extension = strtolower(pathinfo(parse_url($path, PHP_URL_PATH), PATHINFO_EXTENSION));
        if ($extension === 'webp') {
            return asset($path);
        }
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            try {
                $extension = $extension ?: 'jpg';
                $fileName = md5($path) . '.' . $extension;
                $tempPath = public_path('storage/temp/' . $fileName);
                if (!file_exists($tempPath)) {
                    if (!is_dir(public_path('storage/temp'))) {
                        mkdir(public_path('storage/temp'), 0777, true);
                    }

                    $ch = curl_init($path);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                    $file = curl_exec($ch);
                    curl_close($ch);

                    if ($file === false) {
                        return '';
                    }
                    if (!is_dir($absoluteWebpPath) && !file_exists(dirname($absoluteWebpPath))) {
                        $dirPath = dirname($absoluteWebpPath);
                        mkdir($dirPath, 0777, true);
                    }

                    file_put_contents($tempPath, $file);
                }
                $img = Image::load($tempPath)
                    ->format('webp')
                    ->quality($quality);
                if ($height) {
                    $img->height($height);
                }
                $img->save($absoluteWebpPath);
                unlink($tempPath);
                return asset(str_replace(public_path(), '', $absoluteWebpPath));
            } catch (\Throwable $e) {
                Log::warning("WebP conversion failed for {$path}: " . $e->getMessage());
                return asset($path);
            }
        }

        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $absolutePath = public_path($path);
        $absoluteWebpPath = public_path(str_replace('.' . $extension, '.webp', $path));
        if ($height) {
            $absoluteWebpPath = public_path(str_replace('.' . $extension, '-h' . $height . '.webp', $path));
        }
        if (!file_exists($absoluteWebpPath) && file_exists($absolutePath)) {
            try {
                $targetDir = dirname($absoluteWebpPath);
                if (is_writable($targetDir) || (!file_exists($targetDir) && @mkdir($targetDir, 0777, true))) {
                    $img = Image::load($absolutePath)
                        ->format('webp')
                        ->quality(80);

                    if ($height) {
                        $img->height($height);
                    }

                    $img->save($absoluteWebpPath);
                }
            } catch (\Throwable $e) {
                Log::warning("WebP conversion failed for {$path}: " . $e->getMessage());
                return asset($path);
            }
        }
        if (file_exists($absoluteWebpPath)) {
            return asset(str_replace(public_path(), '', $absoluteWebpPath));
        }

        return asset($path);
    }
}

if (! function_exists('yesNo')) {
    function yesNo($instance)
    {
        return badge($instance ? 'success' : 'danger', $instance ? 'Yes' : 'No');
    }
}

if (! function_exists('formate_date')) {
    function formate_date($date)
    {
        return parse_date_input($date);
    }
}

if (! function_exists('badge')) {
    function badge(?string $status = '', ?string $text = '')
    {
        $status = str_replace(['_', '-'], ' ', strtolower(trim((string)($status ?? ''))));
        $text = ucwords(trim((string)($text ?? '')));
        $statuses = [
            'success' => 'badge bg-success bg-opacity-10 text-success',
            'pending' => 'badge bg-warning bg-opacity-10 text-warning',
            'completed' => 'badge bg-success bg-opacity-10 text-success',
            'approved' => 'badge bg-success bg-opacity-10 text-success',
            'rejected' => 'badge bg-danger bg-opacity-10 text-danger',
            'canceled' => 'badge bg-danger bg-opacity-10 text-danger',
            'paid' => 'badge bg-success bg-opacity-10 text-success',
            'unpaid' => 'badge bg-warning bg-opacity-10 text-warning',
            'delivered' => 'badge bg-success bg-opacity-10 text-success',
            'undelivered' => 'badge bg-warning bg-opacity-10 text-warning',
            'active' => 'badge bg-success bg-opacity-10 text-success',
            'inactive' => 'badge bg-secondary bg-opacity-10 text-secondary',
            'enabled' => 'badge bg-success bg-opacity-10 text-success',
            'disabled' => 'badge bg-secondary bg-opacity-10 text-secondary',
            'locked' => 'badge bg-danger bg-opacity-10 text-danger',
            'unlocked' => 'badge bg-success bg-opacity-10 text-success',
            'verified' => 'badge bg-success bg-opacity-10 text-success',
            'unverified' => 'badge bg-warning bg-opacity-10 text-warning',
            'published' => 'badge bg-success bg-opacity-10 text-success',
            'unpublished' => 'badge bg-secondary bg-opacity-10 text-secondary',
            'featured' => 'badge bg-primary bg-opacity-10 text-primary',
            'not featured' => 'badge bg-secondary bg-opacity-10 text-secondary',
            'hot' => 'badge bg-danger bg-opacity-10 text-danger',
            'not hot' => 'badge bg-secondary bg-opacity-10 text-secondary',
            'new' => 'badge bg-info bg-opacity-10 text-info',
            'old' => 'badge bg-secondary bg-opacity-10 text-secondary',
            'popular' => 'badge bg-primary bg-opacity-10 text-primary',
            'not popular' => 'badge bg-secondary bg-opacity-10 text-secondary',
            'top' => 'badge bg-primary bg-opacity-10 text-primary',
            'not top' => 'badge bg-secondary bg-opacity-10 text-secondary',
            'best' => 'badge bg-primary bg-opacity-10 text-primary',
            'not best' => 'badge bg-secondary bg-opacity-10 text-secondary',
            'recommended' => 'badge bg-primary bg-opacity-10 text-primary',
            'not recommended' => 'badge bg-secondary bg-opacity-10 text-secondary',
            'na' => 'badge bg-secondary bg-opacity-10 text-secondary',
            'secondary' => 'badge bg-secondary bg-opacity-10 text-secondary',
            'primary' => 'badge bg-primary bg-opacity-10 text-primary',
            'info' => 'badge bg-info bg-opacity-10 text-info',
            'warning' => 'badge bg-warning bg-opacity-10 text-warning',
            'danger' => 'badge bg-danger bg-opacity-10 text-danger',
            'light' => 'badge bg-light bg-opacity-10 text-light',
            'dark' => 'badge bg-dark bg-opacity-10 text-dark',
            'mid' => 'badge bg-secondary bg-opacity-10 text-secondary',
            'senior' => 'badge bg-purple bg-opacity-10 text-purple',
            'urgent' => 'badge bg-danger bg-opacity-10 text-danger',
            'regular' => 'badge bg-secondary bg-opacity-10 text-secondary',
            'full time' => 'badge bg-success bg-opacity-10 text-success',
            'part time' => 'badge bg-info bg-opacity-10 text-info',
            'internship' => 'badge bg-primary bg-opacity-10 text-primary',
            'medium' => 'badge bg-warning bg-opacity-10 text-warning',
            'high' => 'badge bg-danger bg-opacity-10 text-danger',
            'low' => 'badge bg-success bg-opacity-10 text-success',
            'closed' => 'badge bg-danger bg-opacity-10 text-danger',
            'open' => 'badge bg-success bg-opacity-10 text-success',
            'available' => 'badge bg-success bg-opacity-10 text-success',
            'unavailable' => 'badge bg-danger bg-opacity-10 text-danger',
            'occupied' => 'badge bg-warning bg-opacity-10 text-warning',
            'reserved' => 'badge bg-info bg-opacity-10 text-info',
            __('global.yes') => 'badge bg-success bg-opacity-10 text-success',
            __('global.no') => 'badge bg-danger bg-opacity-10 text-danger',
            'confirmed' => 'badge bg-warning bg-opacity-10 text-warning',
            'checked in' => 'badge bg-success bg-opacity-10 text-success',
            'checked out' => 'badge bg-info bg-opacity-10 text-info',
            'cancelled' => 'badge bg-danger bg-opacity-10 text-danger',
            'national' => 'badge bg-success bg-opacity-10 text-success',
            'international' => 'badge bg-info bg-opacity-10 text-info',
        ];

        $normalizedStatus = strtolower(trim((string)($status ?? '')));
        $displayText = ($text !== null && $text !== '') ? $text : ucwords($normalizedStatus);
        if (empty(trim($displayText))) {
            $displayText = 'N/A';
        }
        $class = $statuses[$normalizedStatus] ?? $statuses['na'];
        return '<span class="' . $class . '">' . $displayText . '</span>';
    }
}

if (!function_exists('json')) {
    function json($data)
    {
        header('Content-Type: application/json charset=utf-8');
        echo json_encode($data);
        exit;
    }
}

if (!function_exists('can')) {
    function can($expression)
    {
        $expression = md5(trim($expression));
        $administrator = session('administrator');
        if ($administrator === true) {
            return true;
        }
        $cache = cache('permissions');
        $permissions = $cache->permissions ?? [];
        $access = session('access');
        $expression = trim($expression);
        if (in_array($expression, array_keys($permissions))) {
            if (in_array($expression, array_keys($access))) {
                return true;
            }
            return false;
        }
        return true;
    }
}

if (! function_exists('date_period')) {
    function date_period($start_date, $end_date, $timezone = 'UTC')
    {
        $start_date = date('Y-m-d', strtotime($start_date));
        $end_date = date('Y-m-d', strtotime($end_date));
        $timezone = new DateTimeZone($timezone);
        $begin = new DateTime($start_date, $timezone);
        $end = new DateTime($end_date, $timezone);
        $interval = new DateInterval('P1D');
        $periods = new DatePeriod($begin, $interval, $end);
        $monthsArray = [];
        $m = 0;
        $d = 0;
        $w = 0;
        $days = 0;
        $text = '';
        $text_kh = '';
        $daysBefore = 0;
        $daysAfter = 0;
        $data = [];
        foreach ($periods as $period) {
            if ($period->format('Y-m-t') < $end_date && $period->format('Y-m-01') > $start_date) {
                $monthsArray[$period->format('Y-m-t')] = $period->format('t');
            }
        }
        $first = min(array_keys($monthsArray));
        $last = max(array_keys($monthsArray));
        $first = date('Y-m-01', strtotime($first));
        $before = new DateTime($first, $timezone);
        $after = new DateTime($last, $timezone);
        $after->modify('+1 day');
        $m = count($monthsArray);
        $daysBefore = $begin->diff($before)->days;
        $daysAfter = $after->diff($end)->days;
        $d += $daysBefore + $daysAfter;
        $days = $d + array_sum($monthsArray);
        $w = (int) round($days / 7);
        foreach ($monthsArray as $key => $day) {
            $data[strtotime($key)] = date('F', strtotime($key)) . ': ' . ($day > 1 ? $day . ' days' : $day . ' day');
        }
        array_unshift($data, date('F', strtotime($start_date)) . ': ' . $daysBefore . ' days');
        array_push($data, date('F', strtotime($end_date)) . ': ' . $daysAfter . ' days');
        if ($m > 0) {
            $text .= $m . ($m > 1 ? ' Months ' : ' Month ');
            $text_kh .= $m . ' ខែ ';
        }
        if ($d > 0) {
            $text .= $d . ($d > 1 ? ' Days ' : ' Day ');
            $text_kh .= $d . ' ថ្ងៃ ';
        }
        return [
            'm' => $m,
            'd' => $d,
            'w' => $w,
            'days' => $days,
            'text' => trim($text),
            'text_kh' => trim($text_kh),
            'begin' => $start_date,
            'end' => $end_date,
            'timezone' => $timezone,
            'data' => $data,
        ];
    }
}

if (!function_exists('slug')) {
    function slug($string, $separator = '-')
    {
        if (empty($string)) {
            return null;
        }
        $slug = strtolower($string);
        $slug = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $slug);
        $slug = preg_replace('/[^a-z0-9]+/i', $separator, $slug);
        $slug = preg_replace('/' . preg_quote($separator, '/') . '+/', $separator, $slug);
        $slug = trim($slug, $separator);
        return $slug;
    }
}

if (! function_exists('clipboard')) {
    function clipboard($instance)
    {
        return '<i class="ph ph-copy me-3 text-primary cursor-pointer" clipboard-text="' . $instance . '" onclick="copyToClipboard(event)"></i>';
    }
}

if (! function_exists('user_date')) {
    function user_date($instance)
    {
        if (empty($instance)) {
            return 'N/A';
        }
        return date(session('date_format'), strtotime($instance));
    }
}

if (! function_exists('user_datetime')) {
    function user_datetime($instance)
    {
        if (empty($instance)) {
            return 'N/A';
        }
        return date(session('datetime_format'), strtotime($instance));
    }
}

if (! function_exists('locale')) {
    function locale()
    {
        return request()->locale ?? config('app.locale');
    }
}

if (! function_exists('success')) {
    function success(array $data = [], string $message = '')
    {
        return response()->json(array_merge([
            'status' => 'success',
            'message' => $message ?: 'Operation completed successfully.'
        ], $data));
    }
}

if (! function_exists('errors')) {
    function errors(string $message = '', array $errors = [])
    {
        return response()->json(array_merge([
            'status' => 'error',
            'message' => $message ?: 'An error occurred while processing your request.'
        ], $errors));
    }
}

if (! function_exists('uploadFile')) {
    function uploadFile(UploadedFile $image, string $path, string $pathThumb = '', int $width = 200): string
    {
        $fileName = md5(time() . $image->getClientOriginalName()) . '.' . $image->extension();
        $path      = trim($path, '/');
        $pathThumb = trim($pathThumb, '/');
        $putFile   = $path . '/' . $fileName;

        if (! is_dir(storage_path('app/public/' . $pathThumb))) {
            mkdir(storage_path('app/public/' . $pathThumb), 0777, true);
        }

        $stored = Storage::disk('public')->put($putFile, file_get_contents($image->getRealPath()));
        if (! $stored || ! Storage::disk('public')->exists($putFile)) {
            Log::error("Failed to store file or file missing after upload: $putFile");
            return '';
        }

        if (! empty($pathThumb)) {
            try {
                $imageContent = Storage::disk('public')->get($putFile);
                $manager      = new ImageManager(new Driver());
                $thumbImage   = $manager->read($imageContent);
                $thumbPath    = storage_path("app/public/{$pathThumb}");

                if (! File::exists($thumbPath)) {
                    File::makeDirectory($thumbPath, 0755, true);
                }

                $thumbImage->scaleDown(width: $width)
                    ->save("{$thumbPath}/{$fileName}");
            } catch (\Throwable $e) {
                Log::error("image creation failed for $putFile: " . $e->getMessage());
            }
        }
        if (storage_path('app/public/' . $putFile)) {
            chmod(storage_path('app/public/' . $putFile), 0644);
        }
        $putFile = Str::of($putFile)->rtrim('/');
        if (trim(strtolower(substr($putFile, 0, 7))) === 'uploads') {
            $putFile = 'storage/' . ltrim($putFile, '/');
        }
        if (trim(strtolower(substr($putFile, 0, 7))) === 'uploads') {
            $putFile = 'storage/' . ltrim($putFile, '/');
        }

        return $putFile;
    }
}

if (! function_exists('uploadBase64')) {
    function uploadBase64(string $base64, string $path, string $pathThumb = '', int $width = 200): string
    {
        if (! preg_match('/^data:image\/[a-z]+;base64,/', $base64)) {
            $decoded = base64_decode($base64, true);
            if ($decoded === false) {
                return '';
            }
        } else {
            $base64  = preg_replace('/^data:image\/[a-z]+;base64,/', '', $base64);
            $decoded = base64_decode($base64, true);
            if ($decoded === false) {
                return '';
            }
        }

        $fileName = md5(time() . uniqid()) . '.png';
        $path      = trim($path, '/');
        $pathThumb = trim($pathThumb, '/');
        if (! is_dir(storage_path('app/public/' . $pathThumb))) {
            mkdir(storage_path('app/public/' . $pathThumb), 0777, true);
        }
        $putFile = $path . '/' . $fileName;

        $stored = Storage::disk('public')->put($putFile, $decoded);
        if (! $stored) {
            return '';
        }

        if (! empty($pathThumb)) {
            $manager      = new ImageManager(new Driver());
            $imageContent = Storage::disk('public')->get($putFile);
            $image        = $manager->read($imageContent);
            $image->scaleDown(width: $width)->save(storage_path('app/public/' . $pathThumb . '/' . $fileName));
        }

        if (storage_path('app/public/' . $putFile)) {
            chmod(storage_path('app/public/' . $putFile), 0644);
        }
        $putFile = Str::of($putFile)->rtrim('/');
        if (trim(strtolower(substr($putFile, 0, 7))) === 'uploads') {
            $putFile = 'storage/' . ltrim($putFile, '/');
        }

        return $putFile;
    }
}

if (! function_exists('uploadImage')) {
    function uploadImage($image, string $path, string $pathThumb = '', int $width = 200): string
    {
        if (empty($image)) {
            return '';
        }

        if ($image instanceof UploadedFile) {
            return uploadFile($image, $path, $pathThumb, $width);
        }

        if (is_string($image)) {
            return uploadBase64($image, $path, $pathThumb, $width);
        }

        return '';
    }
}

if (!function_exists('processGalleryImages')) {
    function processGalleryImages(array $images, string $path): array
    {
        return collect($images)->map(function ($image) use ($path) {
            $url = $image['url'] ?? null;

            if (empty($url)) {
                return null;
            }

            if (!preg_match('/^data:image\//', $url)) {
                return [
                    'url' => $url,
                    'alt' => $image['alt'] ?? '',
                    'label' => $image['label'] ?? '',
                ];
            }

            $filePath = uploadBase64($url, $path);
            if (empty($filePath)) {
                return null;
            }

            return [
                'url' => $filePath,
                'alt' => $image['alt'] ?? '',
                'label' => $image['label'] ?? '',
            ];
        })->filter()->toArray();
    }
}

if (!function_exists('getFileSize')) {
    function getFileSize($filePath): int
    {
        if (!$filePath) {
            return 0;
        }

        try {
            if (file_exists(public_path($filePath))) {
                return filesize(public_path($filePath));
            }

            if (Storage::exists($filePath)) {
                return Storage::size($filePath);
            }
        } catch (\Exception $e) {
            Log::warning("getFileSize: Could not get size for {$filePath}", ['error' => $e->getMessage()]);
        }

        return 0;
    }
}

if (!function_exists('get_currencies')) {
    function get_currencies(): array
    {
        return [
            'USD' => 'USD - US Dollar',
            'KHR' => 'KHR - Cambodian Riel',
        ];
    }
}

if (!function_exists('statusBadge')) {
    function statusBadge($status)
    {
        switch (strtolower($status)) {
            case 'active':
                return badge('success', 'Active');
            case 'inactive':
                return badge('danger', 'Inactive');
            case 'pending':
                return badge('warning', 'Pending');
            case 'completed':
                return badge('success', 'Completed');
            case 'canceled':
                return badge('danger', 'Canceled');
            default:
                return badge('secondary', ucfirst($status));
        }
    }
}

if (!function_exists('modelTypes')) {
    function modelTypes(): array
    {
        $models = [];
        $modelPath = app_path('Models');
        if (!is_dir($modelPath)) {
            return [];
        }

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($modelPath)
        );

        foreach ($files as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $relativePath = str_replace(app_path() . DIRECTORY_SEPARATOR, '', $file->getPathname());
                $class = 'App\\' . str_replace(['/', '\\'], '\\', substr($relativePath, 0, -4));
                if (class_exists($class)) {
                    $models[] = $class;
                }
            }
        }

        return $models;
    }
}


if (!function_exists('roomStatusBadge')) {
    function roomStatusBadge($status)
    {
        // If status is a RoomStatus model instance, use its localized name and color
        if ($status instanceof \App\Models\RoomStatus) {
            $color = $status->color ?? '#6c757d'; // Default to secondary color
            $bootstrapColor = hexToBootstrapColor($color);
            return badge($bootstrapColor, $status->localized_name);
        }

        // If status is a string, try to find the matching RoomStatus
        if (is_string($status)) {
            $roomStatus = \App\Models\RoomStatus::where('name_en', $status)
                ->orWhere('name_kh', $status)
                ->first();

            if ($roomStatus) {
                $color = $roomStatus->color ?? '#6c757d';
                $bootstrapColor = hexToBootstrapColor($color);
                return badge($bootstrapColor, $roomStatus->localized_name);
            }
        }

        // Fallback for unknown status
        return badge('secondary', ucfirst((string)$status));
    }
}

if (!function_exists('roomStatusBadgeWithColor')) {
    function roomStatusBadgeWithColor($status)
    {
        // If status is a RoomStatus model instance, use its localized name and actual color
        if ($status instanceof \App\Models\RoomStatus) {
            $color = $status->color ?? '#6c757d';
            $textColor = getContrastColor($color);
            return '<span class="badge" style="background-color: ' . $color . '; color: ' . $textColor . '; border-radius: 0.375rem; padding: 0.25rem 0.5rem; font-size: 0.75rem; font-weight: 500;">' . $status->localized_name . '</span>';
        }

        // If status is a string, try to find the matching RoomStatus
        if (is_string($status)) {
            $roomStatus = \App\Models\RoomStatus::where('name_en', $status)
                ->orWhere('name_kh', $status)
                ->first();

            if ($roomStatus) {
                $color = $roomStatus->color ?? '#6c757d';
                $textColor = getContrastColor($color);
                return '<span class="badge bg-opacity-10" style="background-color: ' . $color . '; color: ' . $textColor . '; border-radius: 0.375rem; padding: 0.25rem 0.5rem; font-size: 0.75rem; font-weight: 500;">' . $roomStatus->localized_name . '</span>';
            }
        }

        // Fallback for unknown status
        return '<span class="badge bg-secondary text-white" style="border-radius: 0.375rem; padding: 0.25rem 0.5rem; font-size: 0.75rem; font-weight: 500;">' . ucfirst((string)$status) . '</span>';
    }
}

if (!function_exists('hexToBootstrapColor')) {
    function hexToBootstrapColor($hexColor)
    {
        // Remove # if present
        $hexColor = ltrim($hexColor, '#');

        // Convert hex to RGB
        $r = hexdec(substr($hexColor, 0, 2));
        $g = hexdec(substr($hexColor, 2, 2));
        $b = hexdec(substr($hexColor, 4, 2));

        // Common hex color mappings to Bootstrap colors
        $colorMap = [
            // Success green
            '#28a745' => 'success',
            '#20c997' => 'success',
            '#17a2b8' => 'info',
            '#007bff' => 'primary',
            '#6f42c1' => 'primary',
            '#e83e8c' => 'danger',
            '#dc3545' => 'danger',
            '#fd7e14' => 'warning',
            '#ffc107' => 'warning',
            '#6c757d' => 'secondary',
            '#343a40' => 'dark',
            '#f8f9fa' => 'light',
        ];

        // Check for exact match
        if (isset($colorMap['#' . strtolower($hexColor)])) {
            return $colorMap['#' . strtolower($hexColor)];
        }

        // Simple color detection based on RGB values
        if ($r > 200 && $g > 200 && $b < 100) {
            return 'warning'; // Yellow
        } elseif ($r > 200 && $g < 100 && $b < 100) {
            return 'danger'; // Red
        } elseif ($r < 100 && $g > 200 && $b < 100) {
            return 'success'; // Green
        } elseif ($r < 100 && $g < 100 && $b > 200) {
            return 'primary'; // Blue
        }

        return 'secondary'; // Default fallback
    }
}

if (!function_exists('getContrastColor')) {
    function getContrastColor($hexColor)
    {
        // Remove # if present
        $hexColor = ltrim($hexColor, '#');

        // Convert to RGB
        $r = hexdec(substr($hexColor, 0, 2));
        $g = hexdec(substr($hexColor, 2, 2));
        $b = hexdec(substr($hexColor, 4, 2));

        // Calculate luminance
        $luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;

        // Return white text for dark backgrounds, black text for light backgrounds
        return $luminance > 0.5 ? '#000000' : '#ffffff';
    }
}


if (!function_exists('paymentMethods')) {
    function paymentMethods(): array
    {
        return [
            'cash_usd' => 'Cash (USD - $)',
            'cash_khr' => 'Cash (KHR - ៛)',
            'khqr' => 'Bakong KHQR (ABA / ACLEDA / Wing / Canadia)',
            'card' => 'Credit / Debit Card (Visa, MasterCard)',
            'bank_transfer' => 'Bank Transfer (ABA / Wing)',
        ];
    }
}

if (!function_exists('active_exchange_rate')) {
    /**
     * Get the active USD to KHR exchange rate
     */
    function active_exchange_rate(): float
    {
        try {
            $rate = \App\Models\ExchangeRate::where('from_currency', 'USD')
                ->where('to_currency', 'KHR')
                ->where('is_active', true)
                ->orderBy('effective_date', 'desc')
                ->value('rate');

            return $rate ? (float)$rate : 4100.00;
        } catch (\Throwable $e) {
            return 4100.00;
        }
    }
}

if (!function_exists('usd_to_khr')) {
    /**
     * Convert USD amount to KHR
     */
    function usd_to_khr(float|int|string|null $amount, ?float $rate = null): float
    {
        $rate = $rate ?: active_exchange_rate();
        return round(((float) $amount) * $rate, -2); // Round to nearest 100 riels
    }
}

if (!function_exists('khr_to_usd')) {
    /**
     * Convert KHR amount to USD
     */
    function khr_to_usd(float|int|string|null $amount, ?float $rate = null): float
    {
        $rate = $rate ?: active_exchange_rate();
        return $rate > 0 ? round(((float) $amount) / $rate, 2) : 0.00;
    }
}

if (!function_exists('format_usd')) {
    /**
     * Format amount in USD
     */
    function format_usd(float|int|string|null $amount): string
    {
        return '$' . number_format((float) $amount, 2);
    }
}

if (!function_exists('format_khr')) {
    /**
     * Format amount in KHR
     */
    function format_khr(float|int|string|null $amount): string
    {
        return number_format((float) $amount, 0) . ' ៛';
    }
}

if (!function_exists('format_dual_currency')) {
    /**
     * Format dual currency representation: $XX.XX (XX,XXX ៛)
     */
    function format_dual_currency(float|int|string|null $usdAmount, ?float $rate = null): string
    {
        $usd = (float) $usdAmount;
        $khr = usd_to_khr($usd, $rate);
        return format_usd($usd) . ' (' . format_khr($khr) . ')';
    }
}

if (!function_exists('cambodian_visa_types')) {
    /**
     * List of Cambodian visa types for international guests
     */
    function cambodian_visa_types(): array
    {
        return [
            'T' => 'Tourist Visa (T) - ទេសចរណ៍',
            'E' => 'Ordinary / Business Visa (E) - ធម្មតា/ពាណិជ្ជកម្ម',
            'K' => 'Khmer Special Visa (K) - ពិសេសខ្មែរ',
            'B' => 'Official Visa (B) - ផ្លូវការ',
            'A' => 'Diplomatic Visa (A) - ការទូត',
            'C' => 'Courtesy Visa (C) - គួរសម',
        ];
    }
}

if (!function_exists('format_date')) {
    /**
     * Format a date always as dd-mm-yyyy (d-m-Y)
     */
    function format_date(\DateTimeInterface|string|null $date, string $fallback = '-'): string
    {
        if (empty($date)) {
            return $fallback;
        }

        try {
            if ($date instanceof \DateTimeInterface) {
                return $date->format('d-m-Y');
            }
            return \Carbon\Carbon::parse($date)->format('d-m-Y');
        } catch (\Throwable $e) {
            return (string) $date;
        }
    }
}

if (!function_exists('format_datetime')) {
    /**
     * Format a datetime always as dd-mm-yyyy H:i (d-m-Y H:i)
     */
    function format_datetime(\DateTimeInterface|string|null $datetime, bool $withSeconds = false, string $fallback = '-'): string
    {
        if (empty($datetime)) {
            return $fallback;
        }

        $pattern = $withSeconds ? 'd-m-Y H:i:s' : 'd-m-Y H:i';

        try {
            if ($datetime instanceof \DateTimeInterface) {
                return $datetime->format($pattern);
            }
            return \Carbon\Carbon::parse($datetime)->format($pattern);
        } catch (\Throwable $e) {
            return (string) $datetime;
        }
    }
}

if (!function_exists('parse_date_input')) {
    /**
     * Parse date input (dd-mm-yyyy or yyyy-mm-dd) into Y-m-d for database queries
     */
    function parse_date_input(?string $dateString): ?string
    {
        if (empty($dateString)) {
            return null;
        }

        $dateString = trim($dateString);

        try {
            // Check for dd-mm-yyyy or dd/mm/yyyy
            if (preg_match('/^(\d{1,2})[\-\/](\d{1,2})[\-\/](\d{4})$/', $dateString, $matches)) {
                $day = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
                $month = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
                $year = $matches[3];
                return "{$year}-{$month}-{$day}";
            }

            return \Carbon\Carbon::parse($dateString)->format('Y-m-d');
        } catch (\Throwable $e) {
            return $dateString;
        }
    }
}

if (!function_exists('sql_date')) {
    /**
     * Parse date into Y-m-d (MySQL format), identical to beltei_ums sql_date()
     */
    function sql_date(?string $date): ?string
    {
        return parse_date_input($date);
    }
}

if (!function_exists('dateFormat')) {
    /**
     * Format a date into dd-mm-yyyy, identical to beltei_ums dateFormat()
     */
    function dateFormat(\DateTimeInterface|string|null $date, string $fallback = '-'): string
    {
        return format_date($date, $fallback);
    }
}

if (!function_exists('get_lucide_icon')) {
    /**
     * Map FontAwesome or standard icon name to modern Lucide icon name
     */
    function get_lucide_icon(?string $icon): string
    {
        if (empty($icon)) return 'circle';

        $name = strtolower(trim($icon));
        // Remove FA prefixes like "fa-solid ", "fa-light ", "fas ", "fa-", etc.
        $name = preg_replace('/^(fa-solid|fa-regular|fa-light|fa-thin|fa-duotone|fa|fas|far|fal|fad)\s+/', '', $name);
        $name = preg_replace('/^fa-/', '', $name);

        $map = [
            'chart-simple' => 'layout-dashboard',
            'chart-line' => 'trending-up',
            'right-to-bracket' => 'log-in',
            'right-from-bracket' => 'log-out',
            'arrow-right-from-bracket' => 'log-out',
            'bed' => 'bed',
            'bed-double' => 'bed-double',
            'users' => 'users',
            'users-line' => 'users-round',
            'user' => 'user',
            'user-circle' => 'user-round',
            'file-invoice-dollar' => 'receipt',
            'receipt' => 'receipt',
            'sliders' => 'settings',
            'gear' => 'settings',
            'bars' => 'menu',
            'magnifying-glass' => 'search',
            'search' => 'search',
            'chevron-down' => 'chevron-down',
            'chevron-right' => 'chevron-right',
            'chevron-left' => 'chevron-left',
            'chevron-up' => 'chevron-up',
            'calendar' => 'calendar',
            'calendar-check' => 'calendar-check',
            'calendar-days' => 'calendar-days',
            'clock' => 'clock',
            'clock-rotate-left' => 'history',
            'van-shuttle' => 'bus',
            'plane' => 'plane',
            'plane-arrival' => 'plane-landing',
            'plane-departure' => 'plane-takeoff',
            'car' => 'car',
            'truck-monster' => 'truck',
            'motorcycle' => 'bike',
            'passport' => 'file-badge',
            'id-card' => 'id-card',
            'house' => 'home',
            'hotel' => 'building-2',
            'circle-dollar-to-slot' => 'circle-dollar-sign',
            'coins' => 'coins',
            'earth-americas' => 'globe',
            'compass' => 'compass',
            'mountain-sun' => 'mountain',
            'shirt' => 'shirt',
            'spa' => 'flower-2',
            'utensils' => 'utensils',
            'wine-glass' => 'wine',
            'layer-group' => 'layers',
            'list' => 'list',
            'list-check' => 'list-checks',
            'pen-to-square' => 'pencil',
            'pen' => 'pencil',
            'trash' => 'trash-2',
            'plus' => 'plus',
            'plus-circle' => 'plus-circle',
            'check' => 'check',
            'check-circle' => 'check-circle',
            'circle-check' => 'check-circle',
            'xmark' => 'x',
            'circle-xmark' => 'x-circle',
            'filter' => 'filter',
            'rotate-right' => 'rotate-cw',
            'arrow-down' => 'arrow-down',
            'arrow-up' => 'arrow-up',
            'file-excel' => 'file-spreadsheet',
            'file-lines' => 'file-text',
            'file-pdf' => 'file-text',
            'cogs' => 'settings',
            'info-circle' => 'info',
            'calendar-day' => 'calendar',
            'calendar-week' => 'calendar-range',
            'money-bill-transfer' => 'arrow-left-right',
            'universal-access' => 'accessibility',
            'ban' => 'ban',
            'times' => 'x',
            'sign-out-alt' => 'log-out',
            'sign-in-alt' => 'log-in',
            'calendar-alt' => 'calendar',
            'money-bill' => 'banknote',
            'money-bill-wave' => 'banknote',
            'bed-pulse' => 'bed',
            'file-invoice' => 'receipt',
            'cash-register' => 'receipt',
            'concierge-bell' => 'bell',
            'print' => 'printer',
            'broom-wide' => 'sparkles',
            'location-dot' => 'map-pin',
            'circle-dot' => 'disc',
            'suitcase' => 'briefcase',
            'door-open' => 'door-open',
            'phone' => 'phone',
            'envelope' => 'mail',
            'eye' => 'eye',
            'lock' => 'lock',
            'unlock' => 'unlock',
            'bolt' => 'zap',
            'folder' => 'folder',
            'folder-open' => 'folder-open',
        ];

        return $map[$name] ?? $name;
    }
}
