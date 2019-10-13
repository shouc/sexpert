<?php

function delete_inquiry(WP_REST_Request $request){
    global $wpdb, $INQUIRY_TABLE_NAME;
    if (!authorize("admin")){
        wp_send_json(
            array(
                "success" => false,
                "message" => "Not authorized",
            )
        );
    }
    $id = $request->get_param("id");
    $wpdb->delete(
        $INQUIRY_TABLE_NAME,
        array('id'=>$id)
    );

    wp_send_json(
        array(
            "success" => true,
            "message" => "",
        )
    );
}