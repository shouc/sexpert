<?php

// Some must-have stuffs
if ( ! defined( 'WPINC' ) ) {
    die;
}

require_once "table.php";

function init_admin(){
    ?>
    <div class="wrap">
    <p id="status"></p>
    <button class="button button-primary" id="status_button" onclick="change_status()"></button>
    <div style="display: flex; flex-direction: column;">
        <p>Inquiry Received Title</p>
        <textarea id="inquiry_received_title"></textarea>
        <p>Inquiry Received Content</p>
        <textarea id="inquiry_received"></textarea>
        <p>Inquiry Resolved Title</p>
        <textarea id="inquiry_resolved_title"></textarea>
        <p>Inquiry Resolved Content</p>
        <textarea id="inquiry_resolved"></textarea>
        <p>Disabled Banner Content</p>
        <textarea id="disabled_banner"></textarea>
        <p>Disclaimer Content</p>
        <textarea id="disclaimer"></textarea>
        <br>
        <button class="button button-primary" id="config_button" onclick="change_config()">Change config</button>

    </div>
    <?php
    $exampleListTable = new Inquiry_List_Table();
    $exampleListTable->prepare_items();
    $exampleListTable->display();
    ?>
    </div>
    <script>
        get_status();
        get_config();
    </script>
    <?php
}