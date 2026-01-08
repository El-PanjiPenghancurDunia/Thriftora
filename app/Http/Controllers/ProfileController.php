<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Review;

class ProfileController extends Controller
{
    // 1. Tampilkan Halaman Edit Profil
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'no_hp' => 'required',
            'alamat_pengiriman' => 'required',
            'password' => 'nullable|min:8|confirmed',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validasi Foto
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'alamat_pengiriman' => $request->alamat_pengiriman,
        ];

        // 1. Cek Password
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // 2. Cek Upload Foto
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada (dan bukan default)
            if ($user->profile_photo) {
                Storage::delete('public/' . $user->profile_photo);
            }
            // Simpan foto baru
            $path = $request->file('photo')->store('profile_photos', 'public');
            $data['profile_photo'] = $path;
        }

        User::where('id', $user->id)->update($data);

        return redirect()->back()->with('success', 'Profil dan Foto berhasil diperbarui!');
    }

    // 3. Konfirmasi Pesanan Diterima (Syarat sebelum review)
    public function completeOrder($id)
    {
        $trx = Transaction::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        
        $trx->update(['status' => 'Selesai']); // Status Final

        return redirect()->back()->with('success', 'Pesanan selesai! Silakan berikan ulasan.');
    }

    // 4. Kirim Ulasan
    public function submitReview(Request $request, $productId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:500',
        ]);

        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $productId,
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        return redirect()->back()->with('success', 'Terima kasih atas ulasan Anda!');
    }
}