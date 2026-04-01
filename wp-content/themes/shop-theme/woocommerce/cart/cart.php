<?php
if (!defined('ABSPATH')) {
    exit;
}

do_action('woocommerce_before_cart');
?>
<div class="cart-shell">
    <h1><?php esc_html_e('Cart', 'shop-theme'); ?></h1>

    <form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
        <?php do_action('woocommerce_before_cart_table'); ?>

        <table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
            <thead>
                <tr>
                    <th class="product-name"><?php esc_html_e('Product', 'woocommerce'); ?></th>
                    <th class="product-price"><?php esc_html_e('Price', 'woocommerce'); ?></th>
                    <th class="product-quantity"><?php esc_html_e('Quantity', 'woocommerce'); ?></th>
                    <th class="product-subtotal"><?php esc_html_e('Subtotal', 'woocommerce'); ?></th>
                    <th class="product-remove">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) :
                    $_product = $cart_item['data'];
                    if (!$_product || !$_product->exists() || $cart_item['quantity'] <= 0) {
                        continue;
                    }
                    ?>
                    <tr>
                        <td class="product-name"><?php echo wp_kses_post($_product->get_name()); ?></td>
                        <td class="product-price"><?php echo wp_kses_post(WC()->cart->get_product_price($_product)); ?></td>
                        <td class="product-quantity">
                            <?php
                            echo woocommerce_quantity_input(
                                [
                                    'input_name'  => "cart[{$cart_item_key}][qty]",
                                    'input_value' => $cart_item['quantity'],
                                    'min_value'   => 0,
                                    'max_value'   => $_product->get_max_purchase_quantity(),
                                ],
                                $_product,
                                false
                            );
                            ?>
                        </td>
                        <td class="product-subtotal"><?php echo wp_kses_post(WC()->cart->get_product_subtotal($_product, $cart_item['quantity'])); ?></td>
                        <td class="product-remove"><?php echo wc_get_cart_remove_url($cart_item_key) ? sprintf('<a href="%s" class="remove">×</a>', esc_url(wc_get_cart_remove_url($cart_item_key))) : ''; ?></td>
                    </tr>
                <?php endforeach; ?>

                <tr>
                    <td colspan="5" class="actions">
                        <button type="submit" class="button" name="update_cart" value="<?php esc_attr_e('Update cart', 'woocommerce'); ?>">
                            <?php esc_html_e('Update cart', 'woocommerce'); ?>
                        </button>
                        <?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
                    </td>
                </tr>
            </tbody>
        </table>

        <?php do_action('woocommerce_after_cart_table'); ?>
    </form>

    <div class="cart-collaterals">
        <?php do_action('woocommerce_cart_collaterals'); ?>
    </div>
</div>
<?php do_action('woocommerce_after_cart');
