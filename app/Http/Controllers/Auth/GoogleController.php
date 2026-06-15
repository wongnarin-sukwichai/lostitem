<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();
        $email      = $googleUser->getEmail();
        $allowedDomain = config('services.google.allowed_domain', env('GOOGLE_ALLOWED_DOMAIN', 'msu.ac.th'));

        // ตรวจสอบว่าเป็น email ขององค์กร @msu.ac.th
        if (!str_ends_with($email, '@' . $allowedDomain)) {
            return redirect()->route('login')
                ->with('error', 'กรุณาใช้บัญชี @' . $allowedDomain . ' เท่านั้น');
        }

        // ตรวจสอบว่า email นี้ได้รับสิทธิ์เข้าระบบหรือไม่
        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'บัญชีนี้ไม่มีสิทธิ์เข้าใช้งานระบบ กรุณาติดต่อผู้ดูแล');
        }

        // อัปเดต google_id และ avatar
        $user->update([
            'google_id' => $googleUser->getId(),
            'avatar'    => $googleUser->getAvatar(),
            'name'      => $googleUser->getName(),
        ]);

        Auth::login($user);

        return redirect()->route('admin.dashboard');
    }

    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login');
    }
}
