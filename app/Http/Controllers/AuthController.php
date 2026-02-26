<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

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

    // Form forgot password
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    // Send reset link
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        Password::sendResetLink(
            $request->only('email')
        );
        return back()->with('status', 'Lien envoyé par email.');
    }

    // Form reset password
    public function showResetForm($token)
    {
        return view('auth.reset-password', [
            'token' => $token
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', 'Mot de passe modifié avec succès')
            : back()->withErrors(['email' => 'Échec de la réinitialisation du mot de passe.']);
    }
}
