<?php

function status(WP_REST_Request $request){
    global $wpdb, $INQUIRY_TABLE_NAME;
    $id = (int) $request->get_param("id");
    $new_status = (int) $request->get_param("status");
    // TODO: add authorization
    if (!authorize("admin")){
        // get current status
        $current_status_object = $wpdb->get_results($wpdb->prepare(
            "SELECT status FROM $INQUIRY_TABLE_NAME WHERE id=%d",
            $id
        ));
        // todo: add try catch
        $current_status = (int) $current_status_object[0]->id;

        if ($current_status >= $new_status){
            wp_send_json(
                array(
                    "success" => false,
                    "message" => "You are trying to overwrite status, not authorized",
                )
            );
        }
    }
    $wpdb->update(
        $INQUIRY_TABLE_NAME,
        array(
            'status'=> $new_status,
        ),
        array('id'=>$id)
    );

    wp_send_json(
        array(
            "success" => true,
            "message" => "",
        )
    );
}