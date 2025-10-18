<?php
/**
 * Template for adding jobs in Miller Job Portal
 */
if (!defined('ABSPATH')) {
    exit;
}
?>
<div class="mj-job-form">
    <h2><?php _e('Add New Job', 'mj-job-portal'); ?></h2>
    <form method="post" action="">
        <?php wp_nonce_field('mj_job_form', 'mj_job_form_nonce'); ?>
        <p>
            <label for="job_title"><?php _e('Job Title', 'mj-job-portal'); ?></label>
            <input type="text" name="job_title" id="job_title" required class="widefat">
        </p>
        <p>
            <label for="job_description"><?php _e('Job Description', 'mj-job-portal'); ?></label>
            <textarea name="job_description" id="job_description" rows="5" class="widefat"></textarea>
        </p>
        <p>
            <label for="job_category"><?php _e('Job Category', 'mj-job-portal'); ?></label>
            <select name="job_category" id="job_category">
                <option value=""><?php _e('Select Category', 'mj-job-portal'); ?></option>
                <?php
                $categories = get_terms(['taxonomy' => 'jobpost_category', 'hide_empty' => false]);
                foreach ($categories as $category) {
                    echo '<option value="' . esc_attr($category->term_id) . '">' . esc_html($category->name) . '</option>';
                }
                ?>
            </select>
        </p>
        <p>
            <label for="job_type"><?php _e('Job Type', 'mj-job-portal'); ?></label>
            <select name="job_type" id="job_type">
                <option value=""><?php _e('Select Type', 'mj-job-portal'); ?></option>
                <?php
                $types = get_terms(['taxonomy' => 'jobpost_job_type', 'hide_empty' => false]);
                foreach ($types as $type) {
                    echo '<option value="' . esc_attr($type->term_id) . '">' . esc_html($type->name) . '</option>';
                }
                ?>
            </select>
        </p>
        <p>
            <label for="job_location"><?php _e('Job Location', 'mj-job-portal'); ?></label>
            <select name="job_location" id="job_location">
                <option value=""><?php _e('Select Location', 'mj-job-portal'); ?></option>
                <?php
                $locations = get_terms(['taxonomy' => 'jobpost_location', 'hide_empty' => false]);
                foreach ($locations as $location) {
                    echo '<option value="' . esc_attr($location->term_id) . '">' . esc_html($location->name) . '</option>';
                }
                ?>
            </select>
        </p>
        <p>
            <label for="job_salary"><?php _e('Salary Range', 'mj-job-portal'); ?></label>
            <input type="text" name="job_salary" id="job_salary" class="widefat">
        </p>
        <p>
            <input type="submit" name="mj_job_submit" value="<?php _e('Submit Job', 'mj-job-portal'); ?>" class="button button-primary">
        </p>
    </form>
    <?php
    if (isset($_POST['mj_job_submit']) && wp_verify_nonce($_POST['mj_job_form_nonce'], 'mj_job_form')) {
        $post_id = wp_insert_post([
            'post_title' => sanitize_text_field($_POST['job_title']),
            'post_content' => wp_kses_post($_POST['job_description']),
            'post_type' => 'jobpost',
            'post_status' => 'publish',
        ]);

        if ($post_id) {
            if ($_POST['job_category']) {
                wp_set_post_terms($post_id, [intval($_POST['job_category'])], 'jobpost_category');
            }
            if ($_POST['job_type']) {
                wp_set_post_terms($post_id, [intval($_POST['job_type'])], 'jobpost_job_type');
            }
            if ($_POST['job_location']) {
                wp_set_post_terms($post_id, [intval($_POST['job_location'])], 'jobpost_location');
            }
            if ($_POST['job_salary']) {
                update_post_meta($post_id, 'mj_job_salary', sanitize_text_field($_POST['job_salary']));
            }
            echo '<p>' . __('Job added successfully!', 'mj-job-portal') . '</p>';
        }
    }
    ?>
</div>
?>