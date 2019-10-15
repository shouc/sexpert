function get(url, d, ol) {
    for (let key in d){
        if (d.hasOwnProperty(key)){
            url += `&${key}=${d[key]}`
        }
    }
    let xhr = new XMLHttpRequest();
    xhr.open('GET', url, true);
    xhr.onload = ol ? function () {
        console.log(this.responseText);
    } : ol;
    xhr.send();
}

function post(url, d, ol) {
    let data = new FormData();
    for (let key in d){
        if (d.hasOwnProperty(key)){
            data.append(key, d[key])
        }
    }

    let xhr = new XMLHttpRequest();
    xhr.open('POST', url, true);
    xhr.onload = ol ? function () {
        console.log(this.responseText);
    }: ol;
    xhr.send(data);
}

function patch(url, d, ol) {
    for (let key in d){
        if (d.hasOwnProperty(key)){
            url += `&${key}=${d[key]}`
        }
    }
    let xhr = new XMLHttpRequest();
    xhr.open('PATCH', url, true);
    xhr.onload = ol ? function () {
        console.log(this.responseText);
    } : ol;
    xhr.send();
}

function del(url, d, ol) {
    for (let key in d){
        if (d.hasOwnProperty(key)){
            url += `&${key}=${d[key]}`
        }
    }
    let xhr = new XMLHttpRequest();
    xhr.open('DELETE', url, true);
    xhr.onload = ol ? function () {
        console.log(this.responseText);
    } : ol;
    xhr.send();
}

function get_val(id) {
    return document.getElementById(id).value
}


function toggle_modal(html) {
    let modal = document.querySelector(".modal");
    modal.classList.toggle("show-modal");
    if (html){
        document.getElementById("modal_content").innerHTML = html
    } else {
        location.reload();
    }
}

function submit_inquiry() {
    post("/?rest_route=/sexpert/v1/inquiry/", {
        'email': get_val("email"),
        'age': get_val("age"),
        'gender': get_val("gender"),
        'country': get_val("country"),
        'message': get_val("message"),
        'status': 0,
    });
    location.reload();
}

function assign_inquiry(i, assignee_id) {
    post("/?rest_route=/sexpert/v1/assignment/" + i, {
        'assignee_id': assignee_id,
        '_wpnonce': php_variables.nonce
    });
    location.reload();
}

function unassign_inquiry(i, assignee_id) {
    del("/?rest_route=/sexpert/v1/assignment/" + i, {
        'assignee_id': assignee_id,
        '_wpnonce': php_variables.nonce
    });
    location.reload();
}

function open_inquiry_modal(i, inquirer_info, message, response) {
    patch("/?rest_route=/sexpert/v1/status/" + i, {
        'status': 2,
        '_wpnonce': php_variables.nonce
    });
    toggle_modal(
        `
            <div class="blocks">
                <div class='info'>${inquirer_info}</div
                <br><br>
                <strong>Inquiry</strong>
                <div class='message'>${message}</div>
            </div>
            <br>
            <textarea rows="10" 
                id="response${i}" 
                onchange="save_response(${i})">${response === "No response yet" ? "" : response}</textarea>
            <br><br>
            <button class="button button-secondary" onclick="submit_response(${i})">Submit</button>
            <button class="button button-primary" onclick="send_response(${i})">Send</button>
        `
    )
}


function open_comment_modal(i, inquirer_info, message, response) {
    toggle_modal(
        `
            <div class="blocks">
                <div class='info'>${inquirer_info}</div
                <br><br>
                <strong>Inquiry</strong>
                <div class='message'>${message}</div>
                <strong>Response</strong>
                <div class='message'>${response}</div>
            </div>
            <br>
            <textarea rows="10" 
                id="comment${i}" placeholder="Write your comment."></textarea>
            <br><br>
            <button class="button button-primary" onclick="send_comment(${i})">Send</button>
        `
    )
}

function save_response(i) {
    patch("/?rest_route=/sexpert/v1/response_of_inquiry/" + i, {
        'response': get_val("response" + i),
        '_wpnonce': php_variables.nonce
    })
}


function submit_response(i) {
    post("/?rest_route=/sexpert/v1/response_of_inquiry/" + i, {
        'response': get_val("response" + i),
        '_wpnonce': php_variables.nonce
    });
    toggle_modal();
}

function send_response(i) {
    post("/?rest_route=/sexpert/v1/mailing/" + i, {
        'response': get_val("response" + i),
        '_wpnonce': php_variables.nonce
    });
    toggle_modal();
}

function send_comment(i) {
    post("/?rest_route=/sexpert/v1/comment_of_inquiry/" + i, {
        'comment': get_val("comment" + i),
        '_wpnonce': php_variables.nonce
    });
    toggle_modal();
}

function change_showing_status() {
    let new_status = get_val("contributor_selector");
    let re_exp = /&status=\d/;
    if (new_status === "-1"){
        window.location.href = window.location.href.replace(re_exp, "");
    } else if (re_exp.exec(window.location.href)) {
        window.location.href = window.location.href.replace(re_exp,
            "&status=" + new_status);
    } else {
        window.location.href += ("&status=" + new_status);
    }
}