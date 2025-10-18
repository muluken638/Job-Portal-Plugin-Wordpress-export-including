<?php
/**
 * Registers taxonomies for job categories, types, and locations
 */

if (!defined('ABSPATH')) {
    exit;
}

class MJ_Job_Taxonomies {
    public static function register_taxonomies() {
        // Job Category Taxonomy
        $category_labels = [
            'name' => __('Job Categories', 'mj-job-portal'),
            'singular_name' => __('Job Category', 'mj-job-portal'),
            'search_items' => __('Search Job Categories', 'mj-job-portal'),
            'all_items' => __('All Job Categories', 'mj-job-portal'),
            'edit_item' => __('Edit Job Category', 'mj-job-portal'),
            'update_item' => __('Update Job Category', 'mj-job-portal'),
            'add_new_item' => __('Add New Job Category', 'mj-job-portal'),
        ];

        $category_args = [
            'labels' => $category_labels,
            'hierarchical' => true,
            'public' => true,
            'show_admin_column' => true,
            'rewrite' => ['slug' => 'job-category'],
        ];
        register_taxonomy('jobpost_category', ['jobpost'], $category_args);

        // Job Type Taxonomy
        $type_labels = [
            'name' => __('Job Types', 'mj-job-portal'),
            'singular_name' => __('Job Type', 'mj-job-portal'),
            'search_items' => __('Search Job Types', 'mj-job-portal'),
            'all_items' => __('All Job Types', 'mj-job-portal'),
            'edit_item' => __('Edit Job Type', 'mj-job-portal'),
            'update_item' => __('Update Job Type', 'mj-job-portal'),
            'add_new_item' => __('Add New Job Type', 'mj-job-portal'),
        ];

        $type_args = [
            'labels' => $type_labels,
            'hierarchical' => true,
            'public' => true,
            'show_admin_column' => true,
            'rewrite' => ['slug' => 'job-type'],
        ];
        register_taxonomy('jobpost_job_type', ['jobpost'], $type_args);

        // Job Location Taxonomy
        $location_labels = [
            'name' => __('Job Locations', 'mj-job-portal'),
            'singular_name' => __('Job Location', 'mj-job-portal'),
            'search_items' => __('Search Job Locations', 'mj-job-portal'),
            'all_items' => __('All Job Locations', 'mj-job-portal'),
            'edit_item' => __('Edit Job Location', 'mj-job-portal'),
            'update_item' => __('Update Job Location', 'mj-job-portal'),
            'add_new_item' => __('Add New Job Location', 'mj-job-portal'),
        ];

        $location_args = [
            'labels' => $location_labels,
            'hierarchical' => true,
            'public' => true,
            'show_admin_column' => true,
            'rewrite' => ['slug' => 'job-location'],
        ];
        register_taxonomy('jobpost_location', ['jobpost'], $location_args);
    }
}
?>