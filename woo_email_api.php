<?php

//include_once "database_query.php";



add_action('rest_api_init', 'register_email_routes');

 

function register_email_routes() {

    register_rest_route(
        'woo-email-recorder/v1',
        '/wc-emails',
        array(
            'methods' => 'GET',
            'callback' => 'get_emails',
            'permission_callback' => '__return_true'
        )
    );

    register_rest_route(
        'woo-email-recorder/v1',
        '/wc-emails/(?P<id>\d+)',
        array(
            'methods' => 'GET',
            'callback' => 'get_email_by_id',
            'permission_callback' => '__return_true'
        )
    );

    register_rest_route(
        'woo-email-recorder/v1',
        '/wc-emails/(?P<id>\d+)/update',
        array(
            'methods' => 'PUT',
            'callback' => 'update_email',
            'permission_callback' => '__return_true'
        )
    );
    
    register_rest_route(
        'woo-email-recorder/v1',
        '/wpform-emails/',
        array(
            'methods' => 'GET',
            'callback' => 'get_emails',
            'permission_callback' => '__return_true'
        )
    );

    register_rest_route(
        'woo-email-recorder/v1',
        '/wpform-emails/(?P<id>\d+)',
        array(
            'methods' => 'GET',
            'callback' => 'get_email_by_id',
            'permission_callback' => '__return_true'
        )
    );

    register_rest_route(
        'woo-email-recorder/v1',
        '/wpform-emails/(?P<id>\d+)/update',
        array(
            'methods' => 'PUT',
            'callback' => 'wp_learn_update_form_submission',
            'permission_callback' => '__return_true'
        )
    );

    
} 
 
function get_emails() {
    
    $database_query = new Database_query();
    
    $table_name = get_mail_type(); 
    //return $table_name;
     
    // global $wpdb;
    // $table_name = $wpdb->prefix . 'wc_emails_recorded';

    // $results = $wpdb->get_results("SELECT * FROM $table_name");

    // return $results;
    return $database_query->get_emails($table_name); 

}

function get_email_by_id($request) {
     
    $database_query = new Database_query();
    $table_name = get_mail_type(); 
    $id = $request['id'];     
    //$id = get_mail_id(); 
    return $database_query->get_email_by_id($id, $table_name);   
    
}

function update_email($request) {
    global $wpdb;

    $table_name = get_mail_type(); 
    $id = $request['id'];
    $data = array(
        'is_sent' => 1
    );
    $where = array(
        'id' => $id 
    );
    $row = $wpdb->update($table_name, $data, $where);

    return $row;
}

function get_mail_type() {

    $url = $_SERVER['REQUEST_URI'];
    $method = $_SERVER['REQUEST_METHOD'];

   
    $email_type = explode("/", $url)[5]; 
    
    switch($email_type) {
        case "wc-emails" :
            $email_type = "wp_wc_emails_recorded";
            break;

        case "wpform-emails" :
            $email_type = "wp_wpform_mail_recorded";
            break; 
              
    }

    return $email_type;
} 

function get_mail_id() {

    $url = $_SERVER['REQUEST_URI'];
    $method = $_SERVER['REQUEST_METHOD'];
    
    $email_id= explode("/", $url)[6]; 
     
    return $email_id;
} 


