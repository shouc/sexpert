<?php

function make_emergency(WP_REST_Request $request){
    global $wpdb, $INQUIRY_TABLE_NAME, $USER_TABLE_NAME;
    $id = $request->get_param("id");

    $wpdb->update(
        $INQUIRY_TABLE_NAME,
        array(
            'is_emergency' => 1,
        ),
        array('id' => $id, 'is_emergency' => 0)
    );
    wp_send_json(
        array(
            "success" => true,
            "message" => "",
        )
    );
}

function cancel_emergency(WP_REST_Request $request){
    global $wpdb, $INQUIRY_TABLE_NAME, $USER_TABLE_NAME;
    $id = $request->get_param("id");

    $wpdb->update(
        $INQUIRY_TABLE_NAME,
        array(
            'is_emergency' => 0,
        ),
        array('id' => $id, 'is_emergency' => 1)
    );
    wp_send_json(
        array(
            "success" => true,
            "message" => "",
        )
    );
}
