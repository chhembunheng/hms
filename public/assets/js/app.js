/* ------------------------------------------------------------------------------
 *
 *  # Template JS core
 *
 *  Includes minimum required JS code for proper template functioning
 *
 * ---------------------------------------------------------------------------- */


// Setup module
// ------------------------------

const App = function () {


    // Utils
    // -------------------------

    //
    // Transitions
    //

    // Disable all transitions
    const transitionsDisabled = function () {
        document.body.classList.add('no-transitions');
    };

    // Enable all transitions
    const transitionsEnabled = function () {
        document.body.classList.remove('no-transitions');
    };


    //
    // Detect OS to apply custom scrollbars
    //

    // Custom scrollbar style is controlled by CSS. This function is needed to keep default
    // scrollbars on MacOS and avoid usage of extra JS libraries
    const detectOS = function () {
        const platform = window.navigator.platform,
            windowsPlatforms = ['Win32', 'Win64', 'Windows', 'WinCE'],
            customScrollbarsClass = 'custom-scrollbars';

        // Add class if OS is windows
        windowsPlatforms.indexOf(platform) != -1 && document.documentElement.classList.add(customScrollbarsClass);
    };



    // Sidebars
    // -------------------------
    //
    // On desktop
    //

    // Resize main sidebar
    const sidebarMainResize = function () {

        // Elements
        const sidebarMainElement = document.querySelector('.sidebar-main'),
            sidebarMainToggler = document.querySelectorAll('.sidebar-main-resize'),
            resizeClass = 'sidebar-main-resized',
            unfoldClass = 'sidebar-main-unfold';


        // Config
        if (sidebarMainElement) {

            // Define variables
            const unfoldDelay = 150;
            let timerStart,
                timerFinish;

            // Toggle classes on click
            sidebarMainToggler.forEach(function (toggler) {
                toggler.addEventListener('click', function (e) {
                    e.preventDefault();
                    sidebarMainElement.classList.toggle(resizeClass);
                    !sidebarMainElement.classList.contains(resizeClass) && sidebarMainElement.classList.remove(unfoldClass);
                });
            });

            // Add class on mouse enter
            sidebarMainElement.addEventListener('mouseenter', function () {
                clearTimeout(timerFinish);
                timerStart = setTimeout(function () {
                    sidebarMainElement.classList.contains(resizeClass) && sidebarMainElement.classList.add(unfoldClass);
                }, unfoldDelay);
            });

            // Remove class on mouse leave
            sidebarMainElement.addEventListener('mouseleave', function () {
                clearTimeout(timerStart);
                timerFinish = setTimeout(function () {
                    sidebarMainElement.classList.remove(unfoldClass);
                }, unfoldDelay);
            });
        }
    };

    // Toggle main sidebar
    const sidebarMainToggle = function () {

        // Elements
        const sidebarMainElement = document.querySelector('.sidebar-main'),
            sidebarMainRestElements = document.querySelectorAll('.sidebar:not(.sidebar-main):not(.sidebar-component)'),
            sidebarMainDesktopToggler = document.querySelectorAll('.sidebar-main-toggle'),
            sidebarMainMobileToggler = document.querySelectorAll('.sidebar-mobile-main-toggle'),
            sidebarCollapsedClass = 'sidebar-collapsed',
            sidebarMobileExpandedClass = 'sidebar-mobile-expanded';

        // On desktop
        sidebarMainDesktopToggler.forEach(function (toggler) {
            toggler.addEventListener('click', function (e) {
                e.preventDefault();
                sidebarMainElement.classList.toggle(sidebarCollapsedClass);
            });
        });

        // On mobile
        sidebarMainMobileToggler.forEach(function (toggler) {
            toggler.addEventListener('click', function (e) {
                e.preventDefault();
                sidebarMainElement.classList.toggle(sidebarMobileExpandedClass);

                sidebarMainRestElements.forEach(function (sidebars) {
                    sidebars.classList.remove(sidebarMobileExpandedClass);
                });
            });
        });
    };

    // Toggle secondary sidebar
    const sidebarSecondaryToggle = function () {

        // Elements
        const sidebarSecondaryElement = document.querySelector('.sidebar-secondary'),
            sidebarSecondaryRestElements = document.querySelectorAll('.sidebar:not(.sidebar-secondary):not(.sidebar-component)'),
            sidebarSecondaryDesktopToggler = document.querySelectorAll('.sidebar-secondary-toggle'),
            sidebarSecondaryMobileToggler = document.querySelectorAll('.sidebar-mobile-secondary-toggle'),
            sidebarCollapsedClass = 'sidebar-collapsed',
            sidebarMobileExpandedClass = 'sidebar-mobile-expanded';

        // On desktop
        sidebarSecondaryDesktopToggler.forEach(function (toggler) {
            toggler.addEventListener('click', function (e) {
                e.preventDefault();
                sidebarSecondaryElement.classList.toggle(sidebarCollapsedClass);
            });
        });

        // On mobile
        sidebarSecondaryMobileToggler.forEach(function (toggler) {
            toggler.addEventListener('click', function (e) {
                e.preventDefault();
                sidebarSecondaryElement.classList.toggle(sidebarMobileExpandedClass);

                sidebarSecondaryRestElements.forEach(function (sidebars) {
                    sidebars.classList.remove(sidebarMobileExpandedClass);
                });
            });
        });
    };

    // Toggle right sidebar
    const sidebarRightToggle = function () {

        // Elements
        const sidebarRightElement = document.querySelector('.sidebar-end'),
            sidebarRightRestElements = document.querySelectorAll('.sidebar:not(.sidebar-end):not(.sidebar-component)'),
            sidebarRightDesktopToggler = document.querySelectorAll('.sidebar-end-toggle'),
            sidebarRightMobileToggler = document.querySelectorAll('.sidebar-mobile-end-toggle'),
            sidebarCollapsedClass = 'sidebar-collapsed',
            sidebarMobileExpandedClass = 'sidebar-mobile-expanded';

        // On desktop
        sidebarRightDesktopToggler.forEach(function (toggler) {
            toggler.addEventListener('click', function (e) {
                e.preventDefault();
                sidebarRightElement.classList.toggle(sidebarCollapsedClass);
            });
        });

        // On mobile
        sidebarRightMobileToggler.forEach(function (toggler) {
            toggler.addEventListener('click', function (e) {
                e.preventDefault();
                sidebarRightElement.classList.toggle(sidebarMobileExpandedClass);

                sidebarRightRestElements.forEach(function (sidebars) {
                    sidebars.classList.remove(sidebarMobileExpandedClass);
                });
            });
        });
    };

    // Toggle component sidebar
    const sidebarComponentToggle = function () {

        // Elements
        const sidebarComponentElement = document.querySelector('.sidebar-component'),
            sidebarComponentMobileToggler = document.querySelectorAll('.sidebar-mobile-component-toggle'),
            sidebarMobileExpandedClass = 'sidebar-mobile-expanded';

        // Toggle classes
        sidebarComponentMobileToggler.forEach(function (toggler) {
            toggler.addEventListener('click', function (e) {
                e.preventDefault();
                sidebarComponentElement.classList.toggle(sidebarMobileExpandedClass);
            });
        });
    };


    // Navigations
    // -------------------------

    // Sidebar navigation
    const navigationSidebar = function () {

        // Elements
        const navContainerClass = 'nav-sidebar',
            navItemOpenClass = 'nav-item-open',
            navLinkClass = 'nav-link',
            navLinkDisabledClass = 'disabled',
            navSubmenuContainerClass = 'nav-item-submenu',
            navSubmenuClass = 'nav-group-sub',
            navScrollSpyClass = 'nav-scrollspy',
            sidebarNavElement = document.querySelectorAll(`.${navContainerClass}:not(.${navScrollSpyClass})`);

        // Setup
        sidebarNavElement.forEach(function (nav) {
            nav.querySelectorAll(`.${navSubmenuContainerClass} > .${navLinkClass}:not(.${navLinkDisabledClass})`).forEach(function (link) {
                link.addEventListener('click', function (e) {
                    e.preventDefault();
                    const submenuContainer = link.closest(`.${navSubmenuContainerClass}`);
                    const submenu = link.closest(`.${navSubmenuContainerClass}`).querySelector(`:scope > .${navSubmenuClass}`);

                    // Collapsible
                    if (submenuContainer.classList.contains(navItemOpenClass)) {
                        new bootstrap.Collapse(submenu).hide();
                        submenuContainer.classList.remove(navItemOpenClass);
                    }
                    else {
                        new bootstrap.Collapse(submenu).show();
                        submenuContainer.classList.add(navItemOpenClass);
                    }

                    // Accordion
                    if (link.closest(`.${navContainerClass}`).getAttribute('data-nav-type') == 'accordion') {
                        for (let sibling of link.parentNode.parentNode.children) {
                            if (sibling != link.parentNode && sibling.classList.contains(navItemOpenClass)) {
                                sibling.querySelectorAll(`:scope > .${navSubmenuClass}`).forEach(function (submenu) {
                                    new bootstrap.Collapse(submenu).hide();
                                    sibling.classList.remove(navItemOpenClass);
                                });
                            }
                        }
                    }
                });
            });
        });
    };


    // Components
    // -------------------------

    // Tooltip
    const componentTooltip = function () {
        const tooltipSelector = document.querySelectorAll('[data-bs-popup="tooltip"]');

        tooltipSelector.forEach(function (popup) {
            new bootstrap.Tooltip(popup, {
                boundary: '.page-content'
            });
        });
    };

    // Popover
    const componentPopover = function () {
        const popoverSelector = document.querySelectorAll('[data-bs-popup="popover"]');

        popoverSelector.forEach(function (popup) {
            new bootstrap.Popover(popup, {
                boundary: '.page-content'
            });
        });
    };

    // "Go to top" button
    const componentToTopButton = function () {

        // Elements
        const toTopContainer = document.querySelector('.content-wrapper'),
            toTopElement = document.createElement('button'),
            toTopElementIcon = document.createElement('i'),
            toTopButtonContainer = document.createElement('div'),
            toTopButtonColorClass = 'btn-secondary',
            toTopButtonIconClass = 'fa-arrow-up-long',
            scrollableContainer = document.querySelector('.content-inner'),
            scrollableDistance = 250,
            footerContainer = document.querySelector('.navbar-footer');


        // Append only if container exists
        if (scrollableContainer) {

            // Create button container
            toTopContainer.appendChild(toTopButtonContainer);
            toTopButtonContainer.classList.add('btn-to-top');

            // Create button
            toTopElement.classList.add('btn', toTopButtonColorClass, 'btn-icon', 'rounded-pill');
            toTopElement.setAttribute('type', 'button');
            toTopButtonContainer.appendChild(toTopElement);
            toTopElementIcon.classList.add('fa-solid', toTopButtonIconClass);
            toTopElementIcon.classList.add(toTopButtonIconClass);
            toTopElement.appendChild(toTopElementIcon);

            // Show and hide on scroll
            const to_top_button = document.querySelector('.btn-to-top'),
                add_class_on_scroll = () => to_top_button.classList.add('btn-to-top-visible'),
                remove_class_on_scroll = () => to_top_button.classList.remove('btn-to-top-visible');

            scrollableContainer.addEventListener('scroll', function () {
                const scrollpos = scrollableContainer.scrollTop;
                scrollpos >= scrollableDistance ? add_class_on_scroll() : remove_class_on_scroll();
                if (footerContainer) {
                    if (this.scrollHeight - this.scrollTop - this.clientHeight <= footerContainer.clientHeight) {
                        to_top_button.style.bottom = footerContainer.clientHeight + 20 + 'px';
                    }
                    else {
                        to_top_button.removeAttribute('style');
                    }
                }
            });

            // Scroll to top on click
            document.querySelector('.btn-to-top .btn').addEventListener('click', function () {
                scrollableContainer.scrollTo(0, 0);
            });
        }
    };


    // Card actions
    // -------------------------

    // Reload card (uses BlockUI extension)
    const cardActionReload = function () {

        // Elements
        const buttonClass = '[data-card-action=reload]',
            containerClass = 'card',
            overlayClass = 'card-overlay',
            spinnerClass = 'ph-circle-notch',
            overlayAnimationClass = 'card-overlay-fadeout';

        // Configure
        document.querySelectorAll(buttonClass).forEach(function (button) {
            button.addEventListener('click', function (e) {
                e.preventDefault();

                // Elements
                const parentContainer = button.closest(`.${containerClass}`),
                    overlayElement = document.createElement('div'),
                    overlayElementIcon = document.createElement('i');

                // Append overlay with icon
                overlayElement.classList.add(overlayClass);
                parentContainer.appendChild(overlayElement);
                overlayElementIcon.classList.add(spinnerClass, 'spinner', 'text-body');
                overlayElement.appendChild(overlayElementIcon);

                // Remove overlay after 2.5s, for demo only
                setTimeout(function () {
                    overlayElement.classList.add(overlayAnimationClass);
                    ['animationend', 'animationcancel'].forEach(function (e) {
                        overlayElement.addEventListener(e, function () {
                            overlayElement.remove();
                        });
                    });
                }, 2500);
            });
        });
    };

    // Collapse card
    const cardActionCollapse = function () {

        // Elements
        const buttonClass = '[data-card-action=collapse]',
            cardCollapsedClass = 'card-collapsed';

        // Setup
        document.querySelectorAll(buttonClass).forEach(function (button) {
            button.addEventListener('click', function (e) {
                e.preventDefault();

                const parentContainer = button.closest('.card'),
                    collapsibleContainer = parentContainer.querySelectorAll(':scope > .collapse');

                if (parentContainer.classList.contains(cardCollapsedClass)) {
                    parentContainer.classList.remove(cardCollapsedClass);
                    collapsibleContainer.forEach(function (toggle) {
                        new bootstrap.Collapse(toggle, {
                            show: true
                        });
                    });
                }
                else {
                    parentContainer.classList.add(cardCollapsedClass);
                    collapsibleContainer.forEach(function (toggle) {
                        new bootstrap.Collapse(toggle, {
                            hide: true
                        });
                    });
                }
            });
        });
    };

    // Remove card
    const cardActionRemove = function () {

        // Elements
        const buttonClass = '[data-card-action=remove]',
            containerClass = 'card'

        // Config
        document.querySelectorAll(buttonClass).forEach(function (button) {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                button.closest(`.${containerClass}`).remove();
            });
        });
    };

    // Card fullscreen mode
    const cardActionFullscreen = function () {

        // Elements
        const buttonAttribute = '[data-card-action=fullscreen]',
            buttonClass = 'text-body',
            buttonContainerClass = 'd-inline-flex',
            cardFullscreenClass = 'card-fullscreen',
            collapsedClass = 'collapsed-in-fullscreen',
            scrollableContainerClass = 'content-inner',
            fullscreenAttr = 'data-fullscreen';

        // Configure
        document.querySelectorAll(buttonAttribute).forEach(function (button) {
            button.addEventListener('click', function (e) {
                e.preventDefault();

                // Get closest card container
                const cardFullscreen = button.closest('.card');

                // Toggle required classes
                cardFullscreen.classList.toggle(cardFullscreenClass);

                // Toggle classes depending on state
                if (!cardFullscreen.classList.contains(cardFullscreenClass)) {
                    button.removeAttribute(fullscreenAttr);
                    cardFullscreen.querySelectorAll(`:scope > .${collapsedClass}`).forEach(function (collapsedElement) {
                        collapsedElement.classList.remove('show');
                    });
                    document.querySelector(`.${scrollableContainerClass}`).classList.remove('overflow-hidden');
                    button.closest(`.${buttonContainerClass}`).querySelectorAll(`:scope > .${buttonClass}:not(${buttonAttribute})`).forEach(function (actions) {
                        actions.classList.remove('d-none');
                    });
                }
                else {
                    button.setAttribute(fullscreenAttr, 'active');
                    cardFullscreen.removeAttribute('style');
                    cardFullscreen.querySelectorAll(`:scope > .collapse:not(.show)`).forEach(function (collapsedElement) {
                        collapsedElement.classList.add('show', `.${collapsedClass}`);
                    });
                    document.querySelector(`.${scrollableContainerClass}`).classList.add('overflow-hidden');
                    button.closest(`.${buttonContainerClass}`).querySelectorAll(`:scope > .${buttonClass}:not(${buttonAttribute})`).forEach(function (actions) {
                        actions.classList.add('d-none');
                    });
                }
            });
        });
    };


    // Misc
    // -------------------------

    // Dropdown submenus. Trigger on click
    const dropdownSubmenu = function () {

        // Classes
        const menuClass = 'dropdown-menu',
            submenuClass = 'dropdown-submenu',
            menuToggleClass = 'dropdown-toggle',
            disabledClass = 'disabled',
            showClass = 'show';

        if (submenuClass) {

            // Toggle submenus on all levels
            document.querySelectorAll(`.${menuClass} .${submenuClass}:not(.${disabledClass}) .${menuToggleClass}`).forEach(function (link) {
                link.addEventListener('click', function (e) {
                    e.stopPropagation();
                    e.preventDefault();

                    // Toggle classes
                    link.closest(`.${submenuClass}`).classList.toggle(showClass);
                    link.closest(`.${submenuClass}`).querySelectorAll(`:scope > .${menuClass}`).forEach(function (children) {
                        children.classList.toggle(showClass);
                    });

                    // When submenu is shown, hide others in all siblings
                    for (let sibling of link.parentNode.parentNode.children) {
                        if (sibling != link.parentNode) {
                            sibling.classList.remove(showClass);
                            sibling.querySelectorAll(`.${showClass}`).forEach(function (submenu) {
                                submenu.classList.remove(showClass);
                            });
                        }
                    }
                });
            });

            // Hide all levels when parent dropdown is closed
            document.querySelectorAll(`.${menuClass}`).forEach(function (link) {
                if (!link.parentElement.classList.contains(submenuClass)) {
                    link.parentElement.addEventListener('hidden.bs.dropdown', function (e) {
                        link.querySelectorAll(`.${menuClass}.${showClass}`).forEach(function (children) {
                            children.classList.remove(showClass);
                        });
                    });
                }
            });
        }
    };
    const componentDatepicker = function () {
        if (typeof Datepicker == 'undefined') {
            console.warn('Warning - datepicker.min.js is not loaded.');
            return;
        }
        document.querySelectorAll('.date-picker').forEach(function (element) {
            const inModal = element.closest('.modal');
            const containerSelector = inModal ? '.modal-body' : '.content-inner';
            new Datepicker(element, {
                container: containerSelector,
                buttonClass: 'btn',
                prevArrow: document.dir === 'rtl' ? '&rarr;' : '&larr;',
                nextArrow: document.dir === 'rtl' ? '&larr;' : '&rarr;'
            });
        });

    }
    const componentDaterange = function () {
        if (!$().daterangepicker) {
            console.warn('Warning - daterangepicker.js is not loaded.');
            return;
        }
        $('.date-range').daterangepicker({
            parentEl: '.content-inner',
            showDropdowns: true
        });
    }
    const componentSelect2 = function () {
        if (!$().select2) {
            console.warn('Warning - select2.min.js is not loaded.');
            return;
        }
        $('.select').select2({
            minimumResultsForSearch: Infinity,
            width: '100%'
        });
    };
    const componentModal = function () {
        const modalElements = document.querySelectorAll('.modal');
        modalElements.forEach(function (modal) {
            modal.addEventListener('show.bs.modal', function () {
                if (modal.getAttribute('server') && modal.getAttribute('server') !== '') {
                    let server = modal.getAttribute('server');
                    let filters = (modal.getAttribute('filters') || '').split(',');
                    let data = {};
                    filters.forEach(function (filter) {
                        data[filter] = $('[name="' + filter + '[]"]').val() || $('[name="' + filter + '"]').val();
                    });
                    $.ajax({
                        url: server,
                        type: 'POST',
                        data: data,
                        success: function (res) {
                            if (res.data) {
                                modal.querySelector('.modal-body').innerHTML = res.data;
                                reinitializeDynamicComponents();
                            }
                        }
                    });
                }
            });
        });
    }
    const componentMultiselect = function () {
        if (!$().multiselect) {
            console.warn('Warning - multiselect.min.js is not loaded.');
            return;
        }
        $('.multiple-select').multiselect({
            includeSelectAllOption: true,
            enableFiltering: true,
            enableCaseInsensitiveFiltering: true,
            buttonWidth: '100%',
            onChange: function (option, checked) {
                updateSelectAllIndeterminate($(this.$select));
            },
            onSelectAll: function () {
                updateSelectAllIndeterminate($(this.$select));
            },
            onDeselectAll: function () {
                updateSelectAllIndeterminate($(this.$select));
            },
            onDropdownShow: function (event) {
                let select = $(event.target).parent().find('select');
                let exist = select.find('option').map(function () {
                    return $(this).val();
                }).get();
                let server = select.data('server');
                let filters = (select.data('filters') || '').split(',');
                let data = {};
                filters.forEach(function (filter) {
                    data[filter] = $('[name="' + filter + '[]"]').val() || $('[name="' + filter + '"]').val();
                });
                if (server) {
                    $.ajax({
                        url: server,
                        type: 'POST',
                        data: data,
                        async: false,
                        success: function (res) {
                            let options = res.data.map(e => e.id.toString());
                            let isDiff = exist.length !== options.length || exist.some(opt => !options.includes(opt));
                            if (isDiff) {
                                select.empty();
                                $.each(res.data, function (i, e) {
                                    select.append('<option value="' + e.id + '">' + e.text + '</option>');
                                });
                                select.multiselect('rebuild');
                            }
                        }
                    });
                }
            }
        });
    };
    function updateSelectAllIndeterminate($select) {
        var total = $select.find('option').length;
        var selected = $select.find('option:selected').length;
        var selectAllCheckbox = $select.parent('.multiselect-native-select').find('.multiselect-container .multiselect-all input[type="checkbox"]');
        if (selected === 0) {
            selectAllCheckbox.prop('indeterminate', false).prop('checked', false);
        } else if (selected === total) {
            selectAllCheckbox.prop('indeterminate', false).prop('checked', true);
        } else {
            selectAllCheckbox.prop('indeterminate', true).prop('checked', false);
        }
    }
    function reinitializeDynamicComponents() {
        componentMultiselect();
        componentDaterange();
        componentSelect2();
        componentDatepicker();
    }


    return {
        // Disable transitions before page is fully loaded
        initBeforeLoad: function () {
            detectOS();
            transitionsDisabled();
        },

        // Enable transitions when page is fully loaded
        initAfterLoad: function () {
            transitionsEnabled();
        },
        // Initialize all components
        initComponents: function () {
            componentTooltip();
            componentPopover();
            componentToTopButton();
            componentDatepicker();
            componentDaterange();
            componentSelect2();
            componentMultiselect();
            componentModal();
        },

        // Initialize all sidebars
        initSidebars: function () {
            sidebarMainResize();
            sidebarMainToggle();
            sidebarSecondaryToggle();
            sidebarRightToggle();
            sidebarComponentToggle();
        },

        // Initialize all navigations
        initNavigations: function () {
            navigationSidebar();
        },

        // Initialize all card actions
        initCardActions: function () {
            cardActionReload();
            cardActionCollapse();
            cardActionRemove();
            cardActionFullscreen();
        },

        // Dropdown submenu
        initDropdowns: function () {
            dropdownSubmenu();
        },

        // Initialize core
        initCore: function () {
            App.initBeforeLoad();
            App.initSidebars();
            App.initNavigations();
            App.initComponents();
            App.initCardActions();
            App.initDropdowns();
        }
    };
}();


// Initialize module
// ------------------------------

// When content is loaded
document.addEventListener('DOMContentLoaded', function () {
    App.initCore();
});

// When page is fully loaded
window.addEventListener('load', function () {
    App.initAfterLoad();
});
