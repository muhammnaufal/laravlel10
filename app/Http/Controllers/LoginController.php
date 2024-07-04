<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("login");
    }

    public function authenticate(Request $request) 
    {

        $request->validate([
            'nip' => 'required',
            'password' => 'required',
        ]);

        $nip = str_replace(' ', '',$request->nip);
        
        $credentials = [
            "NIP" =>  $nip,
            "password" => $request->password
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            if (auth()->user()->hak_akses_id == 1 || auth()->user()->hak_akses_id == 3) 
            { 
                return redirect()->intended('dashboard')->with('loginSuccess', 'Selamat Datang....');
            }
            else 
            {
                return redirect()->intended('/surat/disposisi_surat')->with('loginSuccess', 'Selamat Datang....');
            }
        } 
        return back()->with('loginError', 'Login Gagal')->withInput();
    }

    public function logout(Request $request) 
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');   
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
