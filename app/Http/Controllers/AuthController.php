<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    // Show login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Fonction simple de connexion
    public function login(Request $request)
    {
        // 1. Validation des champs
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // 2. Tentative de connexion
        if (Auth::attempt($credentials)) { 
            // generate session id
            $request->session()->regenerate();
            // intended where to go after login ?
            // if no intended url, go to dashboard
            return redirect()->intended('dashboard');
        }

        // 3. Échec de connexion
        return back()->withErrors([
            'username' => 'Identifiants incorrects.',
        ])->onlyInput('username');
    }

    // Handle logout request
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
