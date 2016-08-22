<?php

/**
 * Plugin Name: Suggest Correction
 * Plugin URI: http://www.menapo.com
 * Description: Suggest a correction for article.
 * Version: 1.0
 * Author: Omer Kalim
 * Author URI: http://www.omerkalim.com
 * License: Private
 */

if (basename($_SERVER['SCRIPT_NAME']) == basename(__FILE__)) exit('Please do not load this page directly');

require_once( dirname(__FILE__) . '/MPPlugin.php' );

class MPSuggestCorrection extends MPPlugin {
	
    function __construct() {
        parent::__construct( 'MPSuggestCorrection', 1.0 );
        
        //Registering the Function that will be accessed via AJAX
        add_action( 'wp_ajax_ajax_add_suggest_correction', array( &$this, 'ajax_add_suggest_correction' ) );
        add_action( 'wp_ajax_nopriv_ajax_add_suggest_correction', array( &$this, 'ajax_add_suggest_correction' ) );
        //Function that sets the Mail Type
        add_filter( 'wp_mail_content_type', array( &$this, 'set_html_content_type' ) );
    }

    /**
     * 	Create database table for Suggestion Correction.
     */
    protected function install() {
        global $wpdb;

        $wpdb->show_errors();
        $table = $wpdb->prefix . "suggest_correction";
        $sql = "";
        $charset_collate = "";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        if ( ! empty($wpdb->charset) ) $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
        if ( ! empty($wpdb->collate) ) $charset_collate .= " COLLATE $wpdb->collate";

        //Check, if table exists?
        if ( $wpdb->get_var("SHOW TABLES LIKE '$table'") != $table ) { 
            $sql = "CREATE TABLE " . $table ." ( PRIMARY KEY (`correction_id`), `correction_id` INT NOT NULL AUTO_INCREMENT, `post_id` INT NOT NULL, `author_id` INT NOT NULL, `corrector_name` VARCHAR(50) NOT NULL, `corrector_email` VARCHAR(50) NOT NULL, `correction` VARCHAR(1000) NOT NULL, `correction_time` DATETIME NOT NULL default '0000-00-00 00:00:00' ) $charset_collate;";
            dbDelta($sql);
        }
        parent::install();
    }

    static function ajax_add_suggest_correction() {
        global $wpdb;
        
        $post_id = intval( $_POST['hidden_post_id'] );
        $author_id = intval( $_POST['hidden_author_id'] );
        $c_name = $_POST['corrector_name'];
        $c_email = $_POST['corrector_email'];
        $correction = $_POST['correction_text'];
        $now = current_time( 'mysql' );

        $wpdb->show_errors();
        $table = $wpdb->prefix . "suggest_correction";
        
//        $query = "INSERT INTO $table (post_id, author_id, corrector_name, corrector_email, correction, correction_time) VALUES ($post_id, $author_id, '$c_name', '$c_email', '$correction', '$now' );";
                
        $result = $wpdb->query( $wpdb->prepare( "INSERT INTO $table (post_id, author_id, corrector_name, corrector_email, correction, correction_time) VALUES (%d, %d, %s, %s, %s, %s );", $post_id, $author_id, $c_name, $c_email, $correction, $now ) );
        
        if ( $result == FALSE ){
            echo "Error";
        }else{
            echo "Success";
            
            //EMAIL Sending 
            $user_meta = get_userdata($author_id);
            
            if( get_user_meta($author_id, 'pref_email', true) != "" ) {
                $to = get_user_meta($author_id, 'pref_email', true);
            } else {
                $to = $user_meta->user_email;
            }
            
            $subject = "Suggestion Correction";
            $message = "There is a suggestion for you in the article!";
            $message .= "<br />Article: " . get_the_title($post_id);
            $message .= "<br />Corrector Name: " . $c_name;
            $message .= "<br /><br />" . $correction;
            
            $headers = "From: noreply@kasra.co \r\n";
            $headers .= "Bcc:$c_email \r\n";

            wp_mail($to, $subject, $message, $headers);            
        }
        die();
    }

    public static function set_html_content_type() {
        return 'text/html';
    }
}