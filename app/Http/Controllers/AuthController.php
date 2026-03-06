<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Mail\ResetOtpMail;

class AuthController extends Controller
{
    // --- REGISTER ---
    public function showRegisterForm()
    {
        $title = 'Register';
        return view('auth.register', compact('title'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'max:255',
                'unique:users',
                'regex:/^[a-zA-Z0-9._%+\-]+@(gmail\.com|yahoo\.com|outlook\.com|icloud\.com|hotmail\.com)$/'
            ],
            'password' => 'required|string|min:8|confirmed',
        ]);

        $otp = rand(100000, 999999);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'otp_code' => $otp
        ]);

        Mail::to($user->email)->send(new OtpMail($otp));

        // Simpan email dan URL tujuan (redirect) ke session sementara
        session([
            'verify_email' => $user->email,
            'redirect_url' => $request->input('redirect') // Simpan redirect url
        ]);

        // Arahkan ke halaman input OTP, BUKAN ke home
        return redirect()->route('otp.verify')->with('success', 'Transmission sent. Check your email terminal.');
    }

    // --- TAMPILAN HALAMAN OTP (Fungsi yang sebelumnya hilang) ---
    public function showOtpForm()
    {
        // Jika tidak ada session email, kembalikan ke register
        if (!session('verify_email')) {
            return redirect()->route('register');
        }

        $title = 'Verify Identity';
        return view('auth.verify-otp', compact('title'));
    }

    // --- PROSES CEK OTP ---
    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required|numeric']);

        $email = session('verify_email');
        $user = User::where('email', $email)->where('otp_code', $request->otp)->first();

        if ($user) {
            $user->update([
                'email_verified_at' => now(),
                'otp_code' => null
            ]);

            // Baru login DI SINI setelah OTP benar
            Auth::login($user);

            // Ambil URL tujuan dari session, lalu bersihkan session
            $redirect = session('redirect_url');
            session()->forget(['verify_email', 'redirect_url']);

            // Arahkan ke URL tujuan atau ke home jika tidak ada
            return redirect($redirect ?: route('home'))->with('success', 'Access Granted. Welcome to the Fleet.');
        }

        return back()->withErrors(['otp' => 'Access Code Invalid / Corrupted.']);
    }

    // --- LOGIN ---
    public function showLoginForm()
    {
        // Bersihkan session sisa dari fitur lupa password jika ada
        session()->forget(['reset_email', 'otp_sent', 'allow_reset_for']);

        $title = 'Login';
        return view('auth.login', compact('title'));
    }

    public function login(Request $request)
    {
        // 1. Validasi - HANYA email dan password
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // 2. Coba Login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('home'))->with('success', 'You are logged in!');
        }

        // 3. Gagal Login
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    // --- LOGOUT ---
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Logged out successfully');
    }

    // ==========================================
    // --- FITUR LUPA PASSWORD & RESET ---
    // ==========================================

    public function showForgotForm()
    {
        $title = 'System Recovery';
        return view('auth.forgot-password', compact('title'));
    }

    public function sendResetOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ], [
            'email.exists' => 'Terminal email tidak ditemukan di database kami.'
        ]);

        $otp = rand(100000, 999999);
        $user = User::where('email', $request->email)->first();
        $user->update(['otp_code' => $otp]);

        // 1. Menggunakan Mail khusus Reset Password
        Mail::to($user->email)->send(new ResetOtpMail($otp));

        // 2. Gunakan SESSION PERMANEN (bukan ->with) agar data email tidak hilang
        session([
            'reset_email' => $user->email,
            'otp_sent' => true
        ]);

        return back()->with('success', 'Recovery code sent to your terminal.');
    }

    public function verifyResetOtp(Request $request)
    {
        $request->validate(['otp' => 'required|numeric']);

        // 3. Ambil email langsung dari session yang sudah kita simpan
        $email = session('reset_email');

        $user = User::where('email', $email)->where('otp_code', $request->otp)->first();

        if ($user) {
            $user->update(['otp_code' => null]);

            // Bersihkan status otp_sent, berikan izin reset
            session()->forget('otp_sent');
            session(['allow_reset_for' => $user->email]);

            return redirect()->route('password.reset')->with('success', 'Identity Verified. Proceed to modify access code.');
        }

        return back()->withErrors(['otp' => 'Invalid / Corrupted Recovery Code.']);
    }

    public function showResetForm()
    {
        // Cegah akses jika belum verifikasi OTP
        if (!session('allow_reset_for')) {
            return redirect()->route('login');
        }

        $title = 'Override Protocol';
        return view('auth.reset-password', compact('title'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed'
        ]);

        $email = session('allow_reset_for');
        $user = User::where('email', $email)->first();

        if ($user) {
            // ✅ Cek apakah password baru sama dengan password lama
            if (Hash::check($request->password, $user->password)) {
                return back()->withErrors([
                    'password' => 'New access code cannot be the same as the old one.'
                ]);
            }

            $user->update(['password' => Hash::make($request->password)]);
            session()->forget('allow_reset_for');

            return redirect()->route('login')->with('success', 'Access Code Overridden. You may now login.');
        }

        return redirect()->route('login')->withErrors(['email' => 'System Error. Try again.']);
    }
}
