document.addEventListener("DOMContentLoaded", function(){

if(page_id === "creat-auction-page" || page_id === "edit-auction-page") {
    handleAddNew("city_select", "city");
    handleAddNew("town_select", "town");
    handleAddNew("pincode_select", "pincode");
    LocationLoader();
    $(document).ready(function () {
    let formSelector = (page_id === "creat-auction-page") ? "#create-form" : "#edit-form";
    if ($(formSelector).length) {
        $(formSelector).validate({
            ignore: ":hidden, :disabled",
            rules: {
                content: { required: true },
                state_id: { required: true },
                city_id: {
                    required: function() { return $('#state_select').val() !== 'add_new'; }
                },
                new_city: {
                    required: function() { return $('#city_select').val() === 'add_new'; }
                },
                town_id: {
                    required: function() { return $('#city_select').val() !== 'add_new'; }
                },
                new_town: {
                    required: function() { return $('#town_select').val() === 'add_new'; }
                },
                pincode_id: {
                    required: function () {
                        return (
                            $('#town_select').val() !== 'add_new' &&
                            $('#pincode_select').val() !== 'add_new'
                        );
                    }
                },
                new_pincode: {
                    required: function () {
                        return $('#pincode_select').val() === 'add_new';
                    },
                    digits: true,
                    minlength: 6,
                    maxlength: 6
                },
                price: { required: true, number: true, min: 1 }
            },
            messages: {
                content: { required: "* Please enter a description" },
                state_id: { required: "* Please select a state" },
                 city_id: { required: "* Please select a city" },
                new_city: { required: "* Please enter the city" },

                town_id: { required: "* Please select a town" },
                new_town: { required: "* Please enter the town" },

                pincode_id: { required: "* Please select a pincode" },
                new_pincode: {
                    required: "* Please enter the pincode",
                    digits: "* Pincode must be digits only",
                    minlength: "* Pincode must be 6 digits",
                    maxlength: "* Pincode must be 6 digits"
                },

                price: {
                    required: "* Please enter the price",
                    number: "* Price must be a number",
                    min: "* Price must be at least 1"
                }
            },
            errorClass: "error",
            errorElement: "div",
            onfocusout: function(element) {
                if (!$(element).is(":file")) { this.element(element); }
            },
            onkeyup: false,
            submitHandler: function(form, event) {
                event.preventDefault();
                $('#loadingSpinner').show();
                let submitBtn = $(form).find("button[type='submit']");
                submitBtn.hide();
                var formData = new FormData(form);

                let ajaxUrl = baseurl + 'cms-admin/auctions';
                let ajaxType = 'POST';

                if(page_id === "edit-auction-page") {
                    formData.append('_method', 'PUT');
                    ajaxUrl += '/' + $('#projectId').val();
                }

                $.ajax({
                    url: ajaxUrl,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function(response) {
                        $('#loadingSpinner').hide();
                        submitBtn.show();
                        if(response.status === 'success') {
                            if(page_id === "creat-auction-page") {
                                $(form)[0].reset();
                                window.location.href = baseurl + "cms-admin/auctions";
                            } else {
                                openDynamicModal({ heading: 'Success', message: response.message });
                                setTimeout(function(){ location.reload(); }, 2000);
                            }
                        } else {
                            showErrorMessage(responseBlock, { responseJSON: { message: response.message } });
                        }
                    },
                    error: function(xhr) {
                        $('#loadingSpinner').hide();
                        submitBtn.show();
                        showErrorMessage(responseBlock, xhr);
                    }
                });
            }
        });
    }
});
}
if(page_id === "auction-list"){
    // ------------------bulk-action-start--------------------
    $(document).on('click', '.bulk-action', function () {
        let ProjectBulkactionType = $(this).data('action');
        if (ProjectBulkactionType === 'bulkDelete') {
            ProjectBulkActionUrl = baseurl + 'cms-admin/auctions/bulk-delete-items';
            handleBulkAction(ProjectBulkActionUrl, '#data-table', 'DELETE');
        }
    });
    // ------------------bulk-action-start--------------------
    // ------------------serach-function-start------------------
    let auctionUrl = baseurl + "cms-admin/auctions";
    initDynamicTable(auctionUrl, '#data-block', '#filterForm');
    // ------------------serach-function-end------------------

    $(document).on('click', '.DeleteBtn', function () {
                let deleteId = $(this).data('project-id');

                $('#modal-heading').text( 'Confirm Deletion');

                $('#modal-body').text('Are you sure you want to delete this?');

                $('#confirmDeleteModal').modal('show');

                $('#confirm')
                    .off('click')
                    .on('click', function () {
                        $.ajax({
                            url: baseurl + "cms-admin/auctions/" + deleteId,
                            type: "DELETE",
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {
                                updateTableAfterAction(deleteId, '#data-table');
                                $('#confirmDeleteModal').modal('hide');
                            },
                            error: function () {
                                $('#modal-body')
                                    .text('An error occurred. Please try again.')
                                    .addClass('text-danger');
                            }
                        });
                    });
            });


}

if(page_id === "create-unclaimedDeposit-page" || page_id === "edit-unclaimedDeposit-page") {
    handleAddNew("city_select", "city");
    handleAddNew("town_select", "town");
    handleAddNew("pincode_select", "pincode");
    LocationLoader();
    $(document).ready(function () {
    let formSelector = (page_id === "create-unclaimedDeposit-page") ? "#create-form" : "#edit-form";
    if ($(formSelector).length) {
        $(formSelector).validate({
            ignore: ":hidden, :disabled",
            rules: {
                content: { required: true },
                state_id: { required: true },
                city_id: {
                    required: function() { return $('#state_select').val() !== 'add_new'; }
                },
                new_city: {
                    required: function() { return $('#city_select').val() === 'add_new'; }
                },
                town_id: {
                    required: function() { return $('#city_select').val() !== 'add_new'; }
                },
                new_town: {
                    required: function() { return $('#town_select').val() === 'add_new'; }
                },
                pincode_id: {
                    required: function () {
                        return (
                            $('#town_select').val() !== 'add_new' &&
                            $('#pincode_select').val() !== 'add_new'
                        );
                    }
                },
                new_pincode: {
                    required: function () {
                        return $('#pincode_select').val() === 'add_new';
                    },
                    digits: true,
                    minlength: 6,
                    maxlength: 6
                },
                name: { required: true},
                udrn_id: {
                    required: true,
                    digits: true,
                    minlength: 9,
                    maxlength: 9
                }
            },
            messages: {
                content: { required: "* Please enter a description" },
                state_id: { required: "* Please select a state" },
                 city_id: { required: "* Please select a city" },
                new_city: { required: "* Please enter the city" },

                town_id: { required: "* Please select a town" },
                new_town: { required: "* Please enter the town" },

                pincode_id: { required: "* Please select a pincode" },
                new_pincode: {
                    required: "* Please enter the pincode",
                    digits: "* Pincode must be digits only",
                    minlength: "* Pincode must be 6 digits",
                    maxlength: "* Pincode must be 6 digits"
                },

                name: {
                    required: "* Please enter Name",
                },
                udrn_id: {
                    required: "* Enter UDRN Id",
                    number: "* UDRN Id must be a number",
                    minlength: "* UDRN Id must be 9 digits",
                    maxlength: "* UDRN Id must be 9 digits"
                }
            },
            errorClass: "error",
            errorElement: "div",
            onfocusout: function(element) {
                if (!$(element).is(":file")) { this.element(element); }
            },
            onkeyup: false,
            submitHandler: function(form, event) {
                event.preventDefault();
                $('#loadingSpinner').show();
                let submitBtn = $(form).find("button[type='submit']");
                submitBtn.hide();
                var formData = new FormData(form);

                let ajaxUrl = baseurl + 'cms-admin/unclaimed-deposit';
                let ajaxType = 'POST';

                if(page_id === "edit-unclaimedDeposit-page") {
                    formData.append('_method', 'PUT');
                    ajaxUrl += '/' + $('#projectId').val();
                }

                $.ajax({
                    url: ajaxUrl,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function(response) {
                        $('#loadingSpinner').hide();
                        submitBtn.show();
                        if(response.status === 'success') {
                            if(page_id === "create-unclaimedDeposit-page") {
                                $(form)[0].reset();
                                window.location.href = baseurl + "cms-admin/unclaimed-deposit";
                            } else {
                                openDynamicModal({ heading: 'Success', message: response.message });
                                setTimeout(function(){ location.reload(); }, 2000);
                            }
                        } else {
                            showErrorMessage(responseBlock, { responseJSON: { message: response.message } });
                        }
                    },
                    error: function(xhr) {
                        $('#loadingSpinner').hide();
                        submitBtn.show();
                        showErrorMessage(responseBlock, xhr);
                    }
                });
            }
        });
    }
});
}

if(page_id === "unclaimedDeposit-list"){
    // ------------------bulk-action-start--------------------
    $(document).on('click', '.bulk-action', function () {
        let ProjectBulkactionType = $(this).data('action');
        if (ProjectBulkactionType === 'bulkDelete') {
            ProjectBulkActionUrl = baseurl + 'cms-admin/unclaimed-deposit/bulk-delete-items';
            handleBulkAction(ProjectBulkActionUrl, '#data-table', 'DELETE');
        }
    });
    // ------------------bulk-action-start--------------------
    // ------------------serach-function-start------------------
    let auctionUrl = baseurl + "cms-admin/unclaimed-deposit";
    initDynamicTable(auctionUrl, '#data-block', '#filterForm');
    // ------------------serach-function-end------------------

    $(document).on('click', '.DeleteBtn', function () {
                let deleteId = $(this).data('project-id');

                $('#modal-heading').text( 'Confirm Deletion');

                $('#modal-body').text('Are you sure you want to delete this?');

                $('#confirmDeleteModal').modal('show');

                $('#confirm')
                    .off('click')
                    .on('click', function () {
                        $.ajax({
                            url: baseurl + "cms-admin/unclaimed-deposit/" + deleteId,
                            type: "DELETE",
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {
                                updateTableAfterAction(deleteId, '#data-table');
                                $('#confirmDeleteModal').modal('hide');
                            },
                            error: function () {
                                $('#modal-body')
                                    .text('An error occurred. Please try again.')
                                    .addClass('text-danger');
                            }
                        });
                    });
            });


}

if(page_id === "create-state-page" || page_id === "edit-state-page") {
    $(document).ready(function () {
    let formSelector = (page_id === "create-state-page") ? "#create-form" : "#edit-form";
    if ($(formSelector).length) {
        $(formSelector).validate({
            ignore: [],
            rules: {
                state: { required: true }
            },
            messages: {
                state: {
                    required: "* Please enter State Name",
                }
            },
            errorClass: "error",
            errorElement: "div",
            onfocusout: function(element) {
                if (!$(element).is(":file")) { this.element(element); }
            },
            onkeyup: false,
            submitHandler: function(form, event) {
                event.preventDefault();
                $('#loadingSpinner').show();
                let submitBtn = $(form).find("button[type='submit']");
                submitBtn.hide();
                var formData = new FormData(form);

                let ajaxUrl = baseurl + 'cms-admin/states';
                let ajaxType = 'POST';

                if(page_id === "edit-state-page") {
                    formData.append('_method', 'PUT');
                    ajaxUrl += '/' + $('#projectId').val();
                }

                $.ajax({
                    url: ajaxUrl,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function(response) {
                        $('#loadingSpinner').hide();
                        submitBtn.show();
                        if(response.status === 'success') {
                            if(page_id === "create-state-page") {
                                $(form)[0].reset();
                                window.location.href = baseurl + "cms-admin/states";
                            } else {
                                openDynamicModal({ heading: 'Success', message: response.message });
                                setTimeout(function(){ location.reload(); }, 2000);
                            }
                        } else {
                            showErrorMessage(responseBlock, { responseJSON: { message: response.message } });
                        }
                    },
                    error: function(xhr) {
                        $('#loadingSpinner').hide();
                        submitBtn.show();
                        showErrorMessage(responseBlock, xhr);
                    }
                });
            }
        });
    }
});
}
if(page_id === "state-page"){
    // ------------------bulk-action-start--------------------
    $(document).on('click', '.bulk-action', function () {
        let ProjectBulkactionType = $(this).data('action');
        if (ProjectBulkactionType === 'bulkDelete') {
            ProjectBulkActionUrl = baseurl + 'cms-admin/states/bulk-delete-items';
            handleBulkAction(ProjectBulkActionUrl, '#data-table', 'DELETE');
        }
    });
    // ------------------bulk-action-start--------------------
    // ------------------serach-function-start------------------
    let auctionUrl = baseurl + "cms-admin/states";
    initDynamicTable(auctionUrl, '#data-block', '#filterForm');
    // ------------------serach-function-end------------------

    $(document).on('click', '.DeleteBtn', function () {
                let deleteId = $(this).data('project-id');

                $('#modal-heading').text( 'Confirm Deletion');

                $('#modal-body').text('Are you sure you want to delete this?');

                $('#confirmDeleteModal').modal('show');

                $('#confirm')
                    .off('click')
                    .on('click', function () {
                        $.ajax({
                            url: baseurl + "cms-admin/states/" + deleteId,
                            type: "DELETE",
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {
                                updateTableAfterAction(deleteId, '#data-table');
                                $('#confirmDeleteModal').modal('hide');
                            },
                            error: function () {
                                $('#modal-body')
                                    .text('An error occurred. Please try again.')
                                    .addClass('text-danger');
                            }
                        });
                    });
            });


}
if(page_id === "create-city-page" || page_id === "edit-city-page") {
    handleAddNew("state_select", "state");
    $(document).ready(function () {
    let formSelector = (page_id === "create-city-page") ? "#create-form" : "#edit-form";
    if ($(formSelector).length) {
        $(formSelector).validate({
            ignore: [],
            rules: {
                state_id: { required: true },
                city: { required: true },
                new_state: {
                    required: function() { return $('#state_select').val() === 'add_new'; }
                },
            },
            messages: {
                state_id: { required: "* Please select a state" },
                city: { required: "* Please Enter City Name" },
            },
            errorClass: "error",
            errorElement: "div",
            onfocusout: function(element) {
                if (!$(element).is(":file")) { this.element(element); }
            },
            onkeyup: false,
            submitHandler: function(form, event) {
                event.preventDefault();
                $('#loadingSpinner').show();
                let submitBtn = $(form).find("button[type='submit']");
                submitBtn.hide();
                var formData = new FormData(form);

                let ajaxUrl = baseurl + 'cms-admin/cities';
                let ajaxType = 'POST';

                if(page_id === "edit-city-page") {
                    formData.append('_method', 'PUT');
                    ajaxUrl += '/' + $('#projectId').val();
                }

                $.ajax({
                    url: ajaxUrl,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function(response) {
                        $('#loadingSpinner').hide();
                        submitBtn.show();
                        if(response.status === 'success') {
                            if(page_id === "create-city-page") {
                                $(form)[0].reset();
                                window.location.href = baseurl + "cms-admin/cities";
                            } else {
                                openDynamicModal({ heading: 'Success', message: response.message });
                                setTimeout(function(){ location.reload(); }, 2000);
                            }
                        } else {
                            showErrorMessage(responseBlock, { responseJSON: { message: response.message } });
                        }
                    },
                    error: function(xhr) {
                        $('#loadingSpinner').hide();
                        submitBtn.show();
                        showErrorMessage(responseBlock, xhr);
                    }
                });
            }
        });
    }
});
}
if(page_id === "cities-page"){
    // ------------------bulk-action-start--------------------
    $(document).on('click', '.bulk-action', function () {
        let ProjectBulkactionType = $(this).data('action');
        if (ProjectBulkactionType === 'bulkDelete') {
            ProjectBulkActionUrl = baseurl + 'cms-admin/cities/bulk-delete-items';
            handleBulkAction(ProjectBulkActionUrl, '#data-table', 'DELETE');
        }
    });
    // ------------------bulk-action-start--------------------
    // ------------------serach-function-start------------------
    let auctionUrl = baseurl + "cms-admin/cities";
    initDynamicTable(auctionUrl, '#data-block', '#filterForm');
    // ------------------serach-function-end------------------

    $(document).on('click', '.DeleteBtn', function () {
                let deleteId = $(this).data('project-id');

                $('#modal-heading').text( 'Confirm Deletion');

                $('#modal-body').text('Are you sure you want to delete this?');

                $('#confirmDeleteModal').modal('show');

                $('#confirm')
                    .off('click')
                    .on('click', function () {
                        $.ajax({
                            url: baseurl + "cms-admin/cities/" + deleteId,
                            type: "DELETE",
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {
                                updateTableAfterAction(deleteId, '#data-table');
                                $('#confirmDeleteModal').modal('hide');
                            },
                            error: function () {
                                $('#modal-body')
                                    .text('An error occurred. Please try again.')
                                    .addClass('text-danger');
                            }
                        });
                    });
            });


}
if(page_id === "create-town-page" || page_id === "edit-town-page") {
    $(document).ready(function () {
    let formSelector = (page_id === "create-town-page") ? "#create-form" : "#edit-form";
    if ($(formSelector).length) {
        $(formSelector).validate({
            ignore: [],
            rules: {
                city_id: { required: true },
                town: { required: true },
                new_city: {
                    required: function() { return $('#city_select').val() === 'add_new'; }
                },
            },
            messages: {
                city_id: { required: "* Please select a City" },
                town: { required: "* Please Enter Town Name" },
            },
            errorClass: "error",
            errorElement: "div",
            onfocusout: function(element) {
                if (!$(element).is(":file")) { this.element(element); }
            },
            onkeyup: false,
            submitHandler: function(form, event) {
                event.preventDefault();
                $('#loadingSpinner').show();
                let submitBtn = $(form).find("button[type='submit']");
                submitBtn.hide();
                var formData = new FormData(form);

                let ajaxUrl = baseurl + 'cms-admin/towns';
                let ajaxType = 'POST';

                if(page_id === "edit-town-page") {
                    formData.append('_method', 'PUT');
                    ajaxUrl += '/' + $('#projectId').val();
                }

                $.ajax({
                    url: ajaxUrl,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function(response) {
                        $('#loadingSpinner').hide();
                        submitBtn.show();
                        if(response.status === 'success') {
                            if(page_id === "create-town-page") {
                                $(form)[0].reset();
                                window.location.href = baseurl + "cms-admin/towns";
                            } else {
                                openDynamicModal({ heading: 'Success', message: response.message });
                                setTimeout(function(){ location.reload(); }, 2000);
                            }
                        } else {
                            showErrorMessage(responseBlock, { responseJSON: { message: response.message } });
                        }
                    },
                    error: function(xhr) {
                        $('#loadingSpinner').hide();
                        submitBtn.show();
                        showErrorMessage(responseBlock, xhr);
                    }
                });
            }
        });
    }
});
}
if(page_id === "towns-page"){
    // ------------------bulk-action-start--------------------
    $(document).on('click', '.bulk-action', function () {
        let ProjectBulkactionType = $(this).data('action');
        if (ProjectBulkactionType === 'bulkDelete') {
            ProjectBulkActionUrl = baseurl + 'cms-admin/towns/bulk-delete-items';
            handleBulkAction(ProjectBulkActionUrl, '#data-table', 'DELETE');
        }
    });
    // ------------------bulk-action-start--------------------
    // ------------------serach-function-start------------------
    let auctionUrl = baseurl + "cms-admin/towns";
    initDynamicTable(auctionUrl, '#data-block', '#filterForm');
    // ------------------serach-function-end------------------

    $(document).on('click', '.DeleteBtn', function () {
                let deleteId = $(this).data('project-id');

                $('#modal-heading').text( 'Confirm Deletion');

                $('#modal-body').text('Are you sure you want to delete this?');

                $('#confirmDeleteModal').modal('show');

                $('#confirm')
                    .off('click')
                    .on('click', function () {
                        $.ajax({
                            url: baseurl + "cms-admin/towns/" + deleteId,
                            type: "DELETE",
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {
                                updateTableAfterAction(deleteId, '#data-table');
                                $('#confirmDeleteModal').modal('hide');
                            },
                            error: function () {
                                $('#modal-body')
                                    .text('An error occurred. Please try again.')
                                    .addClass('text-danger');
                            }
                        });
                    });
            });


}
if(page_id === "create-pincode-page" || page_id === "edit-pincode-page") {
    $(document).ready(function () {
    let formSelector = (page_id === "create-pincode-page") ? "#create-form" : "#edit-form";
    if ($(formSelector).length) {
        $(formSelector).validate({
            ignore: [],
            rules: {
                town_id: { required: true },
                pincode: {
                    required: true,
                    digits: true,
                    minlength: 6,
                    maxlength: 6
                },
            },
            messages: {
                town_id: { required: "* Please select a Town" },
                pincode: {
                    required: "* Please enter the pincode",
                    digits: "* Pincode must be digits only",
                    minlength: "* Pincode must be 6 digits",
                    maxlength: "* Pincode must be 6 digits"
                }
            },
            errorClass: "error",
            errorElement: "div",
            onfocusout: function(element) {
                if (!$(element).is(":file")) { this.element(element); }
            },
            onkeyup: false,
            submitHandler: function(form, event) {
                event.preventDefault();
                $('#loadingSpinner').show();
                let submitBtn = $(form).find("button[type='submit']");
                submitBtn.hide();
                var formData = new FormData(form);

                let ajaxUrl = baseurl + 'cms-admin/pincodes';
                let ajaxType = 'POST';

                if(page_id === "edit-pincode-page") {
                    formData.append('_method', 'PUT');
                    ajaxUrl += '/' + $('#projectId').val();
                }

                $.ajax({
                    url: ajaxUrl,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function(response) {
                        $('#loadingSpinner').hide();
                        submitBtn.show();
                        if(response.status === 'success') {
                            if(page_id === "create-pincode-page") {
                                $(form)[0].reset();
                                window.location.href = baseurl + "cms-admin/pincodes";
                            } else {
                                openDynamicModal({ heading: 'Success', message: response.message });
                                setTimeout(function(){ location.reload(); }, 2000);
                            }
                        } else {
                            showErrorMessage(responseBlock, { responseJSON: { message: response.message } });
                        }
                    },
                    error: function(xhr) {
                        $('#loadingSpinner').hide();
                        submitBtn.show();
                        showErrorMessage(responseBlock, xhr);
                    }
                });
            }
        });
    }
});
}
if(page_id === "pincode-page"){
    // ------------------bulk-action-start--------------------
    $(document).on('click', '.bulk-action', function () {
        let ProjectBulkactionType = $(this).data('action');
        if (ProjectBulkactionType === 'bulkDelete') {
            ProjectBulkActionUrl = baseurl + 'cms-admin/pincodes/bulk-delete-items';
            handleBulkAction(ProjectBulkActionUrl, '#data-table', 'DELETE');
        }
    });
    // ------------------bulk-action-start--------------------
    // ------------------serach-function-start------------------
    let auctionUrl = baseurl + "cms-admin/pincodes";
    initDynamicTable(auctionUrl, '#data-block', '#filterForm');
    // ------------------serach-function-end------------------

    $(document).on('click', '.DeleteBtn', function () {
                let deleteId = $(this).data('project-id');

                $('#modal-heading').text( 'Confirm Deletion');

                $('#modal-body').text('Are you sure you want to delete this?');

                $('#confirmDeleteModal').modal('show');

                $('#confirm')
                    .off('click')
                    .on('click', function () {
                        $.ajax({
                            url: baseurl + "cms-admin/pincodes/" + deleteId,
                            type: "DELETE",
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {
                                updateTableAfterAction(deleteId, '#data-table');
                                $('#confirmDeleteModal').modal('hide');
                            },
                            error: function () {
                                $('#modal-body')
                                    .text('An error occurred. Please try again.')
                                    .addClass('text-danger');
                            }
                        });
                    });
            });


}
})
