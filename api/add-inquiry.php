<?php

require_once __DIR__ . "/../const.php";
require_once __DIR__ . "/../utils/mail.php";
require_once __DIR__ . "/sexpert-status.php";

function add_inquiry(WP_REST_Request $request){
    global $wpdb, $INQUIRY_TABLE_NAME;
    $email = $request->get_param("email");
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
        wp_send_json(
            array(
                "success" => true,
                "message" => "Email is not valid",
            )
        );
    }
    $age = (int) $request->get_param("age");
    $gender = $request->get_param("gender");
    $country = $request->get_param("country");
    $message = $request->get_param("message");

    $wpdb->insert(
        $INQUIRY_TABLE_NAME,
        array(
            'email' => $email,
            'age' => $age,
            'gender' => $gender,
            'country' => $country,
            'message' => $message,
            'status' => 0,
            'time' => time(),
        )
    );
    $configs = _get_config();
    $content = str_replace("{message}", $message, $configs->inquiry_received);
    $content = str_replace("{wrap}", "\n", $content);
    wp_mail($email, $configs->inquiry_received_title, $content);
    wp_send_json(
        array(
            "success" => true,
            "message" => "",
        )
    );
}