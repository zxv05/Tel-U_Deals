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

        // Ambil data valid (name & email)
        $data = $request->validated();

        // PASTIKAN avatar TIDAK ikut campur di sini
        unset($data['avatar']);

        $user->fill($data);

        // Reset verifikasi email jika berubah
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Gunakan save di dalam pengecekan atau database transaction
        $user->save();

        return redirect()
            ->route('profile.edit')
            ->with('success', 'Profil berhasil diperbarui.');

    } catch (\Exception $e) {
        // Catat detail error ke log agar bisa dicek developer
        Log::error("Gagal update profil User ID " . auth::id() . ": " . $e->getMessage());

        // Kembalikan ke halaman sebelumnya dengan pesan error
        return back()
            ->withInput() // Agar input user tidak hilang
            ->with('error', 'Gagal memperbarui profil. Terjadi kesalahan sistem.');
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
