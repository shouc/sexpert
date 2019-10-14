<?php

function comment(WP_REST_Request $request){
    # TODO: Link $COMMENT_TABLE_NAME
    global $wpdb, $INQUIRY_TABLE_NAME, $COMMENT_TABLE_NAME, $USER_TABLE_NAME;

    $id = $request->get_param("id");

    $inquiry_info_obj = $wpdb->get_results(
        $wpdb->prepare("
              SELECT 
                i.*, u.user_email
              FROM $INQUIRY_TABLE_NAME AS i 
              LEFT JOIN $USER_TABLE_NAME AS u ON u.id = i.assignee_id
              WHERE id = %d
            "
            , $id
        )
    );
    if (count($inquiry_info_obj) == 0){
        wp_send_json(
            array(
                "success" => false,
                "message" => "No inquiry found",
            )
        );
        return;
    }
    $inquiry_info = $inquiry_info_obj[0];
    $sender_info = wp_get_current_user();
    $comment = $request->get_param("comment");

    $wpdb->insert(
        $COMMENT_TABLE_NAME,
        array(
            "inquiry_id" => $id,
            "comment" => $comment,
            "author_id" => $sender_info->ID,
            "time" => time(),
        )
    );

    # TODO: Emailing
    $subject = $sender_info->user_login . " sends you a comment!";
    // $content = $
    // wp_mail($inquiry_info->user_email, $subject, $content);

    wp_send_json(
        array(
            "success" => true,
            "message" => "",
        )
    );
}