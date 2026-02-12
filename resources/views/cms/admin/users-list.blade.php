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

<body id="user-list">
    <div class="wrapper" id="wrapper">
        @include("cms.sidebar")
        <div class="main-content-wrapper">
            @include('cms.header')
            <section id="content-wrapper" class="content-wrapper">
                <div class="content-header-wrap">
                    <h2 class="content-title mb-3">Users </h2>
                    @if(auth()->user()->hasAnyRole(['super-admin', 'admin']))
                        <div class="action-btns">
                            <div class="sub-head">
                                <a href="{{ url('cms-admin/users/create') }}" class="vi-btn vi-btn-primary">
                                    <i class="las la-plus"></i> Add New
                                </a>
                            </div>
                        </div>
                    @endif


                </div>


                <div class="row">
                    <div class="col-lg-12">
                        <div class="wcard">
                             <div class="wcard-flex-header card-header">
                                    <form id="filterForm" class="filter-form vi-d-flex">
                                        <div class="search-bar">
                                            <div class="search-icon">
                                                <i class="las la-search"></i>
                                            </div>
                                            <input class="form-control" name="filter[search]" type="search" id="search-input">
                                        </div>
                                        <div class="date-wrap vi-d-flex">
                                            <input type="date" name="filter[date_range_from]" class="search-date form-control"/>
                                            <input type="date" name="filter[date_range_to]" class="search-date form-control"/>
                                        </div>
                                    </form>
                            </div>
                            <div class="wcard-body">

                                <div id="data-list">
                                    @include('cms.admin.users-data')
                                 </div>

                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
            @include('cms.confirmation-model')
            @include('cms.cms-scripts')
</body>

</html>
