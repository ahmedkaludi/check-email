<?php namespace EmailLog\Core\UI\Setting;

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

/**
 * A Section used in Check Email Log Settings page.
 */
class EmailLogSettingSection {

	public $id;
	public $title;
	public $callback;
	public $option_name;

	public $sanitize_callback;


	public $fields = array();

	public $default_value = array();

	public $field_labels = array();

	public function add_field( EmailLogSettingField $field ) {
		$this->fields[] = $field;
	}
}
