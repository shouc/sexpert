<?php

function form_creation(){
    ?>
        <form>
            First name: <input type="text" name="firstname"><br>
            Last name: <input type="text" name="lastname"><br>
            Message: <textarea name="message" placeholder="Enter text here…"></textarea>
            <br>
            <button>Submit</button>
        </form>
    <?php
}