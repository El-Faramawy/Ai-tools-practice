<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function loginView()
    {
        if (auth()->guard('admin')->check()) {
            return redirect()->route('admin.home');
        }
        return view('Admin.Auth.login');
    }

    public function login(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صالح',
            'password.required' => 'كلمة المرور مطلوبة',
        ]);

        if ($validator->fails()) {
            return response()->json(['messages' => $validator->errors()->getMessages()], 422);
        }

        $credentials = $request->only('email', 'password');

        if (auth()->guard('admin')->attempt($credentials, $request->filled('remember'))) {
            return response()->json([
                'message' => 'تم تسجيل الدخول بنجاح',
                'url' => route('admin.home'),
            ]);
        }

        return response()->json([
            'errors' => ['error' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة']
        ], 422);
    }

    public function logout()
    {
        auth()->guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
