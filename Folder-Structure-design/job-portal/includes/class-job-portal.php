<?php
/**
 * Main plugin class for Miller Job Portal
 * Handles initialization, enqueuing scripts, and core functionality
 */

if (!defined('ABSPATH')) {
    exit;
}

class MJ_Job_Portal {
    public static function init() {
        $instance = new self();
        $instance->setup_hooks();
    }

    private function setup_hooks() {
        // Enqueue scripts and styles
        add_action('wp_enqueue_scripts', [$this, 'enqueue_public_scripts']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_scripts']);
    }

    public function enqueue_public_scripts() {
        wp_enqueue_style('mj-public', MJ_PLUGIN_URL . 'assets/css/public.css', [], '1.0.0');
        wp_enqueue_script('mj-public', MJ_PLUGIN_URL . 'assets/js/public.js', ['jquery'], '1.0.0', true);
        wp_enqueue_script('mj-address-cascade', MJ_PLUGIN_URL . 'assets/js/address-cascade.js', ['jquery'], '1.0.0', true);
        wp_localize_script('mj-public', 'mjAjax', ['ajaxurl' => admin_url('admin-ajax.php')]);
    }

    public function enqueue_admin_scripts() {
        wp_enqueue_style('mj-admin', MJ_PLUGIN_URL . 'assets/css/admin.css', [], '1.0.0');
        wp_enqueue_script('mj-admin', MJ_PLUGIN_URL . 'assets/js/admin.js', ['jquery'], '1.0.0', true);
        wp_localize_script('mj-admin', 'mjAjax', ['ajaxurl' => admin_url('admin-ajax.php')]);
    }
}
?>