<?php
/**
 * Uninstall script for Miller Job Portal
 * 
 * Runs when the plugin is uninstalled to clean up custom post types, taxonomies, and metadata.
 */

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Delete custom posts
$job_posts = get_posts(['post_type' => 'jobpost', 'numberposts' => -1]);
foreach ($job_posts as $post) {
    wp_delete_post($post->ID, true);
}

$applicant_posts = get_posts(['post_type' => 'jobpost_applicants', 'numberposts' => -1]);
foreach ($applicant_posts as $post) {
    wp_delete_post($post->ID, true);
}

// Delete taxonomies terms
$taxonomies = ['jobpost_category', 'jobpost_job_type', 'jobpost_location'];
foreach ($taxonomies as $taxonomy) {
    $terms = get_terms(['taxonomy' => $taxonomy, 'hide_empty' => false]);
    foreach ($terms as $term) {
        wp_delete_term($term->term_id, $taxonomy);
    }
}

// Remove plugin options and metadata
delete_option('mj_plugin_version');
global $wpdb;
$wpdb->query("DELETE FROM $wpdb->postmeta WHERE meta_key LIKE 'jobapp_%'");
?>