<?php

// Some must-have stuffs
if ( ! defined( 'WPINC' ) ) {
    die;
}

require_once "table.php";

function init_admin(){
    ?>
    <p>Hi</p>
    <button class="button button-primary">Assign</button>
    <p>Hi</p>

    <?php
    $exampleListTable = new Inquiry_List_Table();
    $exampleListTable->prepare_items();
    $exampleListTable->display();
}