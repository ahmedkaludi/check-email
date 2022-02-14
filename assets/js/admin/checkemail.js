/**
 * Show/Hide individual add-on license key input.
 */
( function( $ ) {
	$( document ).ready( function() {
            $(".checkemail-hide").hide();
            var widget = $("#check-email-enable-widget").parent().parent();
            var dbNotifications = $("#check-email-enable-db-notifications").parent().parent();
            if (!$('#check-email-enable-logs').is(":checked")) {
                widget.hide();
                dbNotifications.hide();
            }
            
            $("#checkemail_autoheaders,#checkemail_customheaders").on("change", function(){
                    if ($("#checkemail_autoheaders").is(":checked")){
                            $("#customheaders").hide();
                            $("#autoheaders").show();
                    }
                    if ($("#checkemail_customheaders").is(":checked")){
                            $("#autoheaders").hide();
                            $("#customheaders").show();
                    }
            });
            $('#check-email-enable-logs').on('click', function() {
                if ($(this).is(":checked")) {
                    widget.show();
                    dbNotifications.show();
                } else {
                    widget.hide();
                    dbNotifications.hide();
                }
            });

            var from_name_setting = $("#check-email-from_name").parent().parent();
            var from_email_setting = $("#check-email-from_email").parent().parent();
            if (!$('#check-email-overdide-from').is(":checked")) {
                from_name_setting.hide();
                from_email_setting.hide();
            }
            
            $('#check-email-overdide-from').on('click', function() {
                if ($(this).is(":checked")) {
                    from_name_setting.show();
                    from_email_setting.show();
                } else {
                    from_name_setting.hide();
                    from_email_setting.hide();
                }
            });
	} );

} )(jQuery);
