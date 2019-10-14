<?php

function submit(WP_REST_Request $request){
    global $wpdb, $INQUIRY_TABLE_NAME;
    $id = $request->get_param("id");
    $comment = $request->get_param("message");

    $wpdb->update(
        $INQUIRY_TABLE_NAME,
        array(
            'message' => $comment,
        ),
        array('id' => $id)
    );

    wp_send_json(
        array(
            "success" => true,
            "message" => "",
        )
    );
}

function submit_message(WP_REST_Request $request){
    submit($request);
}

function send_message(WP_REST_Request $request){
    submit($request);
    # send_email();
}