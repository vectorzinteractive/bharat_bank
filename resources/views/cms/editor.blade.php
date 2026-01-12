@php
    $editorType = $editorType ?? 'full';
@endphp

<script src="{{ asset('backend/js/tinymce/tinymce.min.js') }}"></script>

<script>
tinymce.init({
    selector: '#content',
    license_key: 'gpl',
    base_url: "{{ rtrim(config('app.url'), '/') }}/backend/js/tinymce/",
    suffix: '.min',
    height: 350,
    branding: false,
    promotion: false,
    relative_urls: false,
    remove_script_host: false,
    forced_root_block: false,

    plugins: {!! $editorType === 'auction'
        ? "'lists link'"
        : "'anchor autolink charmap codesample emoticons link lists media searchreplace table visualblocks wordcount image'"
    !!},


    toolbar: {!! $editorType === 'auction'
    ? "'undo redo | bold italic subscript | bullist numlist | link'"
    : "'undo redo | blocks | bold italic underline subscript | alignleft aligncenter alignright alignjustify | bullist numlist | link image media table | readmoreBtn | removeformat'"
!!},


    menubar: {!! $editorType === 'auction'
        ? 'false'
        : "'file edit view insert format tools table help'"
    !!},

    @if($editorType !== 'auction')
        image_title: true,
        automatic_uploads: true,
        images_upload_handler: imageUploadHandler,
        setup: setupEditor,
    @endif
});

@if($editorType !== 'auction')
function imageUploadHandler(blobInfo, progress) {
    return new Promise((resolve, reject) => {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '{{ url("editor/upload") }}');
        xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);

        xhr.onload = () => {
            if (xhr.status < 200 || xhr.status >= 300) {
                reject('HTTP Error: ' + xhr.status);
                return;
            }

            const json = JSON.parse(xhr.responseText);
            if (!json || !json.location) {
                reject('Invalid response');
                return;
            }
            resolve(json.location);
        };

        const formData = new FormData();
        formData.append('file', blobInfo.blob(), blobInfo.filename());
        xhr.send(formData);
    });
}

function setupEditor(editor) {
    editor.on('BeforeSetContent', function (e) {
        if (e.content && e.content.includes('<img')) {
            e.content = e.content.replace(/<img(?![^>]*class=)/g, '<img class="img-contain"');
        }
    });

    editor.on('PostProcess', function (e) {
        if (e.content && e.content.includes('<img')) {
            e.content = e.content.replace(/<img(?![^>]*class=)/g, '<img class="img-contain"');
        }
    });

    editor.ui.registry.addButton('readmoreBtn', {
        text: 'Read More',
        tooltip: 'Insert Read More Button',
        onAction: function () {
            editor.windowManager.open({
                title: 'Insert Read More Button',
                body: {
                    type: 'panel',
                    items: [
                        { type: 'input', name: 'btnText', label: 'Button Text' },
                        { type: 'input', name: 'btnUrl', label: 'Button URL' }
                    ]
                },
                buttons: [
                    { type: 'cancel', text: 'Cancel' },
                    { type: 'submit', text: 'Insert', buttonType: 'primary' }
                ],
                onSubmit: function (api) {
                    const data = api.getData();
                    const text = data.btnText || 'Read More';
                    const url = data.btnUrl || '#';

                    editor.insertContent(
                        `<a href="${editor.dom.encode(url)}" target="_blank" rel="noopener noreferrer">
                            <button class="readmore-btn" type="button">${editor.dom.encode(text)}</button>
                        </a>`
                    );
                    api.close();
                }
            });
        }
    });
}
@endif
</script>
