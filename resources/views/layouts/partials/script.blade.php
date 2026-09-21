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
    {{-- Pickadate Scripts from beltei_ums --}}
    <script src="{{ asset('assets/extend/pickadate/picker.js') }}?v={{ config('init.layout_version') }}"></script>
    <script src="{{ asset('assets/extend/pickadate/picker.date.js') }}?v={{ config('init.layout_version') }}"></script>
    <script src="{{ asset('assets/extend/pickadate/legacy.js') }}?v={{ config('init.layout_version') }}"></script>
 <script src="{{ asset('assets/js/vendor/media/glightbox.min.js') }}?v={{ config('init.layout_version') }}"></script>
 <script src="{{ asset('assets/js/vendor/editors/ckeditor.js') }}?v={{ config('init.layout_version') }}"></script>
 <script src="{{ asset('assets/js/app.js') }}?v={{ config('init.layout_version') }}"></script>
 <script src="{{ asset('assets/js/helpers.js') }}?v={{ config('init.layout_version') }}"></script>
 <script src="{{ asset('assets/js/vendor/lucide.min.js') }}?v={{ config('init.layout_version') }}"></script>
 <script>
     // Lucide Icons Engine & FontAwesome Auto-Converter
     window.initLucideIcons = function(root) {
         if (typeof lucide === 'undefined') return;
         const container = root || document;

         const faMap = {
             'fa-chart-simple': 'layout-dashboard',
             'fa-chart-line': 'trending-up',
             'fa-right-to-bracket': 'log-in',
             'fa-right-from-bracket': 'log-out',
             'fa-arrow-right-from-bracket': 'log-out',
             'fa-bed': 'bed',
             'fa-bed-double': 'bed-double',
             'fa-users': 'users',
             'fa-users-line': 'users-round',
             'fa-user': 'user',
             'fa-user-circle': 'user-round',
             'fa-file-invoice-dollar': 'receipt',
             'fa-receipt': 'receipt',
             'fa-sliders': 'settings',
             'fa-gear': 'settings',
             'fa-bars': 'menu',
             'fa-magnifying-glass': 'search',
             'fa-search': 'search',
             'fa-chevron-down': 'chevron-down',
             'fa-chevron-right': 'chevron-right',
             'fa-chevron-left': 'chevron-left',
             'fa-chevron-up': 'chevron-up',
             'fa-calendar': 'calendar',
             'fa-calendar-check': 'calendar-check',
             'fa-calendar-days': 'calendar-days',
             'fa-clock': 'clock',
             'fa-clock-rotate-left': 'history',
             'fa-van-shuttle': 'bus',
             'fa-plane': 'plane',
             'fa-plane-arrival': 'plane-landing',
             'fa-plane-departure': 'plane-takeoff',
             'fa-car': 'car',
             'fa-truck-monster': 'truck',
             'fa-motorcycle': 'bike',
             'fa-passport': 'file-badge',
             'fa-id-card': 'id-card',
             'fa-house': 'home',
             'fa-hotel': 'building-2',
             'fa-circle-dollar-to-slot': 'circle-dollar-sign',
             'fa-coins': 'coins',
             'fa-earth-americas': 'globe',
             'fa-compass': 'compass',
             'fa-mountain-sun': 'mountain',
             'fa-shirt': 'shirt',
             'fa-spa': 'flower-2',
             'fa-utensils': 'utensils',
             'fa-wine-glass': 'wine',
             'fa-layer-group': 'layers',
             'fa-list': 'list',
             'fa-list-check': 'list-checks',
             'fa-pen-to-square': 'pencil',
             'fa-pen': 'pencil',
             'fa-trash': 'trash-2',
             'fa-trash-can': 'trash-2',
             'fa-plus': 'plus',
             'fa-plus-circle': 'plus-circle',
             'fa-check': 'check',
             'fa-check-circle': 'check-circle',
             'fa-circle-check': 'check-circle',
             'fa-xmark': 'x',
             'fa-circle-xmark': 'x-circle',
             'fa-filter': 'filter',
             'fa-rotate-right': 'rotate-cw',
             'fa-arrow-down': 'arrow-down',
             'fa-arrow-up': 'arrow-up',
             'fa-arrow-left': 'arrow-left',
             'fa-arrow-right': 'arrow-right',
             'fa-file-excel': 'file-spreadsheet',
             'fa-file-lines': 'file-text',
             'fa-file-pdf': 'file-text',
             'fa-print': 'printer',
             'fa-broom-wide': 'sparkles',
             'fa-location-dot': 'map-pin',
             'fa-circle-dot': 'disc',
             'fa-suitcase': 'briefcase',
             'fa-door-open': 'door-open',
             'fa-phone': 'phone',
             'fa-envelope': 'mail',
             'fa-eye': 'eye',
             'fa-lock': 'lock',
             'fa-unlock': 'unlock',
             'fa-bolt': 'zap',
             'fa-folder': 'folder',
             'fa-folder-open': 'folder-open',
             'fa-shopping-cart': 'shopping-cart',
             'fa-note-sticky': 'sticky-note',
             'fa-circle-info': 'info',
             'fa-triangle-exclamation': 'alert-triangle'
         };

         try {
             container.querySelectorAll('i[class*="fa-"]').forEach(el => {
                 if (el.hasAttribute('data-lucide')) return;
                 for (const cls of el.classList) {
                     if (faMap[cls]) {
                         el.setAttribute('data-lucide', faMap[cls]);
                         el.classList.remove(cls, 'fa-solid', 'fa-regular', 'fa-light', 'fa-fw');
                         break;
                     }
                 }
             });
             lucide.createIcons({ root: container });
         } catch(e) {
             console.warn('Lucide icon init:', e);
         }
     };

     // initialize body overlay
     // please make overlay start and stop when page loaded
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
         window.initLucideIcons();
     });
     $(document).ajaxError(function() {
         error('Something went wrong');
         loading('stop');
     });
     $(document).ready(function() {
         window.initLucideIcons();

         // Clean up any stale full-collapsed state from app.js
         try {
             localStorage.removeItem('sidebar-collapsed');
             $('.sidebar-main').removeClass('sidebar-collapsed');
         } catch (e) {}

         // Persistent sidebar toggle state (matching beltei_ums standard)
         var xSidebar = localStorage.getItem('xSidebar');
         if (xSidebar && $(window).width() >= 992) {
             $('body').addClass('sidebar-xs');
         }

         $('#sidebar-toggle-btn, .sidebar-main-toggle, .sidebar-mobile-main-toggle').on('click', function(e) {
             e.preventDefault();
             e.stopPropagation();
             if ($(window).width() >= 992) {
                 $('body').toggleClass('sidebar-xs');
                 $('.sidebar-main').removeClass('sidebar-collapsed');
                 if ($('body').hasClass('sidebar-xs')) {
                     localStorage.setItem('xSidebar', '1');
                 } else {
                     localStorage.removeItem('xSidebar');
                 }
             } else {
                 $('.sidebar-main').toggleClass('sidebar-mobile-expanded');
             }

             setTimeout(function() {
                 if (typeof $.fn.DataTable !== 'undefined') {
                     $('.datatables').DataTable().columns.adjust().responsive.recalc();
                 }
             }, 250);
         });

         // Fade out page loading overlay once DOM and components are ready (matching beltei_ums standard)
         setTimeout(function() {
             $('#page-loading-overlay').fadeOut(250, function() {
                 $(this).remove();
             });
         }, 150);

         // DISABLE DATATABLE ERROR ALERT (matching beltei_ums)
         if (typeof $.fn.dataTable !== 'undefined') {
             $.fn.dataTable.ext.errMode = 'none';
         }

         // Enterprise Filter Panel Toggle
         $(document).on('click', '#toggle-filter-panel', function(e) {
             e.preventDefault();
             $(this).toggleClass('active');
             $('#enterprise-filter-panel').slideToggle(180);
         });

         // Enterprise Form Submit Loading Spinner
         $(document).on('submit', '#enterprise-form', function() {
             var $btn = $(this).find('#btn-submit-form');
             if ($btn.length && !$btn.prop('disabled')) {
                 setTimeout(function() {
                     $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> {{ __("global.saving") }}...');
                 }, 10);
             }
         });


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

         // Auto-format date input on type like beltei_ums (dd-mm-yyyy)
         $(document).on('input', 'input.pickadate, input.pick-adate, input.datepicker, input.date-picker', function() {
             let value = this.value.replace(/[^0-9]/g, '');
             if (value.length > 2) value = value.slice(0, 2) + '-' + value.slice(2);
             if (value.length > 5) value = value.slice(0, 5) + '-' + value.slice(5, 10);
             this.value = value;
         });

         // Initialize Pickadate globally for all date inputs (beltei_ums style)
         function initGlobalPickadate() {
             if (!$.fn.pickadate) return;

             const isKhmer = $('html').attr('lang') === 'km' || $('html').data('locale') === 'km';

             $('input.datepicker, input.date-picker, input.pickadate, input.pick-adate').each(function() {
                 const $input = $(this);
                 // Prevent double initialization or initializing POS panel inputs that have custom handlers
                 if ($input.data('picker') || $input.hasClass('picker__input')) return;

                 const pickadateOptions = {
                     format: 'dd-mm-yyyy',
                     selectMonths: true,
                     selectYears: 100,
                     editable: true,
                     today: isKhmer ? 'ថ្ងៃនេះ' : 'Today',
                     clear: isKhmer ? 'សម្អាត' : 'Clear',
                     close: isKhmer ? 'បិទ' : 'Close',
                     onOpen: function() {
                        const picker = this;
                        const $inp = picker.$node;
                        const $root = picker.$root;

                        function alignPicker() {
                            if (!picker.get('open') || !$inp.length || !$inp[0].getBoundingClientRect) return;
                            const rect = $inp[0].getBoundingClientRect();
                            const pickerHeight = $root.find('.picker__box').outerHeight() || 310;
                            
                            let top = rect.bottom + 2;
                            // If overflowing bottom of screen and room above, flip up
                            if (top + pickerHeight > window.innerHeight && rect.top > pickerHeight) {
                                top = rect.top - pickerHeight - 2;
                            }

                            $root.css({
                                position: 'fixed',
                                top: top + 'px',
                                left: Math.max(10, Math.min(rect.left, window.innerWidth - 320)) + 'px',
                                width: 'auto',
                                minWidth: '18rem',
                                maxWidth: '20rem',
                                zIndex: 10050
                            });
                        }

                        setTimeout(alignPicker, 0);
                        $(window).on('scroll.picker_' + picker.id + ' resize.picker_' + picker.id, alignPicker);
                    },
                    onClose: function() {
                        const picker = this;
                        $(window).off('scroll.picker_' + picker.id + ' resize.picker_' + picker.id);
                    },
                     onSet: function(context) {
                         if (context.select !== undefined || context.clear !== undefined) {
                             $input.trigger('change');
                             if ($input[0]) {
                                 $input[0].dispatchEvent(new Event('change', { bubbles: true }));
                             }
                         }
                     }
                 };

                 if (isKhmer) {
                     pickadateOptions.monthsFull = ['មករា', 'កុម្ភៈ', 'មីនា', 'មេសា', 'ឧសភា', 'មិថុនា', 'កក្កដា', 'សីហា', 'កញ្ញា', 'តុលា', 'វិច្ឆិកា', 'ធ្នូ'];
                     pickadateOptions.monthsShort = ['មក.', 'កុ.', 'មី.', 'មេ.', 'ឧស.', 'មិថុ.', 'កក្ក.', 'សី.', 'កញ.', 'តុ.', 'វិច្ឆ.', 'ធ.'];
                     pickadateOptions.weekdaysFull = ['អាទិត្យ', 'ចន្ទ', 'អង្គារ', 'ពុធ', 'ព្រហស្បតិ៍', 'សុក្រ', 'សៅរ៍'];
                     pickadateOptions.weekdaysShort = ['អា.', 'ច.', 'អ.', 'ព.', 'ព្រ.', 'សុ.', 'ស.'];
                 }

                 $input.pickadate(pickadateOptions);
             });
         }

         initGlobalPickadate();
         $(document).ajaxComplete(function() {
             initGlobalPickadate();
         });
         $(document).on('shown.bs.modal', function() {
             initGlobalPickadate();
         });

         // Initialize daterangepickers globally with DD-MM-YYYY format
         if ($.fn.daterangepicker) {
             $('.daterange, .date-range').each(function() {
                 const $el = $(this);
                 $el.daterangepicker({
                     autoUpdateInput: false,
                     locale: {
                         format: 'DD-MM-YYYY',
                         separator: ' - ',
                         applyLabel: '{{ __("global.apply") }}',
                         cancelLabel: '{{ __("global.clear") }}'
                     }
                 });
                 $el.on('apply.daterangepicker', function(ev, picker) {
                     $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY')).trigger('change');
                 });
                 $el.on('cancel.daterangepicker', function(ev, picker) {
                     $(this).val('').trigger('change');
                 });
             });
         }
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
         // Check if form has file inputs
         const hasFiles = form.find('input[type="file"]').length > 0;
         let ajaxData;

         if (hasFiles) {
             ajaxData = new FormData(form[0]);
         } else {
             ajaxData = form.serialize();
         }

         $.ajax({
             url: url,
             type: method,
             data: ajaxData,
             dataType: 'json',
             processData: hasFiles ? false : true,
             contentType: hasFiles ? false : 'application/x-www-form-urlencoded',
             success: function(res) {
                 if (res.status === 'success') {
                     success(res.message);
                     const delay = res.delay || 0;
                     if (redirect) {
                         setTimeout(() => {
                             window.location.href = redirect;
                         }, delay);
                     } else if (delay > 0) {
                         // No redirect specified, but delay exists - close modal and reload page
                         setTimeout(() => {
                             $('.modal').modal('hide');
                             window.location.reload();
                         }, delay);
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

     // Safety fallback: ensure page loading overlay is removed even if a script errors
     window.addEventListener('load', function() {
         setTimeout(function() {
             if ($('#page-loading-overlay').length) {
                 $('#page-loading-overlay').fadeOut(200, function() {
                     $(this).remove();
                 });
             }
         }, 300);
     });
     setTimeout(function() {
         if ($('#page-loading-overlay').length) {
             $('#page-loading-overlay').fadeOut(200, function() {
                 $(this).remove();
             });
         }
     }, 2500);
 </script>
