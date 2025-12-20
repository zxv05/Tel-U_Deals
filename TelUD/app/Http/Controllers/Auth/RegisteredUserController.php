<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
// ... baris import di atas ...
public function store(Request $request): RedirectResponse
{
    // 1. VALIDASI (Gunakan nama input dari Form HTML -> Huruf Kecil)
    $request->validate([
        'name' => ['required', 'string', 'max:255'], // Tetap 'name'
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class], // Tetap 'email'
        'password' => ['required', 'confirmed', Rules\Password::defaults()], // Tetap 'password'
    ]);

    // 2. SIMPAN KE DB (Gunakan nama kolom Database -> Huruf Besar)
    $user = User::create([
        'nama' => $request->name,      // Kolom 'Nama' diisi input 'name'
        'email' => $request->email,    // Kolom 'Email' diisi input 'email'
        'password' => Hash::make($request->password), // Kolom 'Password'
        'role' => 'user',              // Default role
    ]);

    event(new Registered($user));

    Auth::login($user);

    return redirect(route('dashboard', absolute: false));
}
}
