<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">

    <title>{{ config('app.name') }}</title>
    <meta name="description" content="">
    <meta name="robots" content="">
    <meta name="author" content="apvaastu">
    <meta name="keywords" content=""/>
    <link rel="canonical" href="" />
    <link rel="alternate" href="" hreflang="en-in"/>
    <meta property="og:title" content="apvaastu">
    <meta property="og:site_name" content="apvaastu">
    <meta property="og:url" content="">
    <meta property="og:description" content="">
    <meta property="og:type" content="website">
    <meta property="og:image" content="">
    <meta property="og:locale" content="en_IN"/>
    <meta property="twitter:card" content="summary">
    <meta property="twitter:site" content="">
    <meta property="twitter:title" content="apvaastu">
    <meta property="twitter:description" content="">

    @include('styles')
</head>

<body id="unclaimed-deposit-list">
    <div class="wrapper">
        {{-- @include('header') --}}

        <!-- page preloader begin -->
        <div id="de-loader"></div>
        <!-- page preloader close -->

       <section id="section-hero" class="section-dark text-light sec-breadcrumb no-top no-bottom relative overflow-hidden mh-400 jarallax">
            <img src="{{ asset('/assets/img/blog.webp') }}" class="jarallax-img h-100" alt="">
            <div class="gradient-edge-top op-6"></div>
            <div class="abs w-80 bottom-10 z-2 w-100">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="relative overflow-hidden">
                                <div class="wow fadeInUpBig" data-wow-duration="1.5s">
                                    <h1 class="fs-120 text-uppercase fs-sm-10vw mb-2 lh-1">Unclaimed Deposits</h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="sw-overlay op-5"></div>
        </section>

        <section class="bg-light">
            <div class="container mt-5">
            <div class="row">
    <div class="col-lg-3">
        <div class="filter-panel">
            <div class="filter-section">
                <input type="text" id="unclaimedDeposit-search" placeholder="Search description, state, city, town" class="form-control mb-3">
            </div>

            <div class="filter-section">
                <strong>State</strong>
                @foreach($states as $state)
                <label><input type="checkbox" class="state-checkbox" name="state[]" value="{{ $state->id }}"> {{ $state->name }}</label>
                @endforeach
            </div>

            <div class="filter-section">
                <strong>City</strong>
                @foreach($cities as $city)
                <label><input type="checkbox" class="city-checkbox" name="city[]" value="{{ $city->id }}"> {{ $city->name }}</label>
                @endforeach
            </div>

            <div class="filter-section">
                <strong>Town</strong>
                @foreach($towns as $town)
                <label><input type="checkbox" class="town-checkbox" name="town[]" value="{{ $town->id }}"> {{ $town->name }}</label>
                @endforeach
            </div>

        </div>
    </div>

    <div class="col-lg-9">
        <div id="unclaimed-results">
            @include('unclaimed-deposit-data', ['data' => $data])
        </div>
    </div>
</div>

        </div>

        </section>

    </div>

    {{-- @include('footer') --}}
    @include('scripts')


</body>

</html>
