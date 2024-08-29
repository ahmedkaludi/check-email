jQuery(document).ready(function ($) {
    $('#my-network-settings-form').on('submit', function (e) {
        e.preventDefault();
        data = $(this).serialize();
        data += '&action=update_network_settings';
        data += '&nonce=' + network_admin_setting.nonce;
        $.ajax({
            type: "POST",
            url: network_admin_setting.ajaxUrl,
            dataType: "json",
            data: data,
            success: function (response) {
                if (response.success) {
                    alert('Settings saved successfully!');
                } else {
                    alert('There was an error saving the settings.');
                }
            },
            error: function (response) {
                console.log(response)
            }
        });
    });
    var check_email_smtp_class = $(".check_email_smtp_class");
    if (!$("#check-email-log-global-enable-smtp").is(":checked")) {
        check_email_smtp_class.hide();
    }
    $(document).on('click', '#check-email-log-global-enable-smtp', function (e) {
        if ($(this).is(':checked')) {
            $('.check_email_smtp_class').show();
        } else {
            $('.check_email_smtp_class').hide();
        }
    });
    $(document).on('click', '#check-email-log-global-enable_global', function (e) {
        if ($(this).is(':checked')) {
            $('#check-email-global-smtp-form').show();
        } else {
            $('#check-email-global-smtp-form').hide();
        }
    });

    var cm_global_forward = $(".cm_global_forward");
    if (!$("#check-email-global-forward_email").is(":checked")) {
        cm_global_forward.hide();
    }

    $("#check-email-global-forward_email").on("click", function () {
        if ($(this).is(":checked")) {
            cm_global_forward.show();
        } else {
            cm_global_forward.hide();
        }
    });

    var cm_global_override = $(".cm_global_override");
    if (!$("#check-email-global-override_emails_from").is(":checked")) {
        cm_global_override.hide();
    }

    $("#check-email-global-override_emails_from").on("click", function () {
        if ($(this).is(":checked")) {
            cm_global_override.show();
        } else {
            cm_global_override.hide();
        }
    });
});
