<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AuthRequest;

class AuthController extends Controller
{
    // Constructor
    public function __construct()
    {
        
    }

    // Trang index dẫn về trang đăng nhập
    public function index() {
        // if(Auth::id() > 0) {
        //     return redirect()->route('dashboard.index');
        // }
        return view('backend.auth.login');
    }

    // Login
    public function login(AuthRequest $request) {

        // Các trường đăng nhập
        $credentials = [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ];

        // Nếu đăng nhập thành công
        if(Auth::attempt($credentials)) {
            toastr()->success("Đăng nhập thành công.");
            return redirect()->route('dashboard.index');
        }

        // Nếu đăng nhập không thành công
        toastr()->error("Email hoặc mật khẩu không đúng.");
        return redirect()->route('auth.admin');
    }

    // Logout
    public function logout(Request $request) {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth.admin');
    }
}
