<?php

// Some must-have stuffs
if ( ! defined( 'WPINC' ) ) {
    die;
}

require_once "table.php";

function init_admin(){
    ?>
    <div class="wrap">
    <button class="button button-primary">Generate User Report</button>

    <?php
    $exampleListTable = new Inquiry_List_Table();
    $exampleListTable->prepare_items();
    $exampleListTable->display();
    ?>
    </div>
    <?php
}