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

<body id="edit-auction-page">
    <div class="wrapper" id="wrapper">
        @include("cms.sidebar")
        <div class="main-content-wrapper">
            @include('cms.header')
            <section id="content-wrapper" class="content-wrapper">
                <div class="content-header-wrap">
                    <h2 class="content-title">Edit</h2>
                    <div class="action-btns">
                        <div class="sub-head">
                            <a href="{{url ('cms-admin/auctions')}}" class="vi-btn vi-btn-info">
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
                                            <div class="create-form-content-wrap form-input-group">
                                                <label for="content" class="form-label">Description</label>
                                                <textarea id="content" name="content" rows="2" class="form-control">{{ $auction->description }}</textarea>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="wcard">
                                    <div class="wcard-body">
                                        <h3 class="mb-4">Location</h3>
                                        <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="create-form-content-wrap form-input-group">
                                                        <label for="state_select" class="form-label">Select State:</label>
                                                        @if ($states->isEmpty())
                                                            <p>No States available</p>
                                                        @else
                                                            <select name="state_id" id="state_select" class="form-select">
                                                                <option value="" disabled>Select State</option>

                                                                @foreach($states as $stateData)
                                                                    <option value="{{ $stateData->id }}"
                                                                        {{ $auction->state_id == $stateData->id ? 'selected' : '' }}>
                                                                        {{ $stateData->name }}
                                                                    </option>
                                                                @endforeach

                                                                <option value="add_new">Add New State</option>
                                                            </select>
                                                        @endif

                                                    </div>
                                                    <div class="mt-2 d-none" id="add_state_wrapper">
                                                        <input type="text" name="new_state" class="form-control" placeholder="Enter new state name">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="create-form-content-wrap form-input-group">
                                                        <label for="city_select" class="form-label">Select City:</label>
                                                        @if ($cities->isEmpty())
                                                            <p>No City available</p>
                                                        @else
                                                            <select name="city_id" id="city_select" class="form-select">
                                                                <option value="" disabled selected>Select City</option>
                                                                @foreach($cities as $cityData)
                                                                    <option value="{{ $cityData->id }}"
                                                                        {{ $auction->city_id == $cityData->id ? 'selected' : '' }}>
                                                                        {{ $cityData->name }}
                                                                    </option>
                                                                @endforeach
                                                                <option value="add_new">Add New City</option>
                                                            </select>
                                                        @endif
                                                    </div>
                                                    <div class="mt-2 d-none" id="add_city_wrapper">
                                                        <input type="text" name="new_city" class="form-control" placeholder="Enter new city name">
                                                        <input type="text" id="" class="form-control mt-2" name="new_pincode" placeholder="Enter Pincode" min="1" maxlength="6" pattern="[0-9]{6}">
                                                    </div>
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

                                            <div class="create-form-wrap">
                                                <div class="create-form-content-wrap form-input-group">
                                                    <label for="price" class="form-label">Price</label>
                                                    <input type="number" id="" class="form-control" name="price" placeholder="" min="1" step="1" value="{{ $auction->price }}">
                                                </div>

                                                <div class="create-form-content-wrap form-input-group">
                                                    <label for="square_feet" class="form-label">Square Feet(sq.ft)</label>
                                                    <input type="number" id="" class="form-control" name="square_feet" placeholder="" min="1" step="1" value="{{ $auction->sq_ft }}">
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                        <input type="hidden" id="projectId" value="{{ $auction->id }}">

                    </form>

                </div>

            </section>
            {{-- @include('cms.footer') --}}
        </div>
    </div>
            @include('cms.confirmation-model')
            @include('cms.cms-scripts')
            @include('cms.editor')
            <script>




</script>

</body>

</html>
