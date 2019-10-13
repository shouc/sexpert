<?php
global $wpdb;
$USER_TABLE_NAME = $wpdb->prefix . "users";
$INQUIRY_TABLE_NAME = $wpdb->prefix . "sexpert_inquiries";
$COMMENT_TABLE_NAME = $wpdb->prefix . "sexpert_comments";
$GENDER_TABLE_NAME = $wpdb->prefix . "sexpert_genders";
$LIST_LIMIT = 5;

function CONVERT_STATUS_CODE($status_code){
    switch ($status_code) {
        case 0:
            return "Unassigned";
        case 1:
            return "Assigned";
        case 2:
            return "Editing";
        case 3:
            return "Edited";
        case 4:
            return "Sent";
        default:
            return "Unknown Status";
    }
}

function CONVERT_GENDER_CODE($gender_code){
    switch ($gender_code) {
        case 0:
            return "Trans Male";
        case 1:
            return "Cis Male";
        case 2:
            return "Tran Female";
        case 3:
            return "Cis Female";
        case 4:
            return "I don't know - Male";
        case 5:
            return "I don't know - Female";
        case 6:
            return "Don't want to disclose";
        default:
            return "Unknown Gender";
    }
}

function CONVERT_TIME($timestamp){
    return $timestamp;
}