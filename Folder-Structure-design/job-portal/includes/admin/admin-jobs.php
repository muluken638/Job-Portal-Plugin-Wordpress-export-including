<?php
/**
 * Admin functions for managing jobs in Miller Job Portal
 */

if (!defined('ABSPATH')) {
    exit;
}

class MJ_Admin_Jobs {
    public static function init() {
        // Add meta boxes for job details
        add_action('add_meta_boxes', [__CLASS__, 'add_job_meta_boxes']);
        add_action('save_post_jobpost', [__CLASS__, 'save_job_meta']);
    }

    public static function add_job_meta_boxes() {
        add_meta_box(
            'mj_job_details',
            __('Job Details', 'mj-job-portal'),
            [__CLASS__, 'render_job_details_meta_box'],
            'jobpost',
            'normal',
            'high'
        );
    }

    public static function render_job_details_meta_box($post) {
        wp_nonce_field('mj_job_meta', 'mj_job_meta_nonce');
        $salary = get_post_meta($post->ID, 'mj_job_salary', true);
        ?>
        <p>
            <label for="mj_job_salary"><?php _e('Salary Range', 'mj-job-portal'); ?></label>
            <input type="text" name="mj_job_salary" id="mj_job_salary" value="<?php echo esc_attr($salary); ?>" class="widefat">
        </p>
        <?php
    }

    public static function save_job_meta($post_id) {
        if (!isset($_POST['mj_job_meta_nonce']) || !wp_verify_nonce($_POST['mj_job_meta_nonce'], 'mj_job_meta')) {
            return;
        }

        if (isset($_POST['mj_job_salary'])) {
            update_post_meta($post_id, 'mj_job_salary', sanitize_text_field($_POST['mj_job_salary']));
        }
    }
}
add_action('init', ['MJ_Admin_Jobs', 'init']);
?>