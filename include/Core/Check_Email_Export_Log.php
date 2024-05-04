<?php namespace CheckEmail\Core;

defined( 'ABSPATH' ) || exit; // Exit if accessed directly

/**
 * Export log data into CSV file
 * @since 1.0.11
 */
class Check_Email_Export_Log {

	private $separator;

	public function __construct() {

		$this->separator = ',';

		add_action('wp_ajax_ck_mail_export_logs_to_csv', array($this, 'ck_mail_export_logs_to_csv'));
	}

	/**
	 * Export email logs to csv file
	 * @since 1.0.11
	 * */
	public function ck_mail_export_logs_to_csv(){
		
		if(!isset($_GET['_wpnonce'])){
	    	wp_die( -1 );
	    }

	    if ( !wp_verify_nonce( $_GET['_wpnonce'], '_wpnonce' ) ){
       		wp_die( -1 );  
    	}

		if ( ! current_user_can( 'manage_check_email' ) ) {
			wp_die( -1 );
		}

		$logs = $this->ck_mail_generate_csv();

		header('Content-type: text/csv');
		header('Content-disposition: attachment; filename=email_logs.csv');

	    echo $logs;
	   wp_die();
	}

	/**
	 * Generate email log
	 * @since 1.0.11
	 * */
	public function ck_mail_generate_csv(){
		global $wpdb;

		$table_name = $wpdb->prefix.'check_email_log';
		$query = $wpdb->prepare("SELECT * FROM $table_name");
		$results = $wpdb->get_results($query, ARRAY_A);

		$logs_data = '';
		$csv_headings = array('Sr No', 'From', 'To', 'Subject', 'Message', 'Sent At', 'Status');
		$logs_data = implode(',', $csv_headings);
		$logs_data .= "\n";

		if( !empty($results) && is_array($results) && !empty($results) ){
			$log_cnt = 1;
			foreach ( $results as $l_key => $l_value ) {
				if( !empty($l_value) && is_array($l_value) ){

					$logs_data .= $log_cnt.$this->separator;

					$explode_headers = explode("\n", $l_value['headers']);
					if( is_array( $explode_headers) && !empty($explode_headers) ){

						foreach ( $explode_headers as $eh_key => $eh_value ) {

							$eh_value = strtolower($eh_value);

							if( strpos($eh_value, 'from:') !== false ){

								$explode_from_email = explode(':', $eh_value);
								if( is_array($explode_from_email) && isset($explode_from_email[1]) ){
									$logs_data .= preg_replace('~[\r\n]+~', '', $explode_from_email[1]).$this->separator; 
								}

							}
						}
					}
					
					$message    = str_replace(',', '', $l_value['message']);
					$logs_data .= $l_value['to_email'].$this->separator; 
					$logs_data .= $l_value['subject'].$this->separator; 
					$logs_data .= $message.$this->separator; 
					$logs_data .= date('d-m-Y H:i:s', strtotime($l_value['sent_date'])).$this->separator; 
					$logs_data .= empty($l_value['error_message'])?'Success':$l_value['error_message']; 

					$log_cnt++;

					$logs_data .= " \n ";
				}
			}
		}
		return $logs_data;
	}
}
