<?php

function submit(WP_REST_Request $request,
                $need_update_status=false,
                $new_status=0){
    global $wpdb, $INQUIRY_TABLE_NAME;
    $id = $request->get_param("id");
    $response = $request->get_param("response");

    // TODO: add authorization check

    $new_data = array(
        'response' => $response,
    );

    if ($need_update_status){
        $new_data["status"] = $new_status;
    }
    $wpdb->update(
        $INQUIRY_TABLE_NAME,
        $new_data,
        array('id' => $id)
    );

    wp_send_json(
        array(
            "success" => true,
            "message" => "",
        )
    );
}

function save_response(WP_REST_Request $request){
    submit($request, false);
}

function submit_response(WP_REST_Request $request){
    submit($request, true, 3);
}

function send_response(WP_REST_Request $request){
    submit($request, true, 4);
    # send_email();
}