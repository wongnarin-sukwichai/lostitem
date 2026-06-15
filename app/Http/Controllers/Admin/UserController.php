<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|ends_with:msu.ac.th|unique:users,email',
            'role'  => 'required|in:admin,staff',
        ]);

        User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'role'      => $request->role,
            'google_id' => null,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'เพิ่มผู้ใช้เรียบร้อย');
    }

    public function show(User $user) {}

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|in:admin,staff',
        ]);
        $user->update($request->only('name', 'role'));
        return redirect()->route('admin.users.index')->with('success', 'แก้ไขผู้ใช้เรียบร้อย');
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')->with('error', 'ไม่สามารถลบบัญชีของตัวเองได้');
        }
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'ลบผู้ใช้เรียบร้อย');
    }
}
