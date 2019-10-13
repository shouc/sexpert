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
              `response` longtext COLLATE utf8_unicode_ci,
              `assignee_id` int(11) DEFAULT NULL,
              `assigner_id` int(11) DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) $charset_collate;";
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
            ) $charset_collate;";
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}


register_activation_hook( __FILE__, 'activate_sexpert' );

// Deactivation
function deactivate_sexpert() {
    // echo "Deactivated";
}
register_deactivation_hook( __FILE__, 'deactivate_sexpert' );


require_once "admin/index.php";
function setup_menu(){
    add_menu_page( 'Sexpert Page',
        'Sexpert Admin',
        'manage_options',
        'sexpert_admin',
        'init_admin' );
    add_menu_page( 'Sexpert 2',
        'Sexpert',
        'read',
        'sexpert',
        'init_admin' );
}
add_action('admin_menu', 'setup_menu');

require_once __DIR__ . "/api/assign.php";
require_once __DIR__ . "/api/add-inquiry.php";
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
    register_rest_route( 'sexpert/v1', '/inquiries', array(
        'methods' => 'GET',
        'callback' => 'get_inquiry',
    ));
}
add_action( 'rest_api_init', 'setup_restful');

require_once 'home/form.php';
add_shortcode("sexpertform", 'form_creation');

function setup_scripts() {
    wp_enqueue_script( 'script', '/wp-content/plugins/sexpert/js/sexpert.js');
}
add_action( 'wp_enqueue_scripts', 'setup_scripts' );