(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	jQuery(document).ready(function ($) {
		var data = {
			'action': 'my_action',
			'product_id': ajax_object.product_id
		};

		const btnCartRequestList = jQuery('#button-cart_request_list');
		const intervalLoadTime = 200;
		const maxLoadTime = 1000;

		let originalHTML = btnCartRequestList[0].innerHTML;

		btnCartRequestList.click(function() {

			btnCartRequestList[0].innerHTML = '...';
			let loadingInterval = setInterval(() => {
				btnCartRequestList[0].innerHTML += '.';
			}, intervalLoadTime);

			// Clear interval after a certian amount of time
			setTimeout(() => {
				clearInterval(loadingInterval);
			}, maxLoadTime);

			jQuery.post(ajax_object.ajax_url, data, function (response) {
				// Clear interval when post request is done
				clearInterval(loadingInterval);

				// Set the buttons HTML back to what it originally was
				btnCartRequestList[0].innerHTML = originalHTML;
				console.log(response);
			});

		});
	});


})( jQuery );
