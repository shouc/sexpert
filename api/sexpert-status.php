<?php

require_once __DIR__ . "/../const.php";
require_once __DIR__ . "/../utils/mail.php";


function _get_config(){
    global $wpdb, $CONFIG_TABLE_NAME;
    $sexpert_post = $wpdb->get_results(
        "SELECT * FROM $CONFIG_TABLE_NAME LIMIT 1"
    );
    return $sexpert_post[0];
}

function get_config(WP_REST_Request $request){
    wp_send_json(
        array(
            "success" => true,
            "message" => _get_config(),
        )
    );
}


function change_config(WP_REST_Request $request){
    global $wpdb, $CONFIG_TABLE_NAME;
    if (!authorize("admin")){
        wp_send_json(
            array(
                "success" => false,
                "message" => "Not authorized",
            )
        );
    }
    $wpdb->update(
        $CONFIG_TABLE_NAME,
        array(
            "inquiry_received_title" => $request->get_param("inquiry_received_title"),
            "inquiry_received"=> $request->get_param("inquiry_received"),
            "inquiry_resolved_title"=> $request->get_param("inquiry_resolved_title"),
            "inquiry_resolved"=> $request->get_param("inquiry_resolved"),
            "disabled_banner"=> $request->get_param("disabled_banner"),
            "disclaimer" => $request->get_param("disclaimer")
        ),
        array('id' => 1)
    );

    wp_send_json(
        array(
            "success" => true,
            "message" => "",
        )
    );
}

function get_sexpert_status(WP_REST_Request $request){
    global $wpdb, $POST_TABLE_NAME;
    $CONTENT_ENABLED = "%<p>[sexpertform]</p>%";
    $sexpert_post = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM $POST_TABLE_NAME WHERE post_content LIKE %s AND post_status='publish'",
            $CONTENT_ENABLED
        )
    );

    wp_send_json(
        array(
            "success" => true,
            "message" => count($sexpert_post) == 1,
        )
    );
}

function change_sexpert_status(WP_REST_Request $request){
    global $wpdb, $POST_TABLE_NAME;
    if (!authorize("admin")){
        wp_send_json(
            array(
                "success" => false,
                "message" => "Not authorized",
            )
        );
    }

    $CONTENT_ENABLED = "<!-- wp:paragraph --><p>[sexpertform]</p><!-- /wp:paragraph -->";
    $CONTENT_DISABLED = "<!-- wp:paragraph -->[smartslider3 slider=4]<p>[sexpertoff]</p><!-- /wp:paragraph -->";
    $sexpert_post = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM $POST_TABLE_NAME WHERE post_content = %s AND post_status='publish'",
            $CONTENT_ENABLED
        )
    );
    if (count($sexpert_post) > 0){
        $wpdb->update($POST_TABLE_NAME, array(
            "post_content" => $CONTENT_DISABLED,
        ), array(
            "ID" => $sexpert_post[0]->ID
        ));
    } else {
        $sexpert_post = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM $POST_TABLE_NAME WHERE post_content = %s AND post_status='publish'",
                $CONTENT_DISABLED
            )
        );
        $wpdb->update($POST_TABLE_NAME, array(
            "post_content" => $CONTENT_ENABLED,
        ), array(
            "ID" => $sexpert_post[0]->ID
        ));
    }

    wp_send_json(
        array(
            "success" => true,
            "message" => "",
        )
    );
}