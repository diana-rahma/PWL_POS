<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login() 
    { 
        // Jika sudah login, redirect ke halaman home 
        if (Auth::check()) {  
            return redirect('/'); 
        } 
        
        return view('auth.login'); 
    } 

    public function postlogin(Request $request) 
    { 
        // Cek apakah request berasal dari AJAX
        if ($request->ajax() || $request->wantsJson()) { 
            $credentials = $request->only('username', 'password'); 

            if (Auth::attempt($credentials)) { 
                // $user = Auth::user();
                // $user->load('level'); 
                // dd(Auth::user());

                return response()->json([ 
                    'status' => true, 
                    'message' => 'Login Berhasil', 
                    'redirect' => url('/') 
                ]); 
            } 

            return response()->json([ 
                'status' => false, 
                'message' => 'Login Gagal' 
            ]); 
        } 

        return redirect('login'); 
    } 

    public function logout(Request $request) 
    { 
        Auth::logout(); 

        // Invalidate session dan regenerate token
        $request->session()->invalidate(); 
        $request->session()->regenerateToken();     
        
        return redirect('login'); 
    }


}
