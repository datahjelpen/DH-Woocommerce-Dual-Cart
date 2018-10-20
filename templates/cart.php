<?php
    defined('ABSPATH') || exit;
    get_header();
?>
<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
        <article id="post-5" class="post-5 page type-page status-publish hentry">
            <div class="entry-content white-bg">
                <div class="woocommerce">
                    <section class="layout-square-medium">
                        <?php wc_print_notices(); ?>
                        <a id="dhwcdc_button_back" href="<?= get_permalink(wc_get_page_id('shop')) ?>" class="button"><i class="icon-arrow-31"></i><span class="text"><?= __('Continue ordering', 'dh-woocommerce-dual-cart') ?></span></a>

                        <div id="dhwcdc_request_list">
                            <h3><?= __('Request list', 'dh-woocommerce-dual-cart') ?></h3>
                            <table id="dh-woocommerce-dual-cart-cart" class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th class="product-remove">&nbsp;</th>
                                        <th class="product-thumbnail">&nbsp;</th>
                                        <th class="product-name"><?php esc_html_e( 'Product', 'woocommerce' ); ?></th>
                                        <th class="product-price"><?php esc_html_e( 'Price', 'woocommerce' ); ?></th>
                                        <th class="product-quantity"><?php esc_html_e( 'Quantity', 'woocommerce' ); ?></th>
                                        <th class="product-subtotal"><?php esc_html_e( 'Total', 'woocommerce' ); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $tmp_products = $_SESSION['dh_woocommerce_dual_cart_request_list'];
                                    $products = [];

                                    foreach ($tmp_products as $product_id => $product_count) {
                                        $_pf = new WC_Product_Factory();
                                        $_product = $_pf->get_product($product_id);
                                        $_product->count = $product_count;

                                        array_push($products, $_product);
                                    }
                                    $tmp_products = null;
                                    $total_price_with_tax = 0;
                                    $total_price_without_tax = 0;

                                    foreach ($products as $product) {
                                        $_product = $product;
                                        $product_id = $product->get_id();

                                        $total_price_with_tax += $_product->count * wc_get_price_including_tax($product);
                                        $total_price_without_tax += $_product->count * $product->get_price();


                                        $product_permalink = $_product->get_permalink();
                                        ?>
                                        <tr class="woocommerce-cart-form__cart-item">

                                            <td class="request-list-item-remove">
                                                <?php
                                                    echo sprintf(
                                                        '<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
                                                        '#remove',
                                                        __( 'Remove this item', 'woocommerce' ),
                                                        esc_attr( $product_id ),
                                                        esc_attr( $_product->get_sku() )
                                                    );
                                                ?>
                                            </td>

                                            <td class="product-thumbnail">
                                            <?php
                                            $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image() );

                                            if ( ! $product_permalink ) {
                                                echo wp_kses_post( $thumbnail );
                                            } else {
                                                printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), wp_kses_post( $thumbnail ) );
                                            }
                                            ?>
                                            </td>

                                            <td class="product-name" data-title="<?php esc_attr_e( 'Product', 'woocommerce' ); ?>">
                                            <?php
                                            if ( ! $product_permalink ) {
                                                echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name() ) . '&nbsp;' );
                                            } else {
                                                echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ) ) );
                                            }

                                            do_action( 'woocommerce_after_cart_item_name' );

                                            ?>
                                            </td>

                                            <td class="product-price" data-title="<?php esc_attr_e( 'Price', 'woocommerce' ); ?>">
                                                <?php
                                                    echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ) ); // PHPCS: XSS ok.
                                                ?>
                                            </td>

                                            <td class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'woocommerce' ); ?>">
                                            <?php
                                            if ( $_product->is_sold_individually() ) {
                                                $product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />' );
                                            } else {
                                                $product_quantity = woocommerce_quantity_input( array(
                                                    'input_name'   => "request_list_qty[{$product_id}]",
                                                    'input_value'  => $product->count,
                                                    'min_value'    => '0',
                                                    'product_name' => $_product->get_name(),
                                                ), $_product, false );
                                            }

                                            echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity ); // PHPCS: XSS ok.
                                            ?>
                                            </td>

                                            <td class="product-subtotal" data-title="<?php esc_attr_e( 'Total', 'woocommerce' ); ?>">
                                                <?php
                                                    echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $product->count ) ); // PHPCS: XSS ok.
                                                ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    <tr>
                                        <td colspan="6" class="actions">
                                            <button type="submit" class="button" name="update_request_list" value="<?php esc_attr_e( 'Update request list', 'dh-woocommerce-dual-cart' ); ?>"><?php esc_html_e( 'Update request list', 'dh-woocommerce-dual-cart' ); ?></button>
                                        </td>
                                    </tr>
                                <tbody>
                            </table>
                            <div class="cart-collaterals">
                                <div class="cart_totals ">
                                    <table class="shop_table shop_table_responsive" cellspacing="0">
                                        <tbody>
                                            <tr class="order-total">
                                                <th>Totalt</th>
                                                <td id="dhwcdc_form_total" data-title="Totalt">
                                                    <strong><?= wc_price($total_price_with_tax); ?></strong>
                                                    <small class="includes_tax">(inkludert <?= wc_price($total_price_with_tax - $total_price_without_tax) ?> MVA)</small>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="wc-proceed-to-checkout">
                                        <a id="dhwcdc_reveal_form" href="#dhwcdc_form" class="checkout-button button alt wc-forward">Send forespørsel</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="dhwcdc_form">
                            <!-- <h3>Fyll ut skjema for å sende din forespørsel for gjennomgang av profesjonell blikkenslager</h3> -->
                            <h3>Vi trenger bare noen detaljer først ...</h3>
                            <p>Vennligst fyll ut skjema</p>

                            <?= the_content(); ?>

                            <form id="dh-uploader-form" enctype="multipart/form-data" method="POST" action="https://i.xdh.no/upload">
                                <input type="file" id="dh-uploader-input" class="invisible" name="image[]" required="" multiple="" data-max-size="128MiB">
                                <input type="hidden" name="X-API-KEY" value="fck5rfsZKfpdRbG4npSVz3Qz">
                                <button id="dh-uploader-submit" class="invisible" type="submit">submit</button>
                            </form>

                            <script src="https://i.xdh.no/upfiler/dist/js/upfiler.min.js"></script>
                            <link rel="stylesheet" href="https://i.xdh.no/upfiler/dist/css/upfiler.css">
                        </div>
                    </div>
                </div>
            </article>
        </section>
    </main>
</div>
<?php
    // do_action ('woocommerce_after_cart');
    get_footer();
?>
