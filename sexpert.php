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

if(!isset($wpdb))
{
    require_once(ABSPATH . 'wp-settings.php');
}

// Activation
function activate_sexpert() {
    // Setup tables
    create_question_table();
}

function create_question_table(){
    global $wpdb;
    $table_name = $wpdb->prefix . "sexpert_question";
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $table_name (
              `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
              `age` int(11) DEFAULT NULL,
              `gender` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
              `country` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
              `message` longtext COLLATE utf8_unicode_ci,
              `status` int(2) DEFAULT NULL,
              `comment` longtext COLLATE utf8_unicode_ci,
              `assignee_id` int(11) DEFAULT NULL,
              `assigner_id` int(11) DEFAULT NULL,
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
        'Sexpert',
        'manage_options',
        'sexpert',
        'init_admin' );
}

add_action('admin_menu', 'setup_menu');

require_once 'home/form.php';
add_shortcode("sexpertform", 'form_creation');
