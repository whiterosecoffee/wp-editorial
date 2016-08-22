<?php 

//Ajax action for Contact Form
//Registering the Function that will be accessed via AJAX
add_action( 'wp_ajax_ajax_contact_form', array( 'MPContactForm', 'ajax_contact_form' ) );
add_action( 'wp_ajax_nopriv_ajax_contact_form', array( 'MPContactForm', 'ajax_contact_form' ) );
//Function that sets the Mail Type
add_filter( 'wp_mail_content_type', array( 'MPContactForm', 'mail_content_type' ) );

class MPContactForm {

    const TO_ADDRESS = "hello@kasra.co";
	const SUBJECT = 'Message from Contact Us Form';

	public static function ajax_contact_form() {
        
        $contact_name = $_POST['contact_name'];
        $contact_email = $_POST['contact_email'];
        $contact_text = $_POST['contact_form_text'];

        //EMAIL Sending 
        $message = "<b>Contact Form<b><br />";
        $message .= "<br />Name: " . $contact_name;
        $message .= "<br />Email: " . $contact_email;
        $message .= "<br />";
        $message .= "<br />" . $contact_text;
        
        $headers = "From: noreply@kasra.co \r\n";

        wp_mail( self::TO_ADDRESS, __( self::SUBJECT, 'menapost-custom' ), $message, $headers);   
        
        die();
    }

    public static function mail_content_type() {
        return 'text/html';
    }
	
}