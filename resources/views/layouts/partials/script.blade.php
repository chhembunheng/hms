 <script src="{{ asset('assets/js/jquery/jquery.min.js') }}"></script>
 <script src="{{ asset('assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
 <script src="{{ asset('assets/js/vendor/uploaders/fileinput/fileinput.min.js') }}"></script>
 <script src="{{ asset('assets/js/vendor/notifications/sweet_alert.min.js') }}"></script>
 <script src="{{ asset('assets/js/vendor/forms/selects/bootstrap_multiselect.js') }}?{{ time() }}"></script>
 <script src="{{ asset('assets/js/vendor/forms/selects/select2.min.js') }}"></script>
 <script src="{{ asset('assets/js/vendor/ui/moment/moment.min.js') }}"></script>
 <script src="{{ asset('assets/js/vendor/pickers/daterangepicker.js') }}"></script>
 <script src="{{ asset('assets/js/vendor/forms/validation/validate.min.js') }}"></script>
 <script src="{{ asset('assets/js/vendor/pickers/datepicker.min.js') }}"></script>
 <script src="https://cdn.ckeditor.com/ckeditor5/41.3.1/classic/ckeditor.js"></script>
 <!-- REQUIRED deps — ORDER MATTERS -->
 <script src="https://cdnjs.cloudflare.com/ajax/libs/tui-code-snippet/2.3.2/tui-code-snippet.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/fabric@4.6.0/dist/fabric.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/tui-color-picker@2.2.8/dist/tui-color-picker.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/tui-image-editor/3.15.3/tui-image-editor.min.js"></script>
 <script src="{{ asset('assets/js/app.js') }}?v={{ time() }}"></script>
 <script src="{{ asset('assets/js/init.js') }}"></script>
 <script>
     $(document).ready(function() {
         $('#body-overlay').hide();
         $(document).find('select.multiple-select').multiselect();
         $(document).find('select.select-icons').select2({
             templateResult: iconFormat,
             minimumResultsForSearch: Infinity,
             templateSelection: iconFormat,
             escapeMarkup: function(m) {
                 return m;
             }
         });
         $(document).find('select.select2').select2();
     });
     const swalInit = swal.mixin({
         buttonsStyling: false,
         customClass: {
             confirmButton: 'btn btn-primary',
             cancelButton: 'btn btn-light',
             denyButton: 'btn btn-light',
             input: 'form-control'
         }
     });

     function loading(e) {
         if (e == 'stop') {
             $('#body-overlay').hide();
         } else {
             $('#body-overlay').show();
         }
     }

     function logout() {
         swalInit.fire({
             title: '{{ __('messages.are_you_sure') }}',
             text: '{{ __('messages.logout_confirmation') }}',
             icon: 'question',
             showCancelButton: true,
             confirmButtonText: '<i class="fa-solid fa-arrow-right-from-bracket fa-fw"></i> &nbsp;{{ __('messages.yes_logout') }}',
             cancelButtonText: '<i class="fa-solid fa-ban fa-fw"></i> &nbsp;{{ __('messages.no_cancel') }}',
             buttonsStyling: false,
             customClass: {
                 confirmButton: 'btn btn-flat-danger',
                 cancelButton: 'btn btn-light'
             }
         }).then(function(result) {
             if (result.value) {
                 // 2.5s delay
                 setTimeout(function() {
                     document.getElementById('logout-form').submit();
                 }, 300);
             }
         });
     }

     function deleteRecord(e) {
         e.preventDefault();
         const el = $(e.target);
         const url = el.attr('href');
         swalInit.fire({
             title: '{{ __('messages.are_you_sure') }}',
             text: '{{ __('messages.delete_confirmation') }}',
             icon: 'question',
             showCancelButton: true,
             confirmButtonText: '<i class="fa-solid fa-trash-can fa-fw"></i> &nbsp; {{ __('messages.yes_delete') }}',
             cancelButtonText: '<i class="fa-solid fa-ban fa-fw"></i> &nbsp; {{ __('messages.no_cancel') }}',
             buttonsStyling: false,
             customClass: {
                 confirmButton: 'btn btn-flat-danger',
                 cancelButton: 'btn btn-light'
             }
         }).then(function(result) {
             if (result.value) {
                 $.ajax({
                     url: url,
                     type: 'DELETE',
                     success: function(response) {
                         if (response.status === 'success') {
                             swalInit.fire({
                                 title: 'Deleted!',
                                 text: response.message,
                                 icon: 'success',
                                 customClass: {
                                     confirmButton: 'btn btn-success'
                                 }
                             }).then(function() {
                                 if (el.closest('.dataTables_wrapper').length) {
                                     el.closest('.dataTables_wrapper').find('table.datatables').DataTable().ajax.reload();
                                 } else {
                                     window.location.reload();
                                 }
                             });
                         } else {
                             swalInit.fire({
                                 title: 'Error!',
                                 text: response.message,
                                 icon: 'error',
                                 customClass: {
                                     confirmButton: 'btn btn-danger'
                                 }
                             });
                         }
                     },
                     error: function(e) {
                         swalInit.fire({
                             title: 'Error!',
                             text: 'Something went wrong',
                             icon: 'error',
                             customClass: {
                                 confirmButton: 'btn btn-danger'
                             }
                         });
                     }
                 });
             }
         });
     }

     function clearCache() {
         swalInit.fire({
             title: '{{ __('messages.are_you_sure') }}',
             text: '{{ __('messages.clear_confirmation') }}',
             icon: 'question',
             showCancelButton: true,
             confirmButtonText: '<i class="fa-solid fa-broom-wide fa-fw"></i> &nbsp; {{ __('messages.yes_clear') }}',
             cancelButtonText: '<i class="fa-solid fa-ban fa-fw"></i> &nbsp; {{ __('messages.no_cancel') }}',
             buttonsStyling: false,
             customClass: {
                 confirmButton: 'btn btn-flat-success',
                 cancelButton: 'btn btn-light'
             }
         }).then(function(result) {
             if (result.value) {
                 setTimeout(function() {
                     window.location.href = '{{ route('clear-cache') }}';
                 }, 300);
             }
         });
     }

     $.ajaxSetup({
         headers: {
             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         beforeSend: function() {
             loading();
         },
         complete: function(e) {
             loading('stop');
             if (e.status === 403) {
                 window.location.href = '/403';
             }
         }
     });

     function changeLanguage(lang) {
         setTimeout(function() {
             $('#change-language-locale').val(lang);
             document.getElementById('change-language-form').submit();
         }, 300);
     }

     function copyToClipboard(e) {
         e.preventDefault();
         const text = $(e.target).attr('clipboard-text');
         console.log(text);
         navigator.clipboard.writeText(text).then(() => {
             swalInit.fire({
                 text: 'Copied to clipboard',
                 icon: 'success',
                 toast: true,
                 showConfirmButton: false,
                 position: 'top-end',
                 timer: 1000
             });
         });
     }
     $(document).on('click', '.action-buttons button', function(e) {
         e.preventDefault();
         const form = $(this).closest('form');
         const value = $(this).val();
         form.find('input[name="redirect"]').val(value);
         form.submit();
     });

     function iconFormat(icon) {
         var originalOption = icon.element;
         if (!icon.id) {
             return icon.text;
         }
         var $icon = '<i class="fa-solid fa-' + $(icon.element).data('icon') + ' fa-fw fa-lg"></i>' + icon.text;

         return $icon;
     }
     $(document).on('click', '.modal-remote', function(e) {
         e.preventDefault();
         const modal = $('#modal-remote');
         const url = $(this).attr('href');
         const method = $(this).data('method') || 'GET';
         modal.empty();
         $.ajax({
             url: url,
             type: method,
             dataType: 'json',
             success: function(res) {
                 modal.html(res.body);
                 const modalInstance = new bootstrap.Modal(modal, {
                     backdrop: 'static',
                     keyboard: false
                 });
                 modalInstance.show();
                 modal.find('form').validate({
                     errorPlacement: function(error, element) {
                         var elem = $(element);
                         if (elem.hasClass('select2-hidden-accessible')) {
                             error.insertAfter(elem.siblings('span.select2'));
                         } else {
                             error.insertAfter(element);
                         }
                     }
                 });
                 modal.find('select.select2').each(function() {
                     $(this).select2({
                         minimumResultsForSearch: Infinity,
                         dropdownParent: $(this).parents('.modal'),
                         escapeMarkup: function(m) {
                             return m;
                         }
                     }).on('select2:select select2:unselect', function() {
                         $(this).valid();
                     });
                 });
             },
             error: function(e) {
                 swalInit.fire({
                     title: 'Error!',
                     text: 'Something went wrong',
                     icon: 'error',
                     customClass: {
                         confirmButton: 'btn btn-danger'
                     }
                 });
             }
         });
     });
     $(document).on('submit', '.ajax-form-modal', function(e) {
         e.preventDefault();
         const form = $(this);
         const url = form.attr('action');
         const method = form.attr('method') || 'POST';
         const redirect = form.find('input[name="redirect"]').val() || '';
         if (!form.valid()) {
             return;
         }
         $.ajax({
             url: url,
             type: method,
             data: form.serialize(),
             dataType: 'json',
             success: function(res) {
                 if (res.status === 'success') {
                     swalInit.fire({
                         title: 'Success!',
                         text: res.message,
                         icon: 'success',
                         customClass: {
                             confirmButton: 'btn btn-success'
                         }
                     }).then(function() {
                         if (redirect) {
                             window.location.href = redirect;
                         } else {
                             location.reload();
                         }
                     });
                 } else {
                     swalInit.fire({
                         title: 'Error!',
                         text: res.message,
                         icon: 'error',
                         customClass: {
                             confirmButton: 'btn btn-danger'
                         }
                     });
                 }
             },
             error: function(e) {
                 let message = 'Something went wrong';
                 if (e.responseJSON && e.responseJSON.message) {
                     message = e.responseJSON.message;
                 }
                 swalInit.fire({
                     title: 'Error!',
                     text: message,
                     icon: 'error',
                     customClass: {
                         confirmButton: 'btn btn-danger'
                     }
                 });
             }
         });
     });
     const buildSelect2 = () => {
         $(document).find('select.select2').select2({
             minimumResultsForSearch: Infinity,
             dropdownParent: $(this).parents('.modal'),
             escapeMarkup: function(m) {
                 return m;
             }
         });
     };
     const formValidation = document.querySelectorAll('form[validate]');
     formValidation.forEach((form) => {
         $(form).validate({
             errorPlacement: function(error, element) {
                 var elem = $(element);
                 if (elem.hasClass('select2-hidden-accessible')) {
                     error.insertAfter(elem.siblings('span.select2'));
                 } else {
                     error.insertAfter(element);
                 }
             }
         });
     });
     const editorsMap = new Map();

     // Optional: if meta call returns before editor is ready, stash the HTML here
     const pendingEditorHTML = new Map();

     const editors = document.querySelectorAll('.editor');
     editors.forEach((node) => {
         let toolbars = [
             'heading', '|',
             'bold', 'italic', 'link',
             'bulletedList', 'numberedList',
             'insertImage', 'mediaEmbed',
             'undo', 'redo'
         ];
         const basic = node.classList.contains('basic');
         if (basic) {
             toolbars = [
                 'bold', 'italic', 'link',
                 'bulletedList', 'numberedList',
                 'undo', 'redo'
             ];
         }

         const minHeight = Number(node.getAttribute('rows') || 3) * 50 + 'px';
         $(node).parent().css('--editor-min-height', minHeight);
         $(node).parent().css('--editor-height', minHeight);

         ClassicEditor.create(node, {
                 licenseKey: 'GPL',
                 toolbar: toolbars,
                 image: {
                     toolbar: ['imageTextAlternative', 'imageStyle:full', 'imageStyle:side'],
                     insert: {
                         integrations: ['upload', 'url']
                     }
                 },
                 ckfinder: {
                     uploadUrl: '{{ route('text-editor.upload') }}?&_token={{ csrf_token() }}'
                 }
             })
             .then(editor => {
                 editorsMap.set(node, editor);
                 if (pendingEditorHTML.has(node)) {
                     editor.setData(pendingEditorHTML.get(node) || '');
                     pendingEditorHTML.delete(node);
                 }
                 const editableEl = editor.ui.view.editable.element;
                 editableEl.style.minHeight = minHeight;
             })
             .catch(console.error);
     });
     let metaGenerated = false;
     $(document).on('click', '.meta-generator', function() {
         const content = $('input.content-seo').val() || '';
         const title = $('input[name="meta[title]"]');
         const description = $('input[name="meta[description]"]');
         const keywords = $('input[name="meta[keywords]"]');
         const contentNode = document.querySelector('textarea.content-en');
         const instance = editorsMap.get(contentNode);
         if (metaGenerated) {
             return;
         }
         $(this).addClass('fa-fw fa-2x fa-beat-fade');
         metaGenerated = true;
         $.ajax({
             url: '{{ route('meta-generator.generate') }}',
             method: 'POST',
             data: {
                 content: content,
                 _token: '{{ csrf_token() }}'
             },
             success: function(res) {
                 if (res.status === 'success') {
                     swalInit.fire({
                         title: 'Success!',
                         text: 'Meta information generated successfully',
                         icon: 'success',
                         customClass: {
                             confirmButton: 'btn btn-success'
                         }
                     });
                     if (res.data.title) {
                         title.val(res.data.title);
                     }
                     if (res.data.description) {
                         description.val(res.data.description);
                     }
                     if (res.data.keywords) {
                         keywords.val(res.data.keywords);
                     }
                     if (res.data.content) {
                         const instance = editorsMap.get(contentNode);
                         if (instance) {
                             instance.setData(res.data.content);
                         } else {
                             pendingEditorHTML.set(contentNode, res.data.content);
                         }
                     }
                 } else {
                     swalInit.fire({
                         title: 'Error!',
                         text: res.message,
                         icon: 'error',
                         customClass: {
                             confirmButton: 'btn btn-danger'
                         }
                     });
                 }
             },
             complete: function() {
                 $('.meta-generator').removeClass('fa-fw fa-2x fa-beat-fade');
                 metaGenerated = false;
             },
             error: function(e) {
                 let message = 'Something went wrong';
                 if (e.responseJSON && e.responseJSON.message) {
                     message = e.responseJSON.message;
                 }
                 swalInit.fire({
                     title: 'Error!',
                     text: message,
                     icon: 'error',
                     customClass: {
                         confirmButton: 'btn btn-danger'
                     }
                 });
             }
         });
     });

     const previewZoomButtonClasses = {
         rotate: 'btn btn-light btn-icon btn-sm',
         toggleheader: 'btn btn-light btn-icon btn-header-toggle btn-sm',
         fullscreen: 'btn btn-light btn-icon btn-sm',
         borderless: 'btn btn-light btn-icon btn-sm',
         close: 'btn btn-light btn-icon btn-sm'
     };

     // Icons inside zoom modal classes
     const previewZoomButtonIcons = {
         prev: document.dir == 'rtl' ? '<i class="fa-solid fa-arrow-right"></i>' : '<i class="fa-solid fa-arrow-left"></i>',
         next: document.dir == 'rtl' ? '<i class="fa-solid fa-arrow-left"></i>' : '<i class="fa-solid fa-arrow-right"></i>',
         rotate: '<i class="fa-duotone fa-solid fa-rotate-right"></i>',
         toggleheader: '<i class="fa-duotone fa-solid fa-arrow-down-arrow-up"></i>',
         fullscreen: '<i class="fa-regular fa-expand-wide"></i>',
         borderless: '<i class="fa-regular fa-compress-wide"></i>',
         close: '<i class="fa-regular fa-xmark"></i>'
     };

     // File actions
     const fileActionSettings = {
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


     //
     // Basic example
     //

     $('.file-input').fileinput({
         browseLabel: 'Browse',
         browseIcon: '<i class="fa-light fa-clone-plus me-2"></i>',
         uploadIcon: '<i class="fa-light fa-cloud-arrow-up me-2"></i>',
         removeIcon: '<i class="fa-light fa-x fs-base me-2"></i>',
         layoutTemplates: {
             icon: '<i class="fa-light fa-check"></i>'
         },
         uploadClass: 'btn btn-light',
         removeClass: 'btn btn-light',
         browseClass: 'btn btn-light',
         initialCaption: "No file selected",
         previewZoomButtonClasses: previewZoomButtonClasses,
         previewZoomButtonIcons: previewZoomButtonIcons,
         fileActionSettings: fileActionSettings
     });

     let editor = null;

     function resizeEditor() {
         if (!editor || !editor.ui) return;
         const wrap = document.getElementById("editorWrap");
         editor.ui.resizeEditor({
             uiSize: {
                 width: wrap.clientWidth,
                 height: wrap.clientHeight
             }
         });
     }

     async function loadBlank() {
         const wrap = document.getElementById("editorWrap");
         const w = wrap.clientWidth || 1000;
         const h = wrap.clientHeight || Math.max(window.innerHeight - 220, 600);

         const c = document.createElement("canvas");
         c.width = w;
         c.height = h;
         const g = c.getContext("2d");
         g.fillStyle = "#fff";
         g.fillRect(0, 0, w, h);

         // (optional) subtle grid
         g.strokeStyle = "#f0f0f0";
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

         await editor.loadImageFromURL(c.toDataURL("image/png"), "Blank");
         editor.clearUndoStack();
     }

     async function destroyEditor() {
         if (!editor) return;
         try {
             editor.destroy?.();
         } catch (e) {
             console.warn(e);
         }
         editor = null;
     }

     document.addEventListener("DOMContentLoaded", () => {
         const openBtn = document.querySelector(".file-input-open-editor");
         const modalEl = document.getElementById("editorModal");
         openBtn.addEventListener("click", (e) => {
             e.preventDefault();
             bootstrap.Modal.getOrCreateInstance(modalEl).show();
         });
         modalEl.addEventListener("shown.bs.modal", async () => {
             await destroyEditor();
             editor = new tui.ImageEditor(document.getElementById("tuiEditor"), {
                 includeUI: {
                     menu: ['crop', 'flip', 'rotate', 'draw', 'shape', 'icon', 'text', 'mask', 'filter'],
                     initMenu: 'crop',
                     uiSize: {
                         width: '100%',
                         height: '100%'
                     },
                     menuBarPosition: 'bottom'
                 },
                 usageStatistics: false
             });
             await loadBlank();
             requestAnimationFrame(resizeEditor);
             setTimeout(() => {
                 const loadBtn = document.querySelector('.tui-image-editor-load-btn');
                 if (loadBtn) loadBtn.click();
             }, 200);
         });
         modalEl.addEventListener("hidden.bs.modal", async () => {
             await destroyEditor();
         });
         window.addEventListener("resize", () => {
             if (modalEl.classList.contains("show")) resizeEditor();
         });
     });
 </script>
