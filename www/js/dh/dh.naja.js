"use strict";


function NajaDisableSubmitExtension(naja, disableClass) {
	let element = null;

	naja.addEventListener('interaction', function ({element}) {
		if (element.classList.contains(disableClass)) {
			this.element = element;
		} else {
			this.element = null;
		}
	}.bind(this));

	naja.addEventListener('start', diableElement.bind(this));
	naja.addEventListener('complete', enableElement.bind(this));

	function diableElement() {
		if (this.element !== null) {
			this.element.disabled = true;
		}
	}

	function enableElement() {
		if (this.element !== null) {
			this.element.disabled = false;
		}
	}

	return this;
}


function NajaLoaderExtension(naja, hiddenClass) {
	let loader = null;

	naja.addEventListener('interaction', function ({element}) {
		const form = element.closest('form');
		if (!form.length > 0 || !form.hasAttribute('data-loader')) {
			this.loader = null;
			return;
		}

		this.loader = document.querySelector(form.dataset.loader);
	}.bind(this));

	naja.addEventListener('start', showLoader.bind(this));
	naja.addEventListener('complete', hideLoader.bind(this));

	function showLoader() {
		if (this.loader !== null) {
			this.loader.classList.remove(hiddenClass);
			jQuery(this.loader).fadeIn(500);
		}
	}

	function hideLoader() {
		if (this.loader !== null) {
			jQuery(this.loader).fadeOut(500);
		}
	}

	return this;
}


naja.registerExtension(NajaDisableSubmitExtension, 'ajax-disable-submit');
naja.registerExtension(NajaLoaderExtension, 'is-hidden');
document.addEventListener('DOMContentLoaded', naja.initialize.bind(naja));
