<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function edit()
    {
        $contactInfo = SystemSetting::get('contact_info');
        return view('admin.settings', compact('contactInfo'));
    }

    public function update(Request $request)
    {
        SystemSetting::set('contact_info', $request->input('contact_info', ''));
        return redirect()->route('admin.settings')->with('success', 'บันทึกการตั้งค่าเรียบร้อย');
    }
}
