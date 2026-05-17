<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    // Override: cek apakah user boleh login manual (non-OAuth)
    // User dengan role 'antrian_admin' yang is_antrian_admin=true boleh login manual.
    // User lain (admin/vendor) harus pakai Google OAuth.
    protected function credentials(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $user = \App\Models\User::where('email', $credentials['email'])->first();

        if ($user && $user->isAntrianAdmin()) {
            // Antrian admin boleh login manual
            return $credentials;
        }

        if ($user && in_array($user->role, ['admin', 'vendor'])) {
            // Admin dan vendor TIDAK boleh login manual (harus OAuth)
            // id_google harus ada → ini akan fail karena login manual tidak punya id_google
            return array_merge($credentials, ['id_google' => 'required']);
        }

        return $credentials;
    }

    // Redirect berbeda berdasarkan role setelah login
    protected function authenticated(Request $request, $user)
    {
        if ($user->isAntrianAdmin()) {
            return redirect()->route('antrian.admin');
        }
        return redirect()->intended($this->redirectTo);
    }
}