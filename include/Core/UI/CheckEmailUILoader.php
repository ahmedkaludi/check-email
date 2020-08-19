<?php namespace EmailLog\Core\UI;

use EmailLog\Core\Loadie;
use EmailLog\Core\UI\Page\EmailLogListPage;

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

/**
 * Admin UI Loader.
 * Loads and initializes all admin pages and components.
 */
class CheckEmailUILoader implements Loadie {

	protected $components = array();

	protected $pages = array();


	public function load() {
		$this->initialize_pages();

		foreach ( $this->components as $component ) {
			$component->load();
		}

		foreach ( $this->pages as $page ) {
			$page->load();
		}
	}

	public function is_show_dashboard_widget() {
		$this->components['core_settings'] = new Setting\EmailCoreSetting();
		$dashboard_status                  = false;
		$options                           = get_option( 'email-log-core' );
		if( isset( $options['hide_dashboard_widget'] ) ) {
			$dashboard_status = $options['hide_dashboard_widget'];
		}

		return $dashboard_status;
	}

	/**
	 * Initialize Admin page Objects.
	 *
	 * This method may be overwritten in tests.
	 *
	 * @access protected
	 */
	protected function initialize_pages() {
		$this->pages['log_list_page']    = new Page\EmailLogListPage();
                $this->pages['check_email']      = new Page\StatusPage();
	}
}
