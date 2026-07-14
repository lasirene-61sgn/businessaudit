<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login_input' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginField = filter_var($request->login_input,FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile_number';

        $credentials = [
            $loginField =>  $request->login_input,
            'password' => $request->password,
        ];

        if(Auth::guard('admin')->attempt($credentials, $request->filled('remember'))){
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard')->with('Success');
        }
        throw ValidationException::withMessages([
            'login_input' => __('auth.failed'),
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerate();
        return redirect()->route('admin.login');
    }
}
