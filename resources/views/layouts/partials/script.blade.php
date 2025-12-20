 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
 <script>
     if (typeof jQuery === 'undefined') {
         document.write('<script src="' +
             '{{ asset('assets/js/jquery/jquery.min.js') }}?v={{ config('init.layout_version') }}' + '"><\/script>');
     }
 </script>
 <script src="{{ asset('assets/js/bootstrap/bootstrap.bundle.min.js') }}?v={{ config('init.layout_version') }}"></script>
 <script
     src="{{ asset('assets/js/vendor/uploaders/fileinput/fileinput.min.js') }}?v={{ config('init.layout_version') }}">
 </script>
 <script src="{{ asset('assets/js/vendor/notifications/sweet_alert.min.js') }}?v={{ config('init.layout_version') }}">
 </script>
 <script
     src="{{ asset('assets/js/vendor/forms/selects/bootstrap_multiselect.js') }}?v={{ config('init.layout_version') }}">
 </script>
 <script src="{{ asset('assets/js/vendor/forms/selects/select2.min.js') }}?v={{ config('init.layout_version') }}">
 </script>
 <script src="{{ asset('assets/js/vendor/ui/moment/moment.min.js') }}?v={{ config('init.layout_version') }}"></script>
 <script src="{{ asset('assets/js/vendor/pickers/daterangepicker.js') }}?v={{ config('init.layout_version') }}">
 </script>
 <script src="{{ asset('assets/js/vendor/forms/validation/validate.min.js') }}?v={{ config('init.layout_version') }}">
 </script>
 <script src="{{ asset('assets/js/vendor/pickers/datepicker.min.js') }}?v={{ config('init.layout_version') }}">
 </script>
 <script src="{{ asset('assets/js/vendor/media/glightbox.min.js') }}?v={{ config('init.layout_version') }}"></script>
 <script src="{{ asset('assets/js/vendor/editors/ckeditor.js') }}?v={{ config('init.layout_version') }}"></script>
 <script src="{{ asset('assets/js/app.js') }}?v={{ config('init.layout_version') }}"></script>
 <script src="{{ asset('assets/js/helpers.js') }}?v={{ config('init.layout_version') }}"></script>
 <script>
     // initialize body overlay
     //  please make overlay start and stop when page loaded
     $.ajaxSetup({
         headers: {
             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         beforeSend: function() {
             loading('start');
         }
     });
     $(document).ajaxComplete(function() {
         loading('stop');
     });
     $(document).ajaxError(function() {
         error('Something went wrong');
         loading('stop');
     });
     $(document).ready(function() {

         $(document).find('select.select-icons').select2({
             templateResult: iconFormat,
             minimumResultsForSearch: Infinity,
             templateSelection: iconFormat,
             escapeMarkup: function(m) {
                 return m;
             }
         });
         $(document).find('select.select2').select2({
             minimumResultsForSearch: Infinity,
             escapeMarkup: function(m) {
                 return m;
             }
         });

         // Initialize datepickers globally
         $(document).find('.datepicker').each(function() {
             if (!$(this).data('datepicker')) {
                 new Datepicker(this, {
                     format: 'yyyy-mm-dd',
                     autohide: true,
                     todayBtn: true,
                     clearBtn: true,
                     todayBtnMode: 1,
                     todayHighlight: true
                 });
             }
         });
     });

     function loading(e) {
         if (e === 'stop') {
             $(document).find('#body-overlay').addClass('d-none');
         } else {
             $(document).find('#body-overlay').removeClass('d-none');
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
                                 el.closest('.dataTables_wrapper').find('table.datatables')
                                     .DataTable().ajax.reload();
                                 success(res.message);
                             } else {
                                 window.location.reload();
                             }
                         } else {
                             error(res.message);
                         }
                     },
                     error: function(e) {
                         let message = 'Something went wrong';
                         if (e.responseJSON && e.responseJSON.message) {
                             message = e.responseJSON.message;
                         }
                         error(message);
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

     function changeLanguage(lang) {
         setTimeout(function() {
             $('#change-language-locale').val(lang);
             document.getElementById('change-language-form').submit();
         }, 300);
     }

     function copyToClipboard(e) {
         e.preventDefault();
         const text = $(e.target).attr('clipboard-text');
         navigator.clipboard.writeText(text).then(() => {
             success('{{ __('messages.copied_to_clipboard') }}');
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
                 error('Something went wrong');
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
                     success(res.message);
                     if (redirect) {
                         window.location.href = redirect;
                     }
                 } else {
                     error(res.message);
                 }
             },
             error: function(e) {
                 let message = 'Something went wrong';
                 if (e.responseJSON && e.responseJSON.message) {
                     message = e.responseJSON.message;
                 }
                 error(message);
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
                 error('Please fill the form correctly');
                 return;
             }
             $(form).find('input[type="file"]').each(function() {
                 const input = $(this);
                 const base64 = input.parents('.file-input').find('.file-preview-image').attr(
                     'src') || '';
                 if (base64 && base64.startsWith('data:')) {
                     input.replaceWith('<input type="hidden" name="' + input.attr('name') +
                         '" value="' + base64 + '">');
                 }
             });
             //  Ckeditor handling
             $(form).find('.editor').each(function() {
                 const node = $(this);
                 const editorInstance = editorsMap.get(this);
                 if (editorInstance) {
                     const data = editorInstance.getData();
                     node.val(data);
                 }
             });
             $.ajax({
                 url: form.getAttribute('action'),
                 type: form.getAttribute('method') || 'POST',
                 data: $(form).serialize(),
                 dataType: 'json',
                 success: function(res) {
                     if (res.status === 'success') {
                         success(res.message);
                         if (res.redirect) {
                             setTimeout(() => {
                                 window.location.href = res.redirect;
                             }, res.delay || 2000);
                         }
                     } else {
                         error(res.message);
                     }
                 },
                 error: function(e) {
                     let message = 'Something went wrong';
                     if (e.responseJSON && e.responseJSON.message) {
                         message = e.responseJSON.message;
                     }
                     error(message);
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
     const editorHTML = new Map();
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
                     toolbar: ['imageTextAlternative', 'imageStyle:side', 'imageStyle:alignLeft',
                         'imageStyle:alignCenter', 'imageStyle:alignRight'
                     ],
                     insert: {
                         integrations: ['upload'],
                     }
                 },
                 extraPlugins: [CdnUploadPlugin],
             })
             .then(editor => {
                 editorsMap.set(node, editor);
                 if (editorHTML.has(node)) {
                     editor.setData(editorHTML.get(node) || '');
                     editorHTML.delete(node);
                 }
                 const editableEl = editor.ui.view.editable.element;
                 editableEl.style.minHeight = minHeight;
             })
             .catch(console.error);
     });
 </script>
