<?php
/**
 * Helper functions for Miller Job Portal
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Calculate years between two dates
 */
function mj_calculate_years($from, $to) {
    if (!$from || !$to) {
        return '';
    }

    try {
        $start = new DateTime($from);
        $end = new DateTime($to);
        $interval = $start->diff($end);
        return $interval->y . ($interval->y === 1 ? ' year' : ' years');
    } catch (Exception $e) {
        return '';
    }
}
?>