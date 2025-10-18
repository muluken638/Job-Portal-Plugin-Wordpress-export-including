<?php
/**
 * Plugin Name: Miller Job Portal
 * Plugin URI: https://example.com/miller-job-portal
 * Description: A WordPress plugin for managing job postings, applicants, and exporting applicant data to Excel.
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://example.com
 * License: GPL-2.0+
 * Text Domain: mj-job-portal
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Define plugin constants
define('MJ_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('MJ_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include necessary files
require_once MJ_PLUGIN_DIR . 'includes/class-job-portal.php';
require_once MJ_PLUGIN_DIR . 'includes/class-job-post-types.php';
require_once MJ_PLUGIN_DIR . 'includes/class-job-taxonomies.php';
require_once MJ_PLUGIN_DIR . 'includes/class-job-forms.php';
require_once MJ_PLUGIN_DIR . 'includes/class-job-export.php';
require_once MJ_PLUGIN_DIR . 'includes/class-job-address.php';
require_once MJ_PLUGIN_DIR . 'includes/functions.php';

// Initialize the plugin
function mj_init_plugin() {
    MJ_Job_Portal::init();
}
add_action('plugins_loaded', 'mj_init_plugin');

// Activation hook
function mj_activate() {
    // Register post types and taxonomies on activation
    MJ_Job_Post_Types::register_post_types();
    MJ_Job_Taxonomies::register_taxonomies();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'mj_activate');

// Deactivation hook
function mj_deactivate() {
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'mj_deactivate');
?>