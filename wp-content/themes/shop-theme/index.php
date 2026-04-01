<?php
if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>
<section class="hero container" data-reveal>
    <div class="hero-panel">
        <p class="eyebrow">Premium WooCommerce Demo</p>
        <h1>Cloud Commerce Engine</h1>
        <p>A premium WooCommerce demo built with clean architecture.</p>
        <div class="hero-actions">
            <a class="button" href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>">Browse Shop</a>
            <a class="button alt" href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>">View Products</a>
        </div>
    </div>
</section>

<section class="container" data-reveal>
    <h2 class="section-title">Built for modern commerce teams</h2>
    <div class="feature-grid">
        <article class="feature-card"><h3>Scalable System</h3><p>Architecture-first catalog built for predictable growth and extensibility.</p></article>
        <article class="feature-card"><h3>Clean UX</h3><p>Minimal interaction patterns inspired by Apple and Stripe design systems.</p></article>
        <article class="feature-card"><h3>Fast Checkout</h3><p>Focused checkout fields and streamlined purchasing flow for demos and pilots.</p></article>
        <article class="feature-card"><h3>Demo Environment</h3><p>Seeded products, categories, and visuals to showcase production-ready workflows.</p></article>
    </div>
</section>

<section class="container" data-reveal>
    <h2 class="section-title">Featured Products</h2>
    <?php echo do_shortcode('[products limit="8" columns="4" visibility="featured"]'); ?>
</section>
<?php
get_footer();
