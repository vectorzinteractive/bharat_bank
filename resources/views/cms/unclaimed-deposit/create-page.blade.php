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

<body id="create-unclaimedDeposit-page">
    <div class="wrapper" id="wrapper">
        @include("cms.sidebar")
        <div class="main-content-wrapper">
            @include('cms.header')
            <section id="content-wrapper" class="content-wrapper">
                <div class="content-header-wrap">
                    <h2 class="content-title">Create</h2>
                    <div class="action-btns">
                        <div class="sub-head">
                            <a href="{{url ('cms-admin/unclaimed-deposit')}}" class="vi-btn vi-btn-info">
                                <i class="las la-angle-left"></i>Back
                            </a>
                        </div>
                    </div>
                </div>

                <div class="form-wrap">
                    <div id="response-msg"></div>

                    <form action="" id="create-form" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="wcard">
                                    <div class="wcard-body">

                                        <div class="loading-animation">
                                            <div id="loadingSpinner">
                                                <div class="spinner"></div>
                                            </div>
                                        </div>


                                        <div class="create-form-wrap">
                                             <div class="row">
                                            <div class="col-lg-6">
                                                <div class="create-form-content-wrap form-input-group">
                                                    <label for="name" class="form-label"><span>*</span> Name</label>
                                                    <input type="text" name="name" class="form-control" placeholder="Enter Name">
                                                </div>
                                            </div>
                                             <div class="col-lg-6">
                                                <div class="create-form-content-wrap form-input-group">
                                                    <label for="udrn_id" class="form-label"><span>*</span> UDRN ID <span>(Ex: 123456789)</span></label>
                                                    <input type="text" class="form-control" name="udrn_id" maxlength="9" inputmode="numeric" pattern="[0-9]{9}" placeholder="123456789">
                                                </div>
                                            </div>
                                        </div>
                                            </div>

                                        <div class="create-form-wrap">
                                            <div class="create-form-content-wrap form-input-group">
                                                <label for="content" class="form-label"><span>*</span> Description</label>
                                                <textarea id="content" name="content" rows="2" class="form-control"></textarea>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>

                            <div class="col-lg-4">
                                <div class="sticky-wcard">
                                    <div class="wcard">
                                        <div class="wcard-body">

                                            <div class="create-blog-btns mb-4 vi-d-flex justify-content-end">

                                                <button type="submit" id="submit-btn" class="vi-btn vi-btn-success" name="status" value="publish">
                                                    <i class="lab la-telegram-plane"></i>Publish
                                                </button>
                                            </div>

                                                    <div class="create-form-content-wrap form-input-group">
                                                        <label for="state_select" class="form-label"><span>*</span> Select State:</label>
                                                        <select name="state_id" id="state_select" class="form-select">
                                                            <option value="" disabled selected>Select State</option>
                                                            @foreach($states as $state)
                                                                <option value="{{ $state->id }}">{{ $state->name }}</option>
                                                            @endforeach
                                                            {{-- <option value="add_new">Add New State</option> --}}
                                                        </select>
                                                    </div>


                                        </div>
                                    </div>
                                    <div class="wcard">
                                        <div class="wcard-body">
                                            <div class="create-form-content-wrap form-input-group">
                                                        <label for="city_select" class="form-label"><span>*</span> Select City:</label>
                                                            <select name="city_id" id="city_select" class="form-select" disabled>
                                                                <option value="" selected>Select City</option>
                                                                <option value="add_new">Add New City</option>
                                                            </select>
                                                            <div class="mt-2 d-none" id="add_city_wrapper">
                                                                <input type="text" name="new_city" class="form-control" placeholder="Enter new city name">
                                                            </div>
                                                    </div>
                                        </div>
                                    </div>
                                    <div class="wcard">
                                        <div class="wcard-body">
                                            <div class="create-form-content-wrap form-input-group">
                                                        <label for="town_select" class="form-label"><span>*</span> Select Town:</label>
                                                            <select name="town_id" id="town_select" class="form-select" disabled>
                                                                <option value="" selected>Select Town</option>
                                                                <option value="add_new">Add New Town</option>
                                                            </select>
                                                            <div class="mt-2 d-none" id="add_town_wrapper">
                                                                <input type="text" name="new_town" class="form-control" placeholder="Enter new Town name">
                                                            </div>
                                                    </div>
                                        </div>
                                    </div>
                                    <div class="wcard">
                                        <div class="wcard-body">
                                            <div class="create-form-content-wrap form-input-group">
                                                        <label for="pincode_select" class="form-label"><span>*</span> Select Pincode:</label>
                                                            <select name="pincode_id" id="pincode_select" class="form-select" disabled>
                                                                <option value="" selected>Select Pincode</option>
                                                                <option value="add_new">Add New Pincode</option>
                                                            </select>
                                                            <div class="mt-2 d-none" id="add_pincode_wrapper">
                                                                <input type="number" id="" class="form-control mt-2" name="new_pincode" placeholder="Enter Pincode" min="1" maxlength="6" pattern="[0-9]{6}">
                                                            </div>
                                                    </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>

                    </form>

                </div>

            </section>
            {{-- @include('cms.footer') --}}
        </div>
    </div>
            @include('cms.confirmation-model')
            @include('cms.cms-scripts')
            @include('cms.editor', ['editorType' => 'textData'])

</body>

</html>
