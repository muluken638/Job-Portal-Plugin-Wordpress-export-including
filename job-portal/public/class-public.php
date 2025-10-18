<?php
/**
 * Public-facing logic for Miller Job Portal
 */

if (!defined('ABSPATH')) {
    exit;
}

class MJ_Public {
    public static function init() {
        add_action('wp_enqueue_scripts', [__CLASS__, 'enqueue_scripts']);
    }

    public static function enqueue_scripts() {
        wp_enqueue_style('mj-public', MJ_PLUGIN_URL . 'assets/css/public.css', [], '1.0.0');
        wp_enqueue_script('mj-public', MJ_PLUGIN_URL . 'assets/js/public.js', ['jquery'], '1.0.0', true);
        wp_enqueue_script('mj-address-cascade', MJ_PLUGIN_URL . 'assets/js/address-cascade.js', ['jquery'], '1.0.0', true);
        wp_localize_script('mj-public', 'mjAjax', ['ajaxurl' => admin_url('admin-ajax.php')]);
    }
}
add_action('init', ['MJ_Public', 'init']);
?>