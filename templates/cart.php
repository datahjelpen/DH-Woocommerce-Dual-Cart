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

        foreach ($products as $product) {
            $_product = $product;
            $product_id = $product->get_id();

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
