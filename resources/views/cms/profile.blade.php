<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }} - Setting</title>
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

<body id="setting-page">
    <div class="wrapper" id="wrapper">
        @include("cms.sidebar")
        <div class="main-content-wrapper">
            @include('cms.header')
            <section id="content-wrapper" class="content-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="wcard">
                            <div class="wcard-body">
                                @include('cms.update-profile')
                            </div>
                        </div>
                        <div class="wcard">
                            <div class="wcard-body">
                                @include('cms.update-password')
                            </div>
                        </div>

                    </div>
                </div>
            </section>
        </div>
    </div>
    @include('cms.cms-scripts')
    @include('cms.confirmation-model')
</body>

</html>
