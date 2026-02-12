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

<body id="admin-register">
    <div class="wrapper" id="wrapper">
        @include("cms.sidebar")
        <div class="main-content-wrapper">
            @include('cms.header')
            <section id="content-wrapper" class="content-wrapper">

                <div class="content-header-wrap">
                    <h2 class="content-title">Create New User</h2>
                    <div class="action-btns">
                        <div class="sub-head">
                            <a href="{{url ('cms-admin/users')}}" class="vi-btn vi-btn-info">
                                <i class="las la-angle-left"></i>Back
                            </a>
                        </div>
                    </div>
                </div>

                <div class="form-wrap">

                    <form method="POST" action="" id="register-form" autocomplete="off">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="wcard">
                                    <div class="wcard-body">

                                        <div class="create-form-wrap row">

                                            <div class="col-lg-6">
                                                <div class="create-form-content-wrap form-input-group inputicon">
                                                    <input name="email" class="form-control" autocomplete="off" id="email" type="text" placeholder="Email"> <span><i class="las la-envelope"></i></span>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="create-form-content-wrap form-input-group inputicon">
                                                    <input name="name" class="form-control" autocomplete="off" id="name" type="text" placeholder="Name"> <span><i class="las la-user"></i></span>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="create-form-content-wrap form-input-group inputicon">
                                                    <input name="password" class="form-control" autocomplete="off" id="password" type="password" placeholder="Password"> <span><i class="las la-lock"></i></span>
                                                    <span id="eye-sign" class="hideshow-icon"><img id="show-hide-img" src="{{ asset('/backend/images/cls-eye.svg') }}"></span>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="create-form-content-wrap form-input-group inputicon">
                                                    <select name="role" id="role" class="form-select">
                                                        <option value="" disabled selected>Select Role</option>
                                                        @foreach($roles as $role)
                                                            <option value="{{ $role->name }}">
                                                                {{ ucfirst($role->name) }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                </div>
                                            </div>



                                        </div>

                                    </div>
                                </div>
                                <div class="wcard">
                                    <div class="wcard-body">
                                        <h3 class="mb-4">Module Access</h3>
                                        <div class="row row-gap-3">
                                            <div class="form-input-group">
                                                <div class="input-list flex-container">
                                                    @foreach($permissions as $permission)
                                                    <div class="category-list custom-checkbox">
                                                        <input type="checkbox" class="form-check-input" name="permissions[]" value="{{ $permission->name }}"
                                                                            >
                                                                            {{ ucfirst(str_replace('.access', '', $permission->name)) }}
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>

                                    </div>
                                </div>
                                <div class="overlay" id="response-animation" style="display : none">
                                                <div class="spinner"></div>
                                            </div>

                                            <div class="btns-row">
                                                <button type="submit" class="button primary-btn" name="login" id="registerBtn">Submit</button>
                                            </div>
                                            <div id="file2_err"></div>
                            </div>
                        </div>

                    </form>

                </div>



            </section>
        </div>
    </div>
            @include('cms.cms-scripts')
</body>

</html>
