<?php

use Carbon\Carbon;
use Spatie\Image\Image;
use Spatie\Image\Enums\Fit;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Imagick\Driver;

if (!function_exists('webp_variants')) {
    /**
     * Generate responsive WebP variants (with logging).
     */
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

            // Resolve widths
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

            // --- get source file ---
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
            } catch (\Exception $e) {
                Log::error("WebP conversion failed for {$path}: " . $e->getMessage());
                return '';
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
                $img = Image::load($absolutePath)
                    ->format('webp')
                    ->quality(80);

                if ($height) {
                    $img->height($height);
                }

                $img->save($absoluteWebpPath);
            } catch (\Exception $e) {
                Log::error("WebP conversion failed for {$path}: " . $e->getMessage());
                return '';
            }
        }
        if (file_exists($absoluteWebpPath)) {
            return asset(str_replace(public_path(), '', $absoluteWebpPath));
        }

        return '';
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
        return Carbon::parse($date)->format('Y-m-d') ?? null;
    }
}

if (! function_exists('badge')) {
    function badge(?string $status = '', ?string $text = '')
    {
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
        ];

        // Normalize nullable inputs
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
        if(empty($string)) {
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

        // Store the file
        $stored = Storage::disk('public')->put($putFile, file_get_contents($image->getRealPath()));
        if (! $stored || ! Storage::disk('public')->exists($putFile)) {
            Log::error("Failed to store file or file missing after upload: $putFile");
            return '';
        }

        // Create image if pathThumb is provided
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

        return 'storage/' . $putFile;
    }
}

if (! function_exists('uploadBase64')) {
    function uploadBase64(string $base64, string $path, string $pathThumb = '', int $width = 200): string
    {
        // Validate base64 string
        if (! preg_match('/^data:image\/[a-z]+;base64,/', $base64)) {
            $decoded = base64_decode($base64, true);
            if ($decoded === false) {
                return '';
            }
        } else {
            // Remove data URI prefix if present
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

        // Store the file
        $stored = Storage::disk('public')->put($putFile, $decoded);
        if (! $stored) {
            return '';
        }

        // Create image if pathThumb is provided
        if (! empty($pathThumb)) {
            $manager      = new ImageManager(new Driver());
            $imageContent = Storage::disk('public')->get($putFile);
            $image        = $manager->read($imageContent);
            $image->scaleDown(width: $width)->save(storage_path('app/public/' . $pathThumb . '/' . $fileName));
        }

        return 'storage/' . $putFile;
    }
}

if (! function_exists('uploadImage')) {
    /**
     * Upload image from either base64 string or UploadedFile.
     * Handles both uploaded files and base64 encoded images.
     *
     * @param string|UploadedFile $image Base64 string or UploadedFile instance
     * @param string $path Storage path (e.g., 'products', 'blogs', 'teams')
     * @param string $pathThumb Optional image path
     * @param int $width image width
     * @return string Path to stored image or empty string on failure
     */
    function uploadImage($image, string $path, string $pathThumb = '', int $width = 200): string
    {
        if (empty($image)) {
            return '';
        }

        // Handle UploadedFile instances
        if ($image instanceof UploadedFile) {
            return uploadFile($image, $path, $pathThumb, $width);
        }

        // Handle base64 strings
        if (is_string($image)) {
            return uploadBase64($image, $path, $pathThumb, $width);
        }

        return '';
    }
}

if (!function_exists('getFileSize')) {
    /**
     * Get file size in human readable format (bytes).
     */
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