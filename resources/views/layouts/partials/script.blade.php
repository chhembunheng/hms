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
    <script>
        // Backward-compatible Icon Initializer: converts any leftover [data-lucide] into native Font Awesome icons
        window.initLucideIcons = function(root) {
            const container = root || document;
            const lucideToFa = {
                'layout-dashboard': 'fa-solid fa-chart-simple',
                'trending-up': 'fa-solid fa-chart-line',
                'log-in': 'fa-solid fa-right-to-bracket',
                'log-out': 'fa-solid fa-right-from-bracket',
                'bed': 'fa-solid fa-bed',
                'bed-double': 'fa-solid fa-bed',
                'users': 'fa-solid fa-users',
                'users-round': 'fa-solid fa-users-line',
                'user': 'fa-solid fa-user',
                'user-round': 'fa-solid fa-circle-user',
                'receipt': 'fa-solid fa-receipt',
                'settings': 'fa-solid fa-gear',
                'sliders': 'fa-solid fa-sliders',
                'menu': 'fa-solid fa-bars',
                'search': 'fa-solid fa-magnifying-glass',
                'search-x': 'fa-solid fa-magnifying-glass-chart',
                'chevron-down': 'fa-solid fa-chevron-down',
                'chevron-right': 'fa-solid fa-chevron-right',
                'chevron-left': 'fa-solid fa-chevron-left',
                'chevron-up': 'fa-solid fa-chevron-up',
                'calendar': 'fa-solid fa-calendar',
                'calendar-check': 'fa-solid fa-calendar-check',
                'calendar-days': 'fa-solid fa-calendar-days',
                'calendar-plus': 'fa-solid fa-calendar-plus',
                'clock': 'fa-solid fa-clock',
                'history': 'fa-solid fa-clock-rotate-left',
                'bus': 'fa-solid fa-van-shuttle',
                'plane': 'fa-solid fa-plane',
                'plane-landing': 'fa-solid fa-plane-arrival',
                'plane-departure': 'fa-solid fa-plane-departure',
                'car': 'fa-solid fa-car',
                'truck': 'fa-solid fa-truck',
                'bike': 'fa-solid fa-motorcycle',
                'file-badge': 'fa-solid fa-passport',
                'id-card': 'fa-solid fa-id-card',
                'home': 'fa-solid fa-house',
                'building': 'fa-solid fa-hotel',
                'building-2': 'fa-solid fa-hotel',
                'circle-dollar-sign': 'fa-solid fa-circle-dollar-to-slot',
                'coins': 'fa-solid fa-coins',
                'globe': 'fa-solid fa-earth-americas',
                'compass': 'fa-solid fa-compass',
                'mountain': 'fa-solid fa-mountain-sun',
                'shirt': 'fa-solid fa-shirt',
                'flower-2': 'fa-solid fa-spa',
                'utensils': 'fa-solid fa-utensils',
                'wine': 'fa-solid fa-wine-glass',
                'layers': 'fa-solid fa-layer-group',
                'list': 'fa-solid fa-list',
                'list-checks': 'fa-solid fa-list-check',
                'pencil': 'fa-solid fa-pen-to-square',
                'trash-2': 'fa-solid fa-trash',
                'trash': 'fa-solid fa-trash',
                'plus': 'fa-solid fa-plus',
                'plus-circle': 'fa-solid fa-plus-circle',
                'check': 'fa-solid fa-check',
                'check-circle': 'fa-solid fa-circle-check',
                'check-check': 'fa-solid fa-check-double',
                'x': 'fa-solid fa-xmark',
                'x-circle': 'fa-solid fa-circle-xmark',
                'filter': 'fa-solid fa-filter',
                'rotate-cw': 'fa-solid fa-rotate-right',
                'arrow-down': 'fa-solid fa-arrow-down',
                'arrow-up': 'fa-solid fa-arrow-up',
                'arrow-left': 'fa-solid fa-arrow-left',
                'arrow-right': 'fa-solid fa-arrow-right',
                'file-spreadsheet': 'fa-solid fa-file-excel',
                'file-text': 'fa-solid fa-file-lines',
                'file-lines': 'fa-solid fa-file-lines',
                'printer': 'fa-solid fa-print',
                'sparkles': 'fa-solid fa-broom-wide',
                'map-pin': 'fa-solid fa-location-dot',
                'disc': 'fa-solid fa-circle-dot',
                'briefcase': 'fa-solid fa-suitcase',
                'door-open': 'fa-solid fa-door-open',
                'phone': 'fa-solid fa-phone',
                'mail': 'fa-solid fa-envelope',
                'eye': 'fa-solid fa-eye',
                'lock': 'fa-solid fa-lock',
                'unlock': 'fa-solid fa-unlock',
                'zap': 'fa-solid fa-bolt',
                'folder': 'fa-solid fa-folder',
                'folder-open': 'fa-solid fa-folder-open',
                'shopping-cart': 'fa-solid fa-cart-shopping',
                'sticky-note': 'fa-solid fa-note-sticky',
                'info': 'fa-solid fa-circle-info',
                'help-circle': 'fa-solid fa-circle-question',
                'alert-triangle': 'fa-solid fa-triangle-exclamation',
                'palette': 'fa-solid fa-palette',
                'app-window': 'fa-solid fa-window-maximize',
                'stamp': 'fa-solid fa-stamp',
                'image': 'fa-solid fa-image',
                'languages': 'fa-solid fa-language',
                'external-link': 'fa-solid fa-arrow-up-right-from-square',
                'save': 'fa-solid fa-floppy-disk',
                'bell': 'fa-solid fa-bell',
                'download': 'fa-solid fa-download',
                'circle': 'fa-solid fa-circle',
                'minus': 'fa-solid fa-minus'
            };

            try {
                container.querySelectorAll('[data-lucide]').forEach(el => {
                    const iconName = el.getAttribute('data-lucide');
                    el.removeAttribute('data-lucide');
                    const faClass = lucideToFa[iconName] || ('fa-solid fa-' + iconName);
                    faClass.split(' ').forEach(c => {
                        if (c) el.classList.add(c);
                    });
                });
            } catch(e) {
                console.warn('Icon init:', e);
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
               var $btn = $(this);
               var $panel = $('#enterprise-filter-panel');

               $btn.toggleClass('active');

               if ($panel.is(':visible')) {
                   $panel.removeClass('is-open');
                   $panel.slideUp(200, function() {
                       if (typeof $.fn.dataTable !== 'undefined') {
                           $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
                       }
                   });
               } else {
                   $panel.slideDown(200, function() {
                       $panel.addClass('is-open');
                       if (typeof $.fn.dataTable !== 'undefined') {
                           $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
                       }
                   });
               }
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


          // Global Bootstrap Multiselect for all multiple selects
          window.initBootstrapMultiselect = function(container) {
              var $ctx = container ? $(container) : $(document);
              $ctx.find('select.multiple-select, select[multiple]:not(.select2-multiple, .icon-select2)').each(function() {
                  var $select = $(this);
                  if ($select.data('ms-initialized') || $select.parent().hasClass('multiselect-native-select')) {
                      return;
                  }
                  $select.data('ms-initialized', true);
                  var isMulti = $select.prop('multiple');
                  var optCount = $select.find('option').length;
                  var server = $select.data('server');

                  $select.multiselect({
                      includeSelectAllOption: isMulti && (!!server || optCount > 2),
                      enableFiltering: !!server || optCount > 5,
                      enableCaseInsensitiveFiltering: true,
                      buttonWidth: '100%',
                      buttonClass: 'btn btn-light border form-select-sm text-start w-100',
                      maxHeight: 300,
                      numberDisplayed: isMulti ? 1 : 999,
                      nonSelectedText: '{{ __("global.all") }}',
                      selectAllText: '{{ __("global.select_all") }}',
                      allSelectedText: '{{ __("global.all_selected") }}',
                      nSelectedText: '{{ __("global.selected") }}',
                  });
              });
          };

          // Global Select2 for all other single selects
          window.initAppSelect2 = function(container) {
              var $ctx = container ? $(container) : $(document);

              // 1. Icon format selects
              $ctx.find('select.select-icons, select.icon-select2').each(function() {
                  var $el = $(this);
                  if (!$el.hasClass('select2-hidden-accessible')) {
                      var $modal = $el.closest('.modal');
                      var hasEmptyOpt = $el.find('option[value=""]').length > 0;
                      var placeholderText = $el.data('placeholder') || $el.attr('placeholder') || (hasEmptyOpt ? $el.find('option[value=""]').first().text() : '{{ __('form.select_option') }}');
                      if (!hasEmptyOpt) {
                          $el.prepend('<option value=""></option>');
                      }
                      $el.select2({
                          placeholder: placeholderText,
                          allowClear: true,
                          templateResult: typeof iconFormat === 'function' ? iconFormat : undefined,
                          templateSelection: typeof iconFormat === 'function' ? iconFormat : undefined,
                          dropdownParent: $modal.length ? $modal : undefined,
                          escapeMarkup: function(m) { return m; }
                      });
                  }
              });

              // 2. All standard single selects across the application
              $ctx.find('select.form-select, select.form-control, select.select2')
                  .not('[multiple]')
                  .not('.multiple-select')
                  .not('.select-icons')
                  .not('.icon-select2')
                  .not('.no-select2')
                  .not('.swal2-select')
                  .not('.multiselect-native-select')
                  .not('[name$="_length"]')
                  .not('.dt-input')
                  .each(function() {
                      var $el = $(this);
                      if (!$el.hasClass('select2-hidden-accessible')) {
                          var $modal = $el.closest('.modal');
                          var optCount = $el.find('option').length;
                          var hasEmptyOpt = $el.find('option[value=""]').length > 0;
                          var placeholderText = $el.data('placeholder') || $el.attr('placeholder') || (hasEmptyOpt ? $el.find('option[value=""]').first().text() : '{{ __('form.select_option') }}');

                          if (!hasEmptyOpt) {
                              $el.prepend('<option value=""></option>');
                          }

                          var allowClear = $el.data('allow-clear') !== false && $el.data('allow-clear') !== 'false';

                          $el.select2({
                              placeholder: placeholderText,
                              allowClear: allowClear,
                              minimumResultsForSearch: optCount > 8 ? 0 : Infinity,
                              dropdownParent: $modal.length ? $modal : undefined,
                              width: '100%',
                              escapeMarkup: function(m) { return m; }
                          }).on('select2:select select2:unselect select2:clear', function() {
                              if (typeof $(this).valid === 'function') {
                                  $(this).valid();
                              }
                          });
                      }
                  });
          };

          // Initialize on page ready
          window.initBootstrapMultiselect();
          window.initAppSelect2();

          // Auto-init inside modals and offcanvas drawers
          $(document).on('shown.bs.modal', function(e) {
              window.initBootstrapMultiselect(e.target);
              window.initAppSelect2(e.target);
          });
          $(document).on('shown.bs.offcanvas', function(e) {
              window.initBootstrapMultiselect(e.target);
              window.initAppSelect2(e.target);
          });

          // Auto-init on dynamic AJAX completion
          $(document).ajaxComplete(function(event, xhr, settings) {
              if (!settings.url || (!settings.url.includes('select2') && !settings.url.includes('multiselect'))) {
                  window.initBootstrapMultiselect();
                  window.initAppSelect2();
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
                 modal.find('form').validate(typeof getFormValidateConfig === 'function' ? getFormValidateConfig() : {
                     errorPlacement: function(error, element) {
                         var elem = $(element);
                         var s2 = elem.next('.select2-container');
                         if (s2.length) {
                             error.insertAfter(s2);
                         } else {
                             error.insertAfter(element);
                         }
                     }
                 });
                 if (typeof window.initAppSelect2 === 'function') {
                     window.initAppSelect2(modal);
                 }
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
      function getFormValidateConfig() {
          return {
              ignore: ':hidden:not(.select2-hidden-accessible)',
              errorPlacement: function(error, element) {
                  var elem = $(element);
                  var s2 = elem.next('.select2-container');
                  if (s2.length) {
                      error.insertAfter(s2);
                  } else if (elem.parent().hasClass('input-group')) {
                      error.insertAfter(elem.parent());
                  } else {
                      error.insertAfter(element);
                  }
              }
          };
      }
     const buildSelect2 = (context) => {
         if (typeof window.initAppSelect2 === 'function') {
             window.initAppSelect2(context);
         }
     };
     const formValidation = document.querySelectorAll('form[validate]');
     formValidation.forEach((form) => {
         $(form).validate(getFormValidateConfig());
         $(form).on('submit', function(e) {
             if ($(form).hasClass('ajax-form-modal')) {
                 return; // Avoid duplicate submission handled by .ajax-form-modal
             }
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
