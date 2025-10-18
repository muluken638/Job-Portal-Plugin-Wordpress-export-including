/* Public scripts for Miller Job Portal */
jQuery(document).ready(function($) {
    // Job form validation
    $('.mj-job-form form').on('submit', function(e) {
        var $form = $(this);
        var $title = $form.find('#job_title');
        var $description = $form.find('#job_description');

        if (!$title.val()) {
            alert('Please enter a job title.');
            e.preventDefault();
            return;
        }

        if (!$description.val()) {
            alert('Please enter a job description.');
            e.preventDefault();
            return;
        }
    });

    // Applicant filter form validation
    $('.mj-applicant-filter form').on('submit', function(e) {
        var $form = $(this);
        var $dateFrom = $form.find('#date_from');
        var $dateTo = $form.find('#date_to');

        if ($dateFrom.val() && $dateTo.val() && $dateFrom.val() > $dateTo.val()) {
            alert('Date From cannot be later than Date To.');
            e.preventDefault();
            return;
        }
    });
});