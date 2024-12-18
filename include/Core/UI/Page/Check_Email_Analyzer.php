<?php

namespace CheckEmail\Core\UI\Page;

defined('ABSPATH') || exit; // Exit if accessed directly.

$check_email      = wpchill_check_email();
$plugin_path = plugin_dir_path($check_email->get_plugin_file());
require_once $plugin_path . '/vendor/autoload.php';


use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\DNSCheckValidation;
use Egulias\EmailValidator\Validation\MultipleValidationWithAnd;
use Egulias\EmailValidator\Validation\RFCValidation;
use Egulias\EmailValidator\Validation\Extra\SpoofCheckValidation;

class Check_Email_Analyzer extends Check_Email_BasePage
{

    /**
     * Page slug.
     */
    const PAGE_SLUG = 'spam-analyzer';
    const DASHBOARD_SLUG = 'check-email-dashboard';



    /**
     * Specify additional hooks.
     *
     * @inheritdoc
     */
    public function load()
    {
        parent::load();
        add_action('admin_enqueue_scripts', array($this, 'checkemail_assets'));
        add_action('wp_ajax_ck_email_verify', array($this, 'ck_email_verify'));
    }

    public function ck_email_verify()
    {
        if (!isset($_POST['ck_mail_security_nonce'])) {
            return;
        }
        if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['ck_mail_security_nonce'])), 'ck_mail_security_nonce')) {
            return;
        }
        if (! current_user_can('manage_check_email')) {
            return;
        }
        $response = array('status' => false);
        if (isset($_POST['email']) && ! empty($_POST['email'])) {
            $email = $_POST['email'];
            $validator = new EmailValidator();
            $email_valid = $validator->isValid($email, new RFCValidation());
            $dns_valid = $validator->isValid($email, new DNSCheckValidation());
            $spoof_valid = $validator->isValid($email, new SpoofCheckValidation());
            $response['status'] = true;
            $response['spoof_valid'] = ($spoof_valid) ? 1 : 0;
            $response['dns_valid'] = ($dns_valid) ? 1 : 0;
            $response['email_valid'] = ($email_valid) ? 1 : 0;
        } else {
            $response['error'] = 'Please enter email address';
        }

        echo wp_json_encode($response);
        wp_die();
    }
    public function register_page() {
        $this->page = add_submenu_page(
            Check_Email_Status_Page::PAGE_SLUG,
            esc_html__('Spam Analyzer', 'check-email'),
            esc_html__('Spam Analyzer', 'check-email'),
            'manage_check_email',
            self::PAGE_SLUG,
            array($this, 'render_page'),
            2
        );
    }

    public function render_page() {
        ?>
        <div class="wrap">
            <div class="ck_banner">
                <h1><?php esc_html_e('Email Spam Testing of your mail for accurate delivery', 'check-email'); ?></h1>
                <button class="ck_button" id="ck_email_analyze"><?php esc_html_e('Check My Email Spam Score', 'check-email'); ?></button>
                <h2 class="ck_score_cls" style="display:none;" id="ck_score_text"></h2>
                <div class="ck_score ck_score_cls" style="display:none;"></div>
                <div class="ck_loader" id="ck_loader"></div>
                <p class="ck_sub"><?php esc_html_e('One of its kind FREE tool in WordPress', 'check-email'); ?></p>
                <p class="ck_fun-fact"><?php esc_html_e("Fun fact: Did you know that 70% of the emails don't get visibility because of the wrong configuration.", 'check-email'); ?></p>
            </div>
            <div class="ck_subject_bar" style="display:none;" ><span id="ck_top_subject"></span> <span id="ck_email_date"></span> </div>
            <div id="ck_email_analyze_result" style="margin-top:50px;"></div>
            <?php
                $current_user = wp_get_current_user();
                $spam_score_get = get_option('check_email_spam_score_' . $current_user ->user_email,[]); 
            ?>
            <div class="wp-table-wrapper" style="overflow-x:auto; margin: 20px 0;">
                <h1><?php esc_html_e('Previous Spam Score', 'check-email'); ?></h1>
                <table style="width: 100%; border-collapse: collapse; border: 1px solid #ddd; text-align: center;">
                    <thead>
                        <tr style="background-color: #f9f9f9;">
                            <th style="padding: 10px; border: 1px solid #ddd;"><?php esc_html_e('Score', 'check-email'); ?></th>
                            <th style="padding: 10px; border: 1px solid #ddd;"><?php esc_html_e('Date', 'check-email'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ( !empty($spam_score_get) ) {
                            foreach ($spam_score_get as $key => $value) {
                                ?>
                                <tr>
                                    <td style="padding: 10px; border: 1px solid #ddd;"><?php echo esc_html($value['score']) ?></td>
                                    <td style="padding: 10px; border: 1px solid #ddd;"><?php echo esc_html($value['datetime']) ?></td>
                                </tr><?php
                            }
                        }else{
                            ?>
                            <tr>
                                <td style="padding: 10px; border: 1px solid #ddd;" colspan="2"><?php esc_html_e('No score found', 'check-email'); ?></td>
                            </tr><?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>


	<tbody id="the-list">
            <script>
                function ck_toggleAccordion(element) {
                    const accordion = element.parentElement;
                    accordion.classList.toggle('active');
                }
            </script>
        </div>
        <?php
    }

    public function checkemail_assets() {
        $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
        $check_email    = wpchill_check_email();
        $plugin_dir_url = plugin_dir_url($check_email->get_plugin_file());
        wp_enqueue_style('checkemail-css', $plugin_dir_url . 'assets/css/admin/checkemail' . $suffix . '.css', array(), $check_email->get_version());
        wp_enqueue_script('checkemail', $plugin_dir_url . 'assets/js/admin/checkemail' . $suffix . '.js', array('jquery', 'updates'), $check_email->get_version(), true);

        $data['ajax_url'] = admin_url('admin-ajax.php');
        $data['ck_mail_security_nonce'] = wp_create_nonce('ck_mail_security_nonce');

        wp_localize_script('checkemail', 'checkemail_data', $data);
    }
}
