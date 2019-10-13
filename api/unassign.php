<?php

function unassign(WP_REST_Request $request){
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
    $assignee_id = $request->get_param('assignee_id');
    $assigner_id = wp_get_current_user()->ID;
    $wpdb->update(
        $INQUIRY_TABLE_NAME,
        array(
            'assigner_id'=>$assigner_id,
            'assignee_id'=>$assignee_id,
            'status'=>1
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