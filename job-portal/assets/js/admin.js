/* Admin scripts for Miller Job Portal */
jQuery(document).ready(function($) {
    // Handle export form submission
    $('#mj_export_form').on('submit', function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        window.location.href = '?page=mj_export_applicants&' + formData;
    });

    // Handle filter form submission
    $('#mj_applicant_filter_form').on('submit', function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            url: mjAjax.ajaxurl,
            type: 'POST',
            data: {
                action: 'mj_filter_applicants',
                form_data: formData
            },
            success: function(response) {
                if (response.success) {
                    $('.mj-applicant-filter table tbody').html(response.data.html);
                } else {
                    alert('Error filtering applicants.');
                }
            }
        });
    });
});