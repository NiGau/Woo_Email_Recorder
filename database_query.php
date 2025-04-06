<?php 


include_once 'wc_order_email.php';
include_once 'wpforms_email.php'; 

global $wpdb;

$dbprefix = $wpdb->prefix; 


class Database_query {

     
    
    public static $table_name = "wp_wc_emails_recorded";
    public static $table_name2 = "wp_wpform_mail_recorded";

    public function __construct() {

    }
       
    public function insert_wc_email($wc_email) {
        if($wc_email instanceof Wc_Order_Email) {
            $data = array(
                'title' => $wc_email->title,
                'description' => $wc_email->description,
                'subject' => $wc_email->subject,
                'content' => $wc_email->content,
                'recipient' => $wc->recipient,
                'template_html' => $wc_email->template_html,
                'template_plain' => $wc_email->template_plain,
                'placeholders' => $wc_email->placeholders,
                'is_sent' => $wc_email->is_sent,
                'date_created' => date('Y/m/d h:i:sa'), 
                'order_id' => 18000
            );
        } else {
            $data = array(
                'title' => '$wc_email->title',
                'description' => '$wc_email->description',
                'subject' => '$wc_email->subject',
                'content' => '$wc_email->content',
                'recipient' => '$wc->recipient',
                'template_html' => '$wc_email->template_html',
                'template_plain' => '$wc_email->template_plain',
                'placeholders' => '$wc_email->placeholders',
                'is_sent' => '$wc_email->is_sent',
                'date_created' => date('Y/m/d h:i:sa'), 
                'order_id' => 18000
            );
        }
        $wpdb->insert($data, $this::$table_name);     
    }

    public function insert_wc_mail($is_sent, $id, $wc_email) {
        global $wpdb;
        
        if($id == 'customer_new_account' || $id == 'customer_reset_password') {
            $order_phone_number = '-';
            $order_id = 404;
        } else {
            $order_id = $wc_email->format_string("{order_number}");
            $order = wc_get_order($order_id);
            if($wc_email->get_recipient() == get_option('admin_email')) {
                $order_phone_number = '79808166';     
            } else {
                $order_phone_number = $order->get_billing_phone(); 
            }
        }
        
        $data = array(
            'title' => $wc_email->get_title(),
            'description' => $wc_email->get_headers(),
            'subject' => $wc_email->get_subject(),
            'content' => $wc_email->get_content(),
            'recipient' => $wc_email->get_recipient(),
            'template_html' => $wc_email->get_content_html(),
            'template_plain' => $wc_email->get_content_plain(),
            'placeholders' => $order_phone_number,
            'is_sent' => $is_sent,
            'date_created' => current_time('mysql'), 
            'order_id' => $order_id
        );

        $wpdb->insert(self::$table_name, $data,);     
    }

    public function save_wc_mail(Wc_Order_Email $wc_email) {
        global $wpdb;
      
        $data = array(
            'title' => $wc_email->title,
            'description' => $wc_email->description,
            'subject' => $wc_email->subject,
            'content' => $wc_email->content,
            'recipient' => $wc_email->recipient,
            'template_html' => $wc_email->template_html,
            'template_plain' => $wc_email->template_plain,
            'placeholders' => $wc_email->reciptient_number,
            'is_sent' => $wc_email->is_sent,
            'date_created' => current_time('mysql'), 
            'order_id' => $wc_email->order_id
        );

        $wpdb->insert(self::$table_name, $data,);     
    }

    public function save_wpform_mail(Wpforms_email $wpform_email) {
        global $wpdb;
      
        $data = array(
            'title' => $wpform_email->subject,
            'header' =>  $wpform_email->header,
            'content' => $wpform_email->content,
            'recipient' => $wpform_email->reciptient,
            'template_html' => $wpform_email->template_html,
            'contact_name' => $wpform_email->contact_name,
            'contact_email' => $wpform_email->contact_mail,
            'contact_message' => $wpform_email->contact_message, 
            'date_created' =>  current_time('mysql')
        );

        $wpdb->insert(self::$table_name2, $data,);     
    }

    public function insert_wpforms_email($wp_form_email) {
        global $wpdb;
        $data = array(
            'title' => $wp_form_email->get_reply_to(),
            'description' => $wp_form_email->get_headers(),
            'subject' => $wp_form_email->get_from_name(),
            'content' => $wp_form_email->get_content_type(),
            'recipient' => $wp_form_email->get_from_address(),
            'template_html' => $wp_form_email->get_template(),
            'template_plain' => $wp_form_email->get_theme_template_paths(),
            'placeholders' => json_encode(get_object_vars($wp_form_email)),
            'is_sent' => $wp_form_email->is_email_disabled(),
            'date_created' => date('Y/m/d h:i:sa'), 
            'order_id' => 18000
        );
        $wpdb->insert(self::$table_name, $data,);
    } 

    public function insert_wpforms_email_table($order, $sent_to_admin, $plain_text, $wc_email ) {
        global $wpdb;
        $data = array(
            'title' => $wc_email->title,
            'description' => $wc_email->description,
            'subject' => $wc_email->get_subject(),
            'content' => $wc_email->get_content(),
            'recipient' => $wc_email->recipient,
            'template_html' => $wc_email->template_html,
            'template_plain' => $wc_email->template_plain,
            'placeholders' => $order->get_billing_phone(),
            'is_sent' => $wc_email->is_sent,
            'date_created' => current_time('mysql'), 
            'order_id' => $wc_email->order_id
        );
        $wpdb->insert(self::$table_name, $data,);
    } 
    
    public function insert_wpforms_email_data($wp_form_email) {
        global $wpdb;
        $data = array(
            'title' => '$wp_form_email->get_reply_to()',
            'description' => '$wp_form_email->get_headers()',
            'subject' => '$wp_form_email->get_from_name()',
            'content' => json_encode(get_object_vars($wp_form_email)),
            'recipient' => '$wp_form_email->get_from_address()',
            'template_html' => $wp_form_email->get_template(),
            'template_plain' => '$wp_form_email->get_theme_template_paths()',
            'placeholders' => '$wp_form_email->get_content_type()',
            'is_sent' => '$wp_form_email->is_email_disabled()',
            'date_created' => date('Y/m/d h:i:sa'), 
            'order_id' => 18000
        );
        $wpdb->insert(self::$table_name, $data,);
    } 

    public function insert_wpforms_send_email_data($data) {
        global $wpdb;
        $data_email = array(
            'title' => '$wp_form_email->get_reply_to()',
            'description' => '$wp_form_email->get_headers()',
            'subject' => '$wp_form_email->get_from_name()',
            'content' => $data,
            'recipient' => '$wp_form_email->get_from_address()',
            'template_html' => '$wp_form_email->get_template()',
            'template_plain' => '$wp_form_email->get_theme_template_paths()',
            'placeholders' => '$wp_form_email->get_content_type()',
            'is_sent' => '$wp_form_email->is_email_disabled()',
            'date_created' => date('Y/m/d h:i:sa'), 
            'order_id' => 18000
        );
        $wpdb->insert(self::$table_name, $data_email);
        return $data;
    } 
    
    public function insert_wpforms_email_checkout($data) {
        global $wpdb;
        // Récupérer les informations sur l'erreur
        // $erreur_code = $wp_error->get_error_code();
        // $erreur_message = $wp_error->get_error_message();
        // $erreur_data = $wp_error->get_error_data();
    
        // Faites quelque chose avec l'erreur, par exemple :
        // Enregistrez-la dans un journal, envoyez une notification, etc.
    
        // Exemple : Affichage de l'erreur
        //echo "Erreur d'envoi d'email : $erreur_code - $erreur_message";
         
        $data_email = array(
            'title' => '$wp_form_email->get_reply_to()',
            'description' => '$wp_form_email->get_headers()',
            'subject' => '$wp_form_email->get_from_name()',
            'content' => $data,
            'recipient' => '$wp_form_email->get_from_address()',
            'template_html' => '$wp_form_email->get_template()',
            'template_plain' => '$wp_form_email->get_theme_template_paths()',
            'placeholders' => '$wp_form_email->get_content_type()',
            'is_sent' => '$wp_form_email->is_email_disabled()',
            'date_created' => date('Y/m/d h:i:sa'), 
            'order_id' => 18000
        );
        $wpdb->insert(self::$table_name, $data_email);
        //return $data;
        // Vous pouvez également utiliser les informations de l'erreur pour prendre des actions spécifiques
       
    } 

    public function wp_send_mailer_traitement($phpmailer, $to, $subject, $message, $headers, $attachments) {
        global $wpdb;
        $data_email = array(
            'title' => $subject,
            'description' => json_encode($phpmailer->createHeader()),
            'subject' => json_encode($phpmailer->getCustomHeaders()),
            'content' => $phpmailer->createBody(),
            'recipient' => json_encode($phpmailer->getAllRecipientAddresses()),
            'template_html' => $message,
            'template_plain' => $attachments,
            'placeholders' => json_encode($phpmailer->getAttachments()),
            'is_sent' => '$wp_form_email->is_email_disabled()',
            'date_created' => date('Y/m/d h:i:sa'), 
            'order_id' => 18000
        );
        $wpdb->insert(self::$table_name, $data_email);
         
    } 

    public static function get_order_phone_number ($order_id) {
        $order = wc_get_order($order_id);
        // if(isset($order->get_billing_phone())) {
        //     $order_phone_number = $order->get_billing_phone();
        // }
        $order_phone_number = $order->get_billing_phone(); 
        return $order_phone_number;
    }

    public function get_emails($mail_type) {
        global $wpdb;
        //$table_name = $wpdb->prefix . 'wc_emails_recorded';

        $results = $wpdb->get_results("SELECT * FROM $mail_type");

        return $results;
    }

    public function get_email_by_id($id, $mail_type) {
        global $wpdb;
        $result = $wpdb->get_results("SELECT * FROM $mail_type WHERE id= $id");

        return $result[0];
    }
}