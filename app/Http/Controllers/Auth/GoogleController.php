<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\OTPMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'id_google' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(), // Simpan avatar Google
                    'password' => null,
                ]);
            } else {
                $user->update([
                    'id_google' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(), // Update avatar terbaru
                ]);
            }

            $otp = rand(100000, 999999);
            $user->update(['otp' => $otp]);

            Mail::to($user->email)->send(new OTPMail($otp));
            session(['otp_user_id' => $user->id]);

            return redirect()->route('otp.view');

        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Gagal login Google: ' . $e->getMessage());
        }
    }

    public function showOtpForm()
    {
        if (!session()->has('otp_user_id')) {
            return redirect('/login');
        }
        return view('auth.otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required']);
        $userId = session('otp_user_id');
        $user = User::find($userId);

        if ($user && $user->otp == $request->otp) {
            $user->update(['otp' => null]);
            Auth::login($user);
            session()->forget('otp_user_id');
            return redirect('/');
        }

        return back()->withErrors(['otp' => 'Kode OTP salah!']);
    }
}