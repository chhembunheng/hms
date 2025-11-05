/* global fontAwesome */
// If you already define your giant `fontAwesome` object, this will use it.
// Otherwise, you can pass it via options.iconLibrary.

(function ($) {
	'use strict';

	const DEFAULT_LIBRARY = (typeof fontAwesome !== 'undefined') ? fontAwesome : {};

	const tplModal = (insertBtnText = 'Insert') => `
    <div class="aim-modal" id="aim-modal" aria-modal="true" role="dialog">
      <div class="aim-modal--content">
        <div class="aim-modal--header">
          <div class="aim-modal--header-logo-area">
            <span class="aim-modal--header-logo-title">Icon Picker</span>
          </div>
          <button type="button" class="aim-modal--header-close-btn" aria-label="Close">
            <i class="fas fa-times" title="Close"></i>
          </button>
        </div>
        <div class="aim-modal--body">
          <div id="aim-modal--sidebar" class="aim-modal--sidebar">
            <div class="aim-modal--sidebar-tabs"></div>
          </div>
          <div id="aim-modal--icon-preview-wrap" class="aim-modal--icon-preview-wrap">
            <div class="aim-modal--icon-search">
              <input type="search" class="form-control form-control-sm" placeholder="Filter by name..." aria-label="Filter icons"/>
              <i class="fas fa-search" aria-hidden="true"></i>
            </div>
            <div class="aim-modal--icon-preview-inner">
              <div id="aim-modal--icon-preview"></div>
            </div>
            <div class="aim-modal--pagination">
              <button type="button" class="aim-pagination-btn aim-pagination-prev" aria-label="Previous page">
                <i class="fas fa-chevron-left"></i> Previous
              </button>
              <div class="aim-pagination-info">
                <span class="aim-pagination-current">1</span> / <span class="aim-pagination-total">1</span>
              </div>
              <button type="button" class="aim-pagination-btn aim-pagination-next" aria-label="Next page">
                Next <i class="fas fa-chevron-right"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  `;

	function debounce(fn, wait) {
		let t;
		return function () {
			const ctx = this, args = arguments;
			clearTimeout(t);
			t = setTimeout(function () { fn.apply(ctx, args); }, wait);
		};
	}

	function iconsToMarkup(group) {
		const library = group['icon-style'];
		const prefix = group['prefix'];
		return group.icons.map(iconClass => {
			const name = iconClass.replace(prefix, '');
			const label = name.replace(/-/g, ' ');
			return `
        <div class="aim-icon-item" data-library-id="${library}" data-filter="${name}" data-icon-class="${iconClass}">
          <button type="button" class="aim-icon-item-inner" title="${iconClass}" aria-label="${label}">
            <i class="${prefix} ${iconClass}"></i>
            <div class="aim-icon-item-class">${iconClass}</div>
          </button>
        </div>
      `;
		}).join('');
	}

	function sidebarToMarkup(items) {
		return items.map(it => {
			const active = it['library-id'] === 'all' ? 'aesthetic-active' : '';
			return `
        <div class="aim-modal--sidebar-tab-item ${active}" data-library-id="${it['library-id']}">
          <i class="${it['list-icon']}"></i>${it.title}
        </div>
      `;
		}).join('');
	}

	$.fn.iconPicker = function (options) {
		const opts = $.extend(true, {
			onClick: '',             // selector for button that opens the modal
			iconLibrary: DEFAULT_LIBRARY,
			insertButtonText: 'Insert',
			debounceMs: 120,
			itemsPerPage: 20        // pagination: icons per page
		}, options || {});

		if (!opts.onClick) return this;

		return this.each(function () {
			const $root = $(this);
			const $trigger = $(opts.onClick).first();
			if (!$root.length || !$trigger.length) return;

			// state
			const sideBarList = [{ title: 'all icons', 'list-icon': 'fas fa-star-of-life', 'library-id': 'all' }];
			const $modal = $(tplModal(opts.insertButtonText));
			const $sidebarTabs = $modal.find('.aim-modal--sidebar-tabs');
			const $previewWrap = $modal.find('#aim-modal--icon-preview');
			const $searchInput = $modal.find('.aim-modal--icon-search input');
			const $paginationPrev = $modal.find('.aim-pagination-prev');
			const $paginationNext = $modal.find('.aim-pagination-next');
			const $paginationCurrent = $modal.find('.aim-pagination-current');
			const $paginationTotal = $modal.find('.aim-pagination-total');

			// pagination state
			let currentPage = 1;
			let totalPages = 1;

			// build sidebar groups + icons
			let iconMarkupParts = [];
			$.each(opts.iconLibrary, function (libKey, libVal) {
				const libName = (libKey || '').replace('-', ' ');
				$.each(libVal, function (groupKey, group) {
					sideBarList.push({
						title: `${libName} - ${groupKey}`,
						'list-icon': group['list-icon'] && group['list-icon'].length ? group['list-icon'] : 'far fa-dot-circle',
						'library-id': group['icon-style'] && group['icon-style'].length ? group['icon-style'] : 'all'
					});
					iconMarkupParts.push(iconsToMarkup(group));
				});
			});

			$sidebarTabs.html(sidebarToMarkup(sideBarList));
			$previewWrap.html(iconMarkupParts.join(''));

			// cache icon items and initialize visibility attributes
			let $iconItems = $previewWrap.find('.aim-icon-item');
			$iconItems.each(function () {
				$(this).attr('data-search-visible', 'true').attr('data-category-visible', 'true');
			});

			// wire: trigger open
			$trigger.on('click', function () {
				if (!$root.find('#aim-modal').length) {
					$root.append($modal);
				}
				openModal();
			});

			// wire: close buttons / esc / backdrop
			$modal.on('click', '.aim-modal--header-close-btn', closeModal);
			$modal.on('click', function (e) {
				if (e.target === $modal[0]) closeModal();
			});
			$modal.on('keydown', function (e) {
				if (e.key === 'Escape') {
					e.stopPropagation();
					closeModal();
				}
			});

			// wire: sidebar filter
			$sidebarTabs.on('click', '.aim-modal--sidebar-tab-item', function () {
				const $btn = $(this);
				if (!$btn.hasClass('aesthetic-active')) {
					$sidebarTabs.find('.aesthetic-active').removeClass('aesthetic-active');
					$btn.addClass('aesthetic-active');
				}
				const libId = $btn.data('libraryId') || 'all';
				filterBySidebar(libId);
			});

			// wire: search (debounced)
			$searchInput.on('keyup', debounce(function () {
				const text = ($searchInput.val() || '').toString().trim().toLowerCase();
				filterBySearch(text);
				currentPage = 1;
				updatePagination();
			}, opts.debounceMs));

			// wire: pagination buttons
			$paginationPrev.on('click', function () {
				if (currentPage > 1) {
					currentPage--;
					updatePagination();
				}
			});

			$paginationNext.on('click', function () {
				if (currentPage < totalPages) {
					currentPage++;
					updatePagination();
				}
			});

			// wire: icon click (select & insert immediately)
			$previewWrap.on('click', '.aim-icon-item', function () {
				$iconItems.removeClass('aesthetic-selected');
				const $item = $(this).addClass('aesthetic-selected');
				const selected = $item.find('i').attr('class') || '';
				const $input = $root.find('input').first();
				const $i = $input.closest('div').find('i').first();				
				if ($i.length) {
					$i.attr('class', selected);
				}
				if ($input.length) $input.val(selected);
				closeModal();
			});

			// helpers
			function openModal() {
				$modal.addClass('aim-open').removeClass('aim-close');
				requestAnimationFrame(() => $searchInput.trigger('focus'));
				// Initialize pagination when modal opens
				currentPage = 1;
				updatePagination();
			}

			function closeModal() {
				$modal.addClass('aim-close').removeClass('aim-open');
			}

			function filterBySearch(text) {
				if (!text) {
					// show everything currently allowed by sidebar filter
					$iconItems.each(function () { 
						$(this).attr('data-search-visible', 'true');
					});
				} else {
					$iconItems.each(function () {
						const hay = (this.dataset.filter || '').toLowerCase();
						const matches = hay.includes(text);
						$(this).attr('data-search-visible', matches ? 'true' : 'false');
					});
				}
			}

			function filterBySidebar(libraryId) {
				const showAll = libraryId === 'all';
				$iconItems.each(function () {
					const match = showAll || (this.dataset.libraryId === libraryId);
					$(this).attr('data-category-visible', match ? 'true' : 'false');
				});
				currentPage = 1;
				updatePagination();
			}

			function updatePagination() {
				// Get visible icons based on current filters
				const $visibleIcons = $iconItems.filter(function () {
					const searchVisible = $(this).attr('data-search-visible') !== 'false';
					const categoryVisible = $(this).attr('data-category-visible') !== 'false';
					return searchVisible && categoryVisible;
				});

				const totalVisible = $visibleIcons.length;
				totalPages = Math.ceil(totalVisible / opts.itemsPerPage) || 1;
				
				// Update pagination info
				$paginationCurrent.text(currentPage);
				$paginationTotal.text(totalPages);

				// Enable/disable buttons
				$paginationPrev.prop('disabled', currentPage === 1);
				$paginationNext.prop('disabled', currentPage === totalPages);

				// Hide all icons first
				$iconItems.hide();

				// Show/hide icons based on current page
				const startIndex = (currentPage - 1) * opts.itemsPerPage;
				const endIndex = startIndex + opts.itemsPerPage;

				$visibleIcons.slice(startIndex, endIndex).show();

				// Scroll to top of icon preview
				const $previewInner = $modal.find('.aim-modal--icon-preview-inner');
				$previewInner.scrollTop(0);
			}

			// optional: expose destroy
			$root.data('iconPickerDestroy', function () {
				$trigger.off('click');
				$modal.remove();
			});
		});
	};
})(jQuery);

/* ---------- Usage ----------
$('#icon-picker-container').iconPicker({
  onClick: '#open-icon-picker',  // button that opens the modal
  iconLibrary: fontAwesome       // your big object (regular/solid/brands)
});
*/
