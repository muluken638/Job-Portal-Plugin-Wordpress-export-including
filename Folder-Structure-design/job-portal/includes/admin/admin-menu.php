<?php
/**
 * Adds admin menu pages for Miller Job Portal
 */

if (!defined('ABSPATH')) {
    exit;
}

class MJ_Admin_Menu {
    public static function init() {
        add_action('admin_menu', [__CLASS__, 'add_menu_pages']);
    }

    public static function add_menu_pages() {
        // Main menu
        add_menu_page(
            __('Miller Job Portal', 'mj-job-portal'),
            __('MJ Jobs', 'mj-job-portal'),
            'manage_options',
            'mj_job_portal',
            null,
            'dashicons-briefcase',
            20
        );

        // Submenu: Add Job
        add_submenu_page(
            'mj_job_portal',
            __('Add New Job', 'mj-job-portal'),
            __('Add Job', 'mj-job-portal'),
            'manage_options',
            'post-new.php?post_type=jobpost'
        );

        // Submenu: View Applicants
        add_submenu_page(
            'mj_job_portal',
            __('Applicants', 'mj-job-portal'),
            __('Applicants', 'mj-job-portal'),
            'manage_options',
            'mj_applicants',
            [__CLASS__, 'render_applicants_page']
        );

        // Submenu: Export Applicants
        add_submenu_page(
            'mj_job_portal',
            __('Export Applicants', 'mj-job-portal'),
            __('Export Applicants', 'mj-job-portal'),
            'access_excel_export',
            'mj_export_applicants',
            [__CLASS__, 'render_export_page']
        );
    }

    public static function render_applicants_page() {
        include MJ_PLUGIN_DIR . 'public/templates/applicants-view.php';
    }

    public static function render_export_page() {
        ?>
        <div class="wrap">
            <h1><?php _e('Export Applicants', 'mj-job-portal'); ?></h1>
            <form method="get" action="<?php echo admin_url('admin.php'); ?>">
                <input type="hidden" name="page" value="mj_export_applicants">
                <table class="form-table">
                    <tr>
                        <th><label for="date_from"><?php _e('Date From', 'mj-job-portal'); ?></label></th>
                        <td><input type="date" name="date_from" id="date_from" value="<?php echo esc_attr($_GET['date_from'] ?? ''); ?>"></td>
                    </tr>
                    <tr>
                        <th><label for="date_to"><?php _e('Date To', 'mj-job-portal'); ?></label></th>
                        <td><input type="date" name="date_to" id="date_to" value="<?php echo esc_attr($_GET['date_to'] ?? ''); ?>"></td>
                    </tr>
                    <tr>
                        <th><label for="gender"><?php _e('Gender', 'mj-job-portal'); ?></label></th>
                        <td>
                            <select name="gender" id="gender">
                                <option value=""><?php _e('All', 'mj-job-portal'); ?></option>
                                <option value="Male" <?php selected($_GET['gender'] ?? '', 'Male'); ?>><?php _e('Male', 'mj-job-portal'); ?></option>
                                <option value="Female" <?php selected($_GET['gender'] ?? '', 'Female'); ?>><?php _e('Female', 'mj-job-portal'); ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="job_category"><?php _e('Job Category', 'mj-job-portal'); ?></label></th>
                        <td>
                            <select name="job_category" id="job_category">
                                <option value=""><?php _e('All', 'mj-job-portal'); ?></option>
                                <?php
                                $categories = get_terms(['taxonomy' => 'jobpost_category', 'hide_empty' => false]);
                                foreach ($categories as $category) {
                                    echo '<option value="' . esc_attr($category->name) . '" ' . selected($_GET['job_category'] ?? '', $category->name, false) . '>' . esc_html($category->name) . '</option>';
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="job_type"><?php _e('Job Type', 'mj-job-portal'); ?></label></th>
                        <td>
                            <select name="job_type" id="job_type">
                                <option value=""><?php _e('All', 'mj-job-portal'); ?></option>
                                <?php
                                $types = get_terms(['taxonomy' => 'jobpost_job_type', 'hide_empty' => false]);
                                foreach ($types as $type) {
                                    echo '<option value="' . esc_attr($type->name) . '" ' . selected($_GET['job_type'] ?? '', $type->name, false) . '>' . esc_html($type->name) . '</option>';
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="job_location"><?php _e('Job Location', 'mj-job-portal'); ?></label></th>
                        <td>
                            <select name="job_location" id="job_location">
                                <option value=""><?php _e('All', 'mj-job-portal'); ?></option>
                                <?php
                                $locations = get_terms(['taxonomy' => 'jobpost_location', 'hide_empty' => false]);
                                foreach ($locations as $location) {
                                    echo '<option value="' . esc_attr($location->name) . '" ' . selected($_GET['job_location'] ?? '', $location->name, false) . '>' . esc_html($location->name) . '</option>';
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                </table>
                <?php submit_button(__('Export to Excel', 'mj-job-portal')); ?>
            </form>
        </div>
        <?php
    }
}
add_action('init', ['MJ_Admin_Menu', 'init']);
?>