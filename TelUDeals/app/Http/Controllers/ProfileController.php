<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Address;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        // 2. Ambil data alamat milik user yang sedang login
        $addresses = Address::where('user_id', $request->user()->id)
            ->orderBy('is_primary', 'desc')
            ->latest()
            ->get();

        // 3. Kirim data $addresses ke view
        return view('profile.edit', [
            'user' => $request->user(),
            'addresses' => $addresses,
        ]);
    }

    /**
     * ================================
     * UPDATE NAMA & EMAIL (TANPA AVATAR)
     * ================================
     */
public function update(ProfileUpdateRequest $request): RedirectResponse
{
    try {
        $user = $request->user();

        // Ambil data yang sudah divalidasi (termasuk tanggal_lahir & phone)
        $data = $request->validated();

        // Amankan agar avatar tidak tertimpa manual di sini jika ada logika upload terpisah
        unset($data['avatar']);

        // Mengisi data name, email, tanggal_lahir, dan phone secara otomatis
        $user->fill($data);

        // Reset verifikasi email jika email diubah
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return redirect()
            ->route('profile.edit')
            ->with('success', 'Profil berhasil diperbarui.');

    } catch (\Exception $e) {
        // Log menggunakan facade Log dan Auth
        Log::error("Gagal update profil User ID " . Auth::id() . ": " . $e->getMessage());

        return back()
            ->withInput()
            ->with('error', 'Gagal memperbarui profil: ' . $e->getMessage());
    }
}

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:10240'],
        ]);

        $user = $request->user();

        // hapus avatar lama
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        // simpan avatar baru
        $path = $request->file('avatar')->store('avatars', 'public');

        $user->avatar = $path;
        $user->save();

        return redirect()
            ->route('profile.edit')
            ->with('status', 'avatar-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // hapus avatar juga
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
