<?php

function status(WP_REST_Request $request){
    global $wpdb, $INQUIRY_TABLE_NAME;
    $id = (int) $request->get_param("id");
    $new_status = $request->get_param("status");
    if (!authorize("admin")){
        // get current status
        $current_status_object = $wpdb->get_results($wpdb->prepare(
            "SELECT status, assignee_id FROM $INQUIRY_TABLE_NAME WHERE id=%d",
            $id
        ));
        if (count($current_status_object) == 0){
            wp_send_json(
                array(
                    "success" => false,
                    "message" => "No inquiry found",
                )
            );
            return;
        }
        $current_status = (int) $current_status_object[0]->status;
        $assignee_id = (int) $current_status_object[0]->assignee_id;
        if ($current_status >= $new_status){
            wp_send_json(
                array(
                    "success" => false,
                    "message" => $new_status . "You are trying to overwrite status, not authorized",
                )
            );
            return;
        }
        if ($assignee_id != get_current_user_id()){
            wp_send_json(
                array(
                    "success" => false,
                    "message" => $new_status . "Not authorized",
                )
            );
            return;
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