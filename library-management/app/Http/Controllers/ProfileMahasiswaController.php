<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ProfileMahasiswaController extends Controller
{
    public function edit(string $id)
    {
        $user = Auth::user(); 
        return view('mahasiswa.profile', compact('user'));
    }

    public function update(Request $request, string $id)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id, 
        ], [
            'name.required' => 'Nama harus diisi!',
            'email.required' => 'Email harus diisi!',
            'email.email' => 'Email harus berupa email yang valid!',
        ]);

        // Ambil data dan perbarui
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return redirect()->route('profileMahasiswa.edit', ['id' => $id])->with('success', 'Profile berhasil diperbarui!');
    }
}

