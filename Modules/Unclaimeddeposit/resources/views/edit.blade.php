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

<body id="edit-unclaimedDeposit-page">
    <div class="wrapper" id="wrapper">
        @include("cms.sidebar")
        <div class="main-content-wrapper">
            @include('cms.header')
            <section id="content-wrapper" class="content-wrapper">
                <div class="content-header-wrap">
                    <h2 class="content-title">Update</h2>
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

                    <form action="" id="edit-form" method="POST" enctype="multipart/form-data">
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
                                                    <input type="text" name="name" class="form-control" placeholder="Enter Name" value="{{ $data->name }}">
                                                </div>
                                            </div>
                                             <div class="col-lg-6">
                                                <div class="create-form-content-wrap form-input-group">
                                                    <label for="udrn_id" class="form-label"><span>*</span> UDRN ID <span>(Ex: 123456789)</span></label>
                                                    <input type="text" class="form-control" name="udrn_id" maxlength="9" inputmode="numeric" pattern="[0-9]{9}" placeholder="123456789" value="{{ $data->udrn_id }}">
                                                </div>
                                            </div>
                                        </div>
                                            </div>

                                        <div class="create-form-wrap">
                                            <div class="create-form-content-wrap form-input-group">
                                                <label for="content" class="form-label"><span>*</span> Description</label>
                                                <textarea id="content" name="content" rows="2" class="form-control">{{ $data->description }}</textarea>
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

                                            <div class="row row-gap-3">
                                                <div class="col-lg-12">
                                                    <div class="create-form-content-wrap form-input-group">
                                                        <label for="state_select" class="form-label">Select State:</label>
                                                        <select name="state_id" id="state_select" class="form-select">
                                                            @foreach($states as $state)
                                                                <option value="{{ $state->id }}"
                                                                    {{ $state->id == $data->pincode->town->city->state->id ? 'selected' : '' }}>
                                                                    {{ $state->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>

                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="create-form-content-wrap form-input-group">
                                                        <label for="city_select" class="form-label">Select City:</label>
                                                          <select name="city_id" id="city_select" class="form-select">
                                                                @foreach($cities as $city)
                                                                <option value="{{ $city->id }}"
                                                                {{ $city->id == $data->pincode->town->city->id ? 'selected' : '' }}>
                                                                        {{ $city->name }}
                                                                    </option>
                                                                @endforeach
                                                                <option value="add_new">Add New City</option>
                                                            </select>

                                                    </div>
                                                    <div class="mt-2 d-none" id="add_city_wrapper">
                                                        <input type="text" name="new_city" class="form-control" placeholder="Enter new city name">
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="create-form-content-wrap form-input-group">
                                                        <label for="town_select" class="form-label">Select Town:</label>
                                                            <select name="town_id" id="town_select" class="form-select">
                                                                @foreach($towns as $town)
                                                                    <option value="{{ $town->id }}"
                                                                {{ $town->id == $data->pincode->town->id ? 'selected' : '' }}>
                                                                        {{ $town->name }}
                                                                    </option>
                                                                @endforeach
                                                                <option value="add_new">Add New Town</option>
                                                            </select>

                                                    </div>
                                                    <div class="mt-2 d-none" id="add_town_wrapper">
                                                        <input type="text" name="new_town" class="form-control" placeholder="Enter new Town name">
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="create-form-content-wrap form-input-group">
                                                        <label for="pincode_select" class="form-label">Select Pincode:</label>
                                                           <select name="pincode_id" id="pincode_select" class="form-select">
                                                                @foreach($pincodes as $pin)
                                                                    <option value="{{ $pin->id }}"
                                                                        {{ $pin->id == $data->pincode_id ? 'selected' : '' }}>
                                                                        {{ $pin->pincode }}
                                                                    </option>
                                                                @endforeach
                                                                <option value="add_new">Add New Pincode</option>
                                                            </select>

                                                    </div>
                                                    <div class="mt-2 d-none" id="add_pincode_wrapper">
                                                        <input type="number" id="" class="form-control mt-2" name="new_pincode" placeholder="Enter Pincode" min="1" maxlength="6" pattern="[0-9]{6}">
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                        <input type="hidden" id="projectId" value="{{ $data->id }}">
                    </form>

                </div>

            </section>
            {{-- @include('cms.footer') --}}
        </div>
    </div>
            @include('cms.confirmation-model')
            @include('cms.cms-scripts')
            @include('cms.editor', ['editorType' => 'textData'])
            <script src="{{ asset('modules/unclaimeddeposit/js/unclaimed-deposit.js') }}"></script>

</body>

</html>
