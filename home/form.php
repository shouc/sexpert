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
                    <div onclick="show_gender_input()">
                        <input type="radio" name="gender" value="0" id="transmale" />
                        <label for="transmale">Trans Male</label>
                        <br>

                        <input type="radio" name="gender" value="1" id="cismale" />
                        <label for="cismale">Cis Male</label>
                        <br>

                        <input type="radio" name="gender" value="2" id="transfemale"/>
                        <label for="transfemale">Trans Female</label>
                        <br>

                        <input type="radio" name="gender" value="3" id="cisfemale" />
                        <label for="cisfemale">Cis Female</label>
                        <br>

                        <input type="radio" name="gender" value="4" id="notlisted"  />
                        <label for="notlisted">Not Listed</label>
                    </div>


                    <div id="not_listed_specify"></div>
                    <br>
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