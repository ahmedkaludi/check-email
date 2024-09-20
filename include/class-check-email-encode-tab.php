<?php 
defined( 'ABSPATH' ) || exit; // Exit if accessed directly

/**
 * @class Check_Email_SMTP_Tab
 * @since 1.0.12
 */
use CheckEmail\Core\Auth;
class Check_Email_Encode_Tab {

	private $encode_options;

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
		// print_r($this->encode_options);die;
	}

	
	
	public function load_email_encode_settings(){
		?>
		
		<form action="" method="post" >
			<div>
				<table class="form-table" role="presentation">
					<thead>
						<tr>
							<th scope="row"><label for="check-email-email-encode-options-is_enable" class="check-email-opt-labels"><?php esc_html_e( 'Search For Emails Using', 'check-email' ); ?></label></th>
							<td>
							<input class="" type="checkbox" id="check-email-email-encode-options-is_enable" name="check-email-email-encode-options[is_enable]" value="1" <?php echo (isset($this->encode_options['is_enable'])) && $this->encode_options['is_enable'] ? "checked" : ''; ?>>
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

		    if ( !wp_verify_nonce( $_POST['check_mail_email_encode_nonce'], 'check_mail_email_encode_nonce' ) ){
	       		return;  
	    	}

			if ( ! current_user_can( 'manage_check_email' ) ) {
				return;
			}
			$email_encode_option['is_enable'] = 0;
			if ( isset($_POST['check-email-email-encode-options']['is_enable'] ) ) {
				$email_encode_option['is_enable'] = 1;
			}
			
			update_option('check-email-email-encode-options', $email_encode_option);

			wp_safe_redirect(admin_url('admin.php?page=check-email-settings&tab=email-encode'));
		}
	}
}
new Check_Email_Encode_Tab();