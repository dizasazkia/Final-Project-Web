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
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:' . User::class,
                function ($attribute, $value, $fail) use ($request) { 
                    // Cek format email untuk mahasiswa
                    if ($request->role === 'mahasiswa' && !preg_match('/^[a-zA-Z0-9._%+-]+@student\.unhas\.ac\.id$/', $value)) {
                        $fail('Email harus menggunakan format nama@student.unhas.ac.id');
                    }
                }
            ],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|in:admin,pegawai,mahasiswa',
        ]);
        
        // Membuat pengguna baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);
    
        // Menyebarkan event Registered
        event(new Registered($user));
    
        // Login otomatis setelah registrasi
        Auth::login($user);
    
        // Cek role dan arahkan ke halaman yang sesuai
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'pegawai') {
            return redirect()->route('employee.dashboard');
        } elseif ($user->role === 'mahasiswa') {
            return redirect()->route('mahasiswa.dashboard');
        }
    
        // Default redirect jika role tidak dikenal (opsional)
        return redirect('/');
    }        
    
}
