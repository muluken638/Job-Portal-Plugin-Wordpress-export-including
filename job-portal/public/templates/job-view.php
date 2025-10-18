<?php
/**
 * Template for viewing jobs in Miller Job Portal
 */
if (!defined('ABSPATH')) {
    exit;
}
?>
<div class="mj-job-list">
    <h2><?php _e('Available Jobs', 'mj-job-portal'); ?></h2>
    <?php if ($jobs->have_posts()) : ?>
        <ul>
            <?php while ($jobs->have_posts()) : $jobs->the_post(); ?>
                <li>
                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    <p><?php the_excerpt(); ?></p>
                    <p>
                        <?php
                        $categories = get_the_terms(get_the_ID(), 'jobpost_category');
                        if ($categories) {
                            echo __('Category: ', 'mj-job-portal') . esc_html($categories[0]->name);
                        }
                        ?>
                    </p>
                    <p>
                        <?php
                        $types = get_the_terms(get_the_ID(), 'jobpost_job_type');
                        if ($types) {
                            echo __('Type: ', 'mj-job-portal') . esc_html($types[0]->name);
                        }
                        ?>
                    </p>
                    <p>
                        <?php
                        $locations = get_the_terms(get_the_ID(), 'jobpost_location');
                        if ($locations) {
                            echo __('Location: ', 'mj-job-portal') . esc_html($locations[0]->name);
                        }
                        ?>
                    </p>
                    <p>
                        <?php
                        $salary = get_post_meta(get_the_ID(), 'mj_job_salary', true);
                        if ($salary) {
                            echo __('Salary: ', 'mj-job-portal') . esc_html($salary);
                        }
                        ?>
                    </p>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else : ?>
        <p><?php _e('No jobs found.', 'mj-job-portal'); ?></p>
    <?php endif; ?>
</div>
?>