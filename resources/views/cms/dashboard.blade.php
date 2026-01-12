<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }} - Dashboard</title>
    @include('cms.cms-styles')
</head>
<body id="index">
<div class="wrapper" id="wrapper">
    @include("cms.sidebar")
    <div class="main-content-wrapper">
        @include('cms.header')
        <section id="content-wrapper" class="content-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <div class="wcard">
                        <div class="wcard-body">
                            <div class="card-body pb-0">

                                <div class="wcard-flex-header">
                                    <h2 class="content-title">Dashboard</h2>
                                </div>

                                <div class="wcard card-header align-items-start border-0"></div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@include('cms.cms-scripts')
</body>
</html>
