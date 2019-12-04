<?php

function submit(WP_REST_Request $request,
                $need_update_status=false,
                $new_status=0){
    global $wpdb, $INQUIRY_TABLE_NAME, $USER_TABLE_NAME;
    $id = $request->get_param("id");
    $response = $request->get_param("response");

    // TODO: add authorization check

    $new_data = array(
        'response' => $response,
    );

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

    if ($need_update_status){
        $new_data["status"] = $new_status;
        if ($new_status == 4){
            wp_mail($inquiry_info_obj[0]->email, "Your inquiry is resolved", $response);
        }
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
}