<?php
require_once __DIR__ . "/../utils/user-level.php";

$doc_name = $_GET["doc_name"];
header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment; Filename=$doc_name.doc");
// No XSS, log can catch you!
$user_id = wp_get_current_user()->ID;
if (!authorize("contributor")){
    wp_send_json(
        array(
            "success" => false,
            "message" => "Not authorized",
        )
    );
}

echo base64_decode($_GET["content"]);
