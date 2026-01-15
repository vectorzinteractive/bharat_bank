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

<body id="create-town-page">
    <div class="wrapper" id="wrapper">
        @include("cms.sidebar")
        <div class="main-content-wrapper">
            @include('cms.header')
            <section id="content-wrapper" class="content-wrapper">
                <div class="content-header-wrap">
                    <h2 class="content-title">Create Town</h2>
                    <div class="action-btns">
                        <div class="sub-head">
                            <a href="{{url ('cms-admin/towns')}}" class="vi-btn vi-btn-info">
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
                            <div class="col-lg-12">
                                <div class="wcard">
                                    <div class="wcard-body">

                                        <div class="loading-animation">
                                            <div id="loadingSpinner">
                                                <div class="spinner"></div>
                                            </div>
                                        </div>

                                        <div class="create-blog-btns mb-4 vi-d-flex justify-content-end">
                                                <button type="submit" id="submit-btn" class="vi-btn vi-btn-success" name="status" value="publish">
                                                    <i class="lab la-telegram-plane"></i>Publish
                                                </button>
                                            </div>

                                        <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="create-form-content-wrap form-input-group">
                                                        <label for="city_select" class="form-label">Select City:</label>
                                                        <select name="city_id" id="city_select" class="form-select">
                                                            <option value="" disabled selected>Select City</option>
                                                            @foreach($cities as $city)
                                                                <option value="{{ $city->id }}">{{ $city->name }}</option>
                                                            @endforeach
                                                            {{-- <option value="add_new">Add New City</option> --}}
                                                        </select>
                                                    </div>
                                                    <div class="mt-2 d-none" id="add_city_wrapper">
                                                        <input type="text" name="new_city" class="form-control" placeholder="Enter new City name">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="create-form-content-wrap form-input-group">
                                                        <label for="town" class="form-label">Town</label>
                                                        <input type="text" id="" class="form-control" name="town" placeholder="">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                     <div class="create-form-content-wrap form-input-group">
                                                        <label for="slug" class="form-label">Slug <span>(Optional)</span></label>
                                                        <input type="text" id="" class="form-control" name="slug" placeholder="">
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

            <script>




</script>

</body>

</html>
