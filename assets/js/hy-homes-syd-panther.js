(function () {
	'use strict';

	var externalWhatsAppPanel = null;
	var externalWhatsAppTrigger = null;

	function closeAll(exceptWrap) {
		document.querySelectorAll('.hy-homes-search__select-wrap.is-open').forEach(function (wrap) {
			if (wrap !== exceptWrap) {
				wrap.classList.remove('is-open');
				var button = wrap.querySelector('.hy-homes-search__custom-button');
				if (button) {
					button.setAttribute('aria-expanded', 'false');
				}
			}
		});
	}

	function setWhatsAppOpen(picker, isOpen) {
		var toggle = picker.querySelector('[data-hy-homes-whatsapp-toggle]');
		var panel = picker.querySelector('[data-hy-homes-whatsapp-panel]');

		picker.classList.toggle('is-open', isOpen);

		if (toggle) {
			toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
		}

		if (panel) {
			panel.hidden = !isOpen;
		}
	}

	function closeWhatsAppPickers(exceptPicker) {
		document.querySelectorAll('[data-hy-homes-whatsapp].is-open').forEach(function (picker) {
			if (picker !== exceptPicker) {
				setWhatsAppOpen(picker, false);
			}
		});
	}

	function closeExternalWhatsAppPanel() {
		if (externalWhatsAppPanel) {
			externalWhatsAppPanel.remove();
		}

		externalWhatsAppPanel = null;
		externalWhatsAppTrigger = null;
	}

	function getWhatsAppPanelTemplate() {
		return document.querySelector('.hy-homes-whatsapp--floating [data-hy-homes-whatsapp-panel]') || document.querySelector('[data-hy-homes-whatsapp-panel]');
	}

	function positionExternalWhatsAppPanel(panel, trigger) {
		var rect = trigger.getBoundingClientRect();
		var spacing = 14;
		var panelWidth = panel.offsetWidth;
		var panelHeight = panel.offsetHeight;
		var left = rect.left + (rect.width / 2) - (panelWidth / 2);
		var top = rect.bottom + spacing;

		left = Math.max(16, Math.min(left, window.innerWidth - panelWidth - 16));

		if (top + panelHeight > window.innerHeight - 16) {
			top = rect.top - panelHeight - spacing;
		}

		top = Math.max(16, top);

		panel.style.left = left + 'px';
		panel.style.top = top + 'px';
	}

	function openLocalWhatsAppPanel(trigger) {
		var template = getWhatsAppPanelTemplate();
		var panel;

		if (!template) {
			return false;
		}

		closeWhatsAppPickers();
		closeExternalWhatsAppPanel();

		panel = template.cloneNode(true);
		panel.removeAttribute('id');
		panel.hidden = false;
		panel.classList.add('hy-homes-whatsapp-panel--external');
		panel.setAttribute('data-hy-homes-whatsapp-external-panel', 'true');
		document.body.appendChild(panel);

		positionExternalWhatsAppPanel(panel, trigger);
		externalWhatsAppPanel = panel;
		externalWhatsAppTrigger = trigger;

		return true;
	}

	function openFloatingWhatsAppPicker() {
		var picker = document.querySelector('.hy-homes-whatsapp--floating[data-hy-homes-whatsapp]');

		if (!picker) {
			return false;
		}

		closeWhatsAppPickers(picker);
		setWhatsAppOpen(picker, true);

		return true;
	}

	function getFieldValue(field) {
		if (!field) {
			return '';
		}

		return String(field.value || '').trim();
	}

	function updateValueState(field) {
		var control = field.closest('.hy-homes-search__control');

		if (!control) {
			return;
		}

		control.classList.toggle('has-value', getFieldValue(field) !== '');
	}

	function updateCustomSelect(select) {
		var wrap = select.closest('.hy-homes-search__select-wrap');
		var button = wrap ? wrap.querySelector('.hy-homes-search__custom-button') : null;
		var list = wrap ? wrap.querySelector('.hy-homes-search__custom-list') : null;
		var option = select.options[select.selectedIndex];

		if (button && option) {
			button.textContent = option.textContent;
		}

		if (list) {
			list.querySelectorAll('.hy-homes-search__custom-option').forEach(function (item) {
				var isCurrent = item.dataset.value === select.value;
				item.classList.toggle('is-active', isCurrent);
				item.setAttribute('aria-selected', isCurrent ? 'true' : 'false');
			});
		}

		updateValueState(select);
	}

	function findNamedField(form, fieldName) {
		var fields = form.querySelectorAll('select, input');

		for (var index = 0; index < fields.length; index += 1) {
			if (fields[index].name === fieldName) {
				return fields[index];
			}
		}

		return null;
	}

	function buildOption(select, option, list, button) {
		var item = document.createElement('li');
		item.className = 'hy-homes-search__custom-option';
		item.setAttribute('role', 'option');
		item.setAttribute('tabindex', '-1');
		item.dataset.value = option.value;
		item.textContent = option.textContent;

		if (option.selected) {
			item.setAttribute('aria-selected', 'true');
			item.classList.add('is-active');
			button.textContent = option.textContent;
		} else {
			item.setAttribute('aria-selected', 'false');
		}

		item.addEventListener('click', function () {
			select.value = option.value;
			select.dispatchEvent(new Event('change', { bubbles: true }));
			updateCustomSelect(select);

			closeAll();
			button.focus();
		});

		return item;
	}

	function enhanceSelect(select) {
		if (select.dataset.hyHomesEnhanced === 'true') {
			return;
		}

		var wrap = select.closest('.hy-homes-search__select-wrap');

		if (!wrap) {
			return;
		}

		var button = document.createElement('button');
		var list = document.createElement('ul');
		var placeholder = select.dataset.placeholder || '';
		var selectedOption = select.options[select.selectedIndex];

		button.type = 'button';
		button.className = 'hy-homes-search__custom-button';
		button.setAttribute('aria-haspopup', 'listbox');
		button.setAttribute('aria-expanded', 'false');
		button.textContent = selectedOption ? selectedOption.textContent : placeholder;

		list.className = 'hy-homes-search__custom-list';
		list.setAttribute('role', 'listbox');

		Array.prototype.forEach.call(select.options, function (option) {
			list.appendChild(buildOption(select, option, list, button));
		});

		select.addEventListener('change', function () {
			updateCustomSelect(select);
		});

		button.addEventListener('click', function () {
			var shouldOpen = !wrap.classList.contains('is-open');
			closeAll(wrap);
			wrap.classList.toggle('is-open', shouldOpen);
			button.setAttribute('aria-expanded', shouldOpen ? 'true' : 'false');
		});

		button.addEventListener('keydown', function (event) {
			var activeItem = list.querySelector('.is-active') || list.querySelector('.hy-homes-search__custom-option');

			if (event.key === 'Escape') {
				wrap.classList.remove('is-open');
				button.setAttribute('aria-expanded', 'false');
				return;
			}

			if (event.key === 'ArrowDown' || event.key === 'Enter' || event.key === ' ') {
				event.preventDefault();
				closeAll(wrap);
				wrap.classList.add('is-open');
				button.setAttribute('aria-expanded', 'true');

				if (activeItem) {
					activeItem.focus();
				}
			}
		});

		list.addEventListener('keydown', function (event) {
			var items = Array.prototype.slice.call(list.querySelectorAll('.hy-homes-search__custom-option'));
			var index = items.indexOf(document.activeElement);

			if (event.key === 'Escape') {
				wrap.classList.remove('is-open');
				button.setAttribute('aria-expanded', 'false');
				button.focus();
			}

			if (event.key === 'ArrowDown') {
				event.preventDefault();
				(items[index + 1] || items[0]).focus();
			}

			if (event.key === 'ArrowUp') {
				event.preventDefault();
				(items[index - 1] || items[items.length - 1]).focus();
			}

			if (event.key === 'Enter' || event.key === ' ') {
				event.preventDefault();
				document.activeElement.click();
			}
		});

		select.classList.add('is-enhanced');
		select.dataset.hyHomesEnhanced = 'true';
		wrap.appendChild(button);
		wrap.appendChild(list);
		updateCustomSelect(select);
	}

	function initSearchFilters() {
		document.querySelectorAll('[data-hy-homes-search] .hy-homes-search__select').forEach(enhanceSelect);
		document.querySelectorAll('[data-hy-homes-search] .hy-homes-search__number').forEach(function (field) {
			if (field.dataset.hyHomesStateBound !== 'true') {
				field.addEventListener('input', function () {
					updateValueState(field);
				});
				field.dataset.hyHomesStateBound = 'true';
			}

			updateValueState(field);
		});

		document.querySelectorAll('[data-hy-homes-search] .hy-homes-search__clear').forEach(function (button) {
			if (button.dataset.hyHomesClearBound === 'true') {
				return;
			}

			button.addEventListener('click', function () {
				var form = button.closest('form');
				var field = form ? findNamedField(form, button.dataset.hyHomesClear) : null;

				if (!field) {
					return;
				}

				field.value = '';
				field.dispatchEvent(new Event('change', { bubbles: true }));
				field.dispatchEvent(new Event('input', { bubbles: true }));

				if (field.tagName.toLowerCase() === 'select') {
					field.selectedIndex = 0;
					updateCustomSelect(field);
				} else {
					updateValueState(field);
					field.focus();
				}
			});

			button.dataset.hyHomesClearBound = 'true';
		});
	}

	function updateCarouselButtons(carousel) {
		var viewport = carousel.querySelector('.hy-homes-carousel__viewport');
		var prevButton = carousel.querySelector('[data-hy-homes-carousel-prev]');
		var nextButton = carousel.querySelector('[data-hy-homes-carousel-next]');
		var maxScroll;

		if (!viewport) {
			return;
		}

		maxScroll = Math.max(0, viewport.scrollWidth - viewport.clientWidth - 1);

		if (!prevButton || !nextButton) {
			carousel.classList.toggle('is-static', maxScroll <= 1);
			return;
		}

		prevButton.disabled = viewport.scrollLeft <= 1;
		nextButton.disabled = viewport.scrollLeft >= maxScroll;
		carousel.classList.toggle('is-static', maxScroll <= 1);
	}

	function scrollCarousel(carousel, direction) {
		var viewport = carousel.querySelector('.hy-homes-carousel__viewport');
		var amount;

		if (!viewport) {
			return;
		}

		amount = viewport.clientWidth;
		viewport.scrollBy({
			left: direction * amount,
			behavior: 'smooth'
		});
	}

	function initCarouselAutoplay(carousel) {
		var viewport = carousel.querySelector('.hy-homes-carousel__viewport');
		var interval = parseInt(carousel.dataset.hyHomesCarouselInterval || '5200', 10);
		var timer;
		var isPaused = false;

		if (!viewport || carousel.dataset.hyHomesCarouselAutoplay !== 'true' || carousel.dataset.hyHomesCarouselAutoplayBound === 'true') {
			return;
		}

		function maxScroll() {
			return Math.max(0, viewport.scrollWidth - viewport.clientWidth - 1);
		}

		function moveNext() {
			var max = maxScroll();
			var next;

			if (isPaused || document.hidden || max <= 1) {
				return;
			}

			next = viewport.scrollLeft + viewport.clientWidth;

			if (next >= max) {
				next = 0;
			}

			viewport.scrollTo({
				left: next,
				behavior: 'smooth'
			});
		}

		function pause() {
			isPaused = true;
		}

		function resume() {
			isPaused = false;
		}

		timer = window.setInterval(moveNext, Math.max(2500, interval));
		carousel.addEventListener('mouseenter', pause);
		carousel.addEventListener('mouseleave', resume);
		carousel.addEventListener('focusin', pause);
		carousel.addEventListener('focusout', resume);

		window.addEventListener('beforeunload', function () {
			window.clearInterval(timer);
		});

		carousel.dataset.hyHomesCarouselAutoplayBound = 'true';
	}

	function initCarousels() {
		document.querySelectorAll('[data-hy-homes-carousel]').forEach(function (carousel) {
			var viewport = carousel.querySelector('.hy-homes-carousel__viewport');
			var prevButton = carousel.querySelector('[data-hy-homes-carousel-prev]');
			var nextButton = carousel.querySelector('[data-hy-homes-carousel-next]');

			initCarouselAutoplay(carousel);

			if (!viewport || !prevButton || !nextButton || carousel.dataset.hyHomesCarouselBound === 'true') {
				updateCarouselButtons(carousel);
				return;
			}

			prevButton.addEventListener('click', function () {
				scrollCarousel(carousel, -1);
			});

			nextButton.addEventListener('click', function () {
				scrollCarousel(carousel, 1);
			});

			viewport.addEventListener('scroll', function () {
				window.requestAnimationFrame(function () {
					updateCarouselButtons(carousel);
				});
			});

			viewport.addEventListener('keydown', function (event) {
				if (event.key === 'ArrowLeft') {
					event.preventDefault();
					scrollCarousel(carousel, -1);
				}

				if (event.key === 'ArrowRight') {
					event.preventDefault();
					scrollCarousel(carousel, 1);
				}
			});

			window.addEventListener('resize', function () {
				updateCarouselButtons(carousel);
			});

			carousel.dataset.hyHomesCarouselBound = 'true';
			updateCarouselButtons(carousel);
		});
	}

	function setDetailGalleryIndex(gallery, nextIndex) {
		var items = Array.prototype.slice.call(gallery.querySelectorAll('[data-hy-homes-detail-media]'));
		var thumbs = Array.prototype.slice.call(gallery.querySelectorAll('[data-hy-homes-detail-thumb]'));
		var activeIndex;

		if (!items.length) {
			return;
		}

		activeIndex = ((nextIndex % items.length) + items.length) % items.length;

		items.forEach(function (item, index) {
			var isActive = index === activeIndex;
			var video = item.querySelector('video');

			item.classList.toggle('is-active', isActive);

			if (!isActive && video) {
				video.pause();
			}
		});

		thumbs.forEach(function (thumb, index) {
			thumb.classList.toggle('is-active', index === activeIndex);
		});

		gallery.dataset.hyHomesDetailIndex = String(activeIndex);
	}

	function initDetailGalleries() {
		document.querySelectorAll('[data-hy-homes-detail-gallery]').forEach(function (gallery) {
			var prevButton = gallery.querySelector('[data-hy-homes-detail-prev]');
			var nextButton = gallery.querySelector('[data-hy-homes-detail-next]');

			if (gallery.dataset.hyHomesDetailBound === 'true') {
				return;
			}

			if (prevButton) {
				prevButton.addEventListener('click', function () {
					setDetailGalleryIndex(gallery, Number(gallery.dataset.hyHomesDetailIndex || 0) - 1);
				});
			}

			if (nextButton) {
				nextButton.addEventListener('click', function () {
					setDetailGalleryIndex(gallery, Number(gallery.dataset.hyHomesDetailIndex || 0) + 1);
				});
			}

			gallery.querySelectorAll('[data-hy-homes-detail-thumb]').forEach(function (thumb) {
				thumb.addEventListener('click', function () {
					setDetailGalleryIndex(gallery, Number(thumb.dataset.hyHomesDetailThumb || 0));
				});
			});

			gallery.dataset.hyHomesDetailBound = 'true';
			setDetailGalleryIndex(gallery, 0);
		});
	}

	function initAdminLocalityFields() {
		document.querySelectorAll('[data-hy-homes-locality-select]').forEach(function (select) {
			var wrapper = select.closest('.hy-homes-admin-grid');
			var newField = wrapper ? wrapper.querySelector('[data-hy-homes-locality-new]') : null;
			var input = newField ? newField.querySelector('input') : null;
			var addNewValue = select.dataset.addNewValue || '__hy_add_new__';

			if (!newField) {
				return;
			}

			function syncNewField() {
				var shouldShow = select.value === addNewValue;

				newField.hidden = !shouldShow;

				if (input) {
					input.disabled = !shouldShow;
					if (!shouldShow) {
						input.value = '';
					}
				}
			}

			if (select.dataset.hyHomesLocalityBound !== 'true') {
				select.addEventListener('change', syncNewField);
				select.dataset.hyHomesLocalityBound = 'true';
			}

			syncNewField();
		});
	}

	function initWhatsAppPickers() {
		document.querySelectorAll('[data-hy-homes-whatsapp]').forEach(function (picker) {
			var toggle = picker.querySelector('[data-hy-homes-whatsapp-toggle]');

			if (!toggle || picker.dataset.hyHomesWhatsappBound === 'true') {
				return;
			}

			toggle.addEventListener('click', function () {
				var shouldOpen = !picker.classList.contains('is-open');

				closeWhatsAppPickers(picker);
				setWhatsAppOpen(picker, shouldOpen);
			});

			picker.dataset.hyHomesWhatsappBound = 'true';
		});

	}

	function initHyHomesElements() {
		initSearchFilters();
		initCarousels();
		initDetailGalleries();
		initAdminLocalityFields();
		initWhatsAppPickers();
	}

	document.addEventListener('click', function (event) {
		var localWhatsAppTrigger = event.target.closest('.hy-homes-whatsapp-open-local, [data-hy-homes-whatsapp-local]');
		var whatsappTrigger = event.target.closest('.hy-homes-whatsapp-open, [data-hy-homes-whatsapp-open]');

		if (localWhatsAppTrigger && openLocalWhatsAppPanel(localWhatsAppTrigger)) {
			event.preventDefault();
			return;
		}

		if (whatsappTrigger && openFloatingWhatsAppPicker()) {
			event.preventDefault();
			return;
		}

		if (!event.target.closest('.hy-homes-search__select-wrap')) {
			closeAll();
		}

		if (!event.target.closest('[data-hy-homes-whatsapp]')) {
			closeWhatsAppPickers();
		}

		if (
			externalWhatsAppPanel &&
			!event.target.closest('[data-hy-homes-whatsapp-external-panel]') &&
			!event.target.closest('.hy-homes-whatsapp-open-local, [data-hy-homes-whatsapp-local]')
		) {
			closeExternalWhatsAppPanel();
		}
	});

	document.addEventListener('keydown', function (event) {
		if (event.key === 'Escape') {
			closeAll();
			closeWhatsAppPickers();
			closeExternalWhatsAppPanel();
		}
	});

	window.addEventListener('resize', closeExternalWhatsAppPanel);
	window.addEventListener('scroll', closeExternalWhatsAppPanel, true);

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initHyHomesElements);
	} else {
		initHyHomesElements();
	}

	window.addEventListener('elementor/frontend/init', initHyHomesElements);
})();
