<?php

function form_creation(){
    ?>
    <div class="sexpert-form">
        <table border="1">
            <tr>
                <th>
                    <span>Email</span>
                </th>
                <th>
                    <input class="sexpert-form-input" type="text" id="email">
                </th>
            </tr>
            <tr>
                <th>
                    <span>Age</span>
                </th>
                <th>
                    <input class="sexpert-form-input" type="number" id="age">
                </th>
            </tr>
            <tr>
                <th>
                    <span>Gender</span>
                </th>
                <th>
                    <select class="sexpert-form-select" id="gender">
                        <option value="0">Trans Male</option>
                        <option value="1">Cis Male</option>
                        <option value="2">Trans Female</option>
                        <option value="3">Cis Female</option>
                        <option value="4">I don't know - Male</option>
                        <option value="5">I don't know - Female</option>
                        <option value="6">Don't want to disclose</option>
                    </select>
                </th>
            </tr>
            <tr>
                <th>
                    <span>Country</span>
                </th>
                <th>
                    <select class="sexpert-form-select" id="country"></select>
                </th>
            </tr>
        </table>

        <span class="message">Message:</span>
        <textarea id="message" placeholder="Enter text here…"></textarea>
        <br>
        <button onclick="submit_inquiry()">Submit</button>
    </div>
    <?php
}