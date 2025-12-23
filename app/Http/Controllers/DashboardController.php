<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CheckIn;
use App\Models\Guest;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\Floor;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Show the dashboard
     */
    public function index()
    {
        // Today's statistics
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();

        // Room statistics
        $totalRooms = Room::count();
        $occupiedRooms = Room::whereHas('checkIns', function ($query) {
            $query->where('status', 'checked_in');
        })->count();
        $availableRooms = $totalRooms - $occupiedRooms;
        $occupancyRate = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 1) : 0;

        // Today's check-ins and check-outs
        $todayCheckIns = CheckIn::whereDate('check_in_date', $today)->count();
        $todayCheckOuts = CheckIn::whereDate('actual_check_out_at', $today)->count();

        // Today's revenue
        $todayRevenue = CheckIn::whereDate('check_in_date', $today)->sum('paid_amount') +
                       CheckIn::whereDate('actual_check_out_at', $today)->sum('paid_amount');

        // Monthly statistics
        $monthlyCheckIns = CheckIn::whereBetween('check_in_date', [$thisMonth, Carbon::now()])->count();
        $monthlyRevenue = CheckIn::whereBetween('check_in_date', [$thisMonth, Carbon::now()])->sum('paid_amount') +
                         CheckIn::whereBetween('actual_check_out_at', [$thisMonth, Carbon::now()])->sum('paid_amount');

        // Last month comparison
        $lastMonthCheckIns = CheckIn::whereBetween('check_in_date', [$lastMonth, $lastMonth->copy()->endOfMonth()])->count();
        $lastMonthRevenue = CheckIn::whereBetween('check_in_date', [$lastMonth, $lastMonth->copy()->endOfMonth()])->sum('paid_amount') +
                           CheckIn::whereBetween('actual_check_out_at', [$lastMonth, $lastMonth->copy()->endOfMonth()])->sum('paid_amount');

        // Guest statistics
        $totalGuests = Guest::count();
        $activeGuests = CheckIn::where('status', 'checked_in')->distinct('guest_id')->count();

        // Recent check-ins (last 5)
        $recentCheckIns = CheckIn::with(['guest', 'room'])
            ->orderBy('check_in_date', 'desc')
            ->orderBy('check_in_time', 'desc')
            ->take(5)
            ->get();

        // Room status breakdown
        $roomStatuses = Room::join('room_statuses', 'rooms.status_id', '=', 'room_statuses.id')
            ->selectRaw("CASE WHEN ? = 'km' AND room_statuses.name_kh IS NOT NULL THEN room_statuses.name_kh ELSE room_statuses.name_en END as status_name, count(*) as count", [app()->getLocale()])
            ->groupBy('status_name')
            ->get()
            ->pluck('count', 'status_name');

        // Revenue by room type (this month)
        $revenueByRoomType = CheckIn::whereBetween('check_in_date', [$thisMonth, Carbon::now()])
            ->join('rooms', 'check_ins.room_id', '=', 'rooms.id')
            ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->selectRaw("CASE WHEN ? = 'km' AND room_types.name_kh IS NOT NULL THEN room_types.name_kh ELSE room_types.name_en END as name, SUM(check_ins.paid_amount) as revenue", [app()->getLocale()])
            ->groupBy('name')
            ->get();

        // Monthly revenue trend (last 6 months)
        $monthlyRevenueTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();

            $revenue = CheckIn::whereBetween('check_in_date', [$monthStart, $monthEnd])->sum('paid_amount') +
                      CheckIn::whereBetween('actual_check_out_at', [$monthStart, $monthEnd])->sum('paid_amount');

            $monthlyRevenueTrend[] = [
                'month' => $month->format('M Y'),
                'revenue' => $revenue
            ];
        }

        // Create translated labels for room statuses
        $roomStatusLabels = [];
        foreach ($roomStatuses as $status => $count) {
            $roomStatusLabels[$status] = __('rooms.' . strtolower(str_replace(' ', '_', $status)));
        }

        return view('dashboard.index', compact(
            'totalRooms',
            'occupiedRooms',
            'availableRooms',
            'occupancyRate',
            'todayCheckIns',
            'todayCheckOuts',
            'todayRevenue',
            'monthlyCheckIns',
            'monthlyRevenue',
            'lastMonthCheckIns',
            'lastMonthRevenue',
            'totalGuests',
            'activeGuests',
            'recentCheckIns',
            'roomStatuses',
            'roomStatusLabels',
            'revenueByRoomType',
            'monthlyRevenueTrend'
        ));
    }
    //     $locale = app()->getLocale();

    //     // System Status - Real metrics
    //     $systemStatus = $this->getSystemStatus();

    //     // Statistics
    //     $statistics = [
    //         'total_services' => Service::count(),
    //         'total_partners' => Partner::count(),
    //         'total_clients' => Client::count(),
    //         'total_products' => Product::count(),
    //     ];

    //     // Visited Countries (mock data - replace with actual analytics data)
    //     $visitedCountries = collect([
    //         ['name' => 'Cambodia', 'count' => 1250, 'color' => '#FF6384'],
    //         ['name' => 'United States', 'count' => 850, 'color' => '#36A2EB'],
    //         ['name' => 'Thailand', 'count' => 650, 'color' => '#FFCE56'],
    //         ['name' => 'Singapore', 'count' => 420, 'color' => '#4BC0C0'],
    //         ['name' => 'Vietnam', 'count' => 380, 'color' => '#9966FF'],
    //         ['name' => 'Japan', 'count' => 320, 'color' => '#FF9F40'],
    //         ['name' => 'Other Countries', 'count' => 630, 'color' => '#C9CBCF'],
    //     ]);

    //     // Browser Usage (mock data - replace with actual analytics data)
    //     $browserUsage = collect([
    //         ['name' => 'Chrome', 'count' => 2450, 'percentage' => 62.5, 'color' => '#4285F4'],
    //         ['name' => 'Safari', 'count' => 780, 'percentage' => 19.9, 'color' => '#000000'],
    //         ['name' => 'Firefox', 'count' => 420, 'percentage' => 10.7, 'color' => '#FF7139'],
    //         ['name' => 'Edge', 'count' => 180, 'percentage' => 4.6, 'color' => '#0078D4'],
    //         ['name' => 'Others', 'count' => 90, 'percentage' => 2.3, 'color' => '#999999'],
    //     ]);

    //     // Permissions by menu (for chart)
    //     $permissionsByMenu = Menu::with('translations', 'permissions')
    //         ->whereNull('parent_id')
    //         ->get()
    //         ->map(function ($menu) {
    //             $translation = $menu->translations
    //                 ->where('locale', app()->getLocale())
    //                 ->first() ?? $menu->translations->where('locale', 'en')->first();

    //             return [
    //                 'name' => $translation?->name ?? 'N/A',
    //                 'count' => $menu->permissions->count(),
    //                 'color' => '#' . substr(md5($menu->id), 0, 6),
    //             ];
    //         })
    //         ->filter(fn($item) => $item['count'] > 0);

    //     // Recent users (last 5)
    //     $recentUsers = User::with('translations')
    //         ->latest('created_at')
    //         ->take(5)
    //         ->get()
    //         ->map(function ($user) {
    //             $translation = $user->translations
    //                 ->where('locale', app()->getLocale())
    //                 ->first() ?? $user->translations->where('locale', 'en')->first();

    //             return [
    //                 'id' => $user->id,
    //                 'name' => $translation?->first_name . ' ' . $translation?->last_name,
    //                 'email' => $user->email,
    //                 'username' => $user->username,
    //                 'created_at' => $user->created_at->format('M d, Y H:i'),
    //                 'created_at_ago' => $user->created_at->diffForHumans(),
    //             ];
    //         });

    //     // Recent teams (last 5)
    //     $recentTeams = Team::with('translations')
    //         ->latest('created_at')
    //         ->take(5)
    //         ->get()
    //         ->map(function ($team) {
    //             $translation = $team->translations
    //                 ->where('locale', app()->getLocale())
    //                 ->first() ?? $team->translations->where('locale', 'en')->first();

    //             return [
    //                 'id' => $team->id,
    //                 'name' => $translation?->name ?? 'N/A',
    //                 'position' => $translation?->position ?? 'N/A',
    //                 'photo' => $team->photo,
    //                 'email' => $team->email,
    //                 'created_at' => $team->created_at->format('M d, Y H:i'),
    //                 'created_at_ago' => $team->created_at->diffForHumans(),
    //             ];
    //         });

    //     // Recent services (last 5)
    //     $recentServices = Service::with('translations')
    //         ->latest('created_at')
    //         ->take(5)
    //         ->get()
    //         ->map(function ($service) {
    //             $translation = $service->translations
    //                 ->where('locale', app()->getLocale())
    //                 ->first() ?? $service->translations->where('locale', 'en')->first();

    //             return [
    //                 'id' => $service->id,
    //                 'name' => $translation?->name ?? 'N/A',
    //                 'icon' => $service->icon,
    //                 'description' => $translation?->description ?? 'N/A',
    //                 'created_at' => $service->created_at->format('M d, Y H:i'),
    //                 'created_at_ago' => $service->created_at->diffForHumans(),
    //             ];
    //         });

    //     // Cache dashboard data
    //     $cacheKey = 'dashboard_data';
    //     $cachePayload = [
    //         'statistics' => $statistics,
    //         'systemStatus' => $systemStatus,
    //         'visitedCountries' => $visitedCountries,
    //         'browserUsage' => $browserUsage,
    //         'permissionsByMenu' => $permissionsByMenu,
    //         'recentUsers' => $recentUsers,
    //         'recentTeams' => $recentTeams,
    //         'recentServices' => $recentServices,
    //     ];
    //     DashboardCache::updateOrCreate(
    //         ['key' => $cacheKey, 'locale' => $locale],
    //         ['payload' => $cachePayload, 'expires_at' => now()->addHours(1)]
    //     );

    //     return view('dashboard.index', $cachePayload);
    // }

    // /**
    //  * Get real system status metrics
    //  */
    // private function getSystemStatus()
    // {
    //     $status = [];

    //     // Memory Usage
    //     $memoryLimit = ini_get('memory_limit');
    //     $memoryLimitBytes = $this->convertToBytes($memoryLimit);
    //     $memoryUsage = memory_get_usage(true);
    //     $status['memory_usage'] = $memoryLimitBytes > 0 ? round(($memoryUsage / $memoryLimitBytes) * 100, 1) : 0;
    //     $status['memory_used'] = $this->formatBytes($memoryUsage);
    //     $status['memory_total'] = $this->formatBytes($memoryLimitBytes);

    //     // Storage Usage (disk space)
    //     $storagePath = storage_path();
    //     $totalSpace = disk_total_space($storagePath);
    //     $freeSpace = disk_free_space($storagePath);
    //     $usedSpace = $totalSpace - $freeSpace;
    //     $status['storage_usage'] = $totalSpace > 0 ? round(($usedSpace / $totalSpace) * 100, 1) : 0;
    //     $status['storage_used'] = $this->formatBytes($usedSpace);
    //     $status['storage_total'] = $this->formatBytes($totalSpace);

    //     // Database Size
    //     try {
    //         $dbName = config('database.connections.mysql.database');
    //         $dbSize = DB::select("SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size FROM information_schema.TABLES WHERE table_schema = ?", [$dbName]);
    //         $status['database_size'] = $dbSize[0]->size ?? 0;
    //     } catch (\Exception $e) {
    //         $status['database_size'] = 0;
    //     }

    //     // Cache Size (approximation)
    //     $cacheSize = 0;
    //     $cachePath = storage_path('framework/cache/data');
    //     if (is_dir($cachePath)) {
    //         $cacheSize = $this->getDirSize($cachePath);
    //     }
    //     $status['cache_size'] = $this->formatBytes($cacheSize);

    //     // Server Load (Linux only)
    //     if (function_exists('sys_getloadavg')) {
    //         $load = sys_getloadavg();
    //         $cpuCores = $this->getCpuCores();
    //         $status['server_load'] = $cpuCores > 0 ? round(($load[0] / $cpuCores) * 100, 1) : 0;
    //         $status['load_average'] = round($load[0], 2);
    //     } else {
    //         $status['server_load'] = 0;
    //         $status['load_average'] = 0;
    //     }

    //     // Laravel Version
    //     $status['laravel_version'] = app()->version();

    //     // PHP Version
    //     $status['php_version'] = PHP_VERSION;

    //     // Reverb Status (if enabled)
    //     $status['reverb_enabled'] = config('broadcasting.default') === 'reverb';

    //     return $status;
    // }

    // /**
    //  * Convert human-readable size to bytes
    //  */
    // private function convertToBytes($size)
    // {
    //     $size = trim($size);
    //     $unit = strtolower($size[strlen($size) - 1]);
    //     $value = (int) $size;

    //     switch ($unit) {
    //         case 'g': $value *= 1024;
    //         case 'm': $value *= 1024;
    //         case 'k': $value *= 1024;
    //     }

    //     return $value;
    // }

    // /**
    //  * Format bytes to human-readable format
    //  */
    // private function formatBytes($bytes, $precision = 2)
    // {
    //     $units = ['B', 'KB', 'MB', 'GB', 'TB'];

    //     for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
    //         $bytes /= 1024;
    //     }

    //     return round($bytes, $precision) . ' ' . $units[$i];
    // }

    // /**
    //  * Get directory size recursively
    //  */
    // private function getDirSize($directory)
    // {
    //     $size = 0;

    //     if (!is_dir($directory)) {
    //         return 0;
    //     }

    //     foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($directory, \RecursiveDirectoryIterator::SKIP_DOTS)) as $file) {
    //         $size += $file->getSize();
    //     }

    //     return $size;
    // }

    // /**
    //  * Get number of CPU cores
    //  */
    // private function getCpuCores()
    // {
    //     if (stripos(PHP_OS, 'linux') !== false) {
    //         $cpuinfo = file_get_contents('/proc/cpuinfo');
    //         preg_match_all('/^processor/m', $cpuinfo, $matches);
    //         return count($matches[0]);
    //     }

    //     return 1; // Default fallback
    // }

    /**
     * Get color for room status
     */
    public static function getStatusColor($status)
    {
        // Map both English and Khmer status names to colors
        $colors = [
            // English names
            'Available' => '#28a745',
            'Occupied' => '#dc3545',
            'Cleaning' => '#ffc107',
            'Maintenance' => '#6c757d',
            'Out of Order' => '#dc3545',
            // Khmer names
            'ទំនេរ' => '#28a745',        // Available
            'មានអ្នកស្នាក់នៅ' => '#dc3545', // Occupied
            'កំពុងសម្អាត' => '#ffc107',   // Cleaning
            'កំពុងជួសជុល' => '#6c757d',   // Maintenance
            'មិនអាចប្រើបាន' => '#dc3545', // Out of Order
        ];

        return $colors[$status] ?? '#858796';
    }
}
