<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\LostItem;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function index(Request $request)
    {
        $allowedLimits = [10, 20, 50, 100];
        $limit       = in_array((int) $request->limit, $allowedLimits) ? (int) $request->limit : 10;
        $page        = max(1, (int) $request->get('page', 1));
        $search      = trim($request->get('search', ''));
        $categoryId  = (int) $request->get('category', 0);

        $query = LostItem::with(['category', 'location'])
            ->where(function ($q) {
                $q->where('status', 'pending')
                  ->orWhere(function ($q2) {
                      $q2->where('status', 'returned')
                         ->where('returned_date', '>=', now()->subDays(30)->toDateString());
                  });
            });

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('item_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('location', fn($q2) => $q2->where('location_name', 'like', "%{$search}%"));
            });
        }

        if ($categoryId > 0) {
            $query->where('category_id', $categoryId);
        }

        $items      = $query->orderByDesc('found_date')->orderByDesc('created_at')->paginate($limit)->withQueryString();
        $categories = Category::where('status', 1)->orderByRaw('CONVERT(category_name USING tis620)')->get();
        $contactInfoHtml = SystemSetting::get('contact_info',
            '<strong>หากทรัพย์สินนี้เป็นของคุณ</strong><br>โปรดนำหลักฐานแสดงความเป็นเจ้าของ ติดต่อขอรับคืนได้ที่<br><u>เคาน์เตอร์บริการ สำนักวิทยบริการ (ชั้น 2)</u>'
        );

        return view('public.index', compact('items', 'categories', 'search', 'categoryId', 'limit', 'contactInfoHtml'));
    }
}
