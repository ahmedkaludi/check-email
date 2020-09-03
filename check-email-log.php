<?php

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

/**
 * Load Check Email Log.
 */
function check_email_log( $plugin_file ) {
	global $check_email;

	$plugin_dir = plugin_dir_path( $plugin_file );

	// setup autoloader.
	require_once 'include/class-check-email-log-autoloader.php';

	$loader = new \CheckEmail\Check_Email_Log_Autoloader();
	$loader->add_namespace( 'CheckEmail', $plugin_dir . 'include' );

	if ( file_exists( $plugin_dir . 'tests/' ) ) {
		// if tests are present, then add them.
		$loader->add_namespace( 'CheckEmail', $plugin_dir . 'tests/wp-tests' );
	}

	$loader->add_file( $plugin_dir . 'include/Util/helper.php' );

	$loader->register();

	$check_email = new \CheckEmail\Core\Check_Email_Log( $plugin_file, $loader, new \CheckEmail\Core\DB\Check_Email_Table_Manager() );

	$check_email->add_loadie( new \CheckEmail\Core\Check_Email_Logger() );
	$check_email->add_loadie( new \CheckEmail\Core\UI\Check_Email_UI_Loader() );

	$check_email->add_loadie( new \CheckEmail\Core\Request\Check_Email_Nonce_Checker() );
	$check_email->add_loadie( new \CheckEmail\Core\Request\Check_Email_Log_List_Action() );


	// `register_activation_hook` can't be called from inside any hook.
	register_activation_hook( $plugin_file, array( $check_email->table_manager, 'on_activate' ) );

	// Ideally the plugin should be loaded in a later event like `init` or `wp_loaded`.
	// But some plugins like EDD are sending emails in `init` event itself,
	// which won't be logged if the plugin is loaded in `wp_loaded` or `init`.
	add_action( 'plugins_loaded', array( $check_email, 'load' ), 101 );
}

function check_email() {
	global $check_email;

	return $check_email;
}
