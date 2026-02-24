<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

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
        // 1. Validasi Input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'max:255',
                'unique:users',
                'regex:/^[a-zA-Z0-9._%+\-]+@(gmail\.com|yahoo\.com|outlook\.com|icloud\.com|hotmail\.com)$/'
            ], // Email gak boleh kembar
            'password' => 'required|string|min:8|confirmed', // Harus ada input name="password_confirmation" di form
        ]);

        // 2. Buat User Baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user' // Default user biasa
        ]);

        // 3. Langsung Login setelah daftar
        Auth::login($user);

        return redirect()->route('home')->with('success', 'Registration successful! Welcome to NexRig.');
    }

    // --- LOGIN ---
    public function showLoginForm()
    {
        $title = 'Login';
        return view('auth.login', compact('title'));
    }

    public function login(Request $request)
    {
        // 1. Validasi
        $credentials = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'max:255',
                'unique:users',
                'regex:/^[a-zA-Z0-9._%+\-]+@(gmail\.com|yahoo\.com|outlook\.com|icloud\.com|hotmail\.com)$/'
            ],
            'password' => 'required|string|min:8|confirmed',
        ], [
            'email.regex' => 'Email harus menggunakan domain yang valid: gmail.com, yahoo.com, outlook.com, icloud.com, atau hotmail.com.',
            'email.unique' => 'Email ini sudah terdaftar.',
        ]);

        // 2. Coba Login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // Security fix (Session Fixation)
            
            // Redirect ke halaman yang tadi mau diakses, atau ke home
           return redirect()->intended(route('home'))->with('success', 'You are logged in!');
        }

        // 3. Gagal Login
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
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
}