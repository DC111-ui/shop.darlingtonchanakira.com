<?php
if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>
<section class="hero container">
    <h1>Cloud Commerce Engine</h1>
    <p>
        A high-fidelity demo storefront built on WooCommerce, showcasing premium UX,
        fast flows, and production-grade architecture for cloud-enabled commerce systems.
    </p>
</section>

<section class="container">
    <h2 class="section-title">Featured products</h2>
    <?php
    echo do_shortcode('[products limit="8" columns="4" visibility="featured"]');
    ?>
</section>
<?php
get_footer();
