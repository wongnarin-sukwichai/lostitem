<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::orderBy('location_name')->paginate(20);
        return view('admin.locations.index', compact('locations'));
    }

    public function create()
    {
        return view('admin.locations.create');
    }

    public function store(Request $request)
    {
        $request->validate(['location_name' => 'required|string|max:150|unique:locations,location_name']);
        Location::create(['location_name' => $request->location_name, 'status' => 1]);
        return redirect()->route('admin.locations.index')->with('success', 'เพิ่มสถานที่เรียบร้อย');
    }

    public function show(string $id) {}

    public function edit(Location $location)
    {
        return view('admin.locations.edit', compact('location'));
    }

    public function update(Request $request, Location $location)
    {
        $request->validate(['location_name' => 'required|string|max:150|unique:locations,location_name,' . $location->location_id . ',location_id']);
        $location->update(['location_name' => $request->location_name]);
        return redirect()->route('admin.locations.index')->with('success', 'แก้ไขสถานที่เรียบร้อย');
    }

    public function destroy(Location $location)
    {
        if ($location->lostItems()->count() > 0) {
            return redirect()->route('admin.locations.index')->with('error', 'ไม่สามารถลบได้ เนื่องจากมีทรัพย์สินอยู่ในสถานที่นี้');
        }
        $location->delete();
        return redirect()->route('admin.locations.index')->with('success', 'ลบสถานที่เรียบร้อย');
    }

    public function toggleStatus(Location $location)
    {
        $location->update(['status' => !$location->status]);
        return response()->json(['success' => true]);
    }
}
