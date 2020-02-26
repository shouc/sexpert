<?php

// Some must-have stuffs
if ( ! defined( 'WPINC' ) ) {
    die;
}
require_once "table.php";
require_once __DIR__ . "/../const.php";

function init_contributor(){
    ?>
    <link href="https://cdn.jsdelivr.net/npm/suneditor@latest/dist/css/suneditor.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/suneditor@latest/dist/suneditor.min.js"></script>
    <div class='modal m0'>
        <div class='modal-content'>
            <span class='close-button' onclick="close_modal()">×</span>
            <div id='m0_content'></div>
        </div>
    </div>
    <div class='modal m1'>
        <div class='modal-content'>
            <span class='close-button' onclick="toggle_modal_unchange()">×</span>
            <div id='m1_content'></div>
        </div>
    </div>
    <br>
    <select id="contributor_selector" onchange="change_showing_status()">
        <?php
            if ($_GET["status"] != null){
                $status_text = CONVERT_STATUS_CODE((int) $_GET['status']);
                echo "<option selected>Showing $status_text Only - Selected</option>";
            }
        ?>
        <option value="-1">Showing All</option>
        <option value="0">Show Unassigned Only</option>
        <option value="1">Show Assigned Only</option>
        <option value="2">Show Editing Only</option>
        <option value="3">Show Edited Only</option>
        <option value="4">Show Sent Only</option>
    </select>
    <h1>Inquiries Assigned to You</h1>
    <?php
    echo render_inquiry_assigned();
    ?>
    <h1>All Inquiries</h1>
    <div class="wrap">
    <?php
        $exampleListTable = new Inquiry_Contributor_List_Table();
        $exampleListTable->prepare_items();
        $exampleListTable->display();
    ?>
    </div>
    <?php
}

function render_inquiry_assigned(){
    global $wpdb, $INQUIRY_TABLE_NAME, $USER_TABLE_NAME;
    $where = "";
    if ($_GET["status"] != null){
        $status = $_GET["status"];
        $where = $wpdb->prepare("AND status = %d", $status);
    }
    $data = $wpdb->get_results(
        $wpdb->prepare(
            "
                    SELECT 
                        i.id,
                        i.age, i.gender, i.country, i.message, i.status, i.response, i.time,
                        assigner.user_login as assigner_name
                    FROM $INQUIRY_TABLE_NAME AS i
                    LEFT JOIN $USER_TABLE_NAME AS assigner ON i.assigner_id = assigner.id
                    WHERE i.assignee_id=%d $where",
            get_current_user_id()
        )
    );
    $html = "<div class='block-container'>";
    if (count($data) == 0){return "No inquiries found";}
    foreach ($data as $res){
        $inquiry_id = $res->id;
        $inquirer_info = "Age: $res->age<br>Gender: $res->gender<br>Country: $res->country";
        $message = $res->message;
        if ($res->status != 4){
            // open modal without warning
            $func = "open_inquiry_modal";
        } else {
            $func = "open_inquiry_modal_with_confirm";
        }
        $status = CONVERT_STATUS_CODE($res->status);
        $response = $res->response ? $res->response : "No response yet";
        $time = CONVERT_TIME($res->time);
        $assigner = $res->assigner_name;

        $html .= "
            <div class='blocks' onclick='$func($inquiry_id, `$inquirer_info`, `$message`, `$response`)'>
                <div class='info'>$inquirer_info</div>
                <br>
                <strong>Inquiry</strong>
                <div class='message'>$message</div>
                <strong>Your Response</strong>
                <div class='response'>$response</div>
                <div>
                    <span class='dashicons dashicons-clock'></span>$time
                    <span class='dashicons dashicons-admin-users'></span>$assigner
                    <span class='dashicons dashicons-admin-post'></span>$status
                </div>
            </div>
        ";
    }
    return $html . "</div>";
}
