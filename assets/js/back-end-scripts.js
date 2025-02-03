// Hide next element on radio check
jQuery(document).ready(function($) {
    const versionDependentFields = [ 'uncr_settings[captcha_key_type]', 'uncr_settings[captcha_theme_radio]','uncr_settings[captcha_type_radio]', 'uncr_settings[disable_submit_button]', 'uncr_settings[captcha_language_select]' ];
    $('input[name="uncr_settings[captcha_model_type]"]').change(function() {
        if ('v3' === $(this).val()) {
            // Hide the fields
            versionDependentFields.forEach(function(field) {
                $(`input[name="${field}"]`).closest('tr').hide();
            });
        } else {
          // Show the fields
            versionDependentFields.forEach(function(field) {
                $(`input[name="${field}"]`).closest('tr').show();
            });
        }
    });
    // Check if the v3 radio is checked on page load
    if ('v3' === $('input[name="uncr_settings[captcha_model_type]"]:checked').val()) {
        // Hide the fields
        versionDependentFields.forEach(function(field) {
            $(`input[name="${field}"]`).closest('tr').hide();
        });
    }
});
