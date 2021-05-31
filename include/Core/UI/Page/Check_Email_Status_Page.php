<?php namespace CheckEmail\Core\UI\Page;

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

/**
 * Status Page.
 */
class Check_Email_Status_Page extends Check_Email_BasePage {

	/**
	 * Page slug.
	 */
	const PAGE_SLUG = 'check-email-status';

	/**
	 * Specify additional hooks.
	 *
	 * @inheritdoc
	 */
	public function load() {
		parent::load();
                add_action( 'admin_enqueue_scripts', array( $this, 'checkemail_assets' ) );;
	}

	/**
	 * Register page.
	 */
	public function register_page() {

                add_menu_page(
                        __( 'Check & Log Email', 'check-email' ),
                        __( 'Check & Log Email', 'check-email' ),
                        'manage_check_email',
                        self::PAGE_SLUG,
                        array( $this, 'render_page' ),
                        'dashicons-email-alt',
                        26
                );

		$this->page = add_submenu_page(
			Check_Email_Status_Page::PAGE_SLUG,
			__( 'Status', 'check-email' ),
			__( 'Status', 'check-email' ),
			'manage_check_email',
			self::PAGE_SLUG,
			array( $this, 'render_page' ),
                        -10
		);
	}

	public function render_page() {
		?>
		<div class="wrap">
			<h1><?php _e( 'Status', 'check-email' ); ?></h1>
                        <?php
                        global $current_user;
                        global $phpmailer;

                        $from_name = '';
                        $from_email = apply_filters( 'wp_mail_from', $current_user->user_email );
                        $from_name = apply_filters( 'wp_mail_from_name', $from_name );

                        $headers = '';
                        if ( isset($_REQUEST['_wpnonce']) && wp_verify_nonce( $_REQUEST['_wpnonce'], 'checkemail' ) ) {
                            $headers = $this->checkemail_send( $_POST['checkemail_to'], $_POST['checkemail_headers'] );
                        }
                        ?>

                        <div id="CKE_banner">
                            <h2>
                                <img draggable="false" role="img" class="emoji" alt="👉" src="https://s.w.org/images/core/emoji/13.0.1/svg/1f449.svg">
                                <?php _e('Suggest a new feature!', 'check-email') ?>
                                <img draggable="false" role="img" class="emoji" alt="👈" src="https://s.w.org/images/core/emoji/13.0.1/svg/1f448.svg">
                            </h2>
                            <p><?php _e('Help us build the next set of features for Check & Log Email. Tell us what you think and we will make it happen!', 'check-email') ?></p>
                            <a target="_blank" rel="noreferrer noopener" href="https://bit.ly/33QzqBU" class="button button-primary button-hero"><?php _e('Click here', 'check-email') ?></a>
                        </div>

                        <?php
                        require_once 'partials/check-email-admin-status-display.php';
                        ?>
		</div>
		<?php
	}

        // send a test email
        private function checkemail_send($to, $headers = "auto") {
                global $current_user;

                $from_name = '';
                $from_email = apply_filters( 'wp_mail_from', $current_user->user_email );
                $from_name = apply_filters( 'wp_mail_from_name', $from_name );

                if ( $headers == "auto" ) {
                        $headers = "MIME-Version: 1.0\r\n" .
                        "From: " . $from_email . "\r\n" .
                        "Content-Type: text/plain; charset=\"" . get_option('blog_charset') . "\"\r\n";
                } else {
                        $break = chr( 10 );
                        if ( isset( $_POST['checkemail_break'] ) && stripslashes( $_POST["checkemail_break"] ) == '\r\n' ) {
                                $break = chr( 13 ) . chr( 10 );
                        }
                        $headers = "MIME-Version: " . trim( $_POST["checkemail_mime"] ) . $break .
                        "From: " . trim( $_POST["checkemail_from"] ) . $break .
                        "Cc: " . trim( $_POST["checkemail_cc"] ) . $break .
                        "Content-Type: " . trim( $_POST["checkemail_type"] ) . $break;
                }
                $title = __( sprintf( "Test email from %s ", get_bloginfo("url") ), "check-email" );
                $body = __( sprintf( 'This test email proves that your WordPress installation at %1$s can send emails.\n\nSent: %2$s', get_bloginfo( "url" ), date( "r" ) ), "check-email" );
                wp_mail( $to, $title, $body, $headers );
                return $headers;
        }

        public function checkemail_assets() {
		$check_email      = wpchill_check_email();
		$plugin_dir_url = plugin_dir_url( $check_email->get_plugin_file() );
		wp_enqueue_style( 'checkemail-css', $plugin_dir_url . 'assets/css/admin/checkemail.css', array(), $check_email->get_version() );
		wp_enqueue_script( 'checkemail', $plugin_dir_url . 'assets/js/admin/checkemail.js', array(), $check_email->get_version(), true );
	}
}
