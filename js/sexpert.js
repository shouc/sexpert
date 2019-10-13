function get(url) {

}

function post(url, d) {
    let data = new FormData();
    for (let key in d){
        if (d.hasOwnProperty(key)){
            data.append(key, d[key])
        }
    }

    let xhr = new XMLHttpRequest();
    xhr.open('POST', url, true);
    xhr.onload = function () {
        // do something to response
        console.log(this.responseText);
    };
    xhr.send(data);
}

function patch(url, data) {

}

function del(url, data) {

}

function get_val(id) {
    return document.getElementById(id).value
}

function submit_inquiry() {
    post("?rest_route=/sexpert/v1/inquiry/", {
        'email': get_val("email"),
        'age': get_val("age"),
        'gender': get_val("gender"),
        'country': get_val("country"),
        'message': get_val("message"),
        'status': 0,
    })
}