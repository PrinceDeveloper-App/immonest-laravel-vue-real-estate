<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function create()
    {
        return inertia('Auth/Login');
    }

    public function store( Request $request)
    {
       if (!Auth::attempt($request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
       ]), true)) {
        throw ValidationException::withMessages([
            'email' => 'Authentication failed. Please check your credentials and try again.',
        ]);
       }
         $request->session()->regenerate();

        return redirect()->route('listing.index');
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('listing.index');
    }
}
