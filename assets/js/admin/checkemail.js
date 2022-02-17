/**
 * Show/Hide individual add-on license key input.
 */
(function ($) {
  $(document).ready(function () {
    $(".checkemail-hide").hide();
    var widget = $("#check-email-enable-widget").parent().parent();
    var dbNotifications = $("#check-email-enable-db-notifications")
      .parent()
      .parent();
    if (!$("#check-email-enable-logs").is(":checked")) {
      widget.hide();
      dbNotifications.hide();
    }

    $("#checkemail_autoheaders,#checkemail_customheaders").on(
      "change",
      function () {
        if ($("#checkemail_autoheaders").is(":checked")) {
          $("#customheaders").hide();
          $("#autoheaders").show();
        }
        if ($("#checkemail_customheaders").is(":checked")) {
          $("#autoheaders").hide();
          $("#customheaders").show();
        }
      }
    );
    $("#check-email-enable-logs").on("click", function () {
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
    if (!$("#check-email-overdide-from").is(":checked")) {
      from_name_setting.hide();
      from_email_setting.hide();
    }

    $("#check-email-overdide-from").on("click", function () {
      if ($(this).is(":checked")) {
        from_name_setting.show();
        from_email_setting.show();
      } else {
        from_name_setting.hide();
        from_email_setting.hide();
      }
    });

    //oneclick install wp-smtp
    var elm = jQuery("#install_wp_smtp");
    elm.on("click", (e) => {
      e.preventDefault();

      elm.addClass("updating-message");
      elm.html("Installing plugin");

      plugin_link = elm.attr("href");
      jQuery.ajax({
        method: "POST",
        url: ch_em_ajax_url.ajax_url,
        data: {
          action: "oneclick_smtp_install",
          _ajax_nonce: ch_em_ajax_url.ch_em_nonce,
          slug: plugin_link.substring(
            plugin_link.indexOf("//") + 2,
            plugin_link.indexOf("?")
          ),
        },
        success: function (res) {
          if (res["success"]) {
            elm.html("Activating plugin");
            activate_plugin(
              plugin_link.substring(
                plugin_link.indexOf("//") + 2,
                plugin_link.indexOf("?")
              )
            );
          }
          if ( false == res["success"] ) {
            elm.html("Install & Activate");
            elm.removeClass("updating-message");
            jQuery("#install_wp_smtp_info p").html(res['data']["errorMessage"]);
            jQuery("#install_wp_smtp_info").addClass("notice-error notice");
          }
          if (res["error"]) {
            elm.html("Install & Activate");
            elm.removeClass("updating-message");
            jQuery("#install_wp_smtp_info p").html($res["data"]["errorMessage"]);
            jQuery("#install_wp_smtp_info").addClass("notice-error notice");
          }
        },
      });
    });

    //oneclick activate wp-smtp
    function activate_plugin(slug) {
      jQuery.ajax({
        method: "POST",
        url: ch_em_ajax_url.ajax_url,
        data: {
          action: "oneclick_smtp_activate",
          _ajax_nonce: ch_em_ajax_url.ch_em_nonce,
          slug: slug,
        },
        success: function (res) {
          if (res["success"]) {
            elm.html("Plugin installed & active");
            elm.removeClass("updating-message").addClass("button-disabled");
            jQuery("#install_wp_smtp_info p").html(res['data']["message"]);
            jQuery("#install_wp_smtp_info").addClass("notice-success notice");
          }

          if (false == res["success"]) {
            elm.html("Install & Activate");
            elm.removeClass("updating-message");
            jQuery("#install_wp_smtp_info p").html(res["data"]["errorMessage"]);
            jQuery("#install_wp_smtp_info").addClass("notice-error notice");
          }
        },
      });
    }
  });
})(jQuery);
