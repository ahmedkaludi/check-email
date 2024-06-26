let currentStep = 1;
        const steps = ck_mail_wizard_data.steps;
        const ck_mail_security_nonce = ck_mail_wizard_data.ck_mail_security_nonce;
        // const steps = [
        //     {
        //         title: 'Step 1 of 4',
        //         heading: 'Configure General Settings',
        //         content: `
        //             <p class="cm_p">Allowed User Roles</p>
        //             <ul class="cm_checklist">
        //                 <li>
        //                     <span>Administrator</span>
        //                     <span class="checkmark">&#10003;</span>
        //                 </li>
        //                 <li>
        //                     <span>Administrator</span>
        //                     <span class="checkmark"><input id="check-email-remove-on-uninstall" type="checkbox" name="check-email-log-core[remove_on_uninstall]" value="true"></span>
        //                 </li>
        //                 <li>
        //                     <span>Save file attachments sent from WordPress</span>
        //                     <span class="checkmark">&#10003;</span>
        //                 </li>
        //                 <li>
        //                     <span>Track when an email is opened</span>
        //                     <span class="checkmark">&#10003;</span>
        //                 </li>
        //                 <li>
        //                     <span>Track when a link in an email is clicked</span>
        //                     <span class="checkmark">&#10003;</span>
        //                 </li>
        //             </ul>
        //             <p class="cm_p">Enable these powerful logging features for more control of your WordPress emails.</p>
        //             <ul class="cm_checklist">
        //                 <li>
        //                     <span>Remove Data on Uninstall?</span>
        //                     <span class="checkmark"><input id="check-email-remove-on-uninstall" type="checkbox" name="check-email-log-core[remove_on_uninstall]" value="true"></span>
        //                 </li>
        //                 <li>
        //                     <span>Save file attachments sent from WordPress</span>
        //                     <span class="checkmark">&#10003;</span>
        //                 </li>
        //                 <li>
        //                     <span>Track when an email is opened</span>
        //                     <span class="checkmark">&#10003;</span>
        //                 </li>
        //                 <li>
        //                     <span>Track when a link in an email is clicked</span>
        //                     <span class="checkmark">&#10003;</span>
        //                 </li>
        //             </ul>
        //         `,
        //     },
        //     {
        //         title: 'Step 2 of 6',
        //         heading: 'SMTP Configuration',
        //         content: 'Configure the SMTP settings for your email.',
        //     },
        //     {
        //         title: 'Step 3 of 6',
        //         heading: 'Email Test',
        //         content: 'Send a test email to ensure the configuration is correct.',
        //     },
        //     {
        //         title: 'Step 4 of 6',
        //         heading: 'Configure Email Logs',
        //         content: `
        //             <p class="cm_p">Enable these powerful logging features for more control of your WordPress emails.</p>
        //             <ul class="cm_checklist">
        //                 <li>
        //                     <span>Store the content for all sent emails</span>
        //                     <span class="checkmark">&#10003;</span>
        //                 </li>
        //                 <li>
        //                     <span>Save file attachments sent from WordPress</span>
        //                     <span class="checkmark">&#10003;</span>
        //                 </li>
        //                 <li>
        //                     <span>Track when an email is opened</span>
        //                     <span class="checkmark">&#10003;</span>
        //                 </li>
        //                 <li>
        //                     <span>Track when a link in an email is clicked</span>
        //                     <span class="checkmark">&#10003;</span>
        //                 </li>
        //             </ul>
        //         `,
        //     }
        // ];

        function cm_showStep(step) {
            const stepContent = document.getElementById('step-content');
            const progressSteps = document.querySelectorAll('.cm_progress div');

            progressSteps.forEach((stepDiv, index) => {
                if (index < step) {
                    stepDiv.classList.add('active');
                } else {
                    stepDiv.classList.remove('active');
                }
            });

            stepContent.innerHTML = `
                <div class="cm_step">${steps[step - 1].title}</div>
                <h2 class="cm_H2">${steps[step - 1].heading}</h2>
                <div><form id="cm_step_form">
                <input type="hidden" name="action" value="check_mail_save_wizard_data" />
                <input type="hidden" name="ck_mail_security_nonce" value="${ck_mail_security_nonce}">
                ${steps[step - 1].content}</form></div>
            `;
            
            document.getElementById('cm_prevBtn').style.display = step === 1 ? 'none' : 'inline-block';
            document.getElementById('cm_nextBtn').innerText = step === steps.length ? 'Finish' : 'Save and Continue →';
        }

        function cm_nextStep() {
            if (currentStep < steps.length) {
                document.getElementById('cm-container-loader').style.display = 'block';
                currentStep++;
                cm_save_wizard();
                cm_showStep(currentStep);
            } else {
                // Finish action
                alert('Setup Complete!');
            }
        }

        function cm_prevStep() {
            if (currentStep > 1) {
                currentStep--;
                cm_showStep(currentStep);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            cm_showStep(currentStep);
        });

        function cm_save_wizard(){
            var t = jQuery("body" ).find("#cm_nextBtn");
            var data = jQuery("body" ).find("#cm_step_form").serialize();
            jQuery.ajax({
              url:ajaxurl,
              method:'post',
              dataType: "json",
              data:data,
              beforeSend: function(response){
                // t.html('Resend<span class="spinner is-active"></span>');
                t.prop('disabled',true);
              },
              success:function(response){
                console.log(response)
                // if (response.status != 200) {
                //   jQuery('.cm_js_error').html(response.message);
                // }else{
                //   jQuery('.cm_js_success').html(response.message);
                //   location.reload();
                // }
              },
              complete:function(response){
                document.getElementById('cm-container-loader').style.display = 'none';
                // t.html('Resend');
                t.prop('disabled',false);
              }               
            });
        }