<?php
/**
 * @wordpress-plugin
 * Plugin Name:       Sexpert
 * Plugin URI:        https://github.com/shouc/sexpert
 * Description:       You know how to use it
 * Version:           0.0.1
 * Author:            Shou C
 */

// Some must-have stuffs
if ( ! defined( 'WPINC' ) ) {
    die;
}

define( 'PLUGIN_NAME_VERSION', '0.0.1' );

// Get the db cursor
global $wpdb, $table_prefix;

require_once(ABSPATH . 'wp-settings.php');
require_once(__DIR__ . '/const.php');

// Activation
function activate_sexpert() {
    // Setup tables
    create_inquiry_table();
    create_comment_table();
}

function create_inquiry_table(){
    global $wpdb, $INQUIRY_TABLE_NAME;
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $INQUIRY_TABLE_NAME (
              `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
              `age` int(11) DEFAULT NULL,
              `gender` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
              `country` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
              `message` longtext COLLATE utf8_unicode_ci,
              `status` int(2) DEFAULT NULL,
              `time` int(11) DEFAULT NULL,
              `response` longtext COLLATE utf8_unicode_ci,
              `assignee_id` int(11) DEFAULT NULL,
              `assigner_id` int(11) DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) $charset_collate";
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}

function create_comment_table(){
    global $wpdb, $COMMENT_TABLE_NAME;
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $COMMENT_TABLE_NAME (
              `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `inquiry_id` int(11) DEFAULT NULL,
              `comment` longtext COLLATE utf8_unicode_ci,
              `author_id` int(11) DEFAULT NULL,
              `time` int(11) DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) $charset_collate";
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}

function create_config_table(){
    global $wpdb, $CONFIG_TABLE_NAME;
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $CONFIG_TABLE_NAME (
              `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `inquiry_received_title` longtext COLLATE utf8_unicode_ci,
              `inquiry_received` longtext COLLATE utf8_unicode_ci,
              `inquiry_resolved_title` longtext COLLATE utf8_unicode_ci,
              `inquiry_resolved` longtext COLLATE utf8_unicode_ci,
              `disabled_banner` longtext COLLATE utf8_unicode_ci,
              `disclaimer` longtext COLLATE utf8_unicode_ci,
              PRIMARY KEY (`id`)
            ) $charset_collate";
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
    $wpdb->insert($CONFIG_TABLE_NAME, array(
        "id"=>1
    ));
}


register_activation_hook( __FILE__, 'activate_sexpert' );

// Deactivation
function deactivate_sexpert() {
    create_inquiry_table();
    create_comment_table();
    create_config_table();
}
register_deactivation_hook( __FILE__, 'deactivate_sexpert' );


require_once "admin/index.php";
require_once "contributor/index.php";
function setup_menu(){
    add_menu_page( 'Sexpert Admin',
        'Sexpert Admin',
        'manage_options',
        'sexpert_admin',
        'init_admin' );
    add_menu_page( 'Ask the Sexpert',
        'Ask the Sexpert',
        'read',
        'sexpert',
        'init_contributor' );
}
add_action('admin_menu', 'setup_menu');

require_once __DIR__ . "/api/assign.php";
require_once __DIR__ . "/api/unassign.php";
require_once __DIR__ . "/api/add-inquiry.php";
require_once __DIR__ . "/api/update-inquiry.php";
require_once __DIR__ . "/api/status.php";
require_once __DIR__ . "/api/comment.php";
require_once __DIR__ . "/api/sexpert-status.php";


function setup_restful(){
    register_rest_route( 'sexpert/v1', '/assignment/(?P<id>\d+)', array(
        'methods' => 'POST',
        'callback' => 'assign',
    ));
    register_rest_route( 'sexpert/v1', '/assignment/(?P<id>\d+)', array(
        'methods' => 'DELETE',
        'callback' => 'unassign',
    ));
    register_rest_route( 'sexpert/v1', '/inquiry', array(
        'methods' => 'POST',
        'callback' => 'add_inquiry',
    ));
    register_rest_route( 'sexpert/v1', '/comment/(?P<id>\d+)', array(
        'methods' => 'POST',
        'callback' => 'comment',
    ));
    register_rest_route( 'sexpert/v1', '/inquiry/(?P<id>\d+)', array(
        'methods' => 'DELETE',
        'callback' => 'delete_inquiry',
    ));
    register_rest_route( 'sexpert/v1', '/response_of_inquiry/(?P<id>\d+)', array(
        'methods' => 'POST',
        'callback' => 'submit_response',
    ));
    register_rest_route( 'sexpert/v1', '/response_of_inquiry/(?P<id>\d+)', array(
        'methods' => 'PATCH',
        'callback' => 'save_response',
    ));
    register_rest_route( 'sexpert/v1', '/status/(?P<id>\d+)', array(
        'methods' => 'PATCH',
        'callback' => 'status',
    ));
    register_rest_route( 'sexpert/v1', '/mailing/(?P<id>\d+)', array(
        'methods' => 'POST',
        'callback' => 'send_response',
    ));
    register_rest_route( 'sexpert/v1', '/comment_of_inquiry/(?P<id>\d+)', array(
        'methods' => 'POST',
        'callback' => 'comment',
    ));
    register_rest_route( 'sexpert/v1', '/status_sexpert', array(
        'methods' => 'GET',
        'callback' => 'get_sexpert_status',
    ));
    register_rest_route( 'sexpert/v1', '/status_sexpert', array(
        'methods' => 'PATCH',
        'callback' => 'change_sexpert_status',
    ));
    register_rest_route( 'sexpert/v1', '/config', array(
        'methods' => 'GET',
        'callback' => 'get_config',
    ));
    register_rest_route( 'sexpert/v1', '/config', array(
        'methods' => 'PATCH',
        'callback' => 'change_config',
    ));
}
add_action( 'rest_api_init', 'setup_restful');

require_once 'home/form.php';
add_shortcode("sexpertform", 'form_creation');

function setup_scripts() {
    wp_enqueue_script('script', '/wp-content/plugins/sexpert/js/sexpert.js');
    wp_localize_script('script', 'php_variables', array(
            'nonce' => wp_create_nonce("wp_rest"),
        )
    );
    wp_enqueue_style('style', '/wp-content/plugins/sexpert/css/sexpert.css');
}
add_action( 'wp_enqueue_scripts', 'setup_scripts' );
add_action( 'admin_enqueue_scripts', 'setup_scripts' );

add_action( 'phpmailer_init', 'send_smtp_email' );
function send_smtp_email( $phpmailer ) {
    $phpmailer->isSMTP();
    $phpmailer->Host       = SMTP_HOST;
    $phpmailer->SMTPAuth   = SMTP_AUTH;
    $phpmailer->Port       = SMTP_PORT;
    $phpmailer->SMTPSecure = SMTP_SECURE;
    $phpmailer->Username   = SMTP_USERNAME;
    $phpmailer->Password   = SMTP_PASSWORD;
    $phpmailer->From       = SMTP_FROM;
    $phpmailer->FromName   = SMTP_FROMNAME;
}
