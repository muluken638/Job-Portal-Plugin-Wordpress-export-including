<?php
/**
 * Handles forms for adding jobs, viewing jobs, and applicant filters
 */

if (!defined('ABSPATH')) {
    exit;
}

class MJ_Job_Forms {
    public static function init() {
        add_shortcode('mj_job_form', [__CLASS__, 'render_job_form']);
        add_shortcode('mj_job_list', [__CLASS__, 'render_job_list']);
        add_shortcode('mj_applicant_filter', [__CLASS__, 'render_applicant_filter']);
    }

    public static function render_job_form($atts) {
        ob_start();
        include MJ_PLUGIN_DIR . 'public/templates/job-add-form.php';
        return ob_get_clean();
    }

    public static function render_job_list($atts) {
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

    public static function render_applicant_filter($atts) {
        ob_start();
        include MJ_PLUGIN_DIR . 'public/templates/applicants-view.php';
        return ob_get_clean();
    }
}
add_action('init', ['MJ_Job_Forms', 'init']);
?>