<?php

function form_creation(){
    ?>
        Email: <input type="text" id="email"><br>
        Age: <input type="text" id="age"><br>
        Gender: <input type="text" id="gender"><br>
        Country: <input type="text" id="country"><br>

        Message: <textarea id="message" placeholder="Enter text here…"></textarea>
        <br>
        <button onclick="submit_inquiry()">Submit</button>

    <?php
}