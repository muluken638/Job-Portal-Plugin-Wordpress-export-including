<?php
/**
 * Shortcodes for Miller Job Portal
 */

if (!defined('ABSPATH')) {
    exit;
}

class MJ_Shortcodes {
    public static function init() {
        add_shortcode('mj_job_form', [__CLASS__, 'job_form']);
        add_shortcode('mj_job_list', [__CLASS__, 'job_list']);
        add_shortcode('mj_applicant_filter', [__CLASS__, 'applicant_filter']);
    }

    public static function job_form($atts) {
        ob_start();
        include MJ_PLUGIN_DIR . 'public/templates/job-add-form.php';
        return ob_get_clean();
    }

    public static function job_list($atts) {
        $args = [
            'post_type' => 'jobpost',
            'posts_per_page' => 10,
        ];
        $jobs = new WP_Query($args);
        ob_start();
        include MJ_PLUGIN_DIR . 'public/templates/job-view.php';
        wp_reset_postdata();
        return ob_get_clean();
    }

    public static function applicant_filter($atts) {
        if (!current_user_can('manage_options')) {
            return __('You do not have permission to view this page.', 'mj-job-portal');
        }
        ob_start();
        include MJ_PLUGIN_DIR . 'public/templates/applicants-view.php';
        return ob_get_clean();
    }
}
add_action('init', ['MJ_Shortcodes', 'init']);
?>