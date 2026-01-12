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

<body id="auction-state">
    <div class="wrapper" id="wrapper">
        @include("cms.sidebar")
        <div class="main-content-wrapper">
            @include('cms.header')
            <section id="content-wrapper" class="content-wrapper">
                <div class="content-header-wrap">
                    <h2 class="content-title">States</h2>
                    <div class="action-btns">
                        <div class="sub-head">
                            <a href="javascript:void(0)"  class="vi-btn vi-btn-primary " id="reset-form"><i class="las la-plus"></i>Add New</a>
                        </div>
                    </div>
                </div>



                <div class="row">
                    <div class="col-lg-8">
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
                                <div class="caed-body pb-0">
                                    <div id="data-block">
                                        @include('cms.auction-states-data')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="wcard">
                            <div class="wcard-body">
                                <div class="row justify-content-start">
                                    <div class="col-lg-12">
                                         <form id="add-form" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="row">
                                                <div class="create-form-content-wrap form-input-group">
                                                    <input type="hidden" id="edit_id" name="edit_id" value="">
                                                    <label for="add_data" class="form-label" id="label">Category</label>
                                                    <input type="text" class="form-control mb-4" id="edited-data" name="updated_data" value="" style="display: none">
                                                    <input type="text" class="form-control" id="add-data" name="add_data" value="" placeholder="Enter">
                                                </div>
                                                <div class="loading-animation">
                                                <div id="loadingSpinner">
                                                    <div class="spinner"></div>
                                                </div>
                                            </div>
                                                <div>
                                                    <button type="submit" id="submit-btn" class="vi-btn vi-btn-primary">
                                                        <i class="las la-plus"></i><span id="btnText">Add</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                          <div id="response-msg"></div>
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
