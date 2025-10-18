<?php
/**
 * Reusable template for cascading address fields in Miller Job Portal
 */
if (!defined('ABSPATH')) {
    exit;
}
?>
<div class="mj-address-fields">
    <p>
        <label for="jobapp_birth_place_state"><?php _e('Region', 'mj-job-portal'); ?></label>
        <select name="jobapp_birth_place_state" id="jobapp_birth_place_state" class="mj-address-field" data-type="region">
            <option value=""><?php _e('Select Region', 'mj-job-portal'); ?></option>
            <?php
            // This will be populated dynamically via AJAX
            $regions = ['Addis Ababa', 'Amhara']; // Example data, ideally fetched from MJ_Job_Address
            foreach ($regions as $region) {
                echo '<option value="' . esc_attr($region) . '" ' . selected($meta['jobapp_birth_place_state'][0] ?? '', $region, false) . '>' . esc_html($region) . '</option>';
            }
            ?>
        </select>
    </p>
    <p>
        <label for="jobapp_birth_place_zone"><?php _e('Zone', 'mj-job-portal'); ?></label>
        <select name="jobapp_birth_place_zone" id="jobapp_birth_place_zone" class="mj-address-field" data-type="zone" disabled>
            <option value=""><?php _e('Select Zone', 'mj-job-portal'); ?></option>
        </select>
    </p>
    <p>
        <label for="jobapp_birth_place_wereda"><?php _e('Wereda', 'mj-job-portal'); ?></label>
        <select name="jobapp_birth_place_wereda" id="jobapp_birth_place_wereda" class="mj-address-field" data-type="wereda" disabled>
            <option value=""><?php _e('Select Wereda', 'mj-job-portal'); ?></option>
        </select>
    </p>
    <p>
        <label for="jobapp_birth_place_kebele"><?php _e('Kebele', 'mj-job-portal'); ?></label>
        <select name="jobapp_birth_place_kebele" id="jobapp_birth_place_kebele" class="mj-address-field" data-type="kebele" disabled>
            <option value=""><?php _e('Select Kebele', 'mj-job-portal'); ?></option>
        </select>
    </p>
</div>
<script>
jQuery(document).ready(function($) {
    $('.mj-address-field').on('change', function() {
        var $this = $(this);
        var type = $this.data('type');
        var parent = $this.val();
        var nextType = {
            'region': 'zone',
            'zone': 'wereda',
            'wereda': 'kebele'
        }[type];

        if (!nextType || !parent) return;

        var $nextSelect = $('#jobapp_birth_place_' + nextType);
        $nextSelect.prop('disabled', true).html('<option value=""><?php _e('Select ' + nextType.charAt(0).toUpperCase() + nextType.slice(1), 'mj-job-portal'); ?></option>');

        $.ajax({
            url: mjAjax.ajaxurl,
            type: 'POST',
            data: {
                action: 'mj_get_address_options',
                type: nextType,
                parent: parent
            },
            success: function(response) {
                if (response.success && response.data.options) {
                    $.each(response.data.options, function(i, option) {
                        $nextSelect.append('<option value="' + option + '">' + option + '</option>');
                    });
                    $nextSelect.prop('disabled', false);
                }
            }
        });
    });
});
</script>
?>