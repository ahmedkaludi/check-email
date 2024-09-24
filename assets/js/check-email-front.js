document.addEventListener("DOMContentLoaded", function() {
    // functionality for rot13
    // this code for non-anchor email
    if (checkemail_encoder_data.is_enable && checkemail_encoder_data.email_technique == 'rot_13') {
        var emailLinks = document.querySelectorAll('.check-email-encoded-email');
        emailLinks.forEach(function(emailElement) {
            var encodedEmail = emailElement.textContent;
            var decodedEmail = encodedEmail.replace(/[a-zA-Z]/g, function(c) {
                return String.fromCharCode(
                    (c <= 'Z' ? 90 : 122) >= (c = c.charCodeAt(0) + 13) ? c : c - 26
                );
            });
            emailElement.textContent = decodedEmail;
        });
        //  this code for anchor tag
        document.querySelectorAll('a[href^="mailto:"]').forEach(function(emailElement) {
            var encodedEmail = emailElement.getAttribute('href').replace('mailto:', '');
            var decodedEmail = encodedEmail.replace(/[a-zA-Z]/g, function(c) {
                return String.fromCharCode(
                    (c <= 'Z' ? 90 : 122) >= (c = c.charCodeAt(0) + 13) ? c : c - 26
                );
            });
            // emailElement.textContent = decodedEmail;
            emailElement.setAttribute('href', 'mailto:' + decodedEmail);
        });
    }
    if (checkemail_encoder_data.is_enable && checkemail_encoder_data.email_technique == 'css_direction') {
        //  this code for anchor tag
        document.querySelectorAll('a[href^="mailto:"]').forEach(function(encodedEmail) {
            encodedEmail.setAttribute("style", "direction: ltr;");
        });
    }
});