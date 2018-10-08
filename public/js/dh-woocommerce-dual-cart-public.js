(function( $ ) {
	'use strict';

	const btnCartRequestList = $('#button-cart_request_list');        // The button that triggers adding
	if (btnCartRequestList[0] != null) {
		// The data we send to the backend
		const data = {
			'action': 'add_to_request_list_dh_woocommerce_dual_cart',
			'product_id': ajax_object.product_id,
			'count': 1
		};

		const originalHTML = btnCartRequestList[0].innerHTML;           // Store the buttons original HTML
		const intervalLoadTime = 200;                                   // How long between each load "animation"
		const maxLoadTime = intervalLoadTime*5;                         // Maximum amount of load time before we say something went wrong

		btnCartRequestList.click(function() {
			let loadingDone = false;

			let count = $('form.cart .quantity input');
			count = count.val();
			data.count = count;

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

				// Refresh the window, but let's not resend POST data
				if (response) {
					window.location.href = window.location.href;
				}
			});

		});
	}

	const cartRequestListCart = $('#dh-woocommerce-dual-cart-cart'); // Request list on the cart page
	if (cartRequestListCart[0]) {
		// Remove item from request list when user clicks on the remove button
		cartRequestListCart.on('click', '.request-list-item-remove .remove', function () {
			const listItem = $(this);
			const data = {
				'action': 'remove_from_request_list_dh_woocommerce_dual_cart',
				'product_id': listItem.attr('data-product_id')
			};

			listItem.parent().parent().css('opacity', 0.5);

			// Send the data to the backend
			$.post(ajax_object.ajax_url, data, function (response) {
				response = JSON.parse(response)

				// Refresh the window, but let's not resend POST data
				if (response) {
					listItem.parent().parent().remove();
				} else {
					console.error('Could not remove item from request list');
				}
			});
		})

		const updateCountsButton = cartRequestListCart.find('.actions button.button[name="update_request_list"]');
		updateCountsButton.click(function(e) {
			// Update list item counts
			e.preventDefault();
			updateCountsButton.prop('disabled', true);

			const inputs = cartRequestListCart.find('.product-quantity .quantity input.qty');
			const updatedList = {};

			for (let i = 0; i < inputs.length; i++) {
				const input = $(inputs[i]);
				const product_id = input.attr('name').replace('request_list_qty', '').replace('[', '').replace(']', '');
				updatedList[product_id] = parseInt(input.val());
			}

			const data = {
				'action': 'update_request_list_dh_woocommerce_dual_cart',
				'updated_list': updatedList
			};

			setTimeout(() => {
				updateCountsButton.prop('disabled', false);
			}, 5000);

			// Send the data to the backend
			$.post(ajax_object.ajax_url, data, function (response) {
				response = JSON.parse(response)
				updateCountsButton.prop('disabled', false);

				// Refresh the window, but let's not resend POST data
				if (response) {
				} else {
					console.error('Could not update the request list');
				}
			});
		});
	}

})( jQuery );
