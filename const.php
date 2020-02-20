<?php
global $wpdb;
$USER_TABLE_NAME = $wpdb->prefix . "users";
$INQUIRY_TABLE_NAME = $wpdb->prefix . "sexpert_inquiries";
$COMMENT_TABLE_NAME = $wpdb->prefix . "sexpert_comments";
$GENDER_TABLE_NAME = $wpdb->prefix . "sexpert_genders";
$CONFIG_TABLE_NAME = $wpdb->prefix . "sexpert_config";

$POST_TABLE_NAME = $wpdb->prefix . "posts";
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

function CONVERT_TIME($timestamp)
{
    if(!$timestamp) {
        return "Unknown Date";
    }

    $periods         = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
    $lengths         = array("60","60","24","7","4.35","12","10");

    $now             = time();

    // is it future date or past date
    if($now > $timestamp) {
        $difference     = $now - $timestamp;
        $tense         = "ago";

    } else {
        $difference     = $timestamp - $now;
        $tense         = "from now";
    }

    for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
        $difference /= $lengths[$j];
    }

    $difference = round($difference);

    if($difference != 1) {
        $periods[$j].= "s";
    }

    return "$difference $periods[$j] {$tense}";
}