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

<body id="towns-page">
    <div class="wrapper" id="wrapper">
        @include("cms.sidebar")
        <div class="main-content-wrapper">
            @include('cms.header')
            <section id="content-wrapper" class="content-wrapper">
                <div class="content-header-wrap">
                    <h2 class="content-title">Towns</h2>
                    <div class="action-btns">
                        <div class="sub-head">
                            <a href="{{ url('cms-admin/towns/create')}}"  class="vi-btn vi-btn-primary " id="reset-form"><i class="las la-plus"></i>Add New</a>
                        </div>
                    </div>
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
                                <div class="vi-d-flex mb-4">

                                    <div class="bulk-operations vi-d-flex" id="bulkOperations" style="display: none;">

                                        <a href="javascript:void(0)" id="bulkDeleteBtn" class="bulk-action vi-btn vi-btn-danger" data-action="bulkDelete" title="Delete">
                                            <i class='las la-trash'></i>Delete
                                        </a>

                                    </div>

                                </div>
                                <div class="caed-body pb-0">
                                    <div id="data-block">
                                        @include('cms.location.town-data')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            {{-- @include('cms.footer') --}}
        </div>
    </div>
            @include('cms.confirmation-model')
            @include('cms.cms-scripts')
</body>

</html>
