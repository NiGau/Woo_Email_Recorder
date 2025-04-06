<?php

/**
 * Plugin Name: Woo Email Recorder
 * Plugin URI: https://day-peak.com/woo-email-recorder
 * Description: Enregistre les emails envoyés par WooCommerce dans une nouvelle table de base de données.
 * Version: 1.0.0
 * Author: NiGau
 * Author URI: https://day-peak.com
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: woo-email-recorder
 */

// Empêche l'accès direct au fichier
defined('ABSPATH') or die('Aucun accès direct n\'est autorisé.');

include_once("wc_email_handler.php");
include_once("woo_email_api.php");

/**
 * Check if WooCommerce is active
 */
if(in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    
    register_activation_hook(__FILE__, array('Woo_Email_Recorder', 'activate'));

    class Woo_Email_Recorder {
        
        // public function __construct() {

        // }

        public static function init() {
            // Instancie les gestionnaires d'emails et d'API
            $email_handler = new wc_email_handler();
            $email_handler->handle_wc_email();
            //$api_handler = new WooEmailRecorder\APIHandler();
        }

        public static function activate() {
            global $wpdb;
            $table_name = $wpdb->prefix . 'wc_emails_recorded';
            $table_name2 = $wpdb->prefix . 'wpform_mail_recorded';


            $charset_collate = $wpdb->get_charset_collate();

            $sql = "CREATE TABLE $table_name (
                id INT NOT NULL AUTO_INCREMENT,
                order_id BIGINT NOT NULL,  
                title VARCHAR(255) NOT NULL,
                subject  VARCHAR(255) NOT NULL,
                description TEXT NOT NULL,
                content LONGTEXT NOT NULL,
                recipient VARCHAR(255) NOT NULL,
                template_html LONGTEXT NOT NULL,
                template_plain LONGTEXT NOT NULL,
                placeholders LONGTEXT NOT NULL,
                is_sent BOOLEAN NOT NULL, 
                date_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (id)
            ) $charset_collate;";

            $sql2 = "CREATE TABLE $table_name2 (
                id INT NOT NULL AUTO_INCREMENT,             
                title VARCHAR(255) NOT NULL,
                header  VARCHAR(255) NOT NULL,                
                content LONGTEXT NOT NULL,
                recipient VARCHAR(255) NOT NULL,
                template_html LONGTEXT NOT NULL,                
                contact_name VARCHAR(255) NOT NULL,
                contact_email VARCHAR(255) NOT NULL,
                contact_message VARCHAR(255) NOT NULL,                
                date_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (id)
            ) $charset_collate;";

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
            dbDelta($sql2);
        }
    }
}


// Initialise le plugin
add_action('plugins_loaded', array('Woo_Email_Recorder', 'init'));
//register_activation_hook(__FILE__, array('Woo_Email_Recorder', 'activate'));
