<?php

namespace App\Http\Controllers;

use Filament\Facades\Filament;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (auth()->attempt($credentials)) {
            $request->session()->regenerate();
            $user = auth()->user();
            Filament::auth()->login($user);
            if ($user->hasRole('super_admin')) {
                return redirect()->route('filament.mukhiya.pages.dashboard');
            } elseif ($user->hasRole('taker')) {
                return redirect()->route('filament.taker.pages.dashboard');
            } elseif ($user->hasRole('maker')) {
                return redirect()->route('filament.maker.pages.dashboard');
            } elseif ($user->hasRole('packer')) {
                return redirect()->route('filament.packer.pages.dashboard');
            } else {
                return redirect()->route('welcome');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
}
