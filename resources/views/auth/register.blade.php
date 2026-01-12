<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }}</title>
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="author" content="" />
    <!-- Facebook and Twitter integration -->
    <meta property="og:title" content="" />
    <meta property="og:image" content="" />
    <meta property="og:url" content="" />
    <meta property="og:site_name" content="" />
    <meta property="og:description" content="" />
    <meta name="twitter:title" content="" />
    <meta name="twitter:image" content="" />
    <meta name="twitter:url" content="" />
    <meta name="twitter:card" content="" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('cms.cms-styles')
</head>
<body id="register-page">
    <div class="content-wrap">
        <div class="container">
            <div class="login-wrapper">
                <div class="loginform">
                    <div class="login-logo">
                         @include('auth.logo')
                    </div>
                     <form method="POST" action="" id="register-form" autocomplete="off">
                        @csrf
                        <div class="form-group inputicon">
                            <input name="email" autocomplete="off" id="email" type="text" placeholder="Email"> <span><i class="las la-envelope"></i></span>
                        </div>
                        <div class="form-group inputicon">
                            <input name="name" autocomplete="off" id="name" type="text" placeholder="Name"> <span><i class="las la-user"></i></span>
                        </div>
                        <div class="form-group inputicon">
                            <input name="password" autocomplete="off" id="password" type="password" placeholder="Password"> <span><i class="las la-lock"></i></span>
                            <span id="eye-sign" class="hideshow-icon"><img id="show-hide-img" src="{{ asset('/cls-eye.svg') }}"></span>
                        </div>
                        <div class="overlay" id="response-animation" style="display : none">
                            <div class="spinner"></div>
                        </div>
                        <div class="btns-row">
                            <button type="submit" class="button primary-btn" name="login" id="registerBtn">Submit</button>
                        </div>
                    </form>
                    <div id="file2_err"></div>
                    <h5 class="text-center">Already have an account? <a href="{{url('cms-admin/login')}}">Log in</a></h5>
                </div>
            </div>
        </div>
    </div>
    @include('cms.cms-scripts')
</body>
</html>
