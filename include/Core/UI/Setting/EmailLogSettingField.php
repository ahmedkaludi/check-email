<?php namespace EmailLog\Core\UI\Setting;

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

/**
 * A setting field used in Check Email Log Settings page.
 *
 * @see add_settings_field()
 */
class EmailLogSettingField {

	public $id;
	public $title;
	public $callback;
	public $args = array();
}
