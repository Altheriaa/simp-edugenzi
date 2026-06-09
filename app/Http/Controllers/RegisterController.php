<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StoreRegister;

class RegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.auth.signup');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRegister $request): RedirectResponse
    {
        $data = $request->validated();
        $data['role'] = 'peserta_didik'; 
        $data['status'] = 'aktif';
        
        // Auto-generate unique registration number for mahasiswa (peserta_didik)
        $count = User::where('role', 'peserta_didik')->count() + 1;
        $data['no_registrasi'] = 'EDU' . '-'  . time() . str_pad($count, 4, '0', STR_PAD_LEFT);
        
        User::create($data);

        return redirect()->route('login')
            ->with('success', 'Pendaftaran berhasil, silahkan login.');
    }
}
