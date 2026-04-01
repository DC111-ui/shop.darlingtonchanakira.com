<?php
if (!defined('ABSPATH')) {
    exit;
}

global $product;

if (empty($product) || !$product->is_visible()) {
    return;
}
?>
<li <?php wc_product_class('product-card', $product); ?>>
    <a href="<?php the_permalink(); ?>" class="woocommerce-LoopProduct-link">
        <div class="product-thumb"><?php echo $product->get_image(); ?></div>
        <div class="product-meta">
            <h3 class="woocommerce-loop-product__title"><?php the_title(); ?></h3>
            <span class="price"><?php echo wp_kses_post($product->get_price_html()); ?></span>
        </div>
    </a>
    <div class="product-meta">
        <?php woocommerce_template_loop_add_to_cart(); ?>
    </div>
</li>
