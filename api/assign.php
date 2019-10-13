<?php

require_once __DIR__ . "/../const.php";
require_once __DIR__ . "/../utils/user-level.php";

function assign(WP_REST_Request $request){
    global $wpdb, $INQUIRY_TABLE_NAME;

    $id = (int) $request->get_param("id");
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
