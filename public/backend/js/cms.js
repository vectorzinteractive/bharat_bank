const baseurl = "http://localhost:8080/websites/bharatbank/laravel-base-folder/public/";
const submitBtn = document.getElementById("submit-btn");
const responseBlock = document.getElementById("response-msg");
// ---------sidebar-collpase-start--------------------
$(document).ready(function () {
    const $button = document.querySelector('#sidebar-toggle');
    const $wrapper = document.querySelector('#wrapper');

    $button.addEventListener('click', (e) => {
        e.preventDefault();
        $wrapper
        .classList
        .toggle('toggled');
    });

});

// -------------- Sidebar Dropdown ---------------
accordionNav = $(function () {
    $('.menu-toggle').click(function (e) {
        e.preventDefault();
        var toggleButton = $(this);
        if (toggleButton.next().hasClass('active')) {
            toggleButton.next().removeClass('active');
            toggleButton
                .next()
                .slideUp(400);
            toggleButton.removeClass('show');
        } else {
            toggleButton
                .parent()
                .parent()
                .find('li .sub-menu')
                .removeClass('active');
            toggleButton
                .parent()
                .parent()
                .find('li .sub-menu')
                .slideUp(400);
            toggleButton
                .parent()
                .parent()
                .find('.menu-toggle')
                .removeClass('show');
            toggleButton
                .next()
                .toggleClass('active');
            toggleButton
                .next()
                .slideToggle(400);
            toggleButton.toggleClass('show');
        }
    });

    $(function () {
        var currentUrl = window.location.href;

        $('.sidebar-link').each(function () {
            if (this.href === currentUrl) {
                $(this).addClass('active');
            }
        });
    });

    $(function () {
    var currentUrl = window.location.href;

        $('.sidebar-submenu a.sidebar-link').each(function () {
            if (this.href === currentUrl) {
                // add active class to link
                $(this).addClass('active');

                // open parent submenu
                $(this).closest('.sub-menu')
                    .addClass('active')
                    .show();

                // add show class to parent toggle
                $(this).closest('.sub-menu')
                    .prev('.menu-toggle')
                    .addClass('show');

            }
        });
    });
});

// ---------sidebar-collpase-end--------------------

let page_id = document.body.id;

function initPdfUploadRepeater(addBtnId, containerId, onChange = () => {}) {
    const addButton = document.getElementById(addBtnId);
    const container = document.getElementById(containerId);
    const items = [];

    addButton.addEventListener("click", () => createItem());

    function createItem() {
        const wrapper = document.createElement("div");
        wrapper.className = "position-relative create-form-content-wrap form-input-group mb-3 p-3";
        wrapper.style.border = "1px solid #ddd";
        wrapper.style.borderRadius = "6px";

        /* PDF */
        const fileLabel = document.createElement("label");
        fileLabel.textContent = "Upload Floor Plan PDF";

        const fileInput = document.createElement("input");
        fileInput.type = "file";
        fileInput.name = "pdf_files[]";
        fileInput.accept = "application/pdf";
        fileInput.className = "form-control mb-2";

        /* IMAGE */
        const imageLabel = document.createElement("label");
        imageLabel.textContent = "Floor Plan Image";

        const imageInput = document.createElement("input");
        imageInput.type = "file";
        imageInput.name = "pdf_images[]";
        imageInput.accept = "image/*";
        imageInput.className = "form-control mb-2";

        /* DESCRIPTION */
        const descLabel = document.createElement("label");
        descLabel.textContent = "Description";

        const descInput = document.createElement("input");
        descInput.type = "text";
        descInput.name = "pdf_descriptions[]";
        descInput.className = "form-control mb-2";

        const removeBtn = document.createElement("button");
        removeBtn.type = "button";
        removeBtn.textContent = "X";
        removeBtn.className = "remove-pdf btn btn-danger btn-sm";

        const item = { fileInput, imageInput, descInput, wrapper };
        items.push(item);

        removeBtn.addEventListener("click", () => {
            wrapper.remove();
            const index = items.indexOf(item);
            if (index > -1) items.splice(index, 1);
        });

        wrapper.append(
            fileLabel,
            fileInput,
            imageLabel,
            imageInput,
            descLabel,
            descInput,
            removeBtn
        );

        container.prepend(wrapper);
    }
}


 function setupImagePreview(
    inputId,
    previewId,
    cancelId,
    cropSize = { width: 1280, height: 800 },
    enableExistingCrop = false
) {
    const imageInput = document.getElementById(inputId);
    const imagePreview = document.getElementById(previewId);
    const cancelBtn = document.getElementById(cancelId);

    if (!imageInput || !imagePreview || !cancelBtn) return;

    const wrapper = imageInput.closest('.image-upload-wrapper');
    const actionWrap = wrapper.querySelector('.image-actions');
    const rotateBtn = actionWrap.querySelector('.rotate-btn');
    const cropBtn = actionWrap.querySelector('.crop-btn');
    const undoBtn = actionWrap.querySelector('.undo-btn');

    let cropper = null;
    let rotation = 0;

    let currentFile = null;
    let lastFile = null;

    let currentPreviewURL = null;

    function initCropper() {
        if (cropper) cropper.destroy();

        rotation = 0;
        cropper = new Cropper(imagePreview, {
            viewMode: 1,
            aspectRatio: cropSize.width / cropSize.height,
            autoCropArea: 1,
            imageSmoothingQuality: 'high'
        });

        imagePreview.style.display = 'block';
        cancelBtn.style.display = 'block';
        actionWrap.style.display = 'block';
    }

    function convertToWebP(file, callback, quality = 0.9) {
        const img = new Image();
        img.onload = () => {
            const canvas = document.createElement('canvas');
            canvas.width = img.width;
            canvas.height = img.height;
            canvas.getContext('2d').drawImage(img, 0, 0);

            canvas.toBlob(blob => {
                callback(
                    new File([blob], `image_${Date.now()}.webp`, {
                        type: 'image/webp'
                    })
                );
            }, 'image/webp', quality);
        };
        img.src = URL.createObjectURL(file);
    }

    function updateInput(file) {
        const dt = new DataTransfer();
        dt.items.add(file);
        imageInput.files = dt.files;
    }

    function setPreviewFromFile(file) {
        if (currentPreviewURL) {
            URL.revokeObjectURL(currentPreviewURL);
        }
        currentPreviewURL = URL.createObjectURL(file);
        imagePreview.src = currentPreviewURL;
    }

    imageInput.addEventListener('change', e => {
        const file = e.target.files[0];
        if (!file) return;

        convertToWebP(file, webpFile => {
            currentFile = webpFile;
            lastFile = null;

            updateInput(webpFile);
            setPreviewFromFile(webpFile);

            imagePreview.onload = initCropper;
        });
    });

    rotateBtn.addEventListener('click', () => {
        if (!cropper) return;
        rotation = (rotation + 90) % 360;
        cropper.rotateTo(rotation);
    });

    cropBtn.addEventListener('click', () => {
        if (!cropper) return;

        lastFile = currentFile;

        cropper.getCroppedCanvas({
            width: cropSize.width,
            height: cropSize.height,
            imageSmoothingQuality: 'high'
        }).toBlob(blob => {
            const webpFile = new File(
                [blob],
                `image_${Date.now()}.webp`,
                { type: 'image/webp' }
            );

            currentFile = webpFile;
            updateInput(webpFile);
            setPreviewFromFile(webpFile);

            cropper.destroy();
            cropper = null;
        }, 'image/webp', 0.9);
    });

    undoBtn.addEventListener('click', () => {
        if (!lastFile) return;

        currentFile = lastFile;
        lastFile = null;

        updateInput(currentFile);
        setPreviewFromFile(currentFile);

        imagePreview.onload = initCropper;
    });

    cancelBtn.addEventListener('click', () => {
        if (cropper) cropper.destroy();

        if (currentPreviewURL) {
            URL.revokeObjectURL(currentPreviewURL);
        }

        imageInput.value = '';
        imagePreview.src = '';
        imagePreview.style.display = 'none';
        cancelBtn.style.display = 'none';
        actionWrap.style.display = 'none';

        cropper = null;
        currentFile = null;
        lastFile = null;
        currentPreviewURL = null;
    });
}




    const modal = document.createElement("div");
    modal.className = "modal";

    const modalDialog = document.createElement('div');
    modalDialog.classList.add('modal-dialog', 'modal-dialog-centered');

    const modalContent = document.createElement("div");
    modalContent.className = "modal-content";

    const closeBtn = document.createElement("span");
    closeBtn.className = "close";
    closeBtn.innerHTML = "&times;";

    const modalTitle = document.createElement("h4");
    const modalText = document.createElement("p");

    const modalButtons = document.createElement("div");
    modalButtons.className = "modal-buttons";

    const okButton = document.createElement("button");
    okButton.className = "ok-button button secondary-btn";
    okButton.innerText = "OK";

    const cancelButton = document.createElement("button");
    cancelButton.className = "cancel-button button info-btn";
    cancelButton.innerText = "Cancel";

    // modalButtons.appendChild(okButton);
    // modalButtons.appendChild(cancelButton);
    modalContent.appendChild(closeBtn);
    modalContent.appendChild(modalTitle);
    modalContent.appendChild(modalText);
    modalContent.appendChild(modalButtons);
    modalDialog.appendChild(modalContent);
    modal.appendChild(modalDialog);
    document.body.appendChild(modal);

    function openModal(title, text, timeout) {
        modalTitle.innerText = title;
        modalText.innerText = text;
        modal.style.display = "block";

        if (timeout) {
            setTimeout(() => {
                modal.style.display = 'none';
            }, timeout);
        }
    }

    function closeModal() {
        modal.style.display = "none";
    }

    closeBtn.onclick = closeModal;

    window.onclick = function (event) {
        if (event.target === modal) {
            closeModal();
        }
    };

    document.onkeydown = function (event) {
        if (event.key === "Escape") {
            closeModal();
        }
    };

    window.openModal = openModal;

    // -------------------------------modal-function-end----------------------------------

    // ---------------------dynamic-info-modal-start------------------
    function openDynamicModal({ heading = 'Notice', message = '', okText = 'OK', onOk = null }) {
        const modalEl = document.getElementById('dynamicModal');
        const headingEl = document.getElementById('dynamicModalHeading');
        const bodyEl = document.getElementById('dynamicModalBody');
        const okBtn = document.getElementById('dynamicModalOkBtn');

        headingEl.textContent = heading;
        bodyEl.textContent = message;
        okBtn.textContent = okText;

        okBtn.replaceWith(okBtn.cloneNode(true));
        const newOkBtn = document.getElementById('dynamicModalOkBtn');

        newOkBtn.addEventListener('click', () => {
            if (typeof onOk === 'function') {
            onOk();
            }
            const modal = bootstrap.Modal.getInstance(modalEl);
            modal.hide();
        });

        const modal = new bootstrap.Modal(modalEl);
        modal.show();
        }

    // ---------------------dynamic-info-modal-end------------------
// document.addEventListener("DOMContentLoaded", function () {






    // -----------------------image-preview-start----------------------

    function clearInputsByClass(className) {
        const inputs = document.querySelectorAll(`.${className}`);

        inputs.forEach(function (input) {
            if (input.type === 'url') {
                input.value = '';
            }
        });
    }
    // -----------------------image-preview-start----------------------
    function show_hide_password() {
        var eye_btn = document.getElementById('eye-sign');
        var password_input = document.getElementById('password');
        var show_hide_img = document.getElementById('show-hide-img');

        eye_btn.addEventListener("click", function () {
            if (password_input.type === "password") {
                password_input.type = "text";
                show_hide_img.src = baseurl + "open-eye.svg";
            } else {
                password_input.type = "password";
                show_hide_img.src = baseurl + "cls-eye.svg";
            }
        })
    }

    // -----------------------------update-catgory-row-function-start-----------------------------
    function updateTableRowDynamic({
    tableId,
    data,
    isEdit = false,
    rowIdAttr = 'data-id',
    displayKeys = {}, // e.g. {name: 'name', createdAt: 'created_at'}
    editBtnClass,
    deleteBtnClass,
    extraColumns = {} // {columnClass: (data) => '<td>...</td>'}
}) {
    const tableBody = $(`#${tableId} tbody`);
    const newIndex = tableBody.find('tr').length + 1;

    if (isEdit) {
        const row = $(`tr[${rowIdAttr}='${data.id}']`);
        // Update main display columns
        for (const colClass in displayKeys) {
            row.find(`.${colClass}`).text(data[displayKeys[colClass]]);
        }
        // Update extra columns
        for (const colClass in extraColumns) {
            row.find(`.${colClass}`).html(extraColumns[colClass](data));
        }
    } else {
        const extraColsHtml = Object.keys(extraColumns)
            .map(key => extraColumns[key](data))
            .join('');

        const rowHtml = `
            <tr ${rowIdAttr}="${data.id}">
                <td class="sr-no">${newIndex}</td>
                ${Object.keys(displayKeys).map(colClass => `
                    <td class="${colClass} col-des">${data[displayKeys[colClass]]}</td>
                `).join('')}
                ${extraColsHtml}
                <td class="col-actions">
                    <a href="javascript:void(0)" ${rowIdAttr}="${data.id}" class="${editBtnClass} action-edit" title="Edit">
                        <i class='las la-edit'></i>
                    </a>
                    <a href="javascript:void(0)" class="${deleteBtnClass} action-delete" ${rowIdAttr}="${data.id}" title="Delete">
                        <i class='las la-trash'></i>
                    </a>
                </td>
            </tr>
        `;
        tableBody.append(rowHtml);
    }

    // Reindex all rows
    $(`#${tableId} tbody tr`).each(function(index) {
        $(this).find(".sr-no").text(index + 1);
    });
}

    // -----------------------------update-catgory-row-function-end-----------------------------
    // -----------------------------reset-catgory-form-start-----------------------------

    function resetForm(
        label,
        hiddenId,
        inputId,
        updateInputId,
        BtnId,
        btntxt,
        responseblock
    ) {
        $('#' + label).text('Add');
        $('#' + hiddenId).val('');
        $('#' + inputId).val('');
        $('#' + updateInputId).hide();
        $('#' + BtnId).text(btntxt);
        $('#' + responseblock)
            .html('')
            .removeClass('error');
    }

    // -----------------------------reset-catgory-form-end-----------------------------


    // ----dynamic-input-start-------------
    function addDynamicField(containerId, fieldName, placeholderText, removeBtnClass) {
    let container = document.getElementById(containerId);

    let divblock = document.createElement('div');
    divblock.className = 'subcategory-item position-relative mb-3';
    divblock.innerHTML = `
        <input type="text" class="form-control" name="${fieldName}[]" placeholder="${placeholderText}">
        <button type="button" class="${removeBtnClass} tag-close action-tag action-tag-right">
            <i class="las la-times"></i>
        </button>
    `;

    let inputField = divblock.querySelector('input');

    inputField.addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            let newDiv = addDynamicField(containerId, fieldName, placeholderText, removeBtnClass);
            newDiv.querySelector('input').focus();
        }
    });

    container.prepend(divblock);

    inputField.focus();

    divblock.querySelector(`.${removeBtnClass}`).addEventListener('click', function() {
        divblock.remove();
    });

    return divblock;
}


    function removeDynamicField(button, containerId) {
        button
            .parentElement
            .remove();
    }

    function toggleVisibilityBasedOnSelection(
        selectId,
        targetBtnId,
        targetContainerId
    ) {
        $(selectId).change(function () {
            if ($(this).val()) {
                $(targetBtnId).css('opacity', '1');
                $(targetContainerId)
                    .css('opacity', '1')
                    .show();
            } else {
                $(targetBtnId).css('opacity', '0');
                $(targetContainerId)
                    .css('opacity', '0')
                    .hide();
            }
        });
    }

    function addRemoveEventListener(targetContainerId, removeBtnClass) {
        $(targetContainerId).on("click", `.${removeBtnClass}`, function () {
            removeDynamicField(this, targetContainerId);
        });
    }

    // ----dynamic-input-end-------------

    toggleVisibilityBasedOnSelection(
        '#project-category',
        '#add-blog-catBtn',
        '#add-blognewCat-block'
    );

    $("#add-blog-catBtn").click(function () {
        addDynamicField(
            'add-blogcat-container',
            'newBlogCategories',
            'Enter Blog Category',
            'remove-blog-category'
        );
    });
    $("#add-news-catBtn").click(function () {
        addDynamicField(
            'add-newscat-container',
            'newNewsCategories',
            'Enter Category',
            'remove-news-category'
        );
    });

    // ------------------------function-to-show-add-category-start-----------------------------

    addRemoveEventListener('#add-blogcat-container', 'remove-blog-category');
    addRemoveEventListener('#add-newscat-container', 'remove-news-category');

    // ------------------------function-to-show-add-category-end-----------------------------

    // ------------------------select-all-function-start-----------------------------------


    function toggleBulkActions() {
        let selectedCount = $('input[name="selected"]:checked').length;
        if (selectedCount > 0) {
            $('#bulkOperations').show();
        } else {
            $('#bulkOperations').hide();
        }
    }

    $(document).on('change', 'input[name="selected"], #selectAll', function () {
        toggleBulkActions();
    });


    $('#selectAll').on('change', function () {
        $('input[name="selected"]').prop('checked', this.checked);
        toggleBulkActions();
    });

    // ------------------------select-all-function-end-----------------------------------

    function handleBulkAction(actionPage, tableSelector, method = 'PUT') {
        let selectedRecord = $('input[name="selected"]:checked').map(function () {
            return $(this).val();
        }).get();

        if (selectedRecord.length === 0) {
            alert('Please select at least one blog.');
            return;
        }

        $('#modal-body').text('Are you sure you want to perform this action?');
        $('#confirmDeleteModal').modal('show');

        $('#confirm').off('click').on('click', function () {
            $.ajax({
                url: actionPage,
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    _method: method,
                    selected_ids: selectedRecord
                },
                success: function (response) {
                    selectedRecord.forEach(id => {
                        $('tr[data-id="' + id + '"]').remove();
                    });

                    $('#confirmDeleteModal').modal('hide');

                    if ($(tableSelector).find('tbody tr').length === 0) {
                        $('#no-data-message').show();
                        $(tableSelector).hide();
                    } else {
                        $('#no-data-message').hide();
                        $(tableSelector).show();
                    }

                    $('input[name="selected"]').prop('checked', false);
                    $('#selectAll').prop('checked', false);
                    toggleBulkActions();
                },
                error: function () {
                    $('#modal-body').text('An error occurred. Please try again.').addClass('text-danger');
                }
            });
        });
    }

    // -------------------------handle-bulk-function-start----------------------------------

    // -------------------------updaterow-function-start----------------------------------
    function updateTableAfterAction(blogId, tableSelector) {
        $('tr[data-id="' + blogId + '"]').remove();

        if ($(tableSelector).find('tbody tr').length === 0) {
            $(tableSelector).hide();

            if ($('#no-data-message').length === 0) {
                $(tableSelector).parent().append('<div id="no-data-message" class="alert alert-warning" style="display:block;">No data to display.</div>');
            } else {
                $('#no-data-message').show();
            }
        }
    }
    // -------------------------updaterow-function-end----------------------------------


    // -------------------------fetchingsearch-function-start----------------------------------

/**
 * Debounce function to limit how often a function is called
 */

function debounce(func, delay) {
    let timer;
    return function () {
        clearTimeout(timer);
        timer = setTimeout(() => func.apply(this, arguments), delay);
    };
}

/**
 * Fetch data via AJAX
 * @param {string} url - Endpoint URL
 * @param {string} tableSelector - Selector where the table HTML will be rendered
 * @param {string} filterFormSelector - Selector for the filter form
 */
function fetchData(url, tableSelector, filterFormSelector) {
    let search = $('input[name="filter[search]"]').val();
    let from = $('input[name="filter[date_range_from]"]').val();
    let to = $('input[name="filter[date_range_to]"]').val();

    let data = {};

    if (search) {
        data['filter[search]'] = search;
    }

    if (from && to) {
        // Combine into allowed filter
        data['filter[date_range]'] = `${from},${to}`;
    }

    $.ajax({
        url: url,
        type: "GET",
        data: data,
        success: function(response) {
            let trimmedResponse = $.trim(response);
            if (trimmedResponse === '' || trimmedResponse.includes('No data to display')) {
                $(tableSelector).html('<div class="alert alert-warning">No Result.</div>');
            } else {
                $(tableSelector).html(response);
            }
        },
        error: function() {
            $(tableSelector).html('<div class="alert alert-danger">Error loading data.</div>');
        }
    });
}


/**
 * Initialize dynamic table behavior
 * @param {string} listUrl - Endpoint URL
 * @param {string} tableSelector - Selector where table HTML will be rendered
 * @param {string} filterFormSelector - Selector for the filter form
 */
function initDynamicTable(listUrl, tableSelector, filterFormSelector) {
    // Debounced fetch for filters
    let fetchDebounced = debounce(() => fetchData(listUrl, tableSelector, filterFormSelector), 300);

    // Attach filter input/change events
    $(document).on('input change', `${filterFormSelector} input, ${filterFormSelector} select`, fetchDebounced);

    // Attach sortable column click
    $(document).on('click', `${tableSelector} .sortable`, function () {
        let column = $(this).data('column');
        let order = $(this).data('order');

        // Construct URL with sort parameter
        let sortParam = order === 'asc' ? column : '-' + column;
        let urlWithSort = listUrl.includes('?') ? `${listUrl}&sort=${sortParam}` : `${listUrl}?sort=${sortParam}`;

        fetchData(urlWithSort, tableSelector, filterFormSelector);

        // Toggle order for next click
        $(this).data('order', order === 'asc' ? 'desc' : 'asc');

        // Update arrow icons dynamically
        $(tableSelector + ' .sortable i').remove();
        let icon = order === 'asc' ? 'up' : 'down';
        $(this).append(`<i class="fa fa-arrow-${icon}"></i>`);
    });
}


    // -------------------------fetchingsearch-function-end----------------------------------
    function selectedImage(inputId, displayContainer) {
    const img_input = document.getElementById(inputId);
    const preview_block = document.getElementById(displayContainer);
    let selectedFiles = [];

    img_input.addEventListener("change", function () {
        selectedFiles = Array.from(img_input.files);
        preview_block.innerHTML = "";

        selectedFiles.forEach((file) => {
            const reader = new FileReader();
            reader.onload = function (e) {
                const createDiv = document.createElement('div');
                createDiv.classList.add('selected-img-container');
                createDiv.style.position = 'relative';
                createDiv.file = file; // Attach file to the element directly

                const dragHandle = document.createElement('button');
                dragHandle.type = 'button';
                dragHandle.classList.add('project-drag-handle');
                dragHandle.title = 'Drag to reorder';
                dragHandle.style.position = 'absolute';
                dragHandle.style.top = '0';
                dragHandle.style.left = '0';
                dragHandle.style.cursor = 'grab';
                dragHandle.innerHTML = '<i class="las la-arrows-alt"></i>';

                const cancelDiv = document.createElement("span");
                cancelDiv.innerHTML = '<i class="las la-times"></i>';
                cancelDiv.classList.add('action-tag', 'tag-close', 'action-tag-right');
                cancelDiv.addEventListener("click", function () {
                    selectedFiles = selectedFiles.filter(f => f !== createDiv.file);
                    updateFileInput();
                    preview_block.removeChild(createDiv);
                });

                const img = document.createElement('img');
                img.src = e.target.result;
                img.classList.add('img-contain');

                createDiv.appendChild(dragHandle);
                createDiv.appendChild(cancelDiv);
                createDiv.appendChild(img);

                preview_block.appendChild(createDiv);
            };
            reader.readAsDataURL(file);
        });

        if (preview_block.sortableInstance) {
            preview_block.sortableInstance.destroy();
        }

        preview_block.sortableInstance = Sortable.create(preview_block, {
            animation: 150,
            handle: '.project-drag-handle',
            onEnd: function () {
                const sortedContainers = Array.from(preview_block.children);
                selectedFiles = sortedContainers.map(container => container.file).filter(f => f instanceof File);
                updateFileInput();
            }
        });
    });

    function updateFileInput() {
        const dataTransfer = new DataTransfer();
        selectedFiles.forEach(file => {
            if (file instanceof File) {
                dataTransfer.items.add(file);
            }
        });
        img_input.files = dataTransfer.files;
    }
}

function initTeamSelector(options = {}) {
    const teamSearchRoute = options.teamSearchRoute || baseurl + "backend/search-team-members";

    $('.team-search-container').each(function() {
        const container = $(this);
        const searchInput = container.find('.team-search-input');
        const dropdown = container.find('.team-dropdown');
        const selectedWrap = container.find('.selected-members');
        const hiddenInput = container.find('.team-members-hidden');

        let selectedMembers = hiddenInput.val() ? hiddenInput.val().split(',') : [];

        function getSelectedPracticeAreas() {
            return $('input[name="practice_area_ids[]"]:checked')
                .map(function() { return $(this).val(); })
                .get();
        }

        function fetchTeamMembers(query = '') {
            const selectedAreas = getSelectedPracticeAreas();

            $.ajax({
                url: teamSearchRoute,
                type: "GET",
                data: {
                    query: query,
                    practice_area_ids: selectedAreas
                },
                success: function(response) {
                    dropdown.empty();

                    if (response.status === 'no_practice_area') {
                        dropdown.show().html(`
                            <div class="dropdown-item error">${response.message}</div>
                        `);
                        return;
                    }

                    const members = response.data;

                    if (members.length > 0) {
                        dropdown.show();
                        members.forEach(member => {
                            dropdown.append(`
                                <div class="member-option dropdown-item" data-id="${member.id}" data-name="${member.name}">
                                    ${member.name} <span class="text-muted">(${member.post || ''})</span>
                                </div>
                            `);
                        });
                    } else {
                        dropdown.show().html(`
                            <div class="dropdown-item error">${'No team members found.'}</div>
                        `);
                    }
                },
                error: function() {
                    dropdown.show().html(`<div class="dropdown-item text-danger">Error fetching results</div>`);
                }
            });
        }

        // Typing search
        searchInput.on('keyup', function() {
            const query = $(this).val().trim();
            fetchTeamMembers(query);
        });

        // Practice area checkbox change
        $(document).on('change', 'input[name="practice_area_ids[]"]', function() {
            fetchTeamMembers(searchInput.val().trim());
        });

        // Select member
        container.on('click', '.member-option', function() {
            const id = $(this).data('id').toString();
            const name = $(this).data('name');

            if (!selectedMembers.includes(id)) {
                selectedMembers.push(id);
                selectedWrap.append(`
                    <span class="team-badge" data-id="${id}">
                        ${name}
                        <button type="button" class="remove-member" data-id="${id}">×</button>
                    </span>
                `);
                hiddenInput.val(selectedMembers.join(','));
            }

            dropdown.hide();
            searchInput.val('');
        });

        // Remove member
        container.on('click', '.remove-member', function() {
            const id = $(this).data('id').toString();
            selectedMembers = selectedMembers.filter(mid => mid !== id);
            $(this).closest('.team-badge').remove();
            hiddenInput.val(selectedMembers.join(','));
        });

        // Hide dropdown on click outside
        $(document).on('click', function(e) {
            if (!container.is(e.target) && container.has(e.target).length === 0) {
                dropdown.hide();
            }
        });
    });
}

function showErrorMessage(target, error) {
    const container = typeof target === 'string' ? document.getElementById(target) : target;
    if (!container) return;

    $(container).show();
    container.classList.add('error');
    $(container).css({ 'margin': '15px auto 0' });

    let message = '';

    if (!error) {
        message = 'An unexpected error occurred. Please contact your support team.';
    } else if (error.status === 422 && error.responseJSON && error.responseJSON.errors) {
        const firstKey = Object.keys(error.responseJSON.errors)[0];
        message = error.responseJSON.errors[firstKey][0];
    } else if (error.responseJSON && error.responseJSON.message) {
        message = error.responseJSON.message;
    } else {
        message = 'Something went wrong. Please try again later or contact your support team.';
    }

    container.innerHTML = message;

}

if (page_id === "login-page") {
    // -------------------function-to-show-hide-password-start-here--------------------------
    show_hide_password();
    // ---------------------login-validation-start-----------------------------
    $(document).ready(function () {
        var loginResponse_msg = document.getElementById('file2_err');
        var loginBtn = document.getElementById('login-btn');

        if ($('#login-form').length !== 0) {
            $("#login-form").validate({
                rules: {
                    email: {
                        required: true
                    },
                    password: {
                        required: true,
                        maxlength: 12,
                        minlength: 8
                    }
                },
                messages: {
                    email: {
                        required: "* Please enter your Email"
                    },
                    password: {
                        required: "* Please enter password",
                        maxlength: "* Please enter a maximum of 12 characters",
                        minlength: "* Please enter a minimum of 8 characters"
                    }
                },
                errorLabelContainer: $("#login-form div.error"),
                submitHandler: function (form, event) {
                    event.preventDefault();
                    var formData = $(form).serialize();
                    $('#response-animation').show();
                    $(loginBtn).hide();

                    $.ajax({
                        url: 'login',
                        type: 'POST',
                        data: formData,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        success: function (response) {
                            $('#response-animation').hide();

                            if (response.status === 'success') {
                                window.location.href = response.redirectUrl;
                            } else {
                                $(loginBtn).show();
                                showErrorMessage(loginResponse_msg, { responseJSON: { message: response.message } });
                            }
                        },
                        error: function (xhr) {
                            $('#response-animation').hide();
                            $(loginBtn).show();
                            showErrorMessage(loginResponse_msg, xhr);
                        }
                    });
                }
            });
        }
    });
    // ---------------------login-validation-end-----------------------------
}

if (page_id === "register-page") {

        show_hide_password();

        // ---------------------register-validation-start-----------------------------
        $(document).ready(function () {
            var registerResponse_msg = document.getElementById('file2_err');
            var createUserBtn = document.getElementById('registerBtn');

            if ($('#register-form').length !== 0) {
                $("#register-form").validate({
                    rules: {
                        email: { required: true },
                        "name": { required: true, maxlength: 15 },
                        password: { required: true, maxlength: 12, minlength: 8 }
                    },
                    messages: {
                        email: { required: "* Please enter your email" },
                        "name": { required: "* Please enter name", maxlength: "Max 15 characters" },
                        password: { required: "* Please enter password", maxlength: "Max 12 characters", minlength: "Min 8 characters" }
                    },
                    errorLabelContainer: $("#register-form div.error"),
                    submitHandler: function (form, event) {
                        event.preventDefault();
                        var formData = $(form).serialize();
                        $('#response-animation').show();
                        $(createUserBtn).hide();

                        // let url = page_id === "admin-register" ? baseurl + 'backend/users' : 'register';
                        $.ajax({
                            url: "register",
                            type: 'POST',
                            data: formData,
                            dataType: 'json',
                            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            success: function (response) {
                                $('#response-animation').hide();
                                $(createUserBtn).show();

                                if (response.status === 'success') {
                                    window.location.href = response.redirectUrl || baseurl + 'backend/users';
                                } else {
                                    showErrorMessage(registerResponse_msg, { responseJSON: { message: response.message } });
                                }
                            },
                            error: function (xhr) {
                            $('#response-animation').hide();
                            $(createUserBtn).show();
                            showErrorMessage(registerResponse_msg, xhr);
                        }
                        });
                    }
                });
            }
        });
        // ---------------------register-validation-end-----------------------------
    }

    if (page_id === "forgot-password-page") {
        $(document).ready(function () {
    var forgotMsg = document.getElementById('forgot_response_msg');
    var resetBtn = document.getElementById('resetBtn');

    if ($("#forgot-password-form").length !== 0) {
        $("#forgot-password-form").validate({
            rules: {
                email: {
                    required: true,
                    email: true
                }
            },
            messages: {
                email: {
                    required: "* Please enter your email",
                    email: "* Please enter a valid email address"
                }
            },
            errorLabelContainer: $("#forgot-password-form div.error"),
            submitHandler: function (form, event) {
                event.preventDefault();
                let formData = $(form).serialize();
                $(resetBtn).text("Sending...").prop('disabled', true);
                forgotMsg.innerHTML = "";

                $.ajax({
                    url: "forgot-password",
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        $(resetBtn).text("Send Reset Link").prop('disabled', false);
                        forgotMsg.className = 'success';
                        forgotMsg.innerHTML = "Password reset link sent! Please check your email.";
                    },
                    error: function (xhr) {
                        $(resetBtn).text("Send Reset Link").prop('disabled', false);
                        forgotMsg.className = 'error';

                        if (xhr.status === 422 && xhr.responseJSON?.errors?.email) {
                            forgotMsg.innerHTML = xhr.responseJSON.errors.email[0];
                        } else {
                            forgotMsg.innerHTML = 'Something went wrong. Try again later.';
                        }
                    }
                });
            }
        });
    }
});

    }

    if (page_id === "reset-password-page") {
        show_hide_password();

        $(document).ready(function () {
        const resetMsg = document.getElementById('reset_password_msg');
        const resetBtn = document.getElementById('resetPasswordBtn');

        if ($('#reset-password-form').length !== 0) {
            $("#reset-password-form").validate({
                rules: {
                    password: {
                        required: true,
                        minlength: 8,
                        maxlength: 12
                    }
                },
                messages: {
                    password: {
                        required: "* Please enter your new password",
                        minlength: "* Minimum 8 characters required",
                        maxlength: "* Maximum 12 characters allowed"
                    }
                },
                errorLabelContainer: $("#reset-password-form div.error"),
                submitHandler: function (form, event) {
                    event.preventDefault();
                    let formData = $(form).serialize();

                    $(resetBtn).text("Resetting...").prop('disabled', true);
                    resetMsg.innerHTML = "";

                    $.ajax({
                        url: baseurl + "reset-password",
                        type: 'POST',
                        data: formData,
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            $(resetBtn).text("Reset Password").prop('disabled', false);
                            // resetMsg.className = 'success';
                            // resetMsg.innerHTML = "Password reset successfully. Redirecting...";

                            // setTimeout(() => {
                                window.location.href = baseurl + "cms-admin";
                            // }, 2000);
                        },
                        error: function (xhr) {
                            $(resetBtn).text("Reset Password").prop('disabled', false);
                            resetMsg.className = 'error';

                            if (xhr.status === 422 && xhr.responseJSON?.errors?.password) {
                                resetMsg.innerHTML = xhr.responseJSON.errors.password[0];
                            } else {
                                resetMsg.innerHTML = 'Something went wrong. Please try again later.';
                            }
                        }
                    });
                }
            });
        }
    });

        }

        if(page_id === "setting-page") {
            show_hide_password();
        // ---------------------change-email-username-start-----------------------------
        $(document).ready(function () {
        const responseMsg = $('#file2_err');
        const submitBtn = $('#update-profile-btn');
        const form = $('#update-profile-form');

            if (form.length !== 0) {
                form.validate({
                    rules: {
                        email: {
                            required: true,
                            email: true
                        },
                        name: {
                            required: true,
                            maxlength: 15
                        },
                    },
                    messages: {
                        email: {
                            required: "* Please enter your email",
                            email: "* Invalid email format"
                        },
                        name: {
                            required: "* Please enter Name",
                            maxlength: "* Max 15 characters"
                        },
                    },
                    submitHandler: function (formElement, event) {
                        event.preventDefault();
                        const formData = $(formElement).serialize();
                        submitBtn.prop('disabled', true).text('Saving...');
                        $.ajax({
                            url: baseurl + "cms-admin/update-profile",
                            type: 'POST',
                            data: formData,
                            dataType: 'json',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {
                                submitBtn.prop('disabled', false).text('Submit');

                                if (response.status === 'success') {
                                    responseMsg
                                        .removeClass('text-danger')
                                        .addClass('text-success')
                                        .text("Profile updated successfully!");

                                    setTimeout(() => {
                                        responseMsg.text('');
                                        location.reload();
                                    }, 2000);

                                } else {
                                    responseMsg
                                        .removeClass('text-success')
                                        .addClass('text-danger')
                                        .text(response.message || 'Update failed.');
                                }
                            },
                            error: function (xhr) {
                                submitBtn.prop('disabled', false).text('Submit');

                                let message = "Something went wrong. Please try again.";

                                if (xhr.responseJSON && xhr.responseJSON.errors) {
                                    const errors = xhr.responseJSON.errors;
                                    message = Object.values(errors).flat().join(' ');
                                }

                                responseMsg
                                    .removeClass('text-success')
                                    .addClass('text-danger')
                                    .text(message);
                            }
                        });
                    }
                });
            }
        });
        // ---------------------change-email-username-end-----------------------------

        // ---------------------change-password-start-----------------------------
        $(document).ready(function () {
        const responseMsg = $('#updatepass-response');
        const updatePasswordBtn = $('#updatePasswordBtn');
        const form = $('#update-password-form');

            if (form.length !== 0) {
                form.validate({
                    rules: {
                        current_password: {
                            required: true,
                            minlength: 8,
                            maxlength: 12
                        },
                        new_password: {
                            required: true,
                            minlength: 8,
                            maxlength: 12
                        },
                    },
                    messages: {
                        current_password: {
                            required: "* Please enter your current password",
                            minlength: "* Minimum 8 characters required",
                            maxlength: "* Maximum 12 characters allowed"
                        },
                        new_password: {
                            required: "* Please enter your new password",
                            minlength: "* Minimum 8 characters required",
                            maxlength: "* Maximum 12 characters allowed"
                        },
                    },
                    submitHandler: function (formElement, event) {
                        event.preventDefault();
                        const formData = $(formElement).serialize();
                        updatePasswordBtn.prop('disabled', true).text('Updating...');
                        $.ajax({
    url: baseurl + "cms-admin/update-password",
    type: 'POST',
    data: formData,
    dataType: 'json',
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    success: function (response) {
        updatePasswordBtn.prop('disabled', false).text('Update');

        if (response.status === 'success') {
            formElement.reset();
            responseMsg.removeClass('text-danger')
                       .addClass('text-success')
                       .text('Password updated successfully!');
                       setTimeout(() => {
                                        responseMsg.text('');
                                    }, 2000);
        } else {
            responseMsg.removeClass('text-success')
                       .addClass('text-danger')
                       .text(response.message);
        }
    }
});

                    }
                });
            }
        });
        // ---------------------change-password-end-----------------------------
    }

// });

$('#profile_dropdown').on("click", function () {
    $(this).children('.down_menu').stop().slideToggle();
});
