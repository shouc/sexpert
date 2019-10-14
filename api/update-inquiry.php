<?php

function submit(WP_REST_Request $request){
    global $wpdb, $INQUIRY_TABLE_NAME;
    $id = $request->get_param("id");
    $response = $request->get_param("response");

    // TODO: add authorization check

    $wpdb->update(
        $INQUIRY_TABLE_NAME,
        array(
            'response' => $response,
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

function submit_response(WP_REST_Request $request){
    submit($request);
}

function send_response(WP_REST_Request $request){
    submit($request);
    # send_email();
}