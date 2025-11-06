<div class="modal fade" id="image-editor-modal" tabindex="-1" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex align-items-center gap-2 justify-content-between w-100">
                    <h5 class="modal-title">{{ __('form.image_editor') }}</h5>
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal"><i class="fa-light fa-xmark fa-fw"></i> &nbsp;{{ __('form.close') }}</button>
                        <button type="button" class="btn btn-primary image-edited-apply-btn btn-sm"><i class="fa-duotone fa-regular fa-check-double fa-fw"></i> &nbsp;{{ __('form.apply') }}</button>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div id="editorWrap">
                    <div id="tuiEditor"></div>
                </div>
            </div>
        </div>
    </div>
    <style>
        #image-editor-modal .modal-full .modal-content,
        #image-editor-modal .modal-full .modal-body {
            height: 90vh;
        }

        #image-editor-modal .modal-body {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        #editorWrap {
            flex: 1;
            min-height: 0;
            border: 1px solid #e5e7eb;
        }

        #tuiEditor {
            width: 100%;
            height: 100%;
            position: relative;
        }

        #tuiEditor .tui-image-editor-help-menu {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #tuiEditor .tie-crop-button.action {
            display: flex;
            align-items: center;
            gap: 5px;
            justify-content: center;
        }

        #tuiEditor .tie-crop-button.action .tui-image-editor-button {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        #tuiEditor .tui-image-editor-header {
            visibility: hidden;
        }

        #tuiEditor .tui-image-editor-button.apply {
            display: flex;
            align-items: center;
        }

        #tuiEditor .tie-mask-apply.apply {
            display: flex;
            align-items: center;
            justify-content: center
        }
    </style>
</div>

<script src="{{ asset('assets/js/vendor/editors/tui/tui-code-snippet.min.js') }}"></script>
<script src="{{ asset('assets/js/vendor/editors/tui/fabric.min.js') }}"></script>
<script src="{{ asset('assets/js/vendor/editors/tui/tui-color-picker.min.js') }}"></script>
<script src="{{ asset('assets/js/vendor/editors/tui/tui-image-editor.min.js') }}"></script>

<script>
    const previewZoomButtonClasses = {
        rotate: 'btn btn-light btn-icon btn-sm',
        toggleheader: 'btn btn-light btn-icon btn-header-toggle btn-sm',
        fullscreen: 'btn btn-light btn-icon btn-sm',
        borderless: 'btn btn-light btn-icon btn-sm',
        close: 'btn btn-light btn-icon btn-sm'
    };
    const previewZoomButtonIcons = {
        prev: document.dir == 'rtl' ? '<i class="fa-solid fa-arrow-right"></i>' : '<i class="fa-solid fa-arrow-left"></i>',
        next: document.dir == 'rtl' ? '<i class="fa-solid fa-arrow-left"></i>' : '<i class="fa-solid fa-arrow-right"></i>',
        rotate: '<i class="fa-duotone fa-solid fa-rotate-right"></i>',
        toggleheader: '<i class="fa-duotone fa-solid fa-arrow-down-arrow-up"></i>',
        fullscreen: '<i class="fa-regular fa-expand-wide"></i>',
        borderless: '<i class="fa-regular fa-compress-wide"></i>',
        close: '<i class="fa-regular fa-xmark"></i>'
    };
    const fileActionSettings = {
        rotateClass: '',
        rotateIcon: '<i class="fa-solid fa-rotate-right"></i>',
        zoomClass: '',
        zoomIcon: '<i class="fa-solid fa-magnifying-glass-plus"></i>',
        dragClass: 'p-2',
        dragIcon: '<i class="fa-solid fa-dots-six"></i>',
        removeClass: '',
        removeErrorClass: 'text-danger',
        removeIcon: '<i class="fa-light fa-trash"></i>',
        indicatorNew: '<i class="fa-solid fa-file-plus text-success"></i>',
        indicatorSuccess: '<i class="fa-solid fa-check file-icon-large text-success"></i>',
        indicatorError: '<i class="fa-solid fa-x text-danger"></i>',
        indicatorLoading: '<i class="fa-light fa-loader text-muted spinner"></i>'
    };

    document.addEventListener('DOMContentLoaded', () => {
        try {
            $('.file-input').each(function() {
                const $input = $(this);
                const initialPreview = $input.data('initial-preview') || null;
                const initialSize = $input.data('initial-preview-file-size') || null;
                const initialCaption = $input.data('initial-caption') || 'No file selected';
                const initialPreviewConfig = initialPreview ? [{
                    caption: initialCaption,
                    size: initialSize || null
                }] : [];

                $input.fileinput({
                    showPreview: true,
                    showUpload: false,
                    browseOnZoneClick: document.dir === 'rtl' ? false : true,
                    overwriteInitial: true,
                    initialCaption: initialCaption,
                    browseLabel: 'Browse',
                    browseIcon: '<i class="fa-light fa-clone-plus me-2"></i>',
                    removeIcon: '<i class="fa-light fa-x fs-base me-2"></i>',
                    removeClass: 'btn btn-light',
                    browseClass: 'btn btn-light',
                    layoutTemplates: {
                        icon: '<i class="fa-light fa-check"></i>'
                    },
                    initialPreviewAsData: true,
                    initialPreview: initialPreview || [],
                    initialPreviewConfig: initialPreviewConfig,
                    previewZoomButtonClasses: previewZoomButtonClasses,
                    previewZoomButtonIcons: previewZoomButtonIcons,
                    fileActionSettings: Object.assign({}, fileActionSettings, {
                        showUpload: false,
                        showZoom: false
                    }),
                    uploadAsync: false,
                    uploadUrl: null,
                    deleteUrl: null
                }).on('filepreajax filepreupload filebatchpreupload filepredelete filebeforedelete', (e) => {
                    e.preventDefault();
                    return false;
                });
            });
        } catch (e) {
            console.error('Error initializing fileinput:', e);
        }
    });



    let editor = null;

    function resizeEditor() {
        if (!editor || !editor.ui) return;
        const wrap = document.getElementById('editorWrap');
        editor.ui.resizeEditor({
            uiSize: {
                width: wrap.clientWidth,
                height: wrap.clientHeight
            }
        });
    }

    async function loadBlank() {
        const wrap = document.getElementById('editorWrap');
        const w = wrap.clientWidth || 1000;
        const h = wrap.clientHeight || Math.max(window.innerHeight - 220, 600);
        const c = document.createElement('canvas');
        c.width = w;
        c.height = h;
        const g = c.getContext('2d');
        g.fillStyle = '#fff';
        g.fillRect(0, 0, w, h);
        g.strokeStyle = '#f0f0f0';
        g.lineWidth = 1;
        for (let x = 0; x <= w; x += 50) {
            g.beginPath();
            g.moveTo(x, 0);
            g.lineTo(x, h);
            g.stroke();
        }
        for (let y = 0; y <= h; y += 50) {
            g.beginPath();
            g.moveTo(0, y);
            g.lineTo(w, y);
            g.stroke();
        }
        await editor.loadImageFromURL(c.toDataURL('image/png'), 'Blank');
        editor.clearUndoStack();
    }

    async function destroyEditor() {
        if (!editor) return;
        try {
            editor.destroy?.();
        } catch (e) {}
        editor = null;
    }

    function dataURLToFile(dataURL, filename) {
        const parts = dataURL.split(',');
        const mime = parts[0].match(/:(.*?);/)[1];
        const bin = atob(parts[1]);
        const len = bin.length;
        const u8 = new Uint8Array(len);
        for (let i = 0; i < len; i++) u8[i] = bin.charCodeAt(i);
        return new File([u8], filename, {
            type: mime
        });
    }

    function compressImage(dataURL, quality = 0.8, maxWidth = 2000) {
        return new Promise((resolve) => {
            const img = new Image();
            img.onload = () => {
                const canvas = document.createElement('canvas');
                let width = img.width;
                let height = img.height;

                // Resize if larger than maxWidth
                if (width > maxWidth) {
                    height = (height * maxWidth) / width;
                    width = maxWidth;
                }

                canvas.width = width;
                canvas.height = height;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, width, height);

                const compressedDataURL = canvas.toDataURL('image/jpeg', quality);
                resolve(compressedDataURL);
            };
            img.src = dataURL;
        });
    }

    function setFileIntoInput(input, file) {
        const dt = new DataTransfer();
        dt.items.add(file);
        input.files = dt.files;

        const $input = $(input);
        try {
            if ($input.data('fileinput')) {
                $input.fileinput('resetPreview');
                $input.fileinput('clearStack');
            }
        } catch (e) {
            console.warn('Error resetting fileinput:', e);
        }

        const ev = new Event('change', {
            bubbles: true
        });
        input.dispatchEvent(ev);
    }



    document.addEventListener('DOMContentLoaded', () => {
        const openBtns = document.querySelectorAll('.file-input-open-editor');
        const modalEl = document.getElementById('image-editor-modal');
        openBtns.forEach((openBtn) => {
            if (!openBtn.id) openBtn.id = 'file-input-' + Math.random().toString(36).slice(2);
            openBtn.addEventListener('click', (e) => {
                e.preventDefault();
                modalEl.setAttribute('data-input-id', openBtn.id);
                bootstrap.Modal.getOrCreateInstance(modalEl).show();
            });
        });
        modalEl.addEventListener('shown.bs.modal', async () => {
            await destroyEditor();
            editor = new tui.ImageEditor(document.getElementById('tuiEditor'), {
                includeUI: {
                    menu: ['crop', 'flip', 'rotate', 'draw', 'shape', 'icon', 'text', 'mask', 'filter'],
                    initMenu: 'crop',
                    uiSize: {
                        width: '100%',
                        height: '100%'
                    },
                    menuBarPosition: 'bottom',
                    setCropzoneRectOption: {
                        movable: true,
                        resizable: true,
                        drawable: true,
                        aspectRatio: null
                    },
                },
                usageStatistics: false
            });
            const inputId = modalEl.getAttribute('data-input-id');
            const inputEl = document.getElementById(inputId);
            const file = inputEl && inputEl.files && inputEl.files[0] ? inputEl.files[0] : null;
            if (file) {
                const objUrl = URL.createObjectURL(file);
                await editor.loadImageFromURL(objUrl, file.name || 'Image');
                editor.clearUndoStack();
                URL.revokeObjectURL(objUrl);
            } else {
                await loadBlank();
            }
            requestAnimationFrame(resizeEditor);
            setTimeout(() => {
                const loadBtn = document.querySelector('.tui-image-editor-load-btn');
                if (loadBtn) loadBtn.click();
            }, 200);
        });
        modalEl.addEventListener('hidden.bs.modal', async () => {
            await destroyEditor();
        });
        window.addEventListener('resize', () => {
            if (modalEl.classList.contains('show')) resizeEditor();
        });
    });

    $('.image-edited-apply-btn').on('click', async () => {
        if (!editor) return;
        let dataURL = editor.toDataURL();

        // Compress the image
        dataURL = await compressImage(dataURL, 0.85, 2000);

        const file = dataURLToFile(dataURL, 'edited.jpg');

        const modalEl = document.getElementById('image-editor-modal');
        const inputId = modalEl.getAttribute('data-input-id');
        const inputEl = document.getElementById(inputId);
        if (!inputEl) return;

        setFileIntoInput(inputEl, file);

        const inst = bootstrap.Modal.getInstance(modalEl);
        if (inst) inst.hide();
    });


    function loadBase64IntoInput(base64, filename) {
        const file = dataURLToFile(base64, filename || 'image.png');
        const input = document.querySelector('.file-input-open-editor');
        if (input) setFileIntoInput(input, file);
    }

    function setPreviewDirect($input, dataURL, name) {
        try {
            $input.fileinput('refresh', {
                initialPreview: [dataURL],
                initialPreviewAsData: true,
                initialCaption: name
            });
        } catch (e) {
            console.warn('Error refreshing fileinput preview:', e);
        }
    }
</script>
