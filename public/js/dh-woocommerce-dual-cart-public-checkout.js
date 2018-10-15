(function( $ ) {
	'use strict';

    const requestList = $('#dhwcdc_request_list');
    const requestListElements = requestList.find('tbody tr.woocommerce-cart-form__cart-item');

    const buttonRevealer = $('#dhwcdc_reveal_form');
    const buttonBack = $('#dhwcdc_button_back');
    // const buttonBackOriginalHTML = buttonBack.find('span').text();

    const form = $('#dhwcdc_form');
    const formInput = $('#dhwcdc_form .dhwcdc_request_list-input');
    const formTotal = $.trim($('#dhwcdc_form_total').html().replace(/\n/g, ''));

    let HTML = '<table>';
    HTML += '';
    HTML += '<thead><tr><th>SKU</th><th>Produkt</th><th>Pris</th><th>Antall</th><th>Totalt</th></tr></thead>';

    HTML += '<tbody>';

    for (let i = 0; i < requestListElements.length; i++) {
        const requestListElement = $(requestListElements[i]);
        let item = {};

        item.sku = requestListElement.find('.remove').attr('data-product_sku');
        item.name = $.trim(requestListElement.find('.product-name').html().replace(/\n/g, ''));
        item.price = $.trim(requestListElement.find('.product-price').html().replace(/\n/g, ''));
        item.quantity = requestListElement.find('.product-quantity input').val();
        item.subtotal = $.trim(requestListElement.find('.product-subtotal').html().replace(/\n/g, ''));

        HTML += '<tr>';
        HTML += '<td>' + item.sku + '</td>'
        HTML += '<td>' + item.name + '</td>'
        HTML += '<td>' + item.price + '</td>'
        HTML += '<td>' + item.quantity + '</td>'
        HTML += '<td>' + item.subtotal + '</td>'
        HTML += '</tr>';
    }

    HTML += '<tr>';
    HTML += '<td></td><td></td><td></td><td></td><td>' + formTotal + '</td>';
    HTML += '</tr>';

    HTML += '</tbody>';
    HTML += '</table>';

    formInput.val(HTML);

    if (requestListElements.length == 0) {
        buttonRevealer.attr('href', '#');
        buttonRevealer.prop('disabled', true);
        buttonRevealer.css({
            opacity: 0.25,
            cursor: 'not-allowed',
            boxShadow: 'none'
        });
    } else {
        buttonRevealer.click( function(e) {
            e.preventDefault();
            buttonBack.find('span').text('Tilbake');
            buttonBack.attr('href', '/foresporsel-liste');

            requestList.hide()
            $(this).hide();

            form.show();
        });
    }

})( jQuery );
