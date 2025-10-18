<?php
/**
 * Registers custom post types for jobs and applicants
 */

if (!defined('ABSPATH')) {
    exit;
}

class MJ_Job_Post_Types {
    public static function register_post_types() {
        // Register Job Post Type
        $job_labels = [
            'name' => __('Jobs', 'mj-job-portal'),
            'singular_name' => __('Job', 'mj-job-portal'),
            'add_new' => __('Add New Job', 'mj-job-portal'),
            'add_new_item' => __('Add New Job', 'mj-job-portal'),
            'edit_item' => __('Edit Job', 'mj-job-portal'),
            'new_item' => __('New Job', 'mj-job-portal'),
            'view_item' => __('View Job', 'mj-job-portal'),
            'search_items' => __('Search Jobs', 'mj-job-portal'),
        ];

        $job_args = [
            'labels' => $job_labels,
            'public' => true,
            'has_archive' => true,
            'supports' => ['title', 'editor', 'author'],
            'rewrite' => ['slug' => 'jobs'],
            'show_in_menu' => 'mj_job_portal',
            'capability_type' => 'post',
        ];
        register_post_type('jobpost', $job_args);

        // Register Applicant Post Type
        $applicant_labels = [
            'name' => __('Applicants', 'mj-job-portal'),
            'singular_name' => __('Applicant', 'mj-job-portal'),
            'add_new' => __('Add New Applicant', 'mj-job-portal'),
            'add_new_item' => __('Add New Applicant', 'mj-job-portal'),
            'edit_item' => __('Edit Applicant', 'mj-job-portal'),
            'new_item' => __('New Applicant', 'mj-job-portal'),
            'view_item' => __('View Applicant', 'mj-job-portal'),
            'search_items' => __('Search Applicants', 'mj-job-portal'),
        ];

        $applicant_args = [
            'labels' => $applicant_labels,
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => 'mj_job_portal',
            'supports' => ['title'],
            'capability_type' => 'post',
        ];
        register_post_type('jobpost_applicants', $applicant_args);
    }
}
?>