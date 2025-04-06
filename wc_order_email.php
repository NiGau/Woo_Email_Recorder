<?php 

include_once 'database_query.php';

global $num;  

class Wc_Order_Email {
    
    //public $id;

    public $title;

    public $description;

    public $subject;

    public $content;

    public $reciptient;

    public $template_html;

    public $template_plain;

    public $reciptient_number;

    public $is_sent;

    public $date_created;

    public $order_id; 

    static $order_data = "405";
   

    public function __construct($title, $description, $subject, $content, $recipient, $template_html, $template_plain, $order_id, $reciptient_number, $is_sent) {
        $this->title = $title;
        $this->description = $description;
        $this->subject = $subject;
        $this->content = $content;
        $this->recipient = $recipient;
        $this->template_html = $template_html;
        $this->template_plain = $template_plain;
        $this->order_id = $order_id;
        $this->reciptient_number = $reciptient_number;
        $this->date_created = current_time('mysql');
        $this->is_sent = $is_sent;
    }

   
    public function create_wc_mail($is_sent, $id, $wc_email) {
        

        $database_query = new Database_query();

        if($id == 'customer_new_account' || $id == 'customer_reset_password') {
            $order_phone_number = '-';
            $order_id = 404;
        } else {
            $order_id = $wc_email->format_string("{order_number}");
            $order = wc_get_order($order_id);
            if($wc_email->get_recipient() == get_option('admin_email')) {
                $order_phone_number = '79808166';     
            } else {
                if($id = 'customer_processing_order') {
                    
                    // if(Wc_Order_Email::$order_data  == null || Wc_Order_Email::$order_data == "") {
                    //     $order_phone_number = 'null';
                    // } else {
                    //     $order_phone_number = Wc_Order_Email::$order_data;;
                    // }
                    if(isset($_POST) && !empty($_POST)) {
                        $order_phone_number = $_POST['billing_phone'];  
                        //$order_phone_number = serialize($_POST);   
                    } else {
                        $order_phone_number = 'Null';
                    }
                    //$order_phone_number = Wc_Order_Email::$order_data;
                } else {
                    $order_phone_number = $order->get_billing_phone(); 
                }
                
            }
        }

        $wc_email_created = new Wc_Order_Email(
            $wc_email->get_title(),  $wc_email->get_headers(), $wc_email->get_subject(), $wc_email->get_content(),  
            $wc_email->get_recipient(), $wc_email->get_content_html(), $wc_email->get_content_plain(), $order_id, 
            $order_phone_number, $is_sent);  
        /*       
            $this->title = $wc_email->get_title();
            $this->description = $wc_email->get_headers();
            $this->subject = $wc_email->get_subject();
            $this->content = $wc_email->get_content();
            $this->recipient = $wc_email->get_recipient();
            $this->template_html = $wc_email->get_content_html();
            $this->template_plain = $wc_email->get_content_plain();
            $this->order_id = $order_id;
            $this->reciptient_number = $order_phone_number;
            $this->date_created = current_time('mysql');
            $this->is_sent = $is_sent;
        */

        $database_query->save_wc_mail($wc_email_created);

        return $wc_email_created;    
    }    
    
    public function get_phone_number_from_checkout($data)  {

        global $num;
        parse_str($data, $result_array);

        // Accéder aux valeurs individuelles
        $billing_phone = $result_array['billing_phone'];
        $num = $billing_phone;

        return $billing_phone;
    }

    public function  get_billing_number($data) {
        
        $database_query = new Database_query();
        
        global $num;

        parse_str($data, $result_array);
        

        // Accéder aux valeurs individuelles
        $billing_phone = $result_array['billing_phone'];


        Wc_Order_Email::$order_data = $billing_phone; 
        //$num = $billing_phone;

        // if($billing_phone == null) {
        //     $num = '$billing_phone';
        // } else {
        //     $num = $billing_phone;
        // }
        

        $num = new Wc_Order_Email(
            '$wc_email->get_title()',  '$wc_email->get_headers()', '$wc_email->get_subject()', '$wc_email->get_content()',  
            '$wc_email->get_recipient()', '$wc_email->get_content_html()','$wc_email->get_content_plain()', '$order_id',        
            Wc_Order_Email::$order_data, '$is_sent');
        
            $database_query->save_wc_mail($num);
        //$num = $result_array['billing_phone']; 

        //$num = $wc_email_createde;
        return $num;
    } 

}    