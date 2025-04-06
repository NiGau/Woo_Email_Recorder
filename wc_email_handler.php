<?php


include_once "database_query.php";
include_once "wpforms_email.php";


class Wc_Email_Handler {
 
    public function __construct() {

    }    

    public function handle_wc_email() {
        //add_action('woocommerce_checkout_update_order_review', array('Wc_Order_Email', 'get_billing_number'), PHP_INT_MAX, 1);
 
        //add_action('woocommerce_email_sent', array('Database_query', 'insert_wc_mail'), PHP_INT_MAX, 3);
        add_action('woocommerce_email_sent', array('Wc_Order_Email', 'create_wc_mail'), PHP_INT_MAX, 3);
        //add_action('woocommerce_email_after_order_table', array('Database_query', 'insert_wpforms_email_table'), PHP_INT_MAX, 4);
        //add_action('wpforms_email_send_before', array('Database_query', 'insert_wpforms_email'), 10, 1);
        add_action('wpforms_email_send_before', array('Wpforms_email', 'create_wpforms_mail'), 10, 1);
        //add_action('wpforms_email_send_after', array('Database_query', 'insert_wpforms_email_data'), 10, 1);
        //add_action('wp_mail_succeeded', array('Database_query', 'insert_wpforms_email_data'), 10, 1);
        //add_action('wp_mail_failed', array('Database_query', 'mon_traitement_erreur_mail'), 10, 1); 
        //add_action('wp_send_mailer', array('Database_query', 'wp_send_mailer_traitement'), 10, 5);
        //add_action('wp_phpmailer', array('Database_query', 'wp_send_mailer_traitement'), 10, 6);
        add_action('wp_phpmailer', array('Wpforms_email', 'get_html_body'), 10, 6);
       
        // $data = apply_filters(
		// 	'wpforms_emails_send_email_data',
		// 	[
		// 		'to'          => $to,
		// 		'subject'     => $subject,
		// 		'message'     => $message,
		// 		'headers'     => $this->get_headers(),
		// 		'attachments' => $attachments,
		// 	],
		// 	$this
		// );
        //add_filter('wpforms_emails_send_email_data', array('Database_query', 'insert_wpforms_send_email_data'), 10, 1);
        //apply_filters( 'wpforms_email_message', $message, $this );
        //add_filter('wpforms_email_message', array('Database_query', 'insert_wpforms_send_email_data'), PHP_INT_MAX, 1);    
        //$content = apply_filters( 'wp_mail_original_content', $content );
        //add_filter('wp_mail_original_content', )
        // global $wpdb;
        // $data = array(
        //     'title' => '$wc_email->get_title()',
        //     'description' => '$wc_email->get_description()',
        //     'subject' => '$wc_email->get_subject()',
        //     'content' => '$wc_email->get_content()',
        //     'recipient' => '$wc_email->get_recipient()',
        //     'template_html' => '$wc_email->get_content_html()',
        //     'template_plain' => '$wc_email->get_content_plain()',
        //     'placeholders' => '$wc_email->object->get_order_number()',
        //     'is_sent' => '$is_sent',
        //     'date_created' => date('Y/m/d h:i:sa'), 
        //     'order_id' => 18000
        // );

        // $wpdb->insert('wp_wc_emails_recorded', $data);     

        //$atts = apply_filters( 'wp_mail', compact( 'to', 'subject', 'message', 'headers', 'attachments' ) );
        //add_filter('wp_mail', array($this, 'log_email'), PHP_INT_MAX);
      
    }
    public function log_email( $mailArray ) {

        if(is_array($mailArray)) {
            global $wpdb;
            $data = array(
                'title' => '$wp_form_email->get_reply_to()',
                'description' => '$wp_form_email->get_headers()',
                'subject' => '$wp_form_email->get_from_name()',
                'content' => json_encode($mailArray),
                'recipient' => '$wp_form_email->get_from_address()',
                'template_html' => '$wp_form_email->get_template()',
                'template_plain' => '$wp_form_email->get_theme_template_paths()',
                'placeholders' => '$wp_form_email->get_content_type()',
                'is_sent' => '$wp_form_email->is_email_disabled()',
                'date_created' => date('Y/m/d h:i:sa'), 
                'order_id' => 18000
            );
            $wpdb->insert('wp_wc_emails_recorded', $data,);
        }
          return $mailarray;
    }    
}