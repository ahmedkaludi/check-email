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

    function activatePlugin(url) {
      $.ajax({
        async: true,
        type: "GET",
        dataType: "html",
        url: url,
        success: function () {
          location.reload();
        },
      });
    }

    // Install plugins actions
    $("#install_wp_smtp").on("click", (event) => {
      event.preventDefault();
      const current = $(event.currentTarget);
      const plugin_slug = current.data("slug");
      const plugin_action = current.data("action");
      const activate_url = current.data("activation_url");

      // Now let's disable the button and show the action text
      current.attr("disabled", true);

      if ("install" === plugin_action) {
        current.addClass("updating-message");

        const args = {
          slug: plugin_slug,
          success: (response) => {
            current.html("Activating plugin");

            activatePlugin(response.activateUrl);
          },
          error: (response) => {
            current.removeClass("updating-message");
            jQuery("#install_wp_smtp_info p").html(response.errorMessage);
            jQuery("#install_wp_smtp_info").addClass("notice-error notice");
          },
        };

        wp.updates.installPlugin(args);
      } else if ("activate" === plugin_action) {
        activatePlugin(activate_url);
      }
    });
    
    /**
     * On click of Trigger Data option display link to upgrade to pro
     * @since 1.0.11
     * */
    
    $(document).on('click', '#check-email-enable-smtp', function(e){
      if($(this).is(':checked')){
        $('.check_email_all_smtp').show();
      }else{
        $('.check_email_all_smtp').hide();
      }
      chec_email_manage_display_state();
    });
    $(document).on('click', '#check_mail_resend_btn', function(e){
      t = jQuery(this);
      jQuery('.cm_js_error').html('');
      jQuery('.cm_js_success').html('');
      var ajaxurl = jQuery('#cm_ajax_url').val();
      var data = jQuery("#check-mail-resend-form" ).serialize();
      jQuery.ajax({
        url:ajaxurl,
        method:'post',
        dataType: "json",
        data:data,
        beforeSend: function(response){
          t.html('Resend<span class="spinner is-active"></span>');
          t.prop('disabled',true);
        },
        success:function(response){
          if (response.status != 200) {
            jQuery('.cm_js_error').html(response.message);
          }else{
            jQuery('.cm_js_success').html(response.message);
            location.reload();
          }
        },
        complete:function(response){
          t.html('Resend');
          t.prop('disabled',false);
        }               
      });
    });

    function cm_import_data_in_chunks(ajaxurl,data,t){
      jQuery.ajax({
        url:ajaxurl,
        method:'post',
        dataType: "json",
        data:data,
        beforeSend: function(response){
          t.html('Import<span class="spinner is-active"></span>');
          t.prop('disabled',true);
        },
        success:function(response){
          if (response.status != 200) {
            t.parents('.cm_js_migration').find('.cm_js_error').html(response.message);
          }else{
            t.parents('.cm_js_migration').find('.cm_js_success').html(response.message);
          }
        },
        complete:function(response){
          t.html('Import');
          t.prop('disabled',false);
        }

      });
    }

    $(".check-mail-import-plugins").on("click", function(e){
      e.preventDefault();
      jQuery('.cm_js_error').html('');
      jQuery('.cm_js_success').html('');
      var t = $(this);
      var plugin_name = $(this).attr('data-id');
      var ajaxurl = jQuery('#cm_ajax_url').attr('data');                    
      var ck_mail_security_nonce = jQuery('#cm_security_nonce').attr('data');                    
      data = { action:"check_mail_import_plugin_data", plugin_name:plugin_name, ck_mail_security_nonce:ck_mail_security_nonce};
      cm_import_data_in_chunks(ajaxurl,data,t);
    });

    var forward_email_to = $(".check_email_forward_to");
    var forward_email_cc = $(".check_email_forward_cc");
    var forward_email_bcc = $(".check_email_forward_bcc");
    if (!$("#check-email-forward_email").is(":checked")) {
      forward_email_to.hide();
      forward_email_cc.hide();
      forward_email_bcc.hide();
    }

    $("#check-email-forward_email").on("click", function () {
      if ($(this).is(":checked")) {
        forward_email_to.show();
        forward_email_cc.show();
        forward_email_bcc.show();
      } else {
        forward_email_to.hide();
        forward_email_cc.hide();
        forward_email_bcc.hide();
      }
    });
    
    var retention_amount = $(".check_email_retention_amount");
    if (!$("#check-email-is_retention_amount_enable").is(":checked")) {
      retention_amount.hide();
    }
    

    $("#check-email-is_retention_amount_enable").on("click", function () {
      if ($(this).is(":checked")) {
        retention_amount.show();
      } else {
        retention_amount.hide();
      }
    });


    var period = $(".check_email_log_retention_period");
    var days = $(".check_email_log_retention_period_in_days");
    if (!$("#check-email-is_retention_period_enable").is(":checked")) {
      period.hide();
      days.hide();
    }

    $("#check-email-is_retention_period_enable").on("click", function () {
      if ($(this).is(":checked")) {
        period.show();
        $('#check-email-log_retention_period').trigger('change');
      } else {
        period.hide();
        days.hide();
      }
    });
    

    if ($("#check-email-log_retention_period").val() != 'custom_in_days') {
      days.hide();
    }
    $("#check-email-log_retention_period").on("change", function () {
      if ($(this).val() == 'custom_in_days') {
        days.show();
      } else {
        days.hide();
      }
    });

    $(".check_main_js_display_checkbox").on("click", function () {
      if ($(this).is(":checked")) {
        $(this).next('.check_mail_js_hidden_display').val(1);
      } else {
        $(this).next('.check_mail_js_hidden_display').val(0);
      }
    });
    $(".check_main_js_error_tracking").on("click", function () {
      if ($(this).is(":checked")) {
        $(this).next('.check_main_js_error_tracking_hidden').val(1);
      } else {
        $(this).next('.check_main_js_error_tracking_hidden').val(0);
      }
    });
    $(".check-email-enable-widget_checkbox").on("click", function () {
      if ($(this).is(":checked")) {
        $(this).next('.check-email-enable-widget_display').val(1);
      } else {
        $(this).next('.check-email-enable-widget_display').val(0);
      }
    });
  

  });
  
  
  $(document).on('click', '.check_email_mailer_type', function(e){  
    $(".ck_radio_selected").removeClass('ck_radio_selected');
    if($(this).val() == 'outlook'){
      $('#check-email-outllook').show();
      $('#check-email-smtp-form').hide();
      $(this).parents('.ce_radio-label').addClass('ck_radio_selected');
    }
    if($(this).val() == 'smtp' || $(this).val() == 'gmail'){
      $('#check-email-outllook').hide();
      $('#check-email-smtp-form').show();
      $(this).parents('.ce_radio-label').addClass('ck_radio_selected');
    }
  });
  $(document).on('click', '#check-email-email-encode-options-is_enable', function(e){
    if ($(this).is(":checked")) {
      $('.check-email-etr').show();
    } else {
      $('.check-email-etr').hide();
    }
  });

  $(document).on('click', '#check_email_remove_outlook', function(e){
    t = jQuery(this);
    var ajaxurl = checkemail_data.ajax_url;
    var nonce = checkemail_data.ck_mail_security_nonce;
    jQuery.ajax({
      url:ajaxurl,
      method:'post',
      dataType: "json",
      data:{action:"check_email_remove_outlook",'ck_mail_security_nonce':nonce},
      beforeSend: function(response){
      },
      success:function(response){
        if (response.status == 200) {
          location.reload();
        }
      },
      complete:function(response){
      }               
    });
  });

  $("#check_mail_request_uri").on("click", function () {
    check_email_copy_code();
  })

  function check_email_copy_code() {
      var copyText = document.getElementById("check_mail_request_uri");

      // Select the text field
      copyText.select();

      // Copy the text inside the text field
      navigator.clipboard.writeText(copyText.value);

      // Alert the copied text
      $("#check_mail_copy_text").html("Copied!");
  }

  function chec_email_manage_display_state() {
    if($('#check-email-enable-smtp').is(':checked')){
      var check_email_mailer_type = $(".check_email_mailer_type:checked").val();
      if(check_email_mailer_type == 'outlook'){
        $('#check-email-outllook').show();
        $('#check-email-smtp-form').hide();
      }
      if(check_email_mailer_type == 'smtp' || check_email_mailer_type == 'gmail'){
        $('#check-email-outllook').hide();
        $('#check-email-smtp-form').show();
      }
    }
  }
  $(document).on('click', '#check-email-email-notify-options-is_enable', function(e){
    if($(this).is(':checked')){
      $('#ck-notify-table-id').show();
    }else{
      $('#ck-notify-table-id').hide();
    }
  });
  $(document).on('click', '#check-email-notify-by-sms-enable', function(e){
    if($(this).is(':checked')){
      $('.check-email-twilio').show();
    }else{
      $('.check-email-twilio').hide();
    }
  });
  $(document).on('click', '.checkmail_trigger', function(e){
    parent_tr = $(this).parents('tr')
    if($(this).is(':checked')){
      parent_tr.find('.checkmail_trigger_counts').show();
      $(this).next('label').hide();
    }else{
      parent_tr.find('.checkmail_trigger_counts').hide();
      $(this).next('label').show();
    }
  });

  $("#ck_email_analyze").on("click", function(e){
    e.preventDefault();
    jQuery('.cm_js_error').html('');
    jQuery('.cm_js_success').html('');
    var t = $(this);
    const loader = document.getElementById('ck_loader');
    var ajaxurl = checkemail_data.ajax_url;
    var ck_mail_security_nonce = checkemail_data.ck_mail_security_nonce;
    data = { action:"check_email_analyze", ck_mail_security_nonce:ck_mail_security_nonce};
    jQuery.ajax({
      url:ajaxurl,
      method:'post',
      dataType: "json",
      data:data,
      beforeSend: function(response){
        loader.style.display = 'block';
        t.hide();
      },
      success:function(response){
        if (response.is_error == 0) {
          email_result = response.previous_email_result;
          blocklist = response.blocklist;
          previous_spam_score = response.previous_spam_score;
          links = response.data.links;
          authenticated = response.data.authenticated;
          report = response.data.spamcheck_result.report;
          scores = response.data.spamcheck_result.rules;
          data = response.data;
          var totalScore = 0;
          var final_total = 0;

          wrong_icon_svg = `<svg viewBox="0 0 32 32" height="50px" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title>cross-circle</title> <desc>Created with Sketch Beta.</desc> <defs> </defs> <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage"> <g id="Icon-Set-Filled" sketch:type="MSLayerGroup" transform="translate(-570.000000, -1089.000000)" fill="#fa0000"> <path d="M591.657,1109.24 C592.048,1109.63 592.048,1110.27 591.657,1110.66 C591.267,1111.05 590.633,1111.05 590.242,1110.66 L586.006,1106.42 L581.74,1110.69 C581.346,1111.08 580.708,1111.08 580.314,1110.69 C579.921,1110.29 579.921,1109.65 580.314,1109.26 L584.58,1104.99 L580.344,1100.76 C579.953,1100.37 579.953,1099.73 580.344,1099.34 C580.733,1098.95 581.367,1098.95 581.758,1099.34 L585.994,1103.58 L590.292,1099.28 C590.686,1098.89 591.323,1098.89 591.717,1099.28 C592.11,1099.68 592.11,1100.31 591.717,1100.71 L587.42,1105.01 L591.657,1109.24 L591.657,1109.24 Z M586,1089 C577.163,1089 570,1096.16 570,1105 C570,1113.84 577.163,1121 586,1121 C594.837,1121 602,1113.84 602,1105 C602,1096.16 594.837,1089 586,1089 L586,1089 Z" id="cross-circle" sketch:type="MSShapeGroup"> </path> </g> </g> </g></svg>`;

          yes_icon_svg = `<svg viewBox="0 0 32 32" height="50px" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title>checkmark-circle</title> <desc>Created with Sketch Beta.</desc> <defs> </defs> <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage"> <g id="Icon-Set-Filled" sketch:type="MSLayerGroup" transform="translate(-102.000000, -1141.000000)" fill="#038608"> <path d="M124.393,1151.43 C124.393,1151.43 117.335,1163.73 117.213,1163.84 C116.81,1164.22 116.177,1164.2 115.8,1163.8 L111.228,1159.58 C110.85,1159.18 110.871,1158.54 111.274,1158.17 C111.677,1157.79 112.31,1157.81 112.688,1158.21 L116.266,1161.51 L122.661,1150.43 C122.937,1149.96 123.548,1149.79 124.027,1150.07 C124.505,1150.34 124.669,1150.96 124.393,1151.43 L124.393,1151.43 Z M118,1141 C109.164,1141 102,1148.16 102,1157 C102,1165.84 109.164,1173 118,1173 C126.836,1173 134,1165.84 134,1157 C134,1148.16 126.836,1141 118,1141 L118,1141 Z" id="checkmark-circle" sketch:type="MSShapeGroup"> </path> </g> </g> </g></svg>`;
          
          warning_icon_svg = `<svg viewBox="0 0 16 16" height="50px" xmlns="http://www.w3.org/2000/svg" fill="none"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path fill="#f9bc39" fill-rule="evenodd" d="M0 8a8 8 0 1116 0A8 8 0 010 8zm8-4a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 018 4zm0 6a1 1 0 100 2h.007a1 1 0 100-2H8z" clip-rule="evenodd"></path></g></svg>`;

          // Iterate over the array and sum the scores
          $.each(scores, function(index, item) {
              totalScore += parseFloat(item.score); // Convert score to a float and add it to the total
          });
          $("#ck_top_subject").html('Subject : '+data.html_tab.subject);
          $("#ck_email_date").html('Received : '+ck_timeAgo(data.html_tab.date));
          
          html_tab = `<div class="ck-accordion">
                <div class="ck-accordion-header" onclick="ck_toggleAccordion(this)">
                  <div class="ck_icon_with_text">
                      <span class="">`+yes_icon_svg+`</span>
                      <span class="ck_header_span">Click here to view your message</span>
                  </div>
                </div>
                <div class="ck-accordion-content">
                    <p><strong>From : </strong>`+data.html_tab.from+`</p>
                    <p><strong>Email : </strong>`+data.html_tab.email+`</p>
                    <p><strong>Subject : </strong>`+data.html_tab.subject+`</p>
                    <p><strong>Date : </strong>`+data.html_tab.date+`</p>

                    <!-- Child Accordion -->
                    <div class="ck-child-accordion">
                        <div class="ck-child-accordion-header" onclick="ck_toggleAccordion(this)">
                            HTML version
                        </div>
                        <div class="ck-child-accordion-content">
                            <p>`+data.html_tab.body+`</p>
                        </div>
                    </div>
                    <div class="ck-child-accordion">
                        <div class="ck-child-accordion-header" onclick="ck_toggleAccordion(this)">
                            Text version
                        </div>
                        <div class="ck-child-accordion-content">
                            <p><pre>`+data.html_tab.body+`</pre></p>
                        </div>
                    </div>
                    <div class="ck-child-accordion">
                        <div class="ck-child-accordion-header" onclick="ck_toggleAccordion(this)">
                            Source
                        </div>
                        <div class="ck-child-accordion-content">
                            <p><pre>`+data.html_tab.source+`</pre></p>
                        </div>
                    </div>
                </div>
            </div>`;
            if (totalScore.toFixed(1) > 0) {
              spam_icon = yes_icon_svg;
              spam_text = "SpamAssassin likes you";
              spam_score = 2.5;
            } else if (totalScore.toFixed(1) < 0 && totalScore.toFixed(1) > -5) {
              spam_icon = warning_icon_svg;
              spam_text = "SpamAssassin warned you to <strong>improve</strong> your spam score";
              spam_score = 1.5;
            } else{
              spam_icon = wrong_icon_svg;
              spam_text = "SpamAssassin <strong>don't</strong> likes you";
              spam_score = 0;
            }
            html_tab+=`<div class="ck-accordion"><div class="ck-accordion-header" onclick="ck_toggleAccordion(this)"><div class="ck_icon_with_text"><span class="">`+spam_icon+`</span>
                    <span class="ck_header_span">`+spam_text+`</span><span class="ck_score_span">Score : `+totalScore.toFixed(1)+`</span></div></div><div class="ck-accordion-content">
                    <p><i>The famous spam filter SpamAssassin. </i> <strong>Score:`+totalScore.toFixed(1)+`</strong></p><p><i>A score below -5 is considered spam.</i></p><hr/><p><pre>`+report+`</pre></p></div></div>`;
            email_result_html ="";
            if (email_result.email_valid) {
              email_result_html +='<div class="ck-card">\
                    <h4>Format <span class="ck-status">Valid</span></h4>\
                    <p>This email address has the correct format and is not gibberish.</p>\
                </div>';
            }else{
              email_result_html +='<div class="ck-card">\
                    <h4>Format <span class="ck-status" style="background-color:pink;color:red;">Invalid</span></h4>\
                    <p>This email address is not correct.</p>\
                </div>';
            }
            if (email_result.email_valid) {
              email_result_html +='<div class="ck-card">\
              <h4>Type <span class="ck-status" style="background-color: #cce5ff; color: #004085;">Professional</span></h4>\
                  <p>The domain name is not used for webmails or for creating temporary email addresses.</p>\
              </div>';
            }
            if (email_result.dns_valid) {
              email_result_html +='<div class="ck-card">\
                    <h4>Server status <span class="ck-status">Valid</span></h4>\
                    <p>MX records are present for the domain and we can connect to the SMTP server these MX records point to.</p>\
                </div>';
            }else{
              email_result_html +='<div class="ck-card">\
                    <h4>Server status <span class="ck-status" style="background-color:pink;color:red">Invalid</span></h4>\
                    <p>MX records are not present for the domain, or we cannot connect to the SMTP server</p>\
                </div>';
            }
            if (email_result.dns_valid) {
              email_result_html +='<div class="ck-card">\
                    <h4>Email status<span class="ck-status">Valid</span></h4>\
                    <p>This email address exists and can receive emails.</p>\
                </div>';
            }else{
              email_result_html +='<div class="ck-card">\
                    <h4>Email status<span class="ck-status" style="background-color:pink;color:red">Invalid</span></h4>\
                    <p>This email address can not receive emails.</p>\
                </div>';
            }
            html_tab+=`<div class="ck-accordion"><div class="ck-accordion-header" onclick="ck_toggleAccordion(this)"><div class="ck_icon_with_text"><span class="">`+yes_icon_svg+`</span>
                    <span class="ck_header_span">Email validation result</span></div></div><div class="ck-accordion-content">`+email_result_html+`</div></div>`;

            html_content = "";
            vuln = "";
            vuln_count = 0;
            $.each( authenticated, function( key, value ) {
              style ='';
              if (!value.status) {
                vuln_count +=1;
                style ='background-color:pink;color:red;';
                vuln +='<span class="ck-status" style="'+style+'">'+key+'</span>';
              }
              html_content +='<div class="ck-card">\
                    <h4><span class="ck-status" style="'+style+'">'+key+'</span></h4>\
                    <p style="color:blue; overflow-wrap:break-word;">'+value.message+'</p>\
                </div>';
              });
              if (vuln_count == 0) {
                auth_icon = yes_icon_svg;
                auth_text = "You're properly authenticated";
                auth_score = 2.5;
              } else if (vuln_count > 0 && vuln_count < 3) {
                auth_icon = warning_icon_svg;
                auth_text = "You're <strong>not</strong> properly authenticated need some improvement";
                auth_score = 1.5;
              } else if (vuln_count >= 3) {
                auth_icon = wrong_icon_svg;
                auth_text = "You're <strong>not</strong> properly authenticated";
                auth_score = 0;
              }
            if (vuln) {
              html_content +='<div class="ck-card">\
                    <h4>Summary'+vuln+'</h4>\
                    <p style="color:red; overflow-wrap: break-word;"><strong>Vulnerabilities detected :</strong>Recommendation: Address the identified vulnerabilities to improve DNS security.</p>\
                </div>';
            }

            html_tab+=`<div class="ck-accordion"><div class="ck-accordion-header" onclick="ck_toggleAccordion(this)"><div class="ck_icon_with_text"><span class="">`+auth_icon+`</span><span class="ck_header_span">`+auth_text+`</span></div></div><div class="ck-accordion-content">`+html_content+`</div></div>`;
            blocklist_html = "";
            block_count = 0;
            $.each( blocklist, function( kue, value ) {
              if (value.status) {
                block_count +=1;
                blocklist_html+=` <div class="ck-card" style="display:inline-flex; margin:5px; padding:5px; width:30%;"><h4><span class="ck-status" style="color:red; background-color:pink;">Listed : `+value.ip+`</span></h4></div>`;
                }else{
                  blocklist_html+=` <div class="ck-card" style="margin:5px; padding:5px;display:inline-flex; width:30%;"><h4><span class="ck-status" style="color:green;">Not Listed : `+value.ip+`</span></h4></div>`;
              }
            })
            if (block_count == 0) {
              block_icon = yes_icon_svg;
              block_text = "You're not <strong>blocklisted</strong>";
              block_score = 2.5;
            } else if (block_count > 0 && block_count <= 12) {
              block_icon = warning_icon_svg;
              block_text = "You're <strong>blocklisted</strong> need some improvement";
              block_score = 1.5;
            } else if (block_count > 12) {
              block_icon = wrong_icon_svg;
              block_text = "You're <strong>blocklisted</strong>";
              block_score = 0;
            }
            html_tab+=`<div class="ck-accordion"><div class="ck-accordion-header" onclick="ck_toggleAccordion(this)"><div class="ck_icon_with_text"><span class="">`+block_icon+`</span>
                    <span class="ck_header_span">`+block_text+`</span></div></div><div class="ck-accordion-content"><i><strong>Matches your server IP address (`+response.ip_address+`) against 24 of the most common IPv4 blocklists.</strong></i><hr/>`+blocklist_html+`</div></div>`;
            links_html = "";
            link_count = 0;
            $.each( links, function( link, value ) {
                if (value.status > 200) {
                  link_count +=1;
                }
                links_html+=` <p><strong>Status:`+value.status+`</strong> `+link+`</p>`;
            })
            if (link_count == 0) {
              link_icon = yes_icon_svg;
              link_text = "No broken links";
              link_score = 2.5;
            } else {
              link_icon = warning_icon_svg;
              link_text = "<strong>Found</strong> broken links";
              link_score = 1.5;
            }
            html_tab+=`<div class="ck-accordion"><div class="ck-accordion-header" onclick="ck_toggleAccordion(this)"><div class="ck_icon_with_text"><span class="">`+link_icon+`</span>
                    <span class="ck_header_span">`+link_text+`</span></div></div><div class="ck-accordion-content"><i><strong>Checks if your email contains broken links.</strong></i><hr/>`+links_html+`</div></div>`;

          $("#ck_email_analyze_result").html(html_tab);
          $(".ck_score_cls").show();
          $(".ck_subject_bar").show();
          final_total = (link_score + auth_score+spam_score+block_score);
          final_score_text = 'Wow! Perfect';
          if (final_total == 10) {
            final_score_text = "Hey you need to improve";
            final_score_color = "#038608";
          }else if (final_total == 9) {
            final_score_text = "Good";
            final_score_color = "#038608";
          }else if (final_total < 9 && final_total > 5) {
            final_score_text = "Need Improvement";
            final_score_color = "#f9bc39";
          } else if (final_total <= 5) {
            final_score_text = "Need Fixing Urgently";
            final_score_color = "#fa0000";
          }
          $("#ck_email_analyze_result").html(html_tab);
          $(".ck_score").css("background-color", final_score_color);
          $("#ck_score_text").text(final_score_text);
          $(".ck_score").html(final_total+' / 10');
        }else{
          html_content ='<p style="color:red;">'+response.error+'</p>';
          $("#ck_email_analyze_result").html(html_content);
          t.show();
        }
        loader.style.display = 'none';
      },
      complete:function(response){
        loader.style.display = 'none';
      }

    });
  });

  function ck_timeAgo(dateString) {
    let date = new Date(dateString); // Convert to Date object
    let now = new Date(); // Current date

    let diffInSeconds = Math.floor((now - date) / 1000); // Difference in seconds
    let interval;

    if (diffInSeconds < 0) return "In the future"; // Handle future dates

    // Define intervals
    let intervals = [
        { label: "year", seconds: 31536000 },
        { label: "month", seconds: 2592000 },
        { label: "day", seconds: 86400 },
        { label: "hour", seconds: 3600 },
        { label: "minute", seconds: 60 },
        { label: "second", seconds: 1 }
    ];

    // Find the correct interval
    for (let i = 0; i < intervals.length; i++) {
        interval = Math.floor(diffInSeconds / intervals[i].seconds);
        if (interval > 0) {
            return `${interval} ${intervals[i].label}${interval !== 1 ? "s" : ""} ago`;
        }
    }
    return "Just now"; // Default fallback
  }

})(jQuery);

