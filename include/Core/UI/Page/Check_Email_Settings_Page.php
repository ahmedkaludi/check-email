<?php namespace CheckEmail\Core\UI\Page;

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

class Check_Email_Settings_Page extends Check_Email_BasePage {

	const PAGE_SLUG = 'check-email-settings';
	public $page_slug;
	public function load() {
		parent::load();

		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'wp_ajax_oneclick_smtp_install', array( $this, 'install_plugin' ) );
		add_action( 'wp_ajax_oneclick_smtp_activate', array( $this, 'activate_plugin' ) );
	}

	public function register_settings() {
		$sections = $this->get_setting_sections();

		foreach ( $sections as $section ) {
			if( !isset( $section->page_slug ) ) 
			continue;
			$this->page_slug = $section->page_slug;
			register_setting(
				$this->page_slug ,
				$section->option_name,
				array( 'sanitize_callback' => $section->sanitize_callback )
			);

			add_settings_section(
				$section->id,
				$section->title,
				$section->callback,
				$this->page_slug 
			);

			foreach ( $section->fields as $field ) {
				add_settings_field(
					$section->id . '[' . $field->id . ']',
					$field->title,
					$field->callback,
					$this->page_slug,
					$section->id,
					$field->args
				);
			}
		}
	}

	protected function get_setting_sections() {
		return apply_filters( 'check_email_setting_sections', array() );
	}

	public function register_page() {

		$sections = $this->get_setting_sections();
                
		if ( empty( $sections ) ) {
			return;
		}

		$this->page = add_submenu_page(
			Check_Email_Status_Page::PAGE_SLUG,
			esc_html__( 'Settings', 'check-email' ),
			esc_html__( 'Settings', 'check-email' ),
			'manage_options',
			self::PAGE_SLUG,
			array( $this, 'render_page' )
		);

	}
   /**
    * Checks if SMTP plugin is installed
    *
    * @since 1.0.5
    */
	public function is_smtp_installed() {
		if ( ! function_exists( 'get_plugins' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		$all_plugins = get_plugins();
		if ( !empty( $all_plugins['wp-smtp/wp-smtp.php'] ) ) {
		return true;
		} else {
		return false;
		}
	}

   /**
    * Checks if plugin slug is posted and installs the plugin
    *
    * @since 1.0.5
    */
	public function install_plugin() {

		if ( ! isset( $_POST['slug'] ) || empty( $_POST['slug'] ) ) {
			$error = array( 'errorMessage' => 'Plugin not found.' );
			wp_send_json_error( $error );
		}
		wp_ajax_install_plugin();
		
	}

   /**
    * Activates a plugn
    *
    * @since 1.0.5
    */

	public function activate_plugin() {

		if ( ! isset( $_POST['slug'] ) || empty( $_POST['slug'] ) ) {
			wp_send_json_error( array( 'errorMessage' => 'Plugin activation slug missing.' ) );
			
		}
		$activate = activate_plugin( $_POST['slug']. '/' . $_POST['slug'] . '.php' );

		if ( is_wp_error( $activate ) ) {
			wp_send_json_error( array( 'errorMessage' => $activate->get_error_message() ) );
			die;
		}
		$error = array( 'message' => esc_html__( 'Plugin installed and activated successfully.', 'check-email' ) );
		wp_send_json_success($error);
		die;
		
	}

   /**
    * Renders the plugin settings page HTML
    *
    * @since 1.0.5
    */
	public function render_page() {

			$tab = isset( $_GET['tab']) ? $_GET['tab'] : 'general';
			
		?>
		<div class="wrap">

			<nav class="nav-tab-wrapper">
				<a href="?page=check-email-settings" class="nav-tab <?php if( 'general' == $tab ):?>nav-tab-active<?php endif; ?>"><?php esc_html_e( 'General', 'check-email' ); ?></a>
				<a href="?page=check-email-settings&tab=logging" class="nav-tab <?php if( 'logging' == $tab ):?>nav-tab-active<?php endif; ?>"><?php esc_html_e( 'Logging', 'check-email' ); ?></a>
				<a href="?page=check-email-settings&tab=smtp" class="nav-tab <?php if( 'smtp' == $tab ):?>nav-tab-active<?php endif; ?>"><?php esc_html_e( 'SMTP', 'check-email' ); ?></a>
				<a href="https://docs.google.com/forms/d/e/1FAIpQLSdhHrYons-oMg_9oEDVvx8VTvzdeCQpT4PnG6KLCjYPiyQfXg/viewform" target="_blank" class="nav-tab"><span class="dashicons dashicons-external"></span><?php esc_html_e( 'Suggest a feature', 'check-email' ); ?></a>
			</nav>
			
			<div class="tab-content ce_tab_<?php echo $tab; ?>">

			<?php if( 'general' == $tab ): ?>
				<h2><?php esc_html_e( 'Core Check Email Log Settings', 'check-email' ); ?></h2>
			<?php elseif( 'logging' == $tab ): ?>
				<h2><?php esc_html_e( 'Logging', 'check-email' ); ?></h2>
			<?php elseif( 'smtp' == $tab ): ?>
				<h2><?php esc_html_e( 'WP SMTP Installer', 'check-email' ); ?></h2>
			<?php endif; ?>

			<?php if( 'smtp' !== $tab ): ?>
				<?php $submit_url = ( '' != $tab ) ? add_query_arg( 'tab', $tab, admin_url( 'options.php' ) ) : 'options.php'; ?>
				<form method="post" action="<?php echo esc_url( $submit_url ); ?>">
					<?php
					settings_errors();
					settings_fields( $this->page_slug  );
					do_settings_sections( $this->page_slug );
					submit_button( esc_html__( 'Save', 'check-email' ) );
					?>
				</form>
			<?php elseif( 'smtp' == $tab ): ?>
				<table class="form-table" role="presentation">
					<tbody>
						<tr>
							<th scope="row"><?php esc_html_e( 'Install WP SMTP', 'check-email' ); ?></th>
							<?php if( !$this->is_smtp_installed() ): ?> 
							<td>
								<div class="install_plugin_wrap">
									<a id="install_wp_smtp" class="button" href="http://wp-smtp?ch_em_action=ch_em_oneclick_smtp_install&ch_em_nonce=<?php echo wp_create_nonce( 'updates' ); ?>"><?php esc_html_e( 'Install & Activate SMTP', 'check-email' ); ?></a>
									<div id="install_wp_smtp_info"> <p><?php esc_html_e( 'Click to auto install and activate WP SMTP', 'check-email' ); ?> </p></div>
								</div>
								
							</td>
							<?php else: ?>
								<td>
								<div class="install_wp_smtp_wrap"> <?php esc_html_e( 'WP SMTP is allready installed.', 'check-email' ); ?></div>
							</td>
							<?php endif; ?>
						</tr>

					</tbody>
				</table>
			<?php endif; ?>
			</div>
		</div>
		<?php

	}
}
