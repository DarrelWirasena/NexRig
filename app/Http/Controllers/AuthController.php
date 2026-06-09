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
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::min(8)
                ->letters()   // Harus mengandung setidaknya satu huruf
                ->mixedCase() // Harus mengandung huruf besar dan kecil
                ->numbers()   // Harus mengandung setidaknya satu angka
                ->symbols()   // Opsional: Hapus baris ini jika tidak ingin mewajibkan simbol unik
                // ->uncompromised() // Opsional (Lanjutan): Mengecek ke database global apakah password ini pernah bocor di internet
            ],
        ]);

        $otp = random_int(100000, 999999);

        // 🔥 PERUBAHAN: JANGAN BIKIN USER DI DATABASE DULU 🔥
        // Simpan semua data pendaftaran ke dalam Session sementara
        session([
            'pending_user' => [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password), // Hash passwordnya sekarang
                'otp_code' => $otp,
                'expires_at' => now()->addMinutes(10),
            ],
            'redirect_url' => $request->input('redirect') ?: session()->pull('url.intended')
        ]);

        // Kirim Email OTP
        Mail::to($request->email)->send(new OtpMail($otp));

        return redirect()->route('otp.verify')->with('success', 'Kode OTP dikirim ke email.');
    }

    public function showOtpForm() {
        // PERBAIKAN: Cek 'pending_user', bukan 'verify_email'
        if (!session()->has('pending_user')) {
            return redirect()->route('register');
        }
        return view('auth.verify-otp', ['title' => 'Verify OTP']);
    }

    public function verifyOtp(Request $request) {
        $request->validate(['otp' => 'required|numeric']);
        
        // Ambil data user yang sedang "menggantung" di session
        $pendingUser = session('pending_user');

        // Jika tidak ada data di session, tendang kembali ke halaman register
        if (!$pendingUser) {
            return redirect()->route('register')->withErrors(['otp' => 'Sesi pendaftaran berakhir. Silakan daftar ulang.']);
        }

        // 🔥 PERUBAHAN: CEK OTP DARI SESSION, BUKAN DARI DATABASE 🔥
        // Cek kecocokan OTP dan apakah belum kedaluwarsa
        if ($pendingUser['otp_code'] == $request->otp && now()->lessThan($pendingUser['expires_at'])) {
            
            // OTP BENAR! Sekarang baru kita buat User-nya di Database secara resmi
            $user = User::create([
                'name' => $pendingUser['name'],
                'email' => $pendingUser['email'],
                'password' => $pendingUser['password'],
                'role' => 'user',
                'email_verified_at' => now(), // Langsung tandai terverifikasi
                'otp_code' => null,
                'otp_expires_at' => null,
            ]);
            
            // Login & Bersihkan Session
            Auth::login($user);
            $redirect = session('redirect_url');
            session()->forget(['pending_user', 'redirect_url']); // Hapus session karena sudah masuk database
            
            return redirect($redirect ?: route('home'))->with('success', 'Registrasi Berhasil! Akses Diberikan.');
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
        // 1. Pastikan pengguna punya izin (session 'allow_reset_for' harus ada)
        $email = session('allow_reset_for');
        
        if (!$email) {
            return redirect()->route('login')->withErrors(['email' => 'Sesi pemulihan tidak valid atau telah kedaluwarsa.']);
        }

        // 2. Validasi Password Baru (Sama ketatnya dengan saat registrasi)
        $request->validate([
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
            ],
        ], [
            'password.min' => 'Password minimal harus 8 karakter.',
            'password.letters' => 'Password harus mengandung huruf.',
            'password.mixed' => 'Password harus memiliki kombinasi huruf besar dan kecil.',
            'password.numbers' => 'Password harus mengandung minimal satu angka.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // 3. Cari User dan Update Password
        $user = User::where('email', $email)->first();

        if ($user) {
            // Hash password baru dan simpan
            $user->update([
                'password' => Hash::make($request->password)
            ]);
            
            // 4. Bersihkan izin reset agar link tidak bisa dipakai dua kali
            session()->forget('allow_reset_for');
            
            // 5. (Opsional tapi Direkomendasikan) Kirim Email Pemberitahuan
            // Bahwa password akun mereka baru saja diubah.
            try {
                // Jika kamu belum punya Mailable ini, bisa diabaikan atau dibuat nanti
                // Mail::to($user->email)->send(new PasswordChangedAlertMail());
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Gagal mengirim alert perubahan password: " . $e->getMessage());
            }

            return redirect()->route('login')->with('success', 'Override Protocol sukses. Password berhasil diperbarui, silakan login dengan kredensial baru Anda.');
        }

        // Failsafe jika user tiba-tiba tidak ditemukan di database
        return redirect()->route('login')->withErrors(['email' => 'Terjadi kesalahan sistem. Pengguna tidak ditemukan.']);
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