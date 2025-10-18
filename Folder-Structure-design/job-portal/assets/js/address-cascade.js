/* JavaScript for cascading address dropdowns in Miller Job Portal */
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

        if (!nextType || !parent) {
            // Disable and clear subsequent dropdowns
            var types = ['zone', 'wereda', 'kebele'];
            var startIndex = types.indexOf(nextType);
            for (var i = startIndex; i < types.length; i++) {
                var $select = $('#jobapp_birth_place_' + types[i]);
                $select.prop('disabled', true).html('<option value="">Select ' + types[i].charAt(0).toUpperCase() + types[i].slice(1) + '</option>');
            }
            return;
        }

        var $nextSelect = $('#jobapp_birth_place_' + nextType);
        $nextSelect.prop('disabled', true).html('<option value="">Select ' + nextType.charAt(0).toUpperCase() + nextType.slice(1) + '</option>');

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
            },
            error: function() {
                alert('Error loading ' + nextType + ' options.');
            }
        });
    });
});