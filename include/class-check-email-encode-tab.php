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
							<th scope="row" style="padding-left: 10px;;"><label for="check-email-email-encode-options-is_enable" class="check-email-opt-labels"><?php esc_html_e( 'Search for emails using', 'check-email' ); ?></label></th>
							<td>
							<input id="check-email-email-encode-options-filter" type="radio" name="check-email-email-encode-options[email_using]" value="filters" <?php echo (isset($this->encode_options['email_using'])) && $this->encode_options['email_using'] == 'filters' ? "checked" : ''; ?>>
							<label for="check-email-email-encode-options-filter" class="check-email-opt-labels"><?php esc_html_e( 'WordPress filters', 'check-email' ); ?></label>&nbsp;&nbsp;
							<input id="check-email-email-encode-options-full_page" type="radio" name="check-email-email-encode-options[email_using]" value="full_page" <?php echo (isset($this->encode_options['email_using'])) && $this->encode_options['email_using'] == 'full_page' ? "checked" : ''; ?>>
							<label for="check-email-email-encode-options-full_page" class="check-email-opt-labels"><?php esc_html_e( 'Full-page scanner', 'check-email' ); ?></label>&nbsp;&nbsp;
							<input id="check-email-email-encode-options-nothing" type="radio" name="check-email-email-encode-options[email_using]" value="nothing" <?php echo (isset($this->encode_options['email_using'])) && $this->encode_options['email_using'] == 'nothing' ? "checked" : ''; ?>>
							<label for="check-email-email-encode-options-nothing" class="check-email-opt-labels"><?php esc_html_e( 'Turns off email protection', 'check-email' ); ?></label>
							</td>
						</tr>
						<tr class="check-email-etr" style="<?php echo (isset($this->encode_options['is_enable'])) && $this->encode_options['is_enable'] ? "" : 'display:none;'; ?>">
							<th scope="row" style="padding-left: 10px;;"><label for="check-email-email-encode-options-is_enable" class="check-email-opt-labels"><?php esc_html_e( 'Protect emails using', 'check-email' ); ?></label></th>
							<td>
							<input id="check-email-email-encode-options-html-entities" type="radio" name="check-email-email-encode-options[email_technique]" value="html_entities" <?php echo (isset($this->encode_options['email_technique'])) && $this->encode_options['email_technique'] == 'html_entities' ? "checked" : ''; ?>>
							<label for="check-email-email-encode-options-html-entities" class="check-email-opt-labels"><?php esc_html_e( 'Html Entities', 'check-email' ); ?></label>&nbsp;&nbsp;
							<input id="check-email-email-encode-options-css_direction" type="radio" name="check-email-email-encode-options[email_technique]" value="css_direction" <?php echo (isset($this->encode_options['email_technique'])) && $this->encode_options['email_technique'] == 'css_direction' ? "checked" : ''; ?>>
							<label for="check-email-email-encode-options-css_direction" class="check-email-opt-labels"><?php esc_html_e( 'CSS Direction', 'check-email' ); ?></label>&nbsp;&nbsp;
							<input id="check-email-email-encode-options-rot_13" type="radio" name="check-email-email-encode-options[email_technique]" value="rot_13" <?php echo (isset($this->encode_options['email_technique'])) && $this->encode_options['email_technique'] == 'rot_13' ? "checked" : ''; ?>>
							<label for="check-email-email-encode-options-rot_13" class="check-email-opt-labels"><?php esc_html_e( 'ROT13 Encoding', 'check-email' ); ?></label>&nbsp;&nbsp;
							<input id="check-email-email-encode-options-rot_47" type="radio" name="check-email-email-encode-options[email_technique]" value="rot_47" <?php echo (isset($this->encode_options['email_technique'])) && $this->encode_options['email_technique'] == 'rot_47' ? "checked" : ''; ?>>
							<label for="check-email-email-encode-options-rot_47" class="check-email-opt-labels"><?php esc_html_e( 'Polymorphous ROT47/CSS', 'check-email' ); ?></label>
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
			if ( isset($_POST['check-email-email-encode-options']['email_technique'] ) ) {
				$email_encode_option['email_technique'] = sanitize_text_field( wp_unslash( $_POST['check-email-email-encode-options']['email_technique'] ) );
			}else{
				$email_encode_option['email_technique'] = 'html_entities';
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

if ( ! defined( 'CHECK_EMAIL_E_REGEXP' ) ) {
    define(
        'CHECK_EMAIL_E_REGEXP',
        '{
            \s               # Ensures exactly one space before the email
            (?:mailto:)?      # Optional mailto:
            (?:
                [-!#$%&*+/=?^_`.{|}~\w\x80-\xFF]+  # Local part before @
            |
                ".*?"                               # Quoted local part
            )
            \@               # At sign (@)
            (?:
                [-a-z0-9\x80-\xFF]+(\.[-a-z0-9\x80-\xFF]+)*\.[a-z]+   # Domain name
            |
                \[[\d.a-fA-F:]+\]                                     # IPv4/IPv6 address
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
		add_filter( $filter, 'check_email_e_anchor_encode_emails', CHECK_EMAIL_E_FILTER_PRIORITY );
	}
}
if ( $is_enable && $email_using == 'full_page' ) {
	add_action( 'wp', 'check_email_full_page_scanner',999 );
}

add_action( 'init', 'check_email_e_register_shortcode', 2000 );
	
	function check_email_e_register_shortcode() {
		if ( ! shortcode_exists( 'encode' ) ) {
			add_shortcode( 'encode', 'check_email_e_shortcode' );
		}
	}

	function check_email_rot47($str) {
		$rotated = '';
		foreach (str_split($str) as $char) {
			$ascii = ord($char);
			if ($ascii >= 33 && $ascii <= 126) {
				$rotated .= chr(33 + (($ascii + 14) % 94));
			} else {
				$rotated .= $char;
			}
		}
		return $rotated;
	}

	function check_email_encode_str( $string, $hex = false ) {
		$encode_options = get_option('check-email-email-encode-options', true);
		$email_technique = ( isset( $encode_options['email_technique'] ) ) ? $encode_options['email_technique'] : "";
		switch ($email_technique) {
			case 'css_direction':
				$reversed_email = strrev($string);
				// Wrap it with the span and necessary CSS
				return ' <span style="direction: rtl; unicode-bidi: bidi-override;">' . esc_html($reversed_email) . '</span>';
				break;
			case 'rot_13':
				$encoded_email = str_rot13($string);
				return ' <span class="check-email-encoded-email" >' . esc_html($encoded_email).' </span>';
				break;
			case 'rot_47':
				$encoded_email = check_email_rot47($string);
				return ' <span class="check-email-rot47-email" >' . esc_html($encoded_email).' </span>';
				break;
			
			default:
				# code...
				break;
		}
    
		
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
	function check_email_anchor_encode_str( $string, $hex = false ) {
		$string = str_replace('mailto:', '', $string);
		$encode_options = get_option('check-email-email-encode-options', true);
		$email_technique = ( isset( $encode_options['email_technique'] ) ) ? $encode_options['email_technique'] : "";
		switch ($email_technique) {
			case 'css_direction':
				$reversed_email = strrev($string);
				// Wrap it with the span and necessary CSS
				return 'mailto:'.esc_html($reversed_email);
				break;
			case 'rot_13':
				$encoded_email = str_rot13($string);
				return 'mailto:'.esc_html($encoded_email);
				break;
			case 'rot_47':
				$encoded_email = check_email_rot47($string);
				return 'mailto:'.esc_html($encoded_email);
				break;
			
			default:
				# code...
				break;
		}    
		
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

	function check_email_e_shortcode( $attributes, $content = '' ) {
		$atts = shortcode_atts( array(
			'link' => null,
			'class' => null,
		), $attributes, 'encode' );

		// override encoding function with the 'check_email_e_method' filter
		$method = apply_filters( 'check_email_e_method', 'check_email_encode_str' );

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

	function check_email_e_encode_emails( $string ) {
		if ( ! is_string( $string ) ) {
			return $string;
		}
		// abort if `check_email_e_at_sign_check` is true and `$string` doesn't contain a @-sign
		if ( apply_filters( 'check_email_e_at_sign_check', true ) && strpos( $string, '@' ) === false ) {
			return $string;
		}

		
		// override encoding function with the 'check_email_e_method' filter
		$method = apply_filters( 'check_email_e_method', 'check_email_encode_str' );

		// override regular expression with the 'check_email_e_regexp' filter

		$regexp = apply_filters( 'check_email_e_regexp', CHECK_EMAIL_E_REGEXP );

		$callback = function ( $matches ) use ( $method ) {
			return $method( $matches[ 0 ] );
		};
		if ( has_filter( 'check_email_e_callback' ) ) {
			$callback = apply_filters( 'check_email_e_callback', $callback, $method );
			return preg_replace_callback( $regexp, $callback, $string );
		}

		return preg_replace_callback( $regexp, $callback, $string );
	}
	function check_email_e_anchor_encode_emails( $string ) {
		if ( ! is_string( $string ) ) {
			return $string;
		}

		// abort if `check_email_e_at_sign_check` is true and `$string` doesn't contain a @-sign
		if ( apply_filters( 'check_email_e_at_sign_check', true ) && strpos( $string, '@' ) === false ) {
			return $string;
		}

		
		// override encoding function with the 'check_email_e_method' filter
		$method = apply_filters( 'check_email_e_method', 'check_email_anchor_encode_str' );

		// override regular expression with the 'check_email_e_regexp' filter
		// $regexp = apply_filters( 'check_email_e_regexp', CHECK_EMAIL_E_REGEXP );
		$regexp = '/mailto:([a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,})/';

		$callback = function ( $matches ) use ( $method ) {
			return $method( $matches[ 0 ] );
		};

		if ( has_filter( 'check_email_e_anchor_callback' ) ) {
			$callback = apply_filters( 'check_email_e_anchor_callback', $callback, $method );
			return preg_replace_callback( $regexp, $callback, $string );
		}

		return preg_replace_callback( $regexp, $callback, $string );
	}

	function check_email_full_page_scanner() {
		if(!is_admin() ) {
			ob_start('check_email_full_page_callback');
		}
	}
	function check_email_full_page_callback($string) {
		return check_email_e_encode_emails($string);
	}

	if ( ! is_admin() ) {
		add_action( 'init', 'ck_mail_enqueue_encoder_js' );
	}

	function ck_mail_enqueue_encoder_js() {
		$check_email    = wpchill_check_email();
		$plugin_dir_url = plugin_dir_url( $check_email->get_plugin_file() );
		wp_enqueue_script( 'checkemail_encoder', $plugin_dir_url . 'assets/js/check-email-front.js', array(), $check_email->get_version(), true );

		$encode_options = get_option('check-email-email-encode-options', true);
		$email_technique = ( isset( $encode_options['email_technique'] ) ) ? $encode_options['email_technique'] : "";
		$is_enable = ( isset( $encode_options['is_enable'] ) ) ? $encode_options['is_enable'] : 0;
		$email_using = ( isset( $encode_options['email_using'] ) ) ? $encode_options['email_using'] : "";

		$data['email_using'] = $email_using;
		$data['is_enable'] = $is_enable;
		$data['email_technique'] = $email_technique;

        wp_localize_script( 'checkemail_encoder', 'checkemail_encoder_data', $data );
	}

