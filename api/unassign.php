<?php

function unassign(WP_REST_Request $request){
    global $wpdb, $INQUIRY_TABLE_NAME;
    $id = $request->get_param("id");
    $assignee_id = (int) $request->get_param('assignee_id');
    $assigner_id = wp_get_current_user()->ID;
    if ($assignee_id != $assigner_id && !authorize("admin")){
        wp_send_json(
            array(
                "success" => false,
                "message" => "Not authorized",
            )
        );
    }
    $wpdb->update(
        $INQUIRY_TABLE_NAME,
        array(
            'assigner_id'=> null,
            'assignee_id'=> null,
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