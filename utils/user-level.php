<?php

function authorize($need_user_type){
    switch ($need_user_type){
        case "admin":
            $real_level = 8;
            break;
        case "editor":
            $real_level = 3;
            break;
        case "author":
            $real_level = 2;
            break;
        case "contributor":
            $real_level = 1;
            break;
        default:
            $real_level = 0;
    }
    global $user_level;
    if ($user_level < $real_level){
        return false;
    }
    return true;
}
