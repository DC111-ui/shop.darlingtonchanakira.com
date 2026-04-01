<?php
if (!defined('ABSPATH')) {
    exit;
}

get_header('shop');
?>
<div class="single-shell">
    <?php while (have_posts()) : ?>
        <?php the_post(); ?>

        <div class="single-gallery">
            <?php do_action('woocommerce_before_single_product_summary'); ?>
        </div>

        <div class="summary">
            <?php do_action('woocommerce_single_product_summary'); ?>
        </div>
    <?php endwhile; ?>
</div>
<?php
get_footer('shop');
