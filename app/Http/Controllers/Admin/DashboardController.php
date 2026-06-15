<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Location;
use App\Models\LostItem;

class DashboardController extends Controller
{
    public function index()
    {
        $totalItems    = LostItem::count();
        $pendingItems  = LostItem::where('status', 'pending')->count();
        $returnedItems = LostItem::where('status', 'returned')->count();
        $todayItems    = LostItem::whereDate('created_at', today())->count();

        $thaiMonths = ['01'=>'ม.ค.','02'=>'ก.พ.','03'=>'มี.ค.','04'=>'เม.ย.','05'=>'พ.ค.','06'=>'มิ.ย.',
                       '07'=>'ก.ค.','08'=>'ส.ค.','09'=>'ก.ย.','10'=>'ต.ค.','11'=>'พ.ย.','12'=>'ธ.ค.'];

        $dailyLabels = [];
        $dailyData   = [];
        for ($i = 6; $i >= 0; $i--) {
            $date          = now()->subDays($i)->format('Y-m-d');
            $dailyLabels[] = now()->subDays($i)->format('d/m');
            $dailyData[]   = LostItem::whereDate('created_at', $date)->count();
        }

        $monthlyLabels = [];
        $monthlyData   = [];
        for ($i = 11; $i >= 0; $i--) {
            $dt            = now()->subMonths($i);
            $monthlyLabels[] = $thaiMonths[$dt->format('m')] . ' ' . substr((string)((int)$dt->format('Y') + 543), -2);
            $monthlyData[]   = LostItem::whereRaw("DATE_FORMAT(created_at,'%Y-%m') = ?", [$dt->format('Y-m')])->count();
        }

        $yearlyLabels = [];
        $yearlyData   = [];
        for ($i = 4; $i >= 0; $i--) {
            $year          = (int) now()->subYears($i)->format('Y');
            $yearlyLabels[] = $year + 543;
            $yearlyData[]   = LostItem::whereYear('created_at', $year)->count();
        }

        $categoryData = Category::withCount('lostItems')->orderByDesc('lost_items_count')->get();
        $locationData = Location::withCount('lostItems')->orderByDesc('lost_items_count')->get();
        $latestItems  = LostItem::with(['category', 'location'])->orderByDesc('created_at')->limit(5)->get();

        return view('admin.dashboard', compact(
            'totalItems', 'pendingItems', 'returnedItems', 'todayItems',
            'dailyLabels', 'dailyData', 'monthlyLabels', 'monthlyData',
            'yearlyLabels', 'yearlyData', 'categoryData', 'locationData', 'latestItems'
        ));
    }
}
