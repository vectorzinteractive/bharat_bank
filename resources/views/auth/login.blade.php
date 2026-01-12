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
<body id="login-page">
    <div class="content-wrap">
        <div class="container">
            <div class="login-wrapper">
                <div class="loginform">
                    <div class="login-logo">
                         @include('auth.logo')
                    </div>
                     <form method="POST" id="login-form" autocomplete="off">
                        @csrf
                        <div class="form-group inputicon">
                            <input name="email" autocomplete="off" id="email" type="email" placeholder="Email" aria-required="true" class="error   mt-0" aria-invalid="true"> <span><i class="las la-envelope"></i></span>
                        </div>
                        <div class="form-group inputicon">
                            <input name="password" autocomplete="off" id="password" type="password" placeholder="Password" class="mt-0"> <span><i class="las la-lock"></i></span>
                            <span id="eye-sign" class="hideshow-icon"><img id="show-hide-img" src="{{ asset('/cls-eye.svg') }}"></span>
                        </div>
                        <div class="block mt-3">
                            <label for="remember_me" class="inline-flex items-center">
                                <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
                            </label>
                        </div>
                        <div class="overlay" id="response-animation" style="display : none">
                            <div class="spinner"></div>
                        </div>
                        <h5><a href="{{url('cms-admin/forgot-password')}}">Forgot Password?</a></h5>
                        <div class="btns-row">
                            <button type="submit" class="button primary-btn" name="login" id="login-btn">Submit</button>
                        </div>
                    </form>
                    <div id="file2_err"></div>
                    {{-- @if(!$userExists)
                        <h5 class="text-center">New? <a href="{{ url('register') }}">Sign up!</a></h5>
                    @endif --}}

                </div>
            </div>
        </div>
    </div>
    @include('cms.cms-scripts')
</body>
</html>
