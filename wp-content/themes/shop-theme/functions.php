<?php
/**
 * Theme bootstrap.
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('after_setup_theme', function () {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('woocommerce');

    register_nav_menus([
        'primary' => __('Primary Menu', 'shop-theme'),
    ]);
});

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('shop-theme-style', get_stylesheet_uri(), [], '1.0.0');
    wp_enqueue_script(
        'shop-theme-app',
        get_template_directory_uri() . '/assets/js/app.js',
        [],
        '1.0.0',
        true
    );
});

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

add_action('woocommerce_before_main_content', function () {
    echo '<main class="site-main">';
}, 5);

add_action('woocommerce_after_main_content', function () {
    echo '</main>';
}, 50);

add_action('woocommerce_thankyou', function () {
    echo '<div class="demo-message">';
    echo '<h2>Thank you for using this demo system.</h2>';
    echo '<a href="https://darlingtonchanakira.com" class="btn button">Return to main site</a>';
    echo '</div>';
});
