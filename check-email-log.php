<?php

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

/**
 * Load Check Email Log.
 */
function check_email_log( $plugin_file ) {
	global $email_log;

	$plugin_dir = plugin_dir_path( $plugin_file );

	// setup autoloader.
	require_once 'include/CheckEmailLogAutoloader.php';

	$loader = new \EmailLog\CheckEmailLogAutoloader();
	$loader->add_namespace( 'EmailLog', $plugin_dir . 'include' );

	if ( file_exists( $plugin_dir . 'tests/' ) ) {
		// if tests are present, then add them.
		$loader->add_namespace( 'EmailLog', $plugin_dir . 'tests/wp-tests' );
	}

	$loader->add_file( $plugin_dir . 'include/Util/helper.php' );

	$loader->register();

	$email_log = new \EmailLog\Core\CheckEmailLog( $plugin_file, $loader, new \EmailLog\Core\DB\CheckEmailTableManager() );

	$email_log->add_loadie( new \EmailLog\Core\CheckEmailLogger() );
	$email_log->add_loadie( new \EmailLog\Core\UI\CheckEmailUILoader() );

	$email_log->add_loadie( new \EmailLog\Core\Request\CheckEmailNonceChecker() );
	$email_log->add_loadie( new \EmailLog\Core\Request\EmailLogListAction() );


	// `register_activation_hook` can't be called from inside any hook.
	register_activation_hook( $plugin_file, array( $email_log->table_manager, 'on_activate' ) );

	// Ideally the plugin should be loaded in a later event like `init` or `wp_loaded`.
	// But some plugins like EDD are sending emails in `init` event itself,
	// which won't be logged if the plugin is loaded in `wp_loaded` or `init`.
	add_action( 'plugins_loaded', array( $email_log, 'load' ), 101 );
}

/**
 * Return the global instance of Check Email Log plugin.
 */
function check_email() {
	global $email_log;

	return $email_log;
}
