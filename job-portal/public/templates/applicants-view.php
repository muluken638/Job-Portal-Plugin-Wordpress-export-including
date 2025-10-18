<?php
/**
 * Template for viewing applicants with filters in Miller Job Portal
 */
if (!defined('ABSPATH')) {
    exit;
}

if (!current_user_can('manage_options')) {
    echo __('You do not have permission to view this page.', 'mj-job-portal');
    return;
}
?>
<div class="mj-applicant-filter">
    <h2><?php _e('View Applicants', 'mj-job-portal'); ?></h2>
    <form method="get" action="">
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
        <?php submit_button(__('Filter Applicants', 'mj-job-portal')); ?>
    </form>

    <?php
    $args = [
        'post_type' => 'jobpost_applicants',
        'posts_per_page' => 10,
        'meta_query' => ['relation' => 'AND'],
    ];

    if (!empty($_GET['gender'])) {
        $args['meta_query'][] = [
            'key' => 'jobapp_gender',
            'value' => sanitize_text_field($_GET['gender']),
            'compare' => '='
        ];
    }

    if (!empty($_GET['date_from']) || !empty($_GET['date_to'])) {
        $date_query = ['inclusive' => true];
        if ($_GET['date_from']) {
            $date_query['after'] = sanitize_text_field($_GET['date_from']);
        }
        if ($_GET['date_to']) {
            $date_query['before'] = sanitize_text_field($_GET['date_to']);
        }
        $args['date_query'] = [$date_query];
    }

    if (!empty($_GET['job_category']) || !empty($_GET['job_type']) || !empty($_GET['job_location'])) {
        $job_args = ['post_type' => 'jobpost', 'posts_per_page' => -1, 'fields' => 'ids'];
        $tax_query = ['relation' => 'AND'];

        if ($_GET['job_category']) {
            $tax_query[] = ['taxonomy' => 'jobpost_category', 'field' => 'name', 'terms' => sanitize_text_field($_GET['job_category'])];
        }
        if ($_GET['job_type']) {
            $tax_query[] = ['taxonomy' => 'jobpost_job_type', 'field' => 'name', 'terms' => sanitize_text_field($_GET['job_type'])];
        }
        if ($_GET['job_location']) {
            $tax_query[] = ['taxonomy' => 'jobpost_location', 'field' => 'name', 'terms' => sanitize_text_field($_GET['job_location'])];
        }

        if (count($tax_query) > 1) {
            $job_args['tax_query'] = $tax_query;
        }

        $job_ids = get_posts($job_args);
        if (!empty($job_ids)) {
            $args['meta_query'][] = [
                'key' => 'jobapp_job_id',
                'value' => $job_ids,
                'compare' => 'IN'
            ];
        }
    }

    $applicants = new WP_Query($args);
    if ($applicants->have_posts()) :
    ?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th><?php _e('Name', 'mj-job-portal'); ?></th>
                    <th><?php _e('Gender', 'mj-job-portal'); ?></th>
                    <th><?php _e('Age', 'mj-job-portal'); ?></th>
                    <th><?php _e('Applied Job', 'mj-job-portal'); ?></th>
                    <th><?php _e('Application Date', 'mj-job-portal'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php while ($applicants->have_posts()) : $applicants->the_post(); ?>
                    <tr>
                        <td><?php echo esc_html(get_post_meta(get_the_ID(), 'jobapp_name', true)); ?></td>
                        <td><?php echo esc_html(get_post_meta(get_the_ID(), 'jobapp_gender', true)); ?></td>
                        <td><?php echo esc_html(get_post_meta(get_the_ID(), 'jobapp_age', true)); ?></td>
                        <td>
                            <?php
                            $job_id = get_post_meta(get_the_ID(), 'jobapp_job_id', true);
                            echo $job_id ? esc_html(get_the_title($job_id)) : __('N/A', 'mj-job-portal');
                            ?>
                        </td>
                        <td><?php echo esc_html(get_the_date()); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p><?php _e('No applicants found.', 'mj-job-portal'); ?></p>
    <?php
    endif;
    wp_reset_postdata();
    ?>
</div>
?>