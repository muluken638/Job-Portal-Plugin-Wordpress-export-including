<?php
/**
 * Admin functions for managing applicants in Miller Job Portal
 */

if (!defined('ABSPATH')) {
    exit;
}

class MJ_Admin_Applicants {
    public static function init() {
        add_action('add_meta_boxes', [__CLASS__, 'add_applicant_meta_boxes']);
        add_action('save_post_jobpost_applicants', [__CLASS__, 'save_applicant_meta']);
    }

    public static function add_applicant_meta_boxes() {
        add_meta_box(
            'mj_applicant_details',
            __('Applicant Details', 'mj-job-portal'),
            [__CLASS__, 'render_applicant_details_meta_box'],
            'jobpost_applicants',
            'normal',
            'high'
        );
    }

    public static function render_applicant_details_meta_box($post) {
        wp_nonce_field('mj_applicant_meta', 'mj_applicant_meta_nonce');
        $meta = get_post_meta($post->ID);
        ?>
        <table class="form-table">
            <tr>
                <th><label for="jobapp_name"><?php _e('Full Name', 'mj-job-portal'); ?></label></th>
                <td><input type="text" name="jobapp_name" id="jobapp_name" value="<?php echo esc_attr($meta['jobapp_name'][0] ?? ''); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label for="jobapp_gender"><?php _e('Gender', 'mj-job-portal'); ?></label></th>
                <td>
                    <select name="jobapp_gender" id="jobapp_gender">
                        <option value=""><?php _e('Select Gender', 'mj-job-portal'); ?></option>
                        <option value="Male" <?php selected($meta['jobapp_gender'][0] ?? '', 'Male'); ?>><?php _e('Male', 'mj-job-portal'); ?></option>
                        <option value="Female" <?php selected($meta['jobapp_gender'][0] ?? '', 'Female'); ?>><?php _e('Female', 'mj-job-portal'); ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="jobapp_age"><?php _e('Age', 'mj-job-portal'); ?></label></th>
                <td><input type="number" name="jobapp_age" id="jobapp_age" value="<?php echo esc_attr($meta['jobapp_age'][0] ?? ''); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label><?php _e('Birth Place', 'mj-job-portal'); ?></label></th>
                <td>
                    <?php include MJ_PLUGIN_DIR . 'public/templates/address-fields.php'; ?>
                </td>
            </tr>
            <tr>
                <th><label for="jobapp_current_address"><?php _e('Current Address', 'mj-job-portal'); ?></label></th>
                <td><input type="text" name="jobapp_current_address" id="jobapp_current_address" value="<?php echo esc_attr($meta['jobapp_current_address'][0] ?? ''); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label for="jobapp_educational_level"><?php _e('Level of Education', 'mj-job-portal'); ?></label></th>
                <td><input type="text" name="jobapp_educational_level" id="jobapp_educational_level" value="<?php echo esc_attr($meta['jobapp_educational_level'][0] ?? ''); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label for="jobapp_phone"><?php _e('Telephone', 'mj-job-portal'); ?></label></th>
                <td><input type="text" name="jobapp_phone" id="jobapp_phone" value="<?php echo esc_attr($meta['jobapp_phone'][0] ?? ''); ?>" class="regular-text"></td>
            </tr>
        </table>
        <?php
    }

    public static function save_applicant_meta($post_id) {
        if (!isset($_POST['mj_applicant_meta_nonce']) || !wp_verify_nonce($_POST['mj_applicant_meta_nonce'], 'mj_applicant_meta')) {
            return;
        }

        $fields = [
            'jobapp_name', 'jobapp_gender', 'jobapp_age', 'jobapp_birth_place_state',
            'jobapp_birth_place_city', 'jobapp_current_address', 'jobapp_educational_level',
            'jobapp_phone'
        ];

        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
            }
        }
    }
}
add_action('init', ['MJ_Admin_Applicants', 'init']);
?>