<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(LoginUserRequest $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            // Asegura que cada vez que se inicie sesion se recicle el token que tenemos para prevenir malos usos
            $request->session()->regenerate();

            return redirect()->intended(route('ideas.index'))->with('success', 'Has iniciado sesion');
        }

        return back()->withErrors([
            'email' => 'La cuenta no se encuentra registrada',
        ]);
    }

    public function destroy()
    {
        Auth::logout();

        return redirect()->route('ideas.index');
    }
}
