<?php
/**
 * Theme bootstrap.
 */

if (!defined('ABSPATH')) {
    exit;
}

const SHOP_THEME_VERSION = '2.0.0';

add_action('after_setup_theme', function () {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('woocommerce');

    register_nav_menus([
        'primary' => __('Primary Menu', 'shop-theme'),
    ]);
});

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('shop-theme-style', get_stylesheet_uri(), [], SHOP_THEME_VERSION);
    wp_enqueue_style(
        'shop-theme-main',
        get_template_directory_uri() . '/assets/css/main.css',
        ['shop-theme-style'],
        SHOP_THEME_VERSION
    );

    wp_enqueue_script(
        'shop-theme-app',
        get_template_directory_uri() . '/assets/js/app.js',
        [],
        SHOP_THEME_VERSION,
        true
    );
});

add_action('after_switch_theme', function () {
    if (function_exists('update_option')) {
        update_option('woocommerce_currency', 'ZAR');
    }

    shop_theme_seed_catalog();
});

add_action('init', function () {
    if (!class_exists('WooCommerce')) {
        return;
    }

    if (get_option('shop_theme_catalog_seeded') !== '1') {
        shop_theme_seed_catalog();
    }
});

function shop_theme_seed_catalog(): void
{
    if (!class_exists('WooCommerce') || !function_exists('wc_get_product_id_by_sku')) {
        return;
    }

    $categories = [
        'cloud-architecture' => 'Cloud Architecture',
        'dev-systems' => 'Dev Systems',
        'e-commerce-systems' => 'E-Commerce Systems',
        'simulation-kits' => 'Simulation Kits',
    ];

    foreach ($categories as $slug => $name) {
        if (!term_exists($slug, 'product_cat')) {
            wp_insert_term($name, 'product_cat', ['slug' => $slug]);
        }
    }

    $products = shop_theme_product_catalog();
    foreach ($products as $index => $productData) {
        $sku = 'shop-demo-' . ($index + 1);
        $productId = wc_get_product_id_by_sku($sku);

        if (!$productId) {
            $productId = wp_insert_post([
                'post_title' => $productData['name'],
                'post_content' => $productData['long_description'],
                'post_excerpt' => $productData['short_description'],
                'post_status' => 'publish',
                'post_type' => 'product',
            ]);
        }

        if (!$productId || is_wp_error($productId)) {
            continue;
        }

        wp_set_object_terms($productId, $productData['category'], 'product_cat', false);
        update_post_meta($productId, '_sku', $sku);
        update_post_meta($productId, '_regular_price', (string) $productData['price']);
        update_post_meta($productId, '_price', (string) $productData['price']);
        update_post_meta($productId, '_stock', '100');
        update_post_meta($productId, '_stock_status', 'instock');
        update_post_meta($productId, '_manage_stock', 'yes');
        update_post_meta($productId, '_visibility', 'visible');

        if ($index < 8) {
            update_post_meta($productId, '_featured', 'yes');
        }

        $imageId = shop_theme_attach_product_image($productId, $productData['image']);
        if ($imageId) {
            set_post_thumbnail($productId, $imageId);
        }
    }

    update_option('shop_theme_catalog_seeded', '1');
}

function shop_theme_attach_product_image(int $productId, string $fileName): int
{
    $existingThumbnail = (int) get_post_thumbnail_id($productId);
    if ($existingThumbnail > 0) {
        return $existingThumbnail;
    }

    $filePath = get_template_directory() . '/assets/images/' . $fileName;
    if (!file_exists($filePath)) {
        return 0;
    }

    $uploads = wp_upload_dir();
    $destination = trailingslashit($uploads['path']) . basename($filePath);

    if (!file_exists($destination)) {
        wp_mkdir_p($uploads['path']);
        copy($filePath, $destination);
    }

    $attachmentId = attachment_url_to_postid(trailingslashit($uploads['url']) . basename($filePath));
    if ($attachmentId) {
        return (int) $attachmentId;
    }

    $fileType = wp_check_filetype(basename($destination), null);
    $attachmentId = wp_insert_attachment([
        'post_mime_type' => $fileType['type'] ?? 'image/svg+xml',
        'post_title' => preg_replace('/\.[^.]+$/', '', basename($destination)),
        'post_content' => '',
        'post_status' => 'inherit',
    ], $destination, $productId);

    if (is_wp_error($attachmentId)) {
        return 0;
    }

    return (int) $attachmentId;
}

function shop_theme_product_catalog(): array
{
    return [
        [
            'name' => 'AWS Starter Architecture Kit',
            'price' => 199,
            'category' => 'cloud-architecture',
            'image' => 'aws-starter-architecture-kit.svg',
            'short_description' => 'Foundational AWS architecture kit for secure, scalable web delivery.',
            'long_description' => 'This kit provides a foundational AWS architecture design for scalable web applications, including practical networking, compute, and storage patterns aligned to production-ready deployment principles.',
        ],
        [
            'name' => 'Serverless Web App Blueprint',
            'price' => 249,
            'category' => 'cloud-architecture',
            'image' => 'serverless-web-app-blueprint.svg',
            'short_description' => 'Reference implementation for modern event-backed serverless applications.',
            'long_description' => 'A concise blueprint covering API-driven serverless application design using managed components, asynchronous handlers, and secure integration boundaries for low-ops web products.',
        ],
        [
            'name' => 'High-Availability Web Architecture',
            'price' => 299,
            'category' => 'cloud-architecture',
            'image' => 'high-availability-web-architecture.svg',
            'short_description' => 'Resilient multi-zone infrastructure patterns for critical workloads.',
            'long_description' => 'A high-availability architecture package describing redundancy, failover, and reliability controls across distributed services to support stable, fault-tolerant user-facing platforms.',
        ],
        [
            'name' => 'Event-Driven System Design Pack',
            'price' => 349,
            'category' => 'cloud-architecture',
            'image' => 'event-driven-system-design-pack.svg',
            'short_description' => 'Event stream and queue-centric patterns for distributed systems.',
            'long_description' => 'This design pack outlines event-driven communication models, queue orchestration strategies, and contract-based processing patterns for scalable distributed product systems.',
        ],
        [
            'name' => 'Node.js API Starter Kit',
            'price' => 179,
            'category' => 'dev-systems',
            'image' => 'nodejs-api-starter-kit.svg',
            'short_description' => 'Production-lean API foundation for Node.js backend services.',
            'long_description' => 'A backend starter kit that covers modular service boundaries, request validation, error handling, and structured API conventions for consistent Node.js platform development.',
        ],
        [
            'name' => 'Authentication System Blueprint',
            'price' => 229,
            'category' => 'dev-systems',
            'image' => 'authentication-system-blueprint.svg',
            'short_description' => 'Identity and access workflow blueprint for secure applications.',
            'long_description' => 'A security-focused blueprint detailing identity lifecycle management, token exchange, and role-based access patterns designed for robust authentication services.',
        ],
        [
            'name' => 'CI/CD Pipeline Template',
            'price' => 279,
            'category' => 'dev-systems',
            'image' => 'cicd-pipeline-template.svg',
            'short_description' => 'Automated build and deployment template for iterative teams.',
            'long_description' => 'A practical CI/CD template for automated build, validation, and deployment workflows, enabling reliable release cycles and controlled progression across environments.',
        ],
        [
            'name' => 'Observability & Monitoring Kit',
            'price' => 199,
            'category' => 'dev-systems',
            'image' => 'observability-monitoring-kit.svg',
            'short_description' => 'Telemetry and monitoring starter for service health visibility.',
            'long_description' => 'A monitoring-focused kit defining metrics, traces, and alerting structures to improve operational awareness and support measurable reliability objectives.',
        ],
        [
            'name' => 'E-Commerce Backend System',
            'price' => 299,
            'category' => 'e-commerce-systems',
            'image' => 'e-commerce-backend-system.svg',
            'short_description' => 'Core backend architecture for order and catalog operations.',
            'long_description' => 'A backend reference for e-commerce services that includes order lifecycle design, product management patterns, and API boundaries for transactional flows.',
        ],
        [
            'name' => 'E-Commerce System (Advanced)',
            'price' => 399,
            'category' => 'e-commerce-systems',
            'image' => 'e-commerce-system-advanced.svg',
            'short_description' => 'Advanced commerce architecture with inventory and workflow depth.',
            'long_description' => 'An advanced system design that extends e-commerce backends with inventory synchronization, orchestration workflows, and scalable integration patterns.',
        ],
        [
            'name' => 'Event-Driven Order Processing System',
            'price' => 449,
            'category' => 'e-commerce-systems',
            'image' => 'event-driven-order-processing-system.svg',
            'short_description' => 'Asynchronous order processing architecture for modern commerce.',
            'long_description' => 'A focused architecture pack for asynchronous order execution using events, queues, and idempotent processing patterns to improve reliability and throughput.',
        ],
        [
            'name' => 'Developer Starter Box',
            'price' => 499,
            'category' => 'simulation-kits',
            'image' => 'developer-starter-box.svg',
            'short_description' => 'Curated starter simulation for practical product engineering workflows.',
            'long_description' => 'A simulation kit that introduces foundational architecture, backend, and deployment scenarios to help teams validate system thinking across typical product workflows.',
        ],
        [
            'name' => 'Infrastructure Kit',
            'price' => 699,
            'category' => 'simulation-kits',
            'image' => 'infrastructure-kit.svg',
            'short_description' => 'Infrastructure simulation package for resilient platform planning.',
            'long_description' => 'A premium infrastructure simulation package focused on environment topology, reliability controls, and operational baselines for cloud-native platforms.',
        ],
        [
            'name' => 'Enterprise System Bundle',
            'price' => 999,
            'category' => 'simulation-kits',
            'image' => 'enterprise-system-bundle.svg',
            'short_description' => 'Comprehensive bundle for enterprise-scale system simulation.',
            'long_description' => 'An enterprise-grade bundle combining advanced architecture and process simulations that map complex service interactions, governance needs, and large-scale delivery constraints.',
        ],
    ];
}

add_filter('woocommerce_currency_symbol', function ($symbol, $currency) {
    if ($currency === 'ZAR') {
        return 'R';
    }

    return $symbol;
}, 10, 2);

add_filter('woocommerce_checkout_fields', function ($fields) {
    $keepBilling = [
        'billing_first_name',
        'billing_last_name',
        'billing_phone',
        'billing_address_1',
    ];

    foreach ($fields['billing'] as $key => $field) {
        if (!in_array($key, $keepBilling, true)) {
            unset($fields['billing'][$key]);
        }
    }

    $fields['billing']['billing_first_name']['label'] = __('First name', 'shop-theme');
    $fields['billing']['billing_last_name']['label'] = __('Last name', 'shop-theme');
    $fields['billing']['billing_phone']['label'] = __('Phone', 'shop-theme');
    $fields['billing']['billing_address_1']['label'] = __('Address', 'shop-theme');

    $fields['billing']['billing_phone']['required'] = true;
    $fields['billing']['billing_address_1']['required'] = true;

    return $fields;
});

add_filter('woocommerce_enable_order_notes_field', '__return_false');

add_filter('loop_shop_per_page', fn () => 12);

add_action('woocommerce_before_main_content', function () {
    echo '<main class="site-main">';
}, 5);

add_action('woocommerce_after_main_content', function () {
    echo '</main>';
}, 50);

add_action('woocommerce_thankyou', function () {
    echo '<section class="demo-message">';
    echo '<p class="eyebrow">Demo Checkout Complete</p>';
    echo '<h2>Thank you for using this demo system.</h2>';
    echo '<p>Your order has been received in this sandbox storefront environment.</p>';
    echo '<a href="https://darlingtonchanakira.com" class="btn button">Return to darlingtonchanakira.com</a>';
    echo '</section>';
});

function shop_theme_fallback_menu(): void
{
    echo '<ul>';
    echo '<li><a href="' . esc_url(home_url('/')) . '">Home</a></li>';
    echo '<li><a href="' . esc_url(wc_get_page_permalink('shop')) . '">Shop</a></li>';
    echo '<li><a href="' . esc_url(wc_get_cart_url()) . '">Cart</a></li>';
    echo '<li><a href="' . esc_url(wc_get_checkout_url()) . '">Checkout</a></li>';
    echo '</ul>';
}
