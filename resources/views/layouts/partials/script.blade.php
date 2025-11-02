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
 <script src="{{ asset('assets/js/vendor/notifications/noty.min.js') }}"></script>
 <script src="{{ asset('assets/js/vendor/media/glightbox.min.js') }}"></script>
 <script src="https://cdn.ckeditor.com/ckeditor5/41.3.1/classic/ckeditor.js"></script>
 <script src="{{ asset('assets/js/app.js') }}?v={{ time() }}"></script>
 <script src="{{ asset('assets/js/init.js') }}?v={{ time() }}"></script>
 <script src="{{ asset('assets/js/helpers.js') }}?v={{ time() }}"></script>
 <script>
     // override Noty default settings
     Noty.overrideDefaults({
         theme: 'limitless',
         layout: 'topRight',
         type: 'alert',
         timeout: 2500
     });
     // initialize SweetAlert2
     const swalInit = swal.mixin({
         buttonsStyling: false,
         customClass: {
             confirmButton: 'btn btn-primary',
             cancelButton: 'btn btn-light',
             denyButton: 'btn btn-light',
             input: 'form-control'
         }
     });
     // initialize body overlay
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
                     success: function(res) {
                         if (res.status === 'success') {
                             if (el.closest('.dataTables_wrapper').length) {
                                 el.closest('.dataTables_wrapper').find('table.datatables').DataTable().ajax.reload();
                                 new Noty({
                                     type: 'success',
                                     text: '<i class="fa-solid fa-check fa-fw"></i> ' + res.message
                                 }).show();
                             } else {
                                 window.location.reload();
                             }
                         } else {
                             new Noty({
                                 type: 'error',
                                 text: res.message
                             }).show();
                         }
                     },
                     error: function(e) {
                         new Noty({
                             type: 'error',
                             text: 'Something went wrong'
                         }).show();
                     }
                 });
             }
         });
     }

     function clearCache() {
         window.location.href = '{{ route('clear-cache') }}';
     }

     function clearCache() {
         window.location.href = '{{ route('clear-cache') }}';
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
         $(form).on('submit', function(e) {
             e.preventDefault();
             if (!$(form).valid()) {
                 e.preventDefault();
                 return false;
             }
             $.ajax({
                 url: form.getAttribute('action'),
                 type: form.getAttribute('method') || 'POST',
                 data: $(form).serialize(),
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
                             if (res.redirect) {
                                 window.location.href = res.redirect;
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
     });

     class CdnUploadAdapter {
         constructor(loader) {
             this.loader = loader;
             this.xhr = null;
         }

         async upload() {
             try {
                 const file = await this.loader.file;

                 // Convert file to base64
                 const base64 = await this.fileToBase64(file);

                 return {
                     default: base64
                 };
             } catch (error) {
                 console.error('Upload error:', error);
                 throw error;
             }
         }

         fileToBase64(file) {
             return new Promise((resolve, reject) => {
                 const reader = new FileReader();

                 reader.addEventListener('load', () => {
                     resolve(reader.result);
                 });

                 reader.addEventListener('error', () => {
                     reject(new Error('Failed to read file'));
                 });

                 reader.addEventListener('progress', (evt) => {
                     if (evt.lengthComputable) {
                         this.loader.uploadTotal = evt.total;
                         this.loader.uploaded = evt.loaded;
                     }
                 });

                 reader.readAsDataURL(file);
             });
         }

         abort() {
             // No abort needed for local conversion
         }
     }

     function CdnUploadPlugin(editor) {
         editor.plugins.get('FileRepository').createUploadAdapter = loader => new CdnUploadAdapter(loader);
     }

     const editorsMap = new Map();
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
                     toolbar: ['imageTextAlternative', 'imageStyle:side'],
                     insert: {
                         integrations: ['upload'],
                     }
                 },
                 extraPlugins: [CdnUploadPlugin],
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
         if (empty(content)) {
             swalInit.fire({
                 toast: true,
                 icon: 'error',
                 text: 'Please enter content to generate meta information',
                 position: 'top-end',
                 background: '#f8d7da',
                 timer: 3000,
                 timerProgressBar: true,
                 showConfirmButton: false
             });
             return;
         }
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
                         toast: true,
                         position: 'top-end',
                         timer: 3000,
                         showConfirmButton: false,
                         timerProgressBar: true
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
                         showConfirmButton: false,
                         toast: true,
                         position: 'top-end',
                         timer: 3000,
                         timerProgressBar: true
                     });
                 }
             },
             complete: function() {
                 $('.meta-generator').removeClass('fa-fw fa-2x fa-beat-fade');
                 metaGenerated = false;
                 loading('stop');
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
                     toast: true,
                     position: 'top-end',
                     timer: 3000,
                     timerProgressBar: true,
                 });
             }
         });
     });
 </script>
