<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query');
    
        if ($query) {
            $users = User::where('name', 'like', "%{$query}%")
                ->orWhere('email', 'like', "%{$query}%")
                ->orWhere('role', 'like', "%{$query}%")
                ->get();
        } else {
            $users = User::all();
        }
    
        return view('admin.index-user', compact('users'));
    }    

    public function create()
    {
        return view('admin.create-user');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:admin,pegawai,mahasiswa',
            'password' => 'required|string|min:8|confirmed', 
        ], [
            'name.required' => 'Nama pengguna wajib diisi.',
            'name.string' => 'Nama pengguna harus berupa teks.',
            'name.max' => 'Nama pengguna maksimal 255 karakter.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Alamat email tidak valid.',
            'email.unique' => 'Alamat email sudah terdaftar.',
            'role.required' => 'Role pengguna wajib dipilih.',
            'role.in' => 'Role pengguna tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.string' => 'Password harus berupa teks.',
            'password.min' => 'Password harus terdiri dari minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // Simpan data pengguna baru
        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully!');
    }

    public function show(User $user)
    {

    }

    public function edit(User $user)
    {
        return view('admin.edit-user', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'old_password' => 'required|string',  
            'password' => 'nullable|string|min:8|confirmed',  
            'password_confirmation' => 'nullable|string|min:8',
        ], [
            'old_password.required' => 'Password lama wajib diisi.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru harus terdiri dari minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password_confirmation.min' => 'Konfirmasi password harus terdiri dari minimal 8 karakter.',
        ]);
    
        // Verifikasi apakah password lama sesuai
        if (!Hash::check($request->old_password, $user->password)) {
            return back()->withErrors(['old_password' => 'Password lama tidak cocok.']);
        }
    
        // Update data pengguna
        $dataToUpdate = [
            'name' => $request->name,
            'email' => $request->email,
        ];
    
        // Jika password baru diisi, update password pengguna
        if ($request->filled('password')) {
            $dataToUpdate['password'] = Hash::make($request->password);  
        }
    
        $user->update($dataToUpdate);
    

        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully!');
    }
    
        
}
