<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }
public function login(Request $request)
{
    $request->validate([
        'login' => 'required',
        'password' => 'required',
    ]);

    $login_type = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

    if (is_numeric($request->login)) {
        $login_type = 'phone';
    }

    $credentials = [
        $login_type => $request->login,
        'password' => $request->password,
    ];

    if (Auth::guard('admin')->attempt($credentials)) {
        return redirect()->route('dashboard');
    }

    return back()->withErrors([
        'login' => 'Invalid credentials'
    ]);
}
    public function logout()
{
    Auth::guard('admin')->logout();
    return redirect()->route('admin.login');
}

}
