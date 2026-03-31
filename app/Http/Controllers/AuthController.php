<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Mail\OtpMail;
use App\Mail\ResetOtpMail;

class AuthController extends Controller
{
    // ==========================================
    // 1. FITUR REGISTRASI & VERIFIKASI
    // ==========================================
    public function showRegisterForm() {
        // Bersihkan ingatan lupa password
        session()->forget(['reset_email', 'otp_sent', 'allow_reset_for']);
        return view('auth.register', ['title' => 'Register']);
    }

    public function register(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $otp = random_int(100000, 999999);

        // Buat User (tapi belum bisa login karena belum verify OTP)
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'otp_code' => $otp,
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        // Kirim Email
        Mail::to($user->email)->send(new OtpMail($otp));

        // Simpan email ke session agar halaman verify tahu email siapa
        session(['verify_email' => $user->email]);

        return redirect()->route('otp.verify')->with('success', 'Kode OTP dikirim ke email.');
    }

    public function showOtpForm() {
        if (!session('verify_email')) return redirect()->route('register');
        return view('auth.verify-otp', ['title' => 'Verify OTP']);
    }

    public function verifyOtp(Request $request) {
        $request->validate(['otp' => 'required|numeric']);
        $email = session('verify_email');

        $user = User::where('email', $email)->where('otp_code', $request->otp)
                    ->where('otp_expires_at', '>', now())->first();

        if ($user) {
            // Bersihkan OTP & Verifikasi Email
            $user->update(['email_verified_at' => now(), 'otp_code' => null, 'otp_expires_at' => null]);
            
            // Login & Bersihkan Session
            Auth::login($user);
            session()->forget('verify_email');

            return redirect()->route('home')->with('success', 'Akses Diberikan!');
        }

        return back()->withErrors(['otp' => 'Kode OTP Salah atau Kedaluwarsa.']);
    }

    // ==========================================
    // 2. FITUR LOGIN & LOGOUT
    // ==========================================
    public function showLoginForm() {
        // Bersihkan sampah session jika ada
        session()->forget(['verify_email', 'reset_email', 'allow_reset_for']);
        return view('auth.login', ['title' => 'Login']);
    }

    public function login(Request $request) {
        $credentials = $request->validate(['email' => 'required|email', 'password' => 'required']);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('home'))->with('success', 'Login Berhasil.');
        }

        return back()->withErrors(['email' => 'Kredensial tidak valid.'])->onlyInput('email');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }

    // ==========================================
    // 3. FITUR LUPA PASSWORD
    // ==========================================
    public function showForgotForm() {
        // Bersihkan ingatan registrasi
        session()->forget(['pending_user', 'verify_email']);
        return view('auth.forgot-password', ['title' => 'System Recovery']);
    }

    public function sendResetOtp(Request $request) {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $otp = random_int(100000, 999999);
        $user = User::where('email', $request->email)->first();
        $user->update(['otp_code' => $otp, 'otp_expires_at' => now()->addMinutes(10)]);

        // Kirim Email
        \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\ResetOtpMail($otp));

        session(['reset_email' => $user->email, 'otp_sent' => true, 'last_otp_sent_at' => time()]);
        return back()->with('success', 'Kode pemulihan dikirim ke email.');
    }

    public function verifyResetOtp(Request $request) {
        $request->validate(['otp' => 'required|numeric']);
        $email = session('reset_email');

        $user = User::where('email', $email)->where('otp_code', $request->otp)
                    ->where('otp_expires_at', '>', now())->first();

        if ($user) {
            $user->update(['otp_code' => null, 'otp_expires_at' => null]);
            session()->forget(['otp_sent', 'reset_email']);
            session(['allow_reset_for' => $user->email]); // Beri izin reset

            return redirect()->route('password.reset')->with('success', 'Identitas terverifikasi.');
        }

        return back()->withErrors(['otp' => 'Kode OTP Salah.']);
    }

    public function showResetForm() {
        if (!session('allow_reset_for')) return redirect()->route('login');
        return view('auth.reset-password', ['title' => 'Override Protocol']);
    }

    public function updatePassword(Request $request) {
        $request->validate(['password' => 'required|string|min:8|confirmed']);
        $email = session('allow_reset_for');
        $user = User::where('email', $email)->first();

        if ($user) {
            $user->update(['password' => Hash::make($request->password)]);
            session()->forget('allow_reset_for');
            return redirect()->route('login')->with('success', 'Password berhasil diubah, silakan login.');
        }

        return redirect()->route('login')->withErrors(['email' => 'Sistem Error.']);
    }

    public function resendOtp(Request $request) {
        // 1. TANGKAP TIPE RESEND DARI FORM
        $type = $request->input('type'); // isinya nanti 'reset' atau 'register'

        $email = session('pending_user')['email'] ?? session('verify_email') ?? session('reset_email');

        if (!$email) {
            return back()->withErrors(['otp' => 'Sesi berakhir. Silakan ulangi proses dari awal.']);
        }

        // ==========================================
        // CEK COOLDOWN 60 DETIK
        // ==========================================
        $lastSent = session('last_otp_sent_at'); 

        if ($lastSent && is_numeric($lastSent)) {
            $timePassed = time() - $lastSent; 
            
            if ($timePassed < 60) { 
                $remaining = 60 - $timePassed;
                return back()->withErrors(['otp' => 'Harap tunggu ' . $remaining . ' detik sebelum mengirim ulang.']);
            }
        }

        // ==========================================
        // BUAT OTP BARU
        // ==========================================
        $otp = random_int(100000, 999999);
        $user = \App\Models\User::where('email', $email)->first();
        
        if ($user) {
            $user->update([
                'otp_code' => $otp,
                'otp_expires_at' => now()->addMinutes(10)
            ]);
        } else if (session()->has('pending_user')) {
            $pending = session('pending_user');
            $pending['otp_code'] = $otp;
            $pending['expires_at'] = now()->addMinutes(10);
            session(['pending_user' => $pending]);
        }

        // ==========================================
        // KIRIM EMAIL BERDASARKAN INPUT FORM (100% AKURAT)
        // ==========================================
        try {
            if ($type === 'reset') {
                // Pasti kirim Security Alert (Merah)
                \Illuminate\Support\Facades\Mail::to($email)->send(new \App\Mail\ResetOtpMail($otp));
            } else {
                // Pasti kirim Registrasi (Biru)
                \Illuminate\Support\Facades\Mail::to($email)->send(new \App\Mail\OtpMail($otp));
            }
            
            session(['last_otp_sent_at' => time()]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Gagal resend OTP: " . $e->getMessage());
            return back()->withErrors(['otp' => 'Gagal mengirim email. Pastikan koneksi internet stabil.']);
        }

        return back()->with('success', 'Kode akses baru telah dikirim ke terminal Anda.');
    }
}