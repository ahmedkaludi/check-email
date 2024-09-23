<?php 
defined( 'ABSPATH' ) || exit; // Exit if accessed directly

/**
 * @class Check_Email_SMTP_Tab
 * @since 2.0
 */
/**
 * Its functionality is inspired by Email encode address
 */
use CheckEmail\Core\Auth;
class Check_Email_Encode_Tab {

	private $encode_options;
	private $is_enable;

	public function __construct() {
		$this->setup_vars();

		add_action( 'check_mail_email_encode', array($this, 'load_email_encode_settings'));
		add_action('admin_init', array($this, 'smtp_form_submission_handler'));
	}

	/**
	 * Get smtp options
	 *
	 * @return void
	 * @since 1.0.12
	 */
	public function setup_vars(){
		$this->encode_options = get_option('check-email-email-encode-options', true);
	}

	
	
	public function load_email_encode_settings(){
		?>
		
		<form action="" method="post" >
			<div>
				<table class="form-table" role="presentation">
					<thead>
						<tr>
							<th scope="row"><label for="check-email-email-encode-options-is_enable" class="check-email-opt-labels"><?php esc_html_e( 'Enable Email Address Encoder', 'check-email' ); ?></label></th>
							<td>
							<input class="" type="checkbox" id="check-email-email-encode-options-is_enable" name="check-email-email-encode-options[is_enable]" value="1" <?php echo (isset($this->encode_options['is_enable'])) && $this->encode_options['is_enable'] ? "checked" : ''; ?>>
							</td>
						</tr>
						<tr class="check-email-etr" style="<?php echo (isset($this->encode_options['is_enable'])) && $this->encode_options['is_enable'] ? "" : 'display:none;'; ?>">
							<th scope="row"><label for="check-email-email-encode-options-is_enable" class="check-email-opt-labels"><?php esc_html_e( 'Search for emails using', 'check-email' ); ?></label></th>
							<td>
							<input id="check-email-email-encode-options-filter" type="radio" name="check-email-email-encode-options[email_using]" value="filters" <?php echo (isset($this->encode_options['email_using'])) && $this->encode_options['email_using'] == 'filters' ? "checked" : ''; ?>>
							<label for="check-email-email-encode-options-filter" class="check-email-opt-labels"><?php esc_html_e( 'WordPress filters', 'check-email' ); ?></label>&nbsp;&nbsp;
							<input id="check-email-email-encode-options-full_page" type="radio" name="check-email-email-encode-options[email_using]" value="full_page" <?php echo (isset($this->encode_options['email_using'])) && $this->encode_options['email_using'] == 'full_page' ? "checked" : ''; ?>>
							<label for="check-email-email-encode-options-full_page" class="check-email-opt-labels"><?php esc_html_e( 'Full-page scanner', 'check-email' ); ?></label>&nbsp;&nbsp;
							<input id="check-email-email-encode-options-nothing" type="radio" name="check-email-email-encode-options[email_using]" value="nothing" <?php echo (isset($this->encode_options['email_using'])) && $this->encode_options['email_using'] == 'nothing' ? "checked" : ''; ?>>
							<label for="check-email-email-encode-options-nothing" class="check-email-opt-labels"><?php esc_html_e( 'Turns off email protection', 'check-email' ); ?></label>
							</td>
						</tr>
					</thead>
					
				</table>
			</div>
			<?php wp_nonce_field('check_mail_email_encode_nonce','check_mail_email_encode_nonce'); ?>
			<p class="submit"><input type="submit" name="check_mail_email_encode_submit" id="check_mail_email_encode_submit" class="button button-primary" value="<?php esc_attr_e( 'Save', 'check-email' ); ?>"></p>
		</form>
	<?php
	}

	/**
	 * Save SMTP options
	 *
	 * @return void
	 * @since 1.0.12
	 */

	public function smtp_form_submission_handler(){
		if(isset($_POST['check_mail_email_encode_submit']) && $_POST['check_mail_email_encode_submit'] == 'Save'){
			if(!isset($_POST['check_mail_email_encode_nonce'])){
		    	return;
		    }

		    if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['check_mail_email_encode_nonce'] ) ), 'check_mail_email_encode_nonce' ) ){
	       		return;  
	    	}

			if ( ! current_user_can( 'manage_check_email' ) ) {
				return;
			}
			$email_encode_option['is_enable'] = 0;
			if ( isset($_POST['check-email-email-encode-options']['is_enable'] ) ) {
				$email_encode_option['is_enable'] = 1;
			}
			if ( isset($_POST['check-email-email-encode-options']['email_using'] ) ) {
				$email_encode_option['email_using'] = sanitize_text_field( wp_unslash( $_POST['check-email-email-encode-options']['email_using'] ) );
			}else{
				$email_encode_option['email_using'] = 'filters';
			}
			
			update_option('check-email-email-encode-options', $email_encode_option);

			wp_safe_redirect(admin_url('admin.php?page=check-email-settings&tab=email-encode'));
		}
	}

	

}
new Check_Email_Encode_Tab();
/**
 * Define filter-priority constant, unless it has already been defined.
 */
if ( ! defined( 'CHECK_EMAIL_E_FILTER_PRIORITY' ) ) {
	define(
		'CHECK_EMAIL_E_FILTER_PRIORITY',
		(integer) get_option( 'check_email_e_filter_priority', 2000 )
	);
}

/**
 * Define regular expression constant, unless it has already been defined.
 */
if ( ! defined( 'CHECK_EMAIL_E_REGEXP' ) ) {
	define(
		'CHECK_EMAIL_E_REGEXP',
		'{
			(?:mailto:)?
			(?:
				[-!#$%&*+/=?^_`.{|}~\w\x80-\xFF]+
			|
				".*?"
			)
			\@
			(?:
				[-a-z0-9\x80-\xFF]+(\.[-a-z0-9\x80-\xFF]+)*\.[a-z]+
			|
				\[[\d.a-fA-F:]+\]
			)
		}xi'
	);
}
$encode_options = get_option('check-email-email-encode-options', true);
$is_enable = ( isset( $encode_options['is_enable'] ) ) ? $encode_options['is_enable'] : 0;
$email_using = ( isset( $encode_options['email_using'] ) ) ? $encode_options['email_using'] : "";
if ( $is_enable && $email_using == 'filters' ) {
	foreach ( array( 'the_content', 'the_excerpt', 'widget_text', 'comment_text', 'comment_excerpt' ) as $filter ) {
		add_filter( $filter, 'check_email_e_encode_emails', CHECK_EMAIL_E_FILTER_PRIORITY );
	}
}

add_action( 'init', 'check_email_e_register_shortcode', 2000 );
	/**
	 * Register the [encode] shortcode, if it doesn't exist.
	 *
	 * @return void
	 */
	function check_email_e_register_shortcode() {
		if ( ! shortcode_exists( 'encode' ) ) {
			add_shortcode( 'encode', 'check_email_e_shortcode' );
		}
	}

	function check_email_e_encode_str( $string, $hex = false ) {
		$chars = str_split( $string );
		$seed = mt_rand( 0, (int) abs( crc32( $string ) / strlen( $string ) ) );
		

		foreach ( $chars as $key => $char ) {
			$ord = ord( $char );

			if ( $ord < 128 ) { // ignore non-ascii chars
				$r = ( $seed * ( 1 + $key ) ) % 100; // pseudo "random function"

				if ( $r > 75 && $char !== '@' && $char !== '.' ); // plain character (not encoded), except @-signs and dots
				else if ( $hex && $r < 25 ) $chars[ $key ] = '%' . bin2hex( $char ); // hex
				else if ( $r < 45 ) $chars[ $key ] = '&#x' . dechex( $ord ) . ';'; // hexadecimal
				else $chars[ $key ] = "&#{$ord};"; // decimal (ascii)
			}
		}

		return implode( '', $chars );
	}

	

	/**
	 * The [encode] shortcode callback function. Returns encoded shortcode content.
	 *
	 * @param array $attributes Shortcode attributes
	 * @param string $string Shortcode content
	 *
	 * @return string Encoded given text
	 */
	function check_email_e_shortcode( $attributes, $content = '' ) {
		$atts = shortcode_atts( array(
			'link' => null,
			'class' => null,
		), $attributes, 'encode' );

		// override encoding function with the 'check_email_e_method' filter
		$method = apply_filters( 'check_email_e_method', 'check_email_e_encode_str' );

		if ( ! empty( $atts[ 'link' ] ) ) {
			$link = esc_url( $atts[ 'link' ], null, 'shortcode' );

			if ( $link === '' ) {
				return $method( $content );
			}

			if ( empty( $atts[ 'class' ] ) ) {
				return sprintf(
					'<a href="%s">%s</a>',
					$method( $link ),
					$method( $content )
				);
			}

			return sprintf(
				'<a href="%s" class="%s">%s</a>',
				$method( $link ),
				esc_attr( $atts[ 'class' ] ),
				$method( $content )
			);
		}

		return $method( $content );
	}

	/**
	 * Searches for plain email addresses in given $string and
	 * encodes them (by default) with the help of check_email_e_encode_str().
	 *
	 * Regular expression is based on based on John Gruber's Markdown.
	 * http://daringfireball.net/projects/markdown/
	 *
	 * @param string $string Text with email addresses to encode
	 *
	 * @return string Given text with encoded email addresses
	 */
	function check_email_e_encode_emails( $string ) {
		if ( ! is_string( $string ) ) {
			return $string;
		}

		// abort if `check_email_e_at_sign_check` is true and `$string` doesn't contain a @-sign
		if ( apply_filters( 'check_email_e_at_sign_check', true ) && strpos( $string, '@' ) === false ) {
			return $string;
		}

		
		// override encoding function with the 'check_email_e_method' filter
		$method = apply_filters( 'check_email_e_method', 'check_email_e_encode_str' );

		// override regular expression with the 'check_email_e_regexp' filter
		$regexp = apply_filters( 'check_email_e_regexp', CHECK_EMAIL_E_REGEXP );
		
		$callback = function ( $matches ) use ( $method ) {
			return $method( $matches[ 0 ] );
		};
		

		// override callback method with the 'check_email_e_email_callback' filter
		if ( has_filter( 'check_email_e_email_callback' ) ) {
			$callback = apply_filters( 'check_email_e_email_callback', $callback, $method );
			return preg_replace_callback( $regexp, $callback, $string );
		}

		return preg_replace_callback( $regexp, $callback, $string );
	}

