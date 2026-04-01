<?php
if (!defined('ABSPATH')) {
    exit;
}

get_header('shop');
?>
<div class="checkout-shell">
    <h1><?php esc_html_e('Checkout', 'shop-theme'); ?></h1>

    <?php do_action('woocommerce_before_checkout_form', $checkout); ?>

    <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data">

        <div id="customer_details">
            <?php do_action('woocommerce_checkout_billing'); ?>
        </div>

        <h3 id="order_review_heading"><?php esc_html_e('Your order', 'woocommerce'); ?></h3>
        <div id="order_review" class="woocommerce-checkout-review-order">
            <?php do_action('woocommerce_checkout_order_review'); ?>
        </div>
    </form>

    <?php do_action('woocommerce_after_checkout_form', $checkout); ?>
</div>
<?php
get_footer('shop');
