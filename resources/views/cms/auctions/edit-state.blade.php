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

<body id="edit-auction-state-page">
    <div class="wrapper" id="wrapper">
        @include("cms.sidebar")
        <div class="main-content-wrapper">
            @include('cms.header')
            <section id="content-wrapper" class="content-wrapper">
                <div class="content-header-wrap">
                    <h2 class="content-title">Edit State</h2>
                    <div class="action-btns">
                        <div class="sub-head">
                            <a href="{{url ('cms-admin/auction-states')}}" class="vi-btn vi-btn-info">
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
                                                    <label for="state" class="form-label">State</label>
                                                    <input type="text" id="" class="form-control" name="state" placeholder="" value="{{ $auctionState->name }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="create-form-content-wrap form-input-group">
                                                    <label for="state_slug" class="form-label">Slug <span>(Optional)</span></label>
                                                    <input type="text" id="" class="form-control" name="state_slug" placeholder="" value="{{ $auctionState->slug }}">
                                                </div>
                                            </div>
                                            <input type="hidden" id="projectId" value="{{ $auctionState->id }}">
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
