(function( $ ) {
	'use strict';

	// The data we send to the backend
	const data = {
		'action': 'add_to_request_list_dh_woocommerce_dual_cart',
		'product_id': ajax_object.product_id
	};

	const btnCartRequestList = $('#button-cart_request_list');        // The button that triggers everything

	if (btnCartRequestList[0] != null) {
		const originalHTML = btnCartRequestList[0].innerHTML;           // Store the buttons original HTML
		const intervalLoadTime = 200;                                   // How long between each load "animation"
		const maxLoadTime = intervalLoadTime*5;                         // Maximum amount of load time before we say something went wrong

		btnCartRequestList.click(function() {
			let loadingDone = false;

			// Loading "animation"
			btnCartRequestList[0].innerHTML = '...';
			let loadingInterval = setInterval(() => {
				btnCartRequestList[0].innerHTML += '.';
			}, intervalLoadTime);

			// Clear interval after a certian amount of time
			setTimeout(() => {
				clearInterval(loadingInterval);

				// Loading was not done, something must have gone wrong
				if (!loadingDone) {
					loadingDone = true;
					btnCartRequestList[0].innerHTML = 'Noe gikk galt';

					// Restore the buttons original HTML after some time
					setTimeout(() => {
						btnCartRequestList[0].innerHTML = originalHTML;
					}, 2500);
				}
			}, maxLoadTime);

			// Send the data to the backend
			$.post(ajax_object.ajax_url, data, function (response) {
				// Clear interval when post request is done
				clearInterval(loadingInterval);
				loadingDone = true;

				// Set the buttons HTML back to what it originally was
				btnCartRequestList[0].innerHTML = originalHTML;

				response = JSON.parse(response)

				if (response) {
					location.reload();
				}
			});

		});
	}

})( jQuery );
