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
        User::create($data);

        return redirect()->route('login')
            ->with('success', 'Pendaftaran berhasil, silahkan login.');
    }
}
