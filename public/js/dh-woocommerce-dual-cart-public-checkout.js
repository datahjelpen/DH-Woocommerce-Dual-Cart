(function($) {
  "use strict";

  const requestList = $("#dhwcdc_request_list");
  const requestListElements = requestList.find(
    "tbody tr.woocommerce-cart-form__cart-item"
  );

  const buttonRevealer = $("#dhwcdc_reveal_form");
  const buttonBack = $("#dhwcdc_button_back");
  // const buttonBackOriginalHTML = buttonBack.find('span').text();

  const form = $("#dhwcdc_form");
  const formInput = $("#dhwcdc_form .dhwcdc_request_list-input");
  const formTotal = $.trim(
    $("#dhwcdc_form_total")
      .html()
      .replace(/\n/g, "")
  );

  let HTML =
    '<table style="border-collapse: collapse; font-family: SF Mono, Roboto Mono, monospace;">';
  HTML += "";
  HTML +=
    '<thead style="font-weight:700;text-align: left;font-family: SF Mono, Roboto Mono, monospace"><tr><th>SKU</th><th>Produkt</th><th>Pris</th><th>Antall</th><th>Totalt</th></tr></thead>';

  HTML += '<tbody style="font-weight:400;">';

  for (let i = 0; i < requestListElements.length; i++) {
    const requestListElement = $(requestListElements[i]);
    let item = {};

    item.sku = requestListElement.find(".remove").attr("data-product_sku");
    item.name = $.trim(
      requestListElement
        .find(".product-name")
        .html()
        .replace(/\n/g, "")
    );
    item.price = $.trim(
      requestListElement
        .find(".product-price")
        .html()
        .replace(/\n/g, "")
    );
    item.quantity = requestListElement.find(".product-quantity input").val();
    item.subtotal = $.trim(
      requestListElement
        .find(".product-subtotal")
        .html()
        .replace(/\n/g, "")
    );

    HTML +=
      '<tr style="font-family: SF Mono, Roboto Mono, monospace; padding: 0.33em; border-top: 1px solid #eceff1;">';
    HTML +=
      '<td style="font-family: SF Mono, Roboto Mono, monospace; padding: 0.33em;">' +
      item.sku +
      "</td>";
    HTML +=
      '<td style="font-family: SF Mono, Roboto Mono, monospace; padding: 0.33em; border-left: 1px solid #eceff1;">' +
      item.name +
      "</td>";
    HTML +=
      '<td style="font-family: SF Mono, Roboto Mono, monospace; padding: 0.33em; border-left: 1px solid #eceff1;">' +
      item.price +
      "</td>";
    HTML +=
      '<td style="font-family: SF Mono, Roboto Mono, monospace; padding: 0.33em; border-left: 1px solid #eceff1;">' +
      item.quantity +
      "</td>";
    HTML +=
      '<td style="font-family: SF Mono, Roboto Mono, monospace; padding: 0.33em; border-left: 1px solid #eceff1;">' +
      item.subtotal +
      "</td>";
    HTML += "</tr>";
  }

  HTML +=
    '<tr style="font-family: SF Mono, Roboto Mono, monospace; font-weight:700; border-top: 1px solid #eceff1;">';
  HTML +=
    '<td></td><td></td><td></td><td></td><td style="padding: 0.33em;">' +
    formTotal +
    "</td>";
  HTML += "</tr>";

  HTML += "</tbody>";
  HTML += "</table>";

  formInput.val(HTML);

  if (requestListElements.length == 0) {
    buttonRevealer.attr("href", "#");
    buttonRevealer.prop("disabled", true);
    buttonRevealer.css({
      opacity: 0.25,
      cursor: "not-allowed",
      boxShadow: "none"
    });
  } else {
    // Setup button click, which reveals the form
    buttonRevealer.click(function(e) {
      e.preventDefault();

      // Scroll back up
      $([document.documentElement, document.body]).animate(
        {
          scrollTop: 0
        },
        200
      );

      // Setup the back button
      buttonBack.find("span").text("Tilbake");
      buttonBack.attr("href", "/foresporsel-liste");

      // Hide old elements
      requestList.hide();
      $(this).hide();

      // Show new element
      form.show();
    });

    const formElement = form.find("form");

    // Setup the form submission
    formElement.submit(function(e) {
      $.post(ajax_object.ajax_url, {
        action: "remove_all_from_request_list_dh_woocommerce_dual_cart"
      });
    });
  }
})(jQuery);
