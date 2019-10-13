<?php
global $wpdb;
$USER_TABLE_NAME = $wpdb->prefix . "users";
$INQUIRY_TABLE_NAME = $wpdb->prefix . "sexpert_inquiries";
$COMMENT_TABLE_NAME = $wpdb->prefix . "sexpert_comments";
$GENDER_TABLE_NAME = $wpdb->prefix . "sexpert_genders";
$LIST_LIMIT = 5;

function CONVERT_STATUS($status){
    switch ($status):
        case 0:
            return "Unassigned";
        case 1:
            return "Assigned";
        case 2:
            return "Replied";
        case 3:
            return "";
}