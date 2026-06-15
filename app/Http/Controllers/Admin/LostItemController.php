<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Location;
use App\Models\LostItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LostItemController extends Controller
{
    public function index(Request $request)
    {
        $query = LostItem::with(['category', 'location', 'user']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('item_name', 'like', "%$s%")
                  ->orWhereHas('location', fn($q2) => $q2->where('location_name', 'like', "%$s%"))
                  ->orWhereHas('category', fn($q3) => $q3->where('category_name', 'like', "%$s%"));
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('location_id')) {
            $query->where('location_id', $request->location_id);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $items      = $query->orderByDesc('created_at')->paginate(15)->withQueryString();
        $categories = Category::orderBy('category_name')->get();
        $locations  = Location::orderBy('location_name')->get();
        $users      = User::orderBy('name')->get();

        return view('admin.lost_items.index', compact('items', 'categories', 'locations', 'users'));
    }

    public function create()
    {
        $categories = Category::where('status', 1)->orderBy('category_name')->get();
        $locations  = Location::where('status', 1)->orderBy('location_name')->get();

        return view('admin.lost_items.create', compact('categories', 'locations'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'item_name'   => 'required|string|max:255',
            'category_id' => 'required|integer',
            'location_id' => 'required|integer',
            'found_date'  => 'required|date',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|max:5120',
        ]);

        $data['user_id'] = Auth::id();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . rand(1000, 9999) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $data['image'] = $filename;
        }

        LostItem::create($data);

        return redirect()->route('admin.lost-items.index')->with('success', 'เพิ่มทรัพย์สินเรียบร้อย');
    }

    public function show(LostItem $lostItem)
    {
        return response()->json($lostItem->load(['category', 'location', 'user']));
    }

    public function edit(LostItem $lostItem)
    {
        $categories = Category::where('status', 1)->orderBy('category_name')->get();
        $locations  = Location::where('status', 1)->orderBy('location_name')->get();

        return view('admin.lost_items.edit', compact('lostItem', 'categories', 'locations'));
    }

    public function update(Request $request, LostItem $lostItem)
    {
        $data = $request->validate([
            'item_name'   => 'required|string|max:255',
            'category_id' => 'required|integer',
            'location_id' => 'required|integer',
            'found_date'  => 'required|date',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('image')) {
            if ($lostItem->image && file_exists(public_path('uploads/' . $lostItem->image))) {
                unlink(public_path('uploads/' . $lostItem->image));
            }
            $file = $request->file('image');
            $filename = time() . '_' . rand(1000, 9999) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $data['image'] = $filename;
        }

        $lostItem->update($data);

        return redirect()->route('admin.lost-items.index')->with('success', 'แก้ไขทรัพย์สินเรียบร้อย');
    }

    public function destroy(LostItem $lostItem)
    {
        if ($lostItem->image && file_exists(public_path('uploads/' . $lostItem->image))) {
            unlink(public_path('uploads/' . $lostItem->image));
        }
        $lostItem->delete();

        return redirect()->route('admin.lost-items.index')->with('success', 'ลบทรัพย์สินเรียบร้อย');
    }

    public function returnItem(Request $request, LostItem $lostItem)
    {
        $data = $request->validate([
            'owner_first_name' => 'required|string|max:100',
            'owner_last_name'  => 'required|string|max:100',
            'student_id'       => 'nullable|string|max:11',
            'tel'              => 'nullable|string|max:20',
            'email'            => 'nullable|email|max:150',
        ]);

        $lostItem->update(array_merge($data, [
            'status'             => 'returned',
            'returned_date'      => now()->toDateString(),
            'returned_timestamp' => now(),
        ]));

        return response()->json(['success' => true]);
    }

    public function toggleImage(LostItem $lostItem)
    {
        $lostItem->update(['is_image_hidden' => !$lostItem->is_image_hidden]);

        return response()->json(['success' => true, 'new_state' => (int) $lostItem->is_image_hidden]);
    }
}
