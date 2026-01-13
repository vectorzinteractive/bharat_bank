function handleAddNew(selectId, type) {
    const select = document.getElementById(selectId);
    const wrapper = document.getElementById(`add_${type}_wrapper`);

    select.addEventListener('change', function() {
        if (this.value === 'add_new') {
            wrapper.classList.remove('d-none');
        } else {
            wrapper.classList.add('d-none');
        }
    });
}

if(page_id === "creat-auction-page" || page_id === "edit-auction-page") {
    handleAddNew("state_select", "state");
    handleAddNew("city_select", "city");
    $(document).ready(function () {
    let formSelector = (page_id === "creat-auction-page") ? "#create-form" : "#edit-form";
    if ($(formSelector).length) {
        $(formSelector).validate({
            ignore: [],
            rules: {
                content: { required: true },
                state_id: { required: true },
                new_state: {
                    required: function() { return $('#state_select').val() === 'add_new'; }
                },
                city_id: { required: true },
                new_city: {
                    required: function() { return $('#city_select').val() === 'add_new'; }
                },
                new_pincode: {
                    required: function() { return $('#city_select').val() === 'add_new'; },
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
                price: {
                    required: "* Please enter the price",
                    number: "* Price must be a number",
                    min: "* Price must be at least 1"
                },
                new_pincode: {
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

if(page_id === "create-auction-state-page" || page_id === "edit-auction-state-page") {
    $(document).ready(function () {
    let formSelector = (page_id === "create-auction-state-page") ? "#create-form" : "#edit-form";
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

                let ajaxUrl = baseurl + 'cms-admin/auction-states';
                let ajaxType = 'POST';

                if(page_id === "edit-auction-state-page") {
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
                            if(page_id === "create-auction-state-page") {
                                $(form)[0].reset();
                                window.location.href = baseurl + "cms-admin/auction-states";
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

if(page_id === "auction-state"){
    // ------------------bulk-action-start--------------------
    $(document).on('click', '.bulk-action', function () {
        let ProjectBulkactionType = $(this).data('action');
        if (ProjectBulkactionType === 'bulkDelete') {
            ProjectBulkActionUrl = baseurl + 'cms-admin/auction-states/bulk-delete-items';
            handleBulkAction(ProjectBulkActionUrl, '#data-table', 'DELETE');
        }
    });
    // ------------------bulk-action-start--------------------
    // ------------------serach-function-start------------------
    let auctionUrl = baseurl + "cms-admin/auction-states";
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
                            url: baseurl + "cms-admin/auction-states/" + deleteId,
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

if(page_id === "create-auction-city-page" || page_id === "edit-auction-city-page") {
    handleAddNew("state_select", "state");
    handleAddNew("select_pincode", "pincode");
    $(document).ready(function () {
    let formSelector = (page_id === "create-auction-city-page") ? "#create-form" : "#edit-form";
    if ($(formSelector).length) {
        $(formSelector).validate({
            ignore: [],
            rules: {
                state_id: { required: true },
                city: { required: true },
                new_state: {
                    required: function() { return $('#state_select').val() === 'add_new'; }
                },
                pincode_id: { required: true },
                new_pincode: {
                    required: function() { return $('#select_pincode').val() === 'add_new'; },
                    digits: true,
                    minlength: 6,
                    maxlength: 6
                },
            },
            messages: {
                state_id: { required: "* Please select a state" },
                city: { required: "* Please Enter City Name" },
                pincode_id: { required: "* Please select a pincode" },
                new_pincode: {
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

                let ajaxUrl = baseurl + 'cms-admin/auction-cities';
                let ajaxType = 'POST';

                if(page_id === "edit-auction-city-page") {
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
                            if(page_id === "create-auctions-city-page") {
                                $(form)[0].reset();
                                window.location.href = baseurl + "cms-admin/auction-cities";
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

if(page_id === "auction-cities"){
    // ------------------bulk-action-start--------------------
    $(document).on('click', '.bulk-action', function () {
        let ProjectBulkactionType = $(this).data('action');
        if (ProjectBulkactionType === 'bulkDelete') {
            ProjectBulkActionUrl = baseurl + 'cms-admin/auction-cities/bulk-delete-items';
            handleBulkAction(ProjectBulkActionUrl, '#data-table', 'DELETE');
        }
    });
    // ------------------bulk-action-start--------------------
    // ------------------serach-function-start------------------
    let auctionUrl = baseurl + "cms-admin/auction-cities";
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
                            url: baseurl + "cms-admin/auction-cities/" + deleteId,
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
