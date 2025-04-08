<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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

    public function register(){
        return view('auth.register');
    }

    public function register_proses(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required|min:4|max:20|unique:m_user,username',
                'nama' => 'required|min:4|max:20',
                'password' => 'required|min:6|max:20',
                'level_id' => 'required|integer',
            ]);
    
            UserModel::create([
                'username' => $request->username,
                'nama' => $request->nama,
                'password' => bcrypt($request->password),
                'level_id' => $request->level_id
            ]);
    
            return response()->json([
                'status' => true,
                'message' => 'Registrasi berhasil!',
                'redirect' => route('login'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal registrasi: ' . $e->getMessage(),
                'msgField' => [],
            ]);
        }
    }


}
