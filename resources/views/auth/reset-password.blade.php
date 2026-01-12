<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }} - Reset Password</title>
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="author" content="" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    @include('cms.cms-styles')
</head>
<body id="reset-password-page">
    <div class="content-wrap">
        <div class="container">
            <div class="login-wrapper">
                <div class="loginform">
                    <div class="login-logo">
                        @include('auth.logo')
                    </div>
                    <form method="POST" id="reset-password-form" autocomplete="off">
                        @csrf
                        <input type="hidden" name="token" value="{{ request()->route('token') }}">
                        <input type="hidden" name="email" value="{{ request()->get('email') }}">
                        <div class="form-group inputicon">
                            <input name="email" id="email" type="text" readonly
                                value="{{ request('email') }}"
                                class="form-control"
                                placeholder="Email">
                            <span><i class="las la-envelope"></i></span>
                        </div>

                        <div class="form-group inputicon">
                            <input name="password" autocomplete="off" id="password" type="password" placeholder="New Password"> <span><i class="las la-lock"></i></span>
                            <span id="eye-sign" class="hideshow-icon"><img id="show-hide-img" src="{{ asset('/cls-eye.svg') }}"></span>
                        </div>

                        <div class="overlay" id="response-animation" style="display: none">
                            <div class="spinner"></div>
                        </div>

                        <div id="reset_password_msg"></div>

                        <div class="btns-row">
                            <button type="submit" class="button primary-btn" id="resetPasswordBtn">Reset Password</button>
                        </div>
                    </form>
                    <h5 class="text-center"><a href="{{ url('cms-admin') }}">Back to Login</a></h5>
                </div>
            </div>
        </div>
    </div>
    @include('cms.cms-scripts')
</body>
</html>
