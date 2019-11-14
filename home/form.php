<?php

function get_alert(){
    return "1";
}

function form_creation(){
    ?>
    <div class="alert">
        <?php echo get_alert() ?>
    </div>
    <div class="sexpert-form">
        <div class="basic-info">
            <div>
                <span>Email</span>
                <br><br>
                <span>Age</span>
            </div>

            <div>
                <input class="sexpert-form-input" type="text" id="email">
                <input class="sexpert-form-input" type="number" min="1" id="age"/>
            </div>
        </div>


        <span>Gender</span>
        <div>
            <div class="gender-row" onclick="show_gender_input()">
                <div class="gender-col">
                    <input type="radio" name="gender" value="0" id="female" />
                    <label for="female">Female</label>
                    <br>
                    <input type="radio" name="gender" value="2" id="transmale"/>
                    <label for="transmale">Trans Male</label>
                    <br>
                    <input type="radio" name="gender" value="4" id="notlisted"  />
                    <label for="notlisted">Different Gender</label>
                </div>
                <div class="gender-col">
                    <input type="radio" name="gender" value="1" id="male" />
                    <label for="male">Male</label>
                    <br>
                    <input type="radio" name="gender" value="3" id="transfemale" />
                    <label for="transfemale">Trans Female</label>
                    <br>
                    <input type="radio" name="gender" value="5" id="prefernottosay"  />
                    <label for="prefernottosay">Prefer Not to Say</label>
                    <br>
                </div>
                <div class="gender-col">
                    <input type="radio" name="gender" value="6" id="nonbinary" />
                    <label for="nonbinary">Non-binary / Genderqueer</label>
                    <br>
                    <input type="radio" name="gender" value="7" id="transnonbinary" />
                    <label for="transnonbinary">Trans Non-binary / Genderqueer</label>
                    <br>
                </div>
            </div>
            <div id="not_listed_specify"></div>
        </div>
        <br>
        <span>Country</span>
        <select class="sexpert-form-select" id="country"></select>
        <br><br>

        <span class="message">Message:</span>
        <textarea id="message" placeholder="Enter text here…"></textarea>
        <br>
        <button onclick="submit_inquiry()">Submit</button>
    </div>
    <?php
}