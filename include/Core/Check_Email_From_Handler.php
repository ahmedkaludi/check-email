<?php namespace CheckEmail\Core;

defined( 'ABSPATH' ) || exit; // Exit if accessed directly

class Check_Email_From_Handler {
    
    public $options;

    public function __construct() {

        $this->options = get_option('check-email-log-core', false);
        
        add_filter( 'wp_mail', array( $this, 'override_values'), 15 );
        
    }

    public function override_enabled(){

        if( $this->options && isset( $this->options['override_emails_from'] ) && $this->options['override_emails_from'] ){
            return true;
        }

        return false;
    }

    public function override_values( $headers ) {
        if( $this->override_enabled() && isset( $this->options['email_from_email'] ) && '' != $this->options['email_from_email']){
           
            $headers['headers'] = "MIME-Version: 1.0\r\n";

            $email = $this->options['email_from_email'];
 
            if( $this->override_enabled() && isset( $this->options['email_from_name'] ) && '' != $this->options['email_from_name'] ){
                
                $headers['headers'] .= "From: " . $this->options['email_from_name'] . " <". $email .">\r\n" ;
            }else{
               
                $headers['headers'] .= "From: <". $email .">\r\n" ;
            }

            $headers['headers'] .= "Content-Type: text/plain; charset=\"UTF-8\"\r\n";
        }


        return $headers;
    }
}
