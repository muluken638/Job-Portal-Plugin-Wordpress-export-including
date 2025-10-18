<?php
/**
 * Handles cascading address logic (regions, zones, weredas, kebeles)
 */

if (!defined('ABSPATH')) {
    exit;
}

class MJ_Job_Address {
    public static function init() {
        add_action('wp_ajax_mj_get_address_options', [__CLASS__, 'get_address_options']);
        add_action('wp_ajax_nopriv_mj_get_address_options', [__CLASS__, 'get_address_options']);
    }

    public static function get_address_options() {
        $type = $_POST['type'] ?? '';
        $parent = $_POST['parent'] ?? '';

        $address_hierarchy = [
            'region' => [
                'Addis Ababa' => [
                    'zones' => ['Zone 1', 'Zone 2', 'Zone 3'], // Example zones
                ],
                'Amhara' => [
                    'zones' => ['North Gondar', 'South Wollo'],
                ],
            ],
            'zone' => [
                'Zone 1' => [
                    'weredas' => ['Wereda A', 'Wereda B'],
                ],
                'Zone 2' => [
                    'weredas' => ['Wereda C', 'Wereda D'],
                ],
            ],
            'wereda' => [
                'Wereda A' => [
                    'kebeles' => ['Kebele 01', 'Kebele 02'],
                ],
                'Wereda B' => [
                    'kebeles' => ['Kebele 03', 'Kebele 04'],
                ],
            ],
        ];

        $options = [];
        if ($type === 'region') {
            $options = array_keys($address_hierarchy['region']);
        } elseif ($type === 'zone' && isset($address_hierarchy['region'][$parent]['zones'])) {
            $options = $address_hierarchy['region'][$parent]['zones'];
        } elseif ($type === 'wereda' && isset($address_hierarchy['zone'][$parent]['weredas'])) {
            $options = $address_hierarchy['zone'][$parent]['weredas'];
        } elseif ($type === 'kebele' && isset($address_hierarchy['wereda'][$parent]['kebeles'])) {
            $options = $address_hierarchy['wereda'][$parent]['kebeles'];
        }

        wp_send_json_success(['options' => $options]);
    }
}
add_action('init', ['MJ_Job_Address', 'init']);
?>