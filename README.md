# Check & Log Email - Easy Email Testing & Mail logging
Contributors: checkemail  
Tags: logging, email, email log, smtp, log  
Requires at least: 5.0  
Tested up to: 6.6  
Requires PHP: 5.6.20  
Stable tag: 2.0.3  
License: GPLv3 or later  
License URI: http://www.gnu.org/licenses/gpl-3.0.html  

Check & Log email allows you to test if your website is correctly sending emails . Overriding of email headers and carbon copying to another address.

### Description

In need of a tool that allows you to easily log and view all emails sent from WordPress? Check & Log might be just the solution for that. 
This WordPress plugin helps you store sent emails for auditing purposes, as well as debug email related problems in your WordPress site. Works best on eCommerce websites that have been created with WooCommerce or Easy Digital Downloads.

[Home](https://check-email.tech/) | [Help & Tech Support](https://check-email.tech/contact/) | [Documentation](https://check-email.tech/docs/) | [Premium Features](https://check-email.tech/pricing/#pro-feature)

### New Features of the plugin Check & Log Email 2.0

- <strong>Export Logs</strong> - It allows to export email logs in multiple file format with date range and status. 
- <strong>More Fields</strong> - Such as Cc, Bcc, Host IP, Reply To etc. 
- <strong>Log Retention Period</strong> - It allows to deletes old emails when a certain amount of logs and time has passed. 
- <strong>Print or save as PDF</strong> - You can print the email log, or save it as a PDF. 
- <strong>Resend email</strong> - This feature allow you to modify email and resend email. 
- <strong>Forward Email</strong> - By using this feature you can automatically forward a copy of all emails sent by WordPress to other email addresses <a href=" https://check-email.tech/docs/knowledge-base/forward-email-option-in-the-check-log-email-plugin/">Learn More</a>. 
- <strong>Easy migration</strong> - It is allow you to import data from various plugins like <i>Email Log</i>, <i>Mail logging - WP Mail Catcher</i>, <i>WP Mail Logging</i>, <i>WP Mail Log</i> and export the data. 
- <strong>Email Error Tracking</strong> - You can easily see list of errors of emails and view details of error. 
- <strong>Setup Wizard</strong> - Wizard setup allows you to one time setup of this plugin. 
- <strong>Default Format for Message</strong> - Its allow you to Default Format for Message on view of content. 
- <strong>SMTP</strong> - Its allow you to setup your own smtp through Check & Email Log SMTP form. 
- <strong>Multisite Configure</strong> - Its allow to configure global setting for all your sites. 
- <strong>Encoding</strong> - Its allow to encode emails and phone in page content. 
- <strong>Outlook / MS 360</strong> - Its allow to send email using 360 / outlook configuration. 

### Features of the plugin Check & Log Email

- <strong>Test email sending</strong> - if you’re not sure whether WordPress is sending emails, you can use this plugin to find out. The process is very simple: you need to choose an address that will receive a simple test email. You can even override the custom header with your own values, to avoid any other issues.
- <strong>Viewing logged emails</strong> - they can be viewed from the admin interface, as they are stored in a separate table. You get the option to filter them based on subject, email, date, etc.
- <strong>Deleting logged emails</strong> - you can delete them by going to the admin interface. You can either delete them in bulk or  selectively - by date, email, or subject. 
- <strong>Ability to change</strong> the "mail from" email address, "mail from name" and override default email WordPress addresses. 

### Premium Features of the plugin Check & Log Email

- <strong>Triggered Data</strong> - Triggered data helps you in debugging by showing the exact code that is sending that email

### Filters

- <strong>wp_mail_catcher_mail_success</strong> -  is triggered before an email is sent. It has a single argument that is an array containing original mail info.

- <strong>check_email_email_log_before_insert</strong> -  is triggered before an email data about to save in check & Log Email. It has a two argument.
    - <strong>log</strong> -  It is an array containing argument that will store in database
    - <strong>original_mail_info</strong> -   It is an array containing argument of original mail info.

- <strong>wp_check_email_failed</strong> -  is triggered when an email is failed. It has a two argument.
    - <strong>mail_error_data</strong> -  It is an array containing argument that has what possible reasons of error.
    - <strong>mail_error_message</strong> -   It is an string containing error message.


### Action

- <strong>check_email_log_inserted</strong> -  is triggered after data successfully saved in log.

- <strong>check_email_loaded</strong> -  is triggered when check & log email successfully loaded.


<strong>Support</strong><br>
We try our best to provide support on WordPress.org forums. However, We have a special [community support](https://check-email.tech/contact/) where you can ask us questions and get help about your Check & Log Email related questions. Delivering a good user experience means a lot to us and so we try our best to reply each and every question that gets asked.

<strong>Bug Reports</strong><br>
Bug reports for Check & Log Email are [welcomed on GitHub](https://github.com/ahmedkaludi/check-email). Please note GitHub is not a support forum, and issues that aren't properly qualified as bugs will be closed.

<strong>Credits</strong><br>
* jsPDF used https://github.com/parallax/jsPDF - License URI: https://github.com/parallax/jsPDF/blob/master/LICENSE
* league/oauth2-client used https://github.com/thephpleague/oauth2-client - License URI: https://github.com/thephpleague/oauth2-client/blob/master/LICENSE

### Frequently Asked Questions

#### How do I use it?

Check the WordPress Admin Dashboard for "Check & Log Email" menu item.

#### Why did you write this plugin?

Someone using one of my other plugins had trouble with emails not being sent. I knocked this together to help him (and anyone else).

#### Screenshots

1. Check & Log Email - Status
2. Check & Log Email - View Logs
3. Check & Log Email - Settings

### Installation

#### Using the WordPress Plugin Search

1. Navigate to the `Add New` sub-page under the Plugins admin page.
2. Search for `Check & Log Email`.
3. The plugin should be listed first in the search results.
4. Click the `Install Now` link.
5. Lastly click the `Activate Plugin` link to activate the plugin.

#### Uploading in WordPress Admin

1. [Download the plugin zip file](https://wordpress.org/plugins/check-email/) and save it to your computer.
2. Navigate to the `Add New` sub-page under the Plugins admin page.
3. Click the `Upload` link.
4. Select Check & Log Email zip file from where you saved the zip file on your computer.
5. Click the `Install Now` button.
6. Lastly click the `Activate Plugin` link to activate the plugin.


Full changelog available [ at changelog.txt](https://plugins.svn.wordpress.org/check-email/trunk/changelog.txt)