<?php

namespace App\Http\Controllers;

    use App\Models\Address;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Log;

    class AddressController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'recipient_name' => 'required|string|max:50',
            'phone'          => 'required|string|max:15',
            'label'          => 'required|string|max:30',
            'full_address'   => 'required|string|max:255',
            'courier_note'   => 'nullable|string|max:45',
            'latitude'       => 'nullable',
            'longitude'      => 'nullable',
            'is_primary'     => 'nullable|boolean',
        ]);

        // Reset alamat utama lama
        if ($request->boolean('is_primary')) {
            Address::where('user_id', Auth::id())
                ->update(['is_primary' => false]);
        }

        // 2. Gunakan Transaction & Try-Catch
        DB::beginTransaction();
        try {
            $userId = Auth::id();
            $isPrimary = $request->boolean('is_primary');

            // Jika alamat baru diset sebagai Utama, matikan status Utama pada alamat lain milik user ini
            if ($isPrimary) {
                Address::where('user_id', $userId)
                    ->where('is_primary', true)
                    ->update(['is_primary' => false]);
            }

            // Simpan data ke database
            $address = Address::create([
                'user_id'        => $userId,
                'recipient_name' => $data['recipient_name'],
                'phone'          => $data['phone'],
                'label'          => $data['label'],
                'full_address'        => $data['full_address'],
                'courier_note'   => $data['courier_note'] ?? null,
                'latitude'       => $data['latitude'] ?? null,
                'longitude'      => $data['longitude'] ?? null,
                'is_primary'     => $isPrimary,
            ]);

            DB::commit();

            // 3. Response Berdasarkan Jenis Request
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Alamat berhasil ditambahkan',
                    'data'    => $address
                ], 201);
            }

            return back()->with('success', $address->label . ' berhasil ditambahkan');

        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan semua perubahan jika ada error

            // Catat error ke log untuk pengecekan admin
            Log::error("Gagal simpan alamat: " . $e->getMessage());

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyimpan alamat',
                    'error'   => $e->getMessage() 
                ], 500);
            }

            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

        public function update(Request $request, Address $address)
        {
            $this->authorize('update', $address);

            $data = $request->validate([
                'recipient_name' => 'required|string|max:50',
                'phone'          => 'required|string|max:15',
                'label'          => 'required|string|max:30',
                'full_address'   => 'required|string|max:255',
                'courier_note'   => 'nullable|string|max:45',
                'latitude'       => 'nullable',
                'longitude'      => 'nullable',
                'is_primary'     => 'nullable|boolean',
            ]);

            if ($request->boolean('is_primary')) {
                Address::where('user_id', Auth::id())
                    ->where('id', '!=', $address->id)
                    ->update(['is_primary' => false]);
            }

            $address->update([
                'recipient_name' => $data['recipient_name'],
                'phone'          => $data['phone'],
                'label'          => $data['label'],
                'full_address'        => $data['full_address'],
                'courier_note'   => $data['courier_note'] ?? null,
                'latitude'       => $data['latitude'] ?? null,
                'longitude'      => $data['longitude'] ?? null,
                'is_primary'     => $request->boolean('is_primary'),
            ]);

            return back()->with('success', 'Alamat diperbarui');
        }

        public function destroy(Address $address)
        {
            $this->authorize('delete', $address);

            $address->delete();

            return back()->with('success', 'Alamat berhasil dihapus');
        }
    }
