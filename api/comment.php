<?php

function comment(WP_REST_Request $request){
    global $wpdb, $INQUIRY_TABLE_NAME;
    $id = $request->get_param("id");
    $comment = $request->get_param("comment");

    $wpdb->update(
        $INQUIRY_TABLE_NAME,
        array(
            'comment' => $comment,
        ),
        array('id' => $id)
    );

    // Send an email

    wp_send_json(
        array(
            "success" => true,
            "message" => "",
        )
    );
}