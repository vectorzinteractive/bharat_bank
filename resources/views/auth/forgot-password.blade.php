<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }} - Forgot Password</title>
    @include('cms.cms-styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body id="forgot-password-page">
    <div class="content-wrap">
        <div class="container">
            <div class="login-wrapper">
                <div class="loginform">
                    <div class="login-logo">
                        @include('auth.logo')
                    </div>
                    <form method="POST" id="forgot-password-form">
                        @csrf
                        <div class="form-group inputicon">
                            <input type="email" name="email" id="email" placeholder="Enter your email">
                            <span><i class="las la-envelope"></i></span>
                        </div>
                        <div class="btns-row">
                            <button type="submit" class="button primary-btn" id="resetBtn">Send Reset Link</button>
                        </div>
                    </form>
                    <div id="forgot_response_msg" style="margin-top: 10px;"></div>
                    <h5 class="text-center"><a href="{{ url('cms-admin') }}">Back to login</a></h5>
                </div>
            </div>
        </div>
    </div>
    @include('cms.cms-scripts')
</body>
</html>
