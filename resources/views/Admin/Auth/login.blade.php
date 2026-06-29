@extends('layouts.admin.auth.layout')

@section('content')
<div class="container-login100">
    <div class="wrap-login100 p-6">
        <form class="login100-form validate-form my_form" action="{{ route('admin.login.post') }}" method="POST">
            @csrf
            <span class="login100-form-title">
                تسجيل الدخول
            </span>
            <div class="wrap-input100 validate-input" data-validate="البريد الإلكتروني مطلوب">
                <input class="input100" type="email" name="email" placeholder="البريد الإلكتروني" required>
                <span class="focus-input100"></span>
                <span class="symbol-input100">
                    <i class="fe fe-mail" aria-hidden="true"></i>
                </span>
            </div>
            <div class="wrap-input100 validate-input" data-validate="كلمة المرور مطلوبة">
                <input class="input100" type="password" name="password" placeholder="كلمة المرور" required>
                <span class="focus-input100"></span>
                <span class="symbol-input100">
                    <i class="fe fe-lock" aria-hidden="true"></i>
                </span>
            </div>
            <div class="container-login100-form-btn">
                <button type="submit" class="login100-form-btn btn-primary">
                    تسجيل الدخول
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
