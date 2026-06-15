<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function index()
    {
        $staffs = Staff::orderBy('first_name')->paginate(20);
        return view('admin.staffs.index', compact('staffs'));
    }

    public function create()
    {
        return view('admin.staffs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'position'   => 'nullable|string|max:100',
        ]);
        Staff::create($request->only('first_name', 'last_name', 'position') + ['status' => 1]);
        return redirect()->route('admin.staffs.index')->with('success', 'เพิ่มเจ้าหน้าที่เรียบร้อย');
    }

    public function show(string $id) {}

    public function edit(Staff $staff)
    {
        return view('admin.staffs.edit', compact('staff'));
    }

    public function update(Request $request, Staff $staff)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'position'   => 'nullable|string|max:100',
        ]);
        $staff->update($request->only('first_name', 'last_name', 'position'));
        return redirect()->route('admin.staffs.index')->with('success', 'แก้ไขเจ้าหน้าที่เรียบร้อย');
    }

    public function destroy(Staff $staff)
    {
        if ($staff->lostItems()->count() > 0) {
            return redirect()->route('admin.staffs.index')->with('error', 'ไม่สามารถลบได้ เนื่องจากเจ้าหน้าที่นี้มีทรัพย์สินที่รับผิดชอบอยู่');
        }
        $staff->delete();
        return redirect()->route('admin.staffs.index')->with('success', 'ลบเจ้าหน้าที่เรียบร้อย');
    }

    public function toggleStatus(Staff $staff)
    {
        $staff->update(['status' => !$staff->status]);
        return response()->json(['success' => true]);
    }
}
