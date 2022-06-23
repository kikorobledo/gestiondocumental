<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SetPasswordController extends Controller
{
    public function create(Request $request){

        $email = $request->email;

        return view('auth.setpassword', compact('email'));
    }

    public function store(Request $request){

        $request->validate([
            'password' => 'min:8|string|confirmed',
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();

        if(!$user)
            return redirect()->route('login')->with('message', 'El correo proporcionado no se encuentra registrado.');

        $user->update([
            'password' => bcrypt($request->password)
        ]);

        auth()->login($user);

        return redirect()->route('dashboard');
    }
}
