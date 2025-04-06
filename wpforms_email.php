<?php 

include_once 'database_query.php';

global $wpform_mail_sended; 

class Wpforms_email {
    
    //public $id;

    static public $subject;

    public $header;

    public $reciptient;
    
    public $content;

    static public $template_html;

    public $contact_name;

    public $contact_mail;

    public $contact_message; 

    public $date_created;

    

   

    public function __construct() {
         
    }



    public function create_wpforms_mail($wp_form_email) {

        $database_query = new Database_query();

        global $wpform_mail_sended;
        //$database_query = new Database_query();
        $wpform_email_data = new Wpforms_email();

        $wpform_email_data->header = $wp_form_email->get_headers();
        $wpform_email_data->reciptient = $wp_form_email->get_from_address();
        $wpform_email_data->content = json_encode(get_object_vars($wp_form_email));
        $wp_form_email_json_data = json_decode($wpform_email_data->content );
        $wpform_email_data->contact_name = $wp_form_email_json_data->fields[0]->value;
        $wpform_email_data->contact_mail = $wp_form_email_json_data->fields[1]->value;    
        $wpform_email_data->contact_message = $wp_form_email_json_data->fields[2]->value;
         
        //$database_query->save_wpform_mail($wpform_email_data);

        $wpform_mail_sended = $wpform_email_data;

        return $wp_form_email;
          
    }     

    public function get_html_body($phpmailer, $to, $subject, $message, $headers, $attachments) {

        global $wpform_mail_sended;

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



        if(!empty($wpform_mail_sended)) {
            
            $database_query = new Database_query();
            $wpform_mail_sended->template_html = $message; 
            $wpform_mail_sended->subject = $subject;
            $database_query->save_wpform_mail($wpform_mail_sended);
    
            return $wpform_email_data;
        }          
    }

  
} 