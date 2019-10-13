<?php

// Some must-have stuffs
if ( ! defined( 'WPINC' ) ) {
    die;
}

function init_contributor(){
    ?>
    <p>Hi</p>
    <?php
    require_once __DIR__ . "/../api/assign.php";
}