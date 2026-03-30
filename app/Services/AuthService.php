<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail; // Pastikan ini ada
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Mail\OtpMail; // Pastikan ini ada
use App\Mail\ResetOtpMail; // Pastikan ini ada

class AuthController extends Controller
{
    public function register(Request $request) {
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
    ]);

    $otp = random_int(100000, 999999);

    // SIMPAN DATA KE SESSION (Jangan ke Database dulu)
    session([
        'pending_user' => [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Simpan yang sudah di-hash
            'otp_code' => $otp,
            'expires_at' => now()->addMinutes(10),
        ]
    ]);

    // Kirim Email OTP
    Mail::to($request->email)->send(new OtpMail($otp));

    return redirect()->route('otp.verify')->with('success', 'Kode OTP dikirim.');
}
    // --- PROSES LUPA PASSWORD ---
    public function sendResetOtp(Request $request)
    {
        $request->validate(["email" => "required|email|exists:users,email"]);

        $otp = random_int(100000, 999999);
        $user = User::where("email", $request->email)->first();

        $user->update([
            "otp_code" => $otp,
            "otp_expires_at" => now()->addMinutes(10),
        ]);

        // ✅ PERBAIKAN: Gunakan Mail bawaan Laravel (Hapus Brevo)
        try {
            Mail::to($user->email)->send(new ResetOtpMail($otp));
        } catch (\Exception $e) {
            Log::error("Gagal kirim OTP Reset: " . $e->getMessage());
        }

        session(["reset_email" => $user->email, "otp_sent" => true]);

        return back()->with("success", "Kode pemulihan telah dikirim.");
    }
    public function verifyOtp(string $email, string $otp)
    {
        $user = User::where('email', $email)
            ->where('otp_code', $otp)
            ->where('otp_expires_at', '>', now())
            ->first();

        if ($user) {
            $user->update([
                'email_verified_at' => now(),
                'otp_code' => null,
                'otp_expires_at' => null,
            ]);

            Auth::login($user);
            return $user;
        }

        throw new \Exception('Invalid or expired OTP.');
    }

    public function login(Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    // Tambahkan pengecekan manual sebelum atau saat attempt
    $user = User::where('email', $request->email)->first();

    if ($user && !$user->email_verified_at) {
        // Simpan ke session agar bisa lanjut verifikasi
        session(['verify_email' => $user->email]);
        return redirect()->route('otp.verify')
            ->with('error', 'Identitas belum terverifikasi. Silakan masukkan kode OTP.');
    }

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended(route('home'));
    }

    return back()->withErrors(['email' => 'Kredensial tidak valid.']);
}

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
    }
}
