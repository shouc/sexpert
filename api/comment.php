<?php


function get_comments_of_inquiry(WP_REST_Request $request){
    global $wpdb, $COMMENT_TABLE_NAME, $USER_TABLE_NAME;
    $inquiry_id = $request->get_param("id");
    $comment_info_obj = $wpdb->get_results(
        $wpdb->prepare("
              SELECT 
                c.id, c.comment, c.time, u.user_login
              FROM $COMMENT_TABLE_NAME AS c
              INNER JOIN $USER_TABLE_NAME AS u ON c.author_id = u.id
              WHERE c.inquiry_id = %d
            "
            , $inquiry_id
        )
    );
    foreach ($comment_info_obj as &$comment) {
        $comment->time = CONVERT_TIME($comment->time);
    }
    wp_send_json(
        array(
            "success" => true,
            "message" => $comment_info_obj,
        )
    );
}

function comment(WP_REST_Request $request){
    global $wpdb, $INQUIRY_TABLE_NAME, $COMMENT_TABLE_NAME, $USER_TABLE_NAME;
    $id = $request->get_param("id");

    $inquiry_info_obj = $wpdb->get_results(
        $wpdb->prepare("
              SELECT 
                i.*, u.user_email
              FROM $INQUIRY_TABLE_NAME AS i 
              LEFT JOIN $USER_TABLE_NAME AS u ON u.id = i.assignee_id
              WHERE i.id = %d
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

    # TODO: Email styling
    $subject = $sender_info->user_login . " sends you a comment on inquiry $id!";
    $content = "Here is the comment:\n$comment";
    wp_mail($inquiry_info->user_email, $subject, $content);

    wp_send_json(
        array(
            "success" => true,
            "message" => "",
        )
    );
}