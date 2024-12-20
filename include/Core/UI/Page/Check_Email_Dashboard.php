<?php

namespace CheckEmail\Core\UI\Page;

defined('ABSPATH') || exit; // Exit if accessed directly.


class Check_Email_Dashboard extends Check_Email_BasePage
{

    /**
     * Page slug.
     */
    const PAGE_SLUG = 'check-email-status';
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
    }

    /**
     * Register page.
     */
    public function register_page()
    {
        $this->page = add_submenu_page(
            Check_Email_Status_Page::PAGE_SLUG,
            esc_html__('Dashboard', 'check-email'),
            esc_html__('Dashboard', 'check-email'),
            'manage_check_email',
            self::DASHBOARD_SLUG,
            array($this, 'render_page'),
            0
        );
    }

    public function render_page()
    {
?>
        <div class="wrap">
            <h1><?php esc_html_e('Check & Log Email', 'check-email'); ?></h1></br/>
            <div class="ck_dashboard-container">
                <div class="ck_dashboard-box">
                    <h3><?php echo esc_html__('Email Testing', 'check-email'); ?></h3>
                    <p style="height:90px; overflow:hidden;"><?php echo esc_html__('To ensure that your email setup is correctly configured', 'check-email'); ?>
                    <?php echo esc_html__('It is important to send a test email', 'check-email'); ?>
                    <?php echo esc_html__('This process helps verify that the email server', 'check-email'); ?></p>
                    <a class="button button-primary button-hero" href="<?php echo esc_url(admin_url('admin.php?page=check-email-status')); ?>"><?php echo esc_html__( "Go to Email Testing Module", 'check-email' ); ?></a>
                </div>
                <div class="ck_dashboard-box">
                    <h3><?php echo esc_html__('Spam Analyzer', 'check-email'); ?></h3>
                    <p style="height:90px; overflow:hidden;"><?php echo esc_html__('Email Spam Testing of your mail for accurate delivery', 'check-email'); ?>
                    <?php echo esc_html__('One of its kind FREE tool in WordPress', 'check-email'); ?>
                    <?php echo esc_html__("Did you know that 70% of the emails don't get visibility because of the wrong configuration", 'check-email'); ?></p>
                    <a class="button button-primary button-hero" href="<?php echo esc_url(admin_url('admin.php?page=spam-analyzer')); ?>"><?php echo esc_html__( "Go to Spam Analyzer Module", 'check-email' ); ?></a>
                </div>
                <div class="ck_dashboard-box">
                    <h3><?php echo esc_html__('Email Logs', 'check-email'); ?></h3>
                    <p style="height:90px; overflow:hidden;"><?php echo esc_html__('An email list is a valuable asset for any business or organization', 'check-email'); ?>
                    <?php echo esc_html__('Always ensure that the individuals on your list have to receive your emails to maintain trust and compliance', 'check-email'); ?>
                    <?php echo esc_html__("Monitor your email metrics, like email opend delivery and detail, to ensure you’re delivering information.", 'check-email'); ?></p>
                    <a class="button button-primary button-hero" href="<?php echo esc_url(admin_url('admin.php?page=check-email-logs')); ?>"><?php echo esc_html__( "Go to Email Logs Module", 'check-email' ); ?></a>
                </div>
                <?php
                $option = get_option( 'check-email-log-core' );
        
                if ( is_array( $option ) && array_key_exists( 'email_error_tracking', $option ) && 'true' === strtolower( $option['email_error_tracking'] ) ) { ?>
                <div class="ck_dashboard-box">
                    <h3><?php echo esc_html__('Email Error Tracker', 'check-email'); ?></h3>
                    <p style="height:90px; overflow:hidden;"><?php echo esc_html__('Managing email errors is essential to ensure smooth communication and deliverability', 'check-email'); ?>
                    <?php echo esc_html__('An Email Error Tracker helps you identify and resolve issues during email sending', 'check-email'); ?>
                    <?php echo esc_html__("Monitor failed email deliveries in real-time.", 'check-email'); ?></p>
                    <a class="button button-primary button-hero" href="<?php echo esc_url(admin_url('admin.php?page=check-email-error-tracker')); ?>"><?php echo esc_html__( "Go to Email Error Tracker Module", 'check-email' ); ?></a>
                </div>
                <?php } ?>
                <div id="CKE_banner" style="width:27%;padding: 30px;">
                    <h2>
                        <?php // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage, PluginCheck.CodeAnalysis.Offloading.OffloadedContent ?>
                        <img draggable="false" role="img" class="emoji" alt="👉" src="https://s.w.org/images/core/emoji/13.0.1/svg/1f449.svg">
                        <?php esc_html_e('Suggest a new feature!', 'check-email') ?>
                        <?php // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage, PluginCheck.CodeAnalysis.Offloading.OffloadedContent ?>
                        <img draggable="false" role="img" class="emoji" alt="👈" src="https://s.w.org/images/core/emoji/13.0.1/svg/1f448.svg">
                    </h2>
                    <p style="height:90px; overflow:hidden;"><?php esc_html_e('Help us build the next set of features for Check & Log Email. Tell us what you think and we will make it happen!', 'check-email') ?></p>
                    <a target="_blank" rel="noreferrer noopener" href="https://check-email.tech/contact/" class="button button-primary button-hero"><?php esc_html_e('Click here', 'check-email') ?></a>
                </div>
            </div>
        </div>
        <?php
    }

    public function checkemail_assets()
    {
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
