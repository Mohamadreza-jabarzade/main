var _name = document.getElementById("name");
var _email = document.getElementById("email");
var _pass = document.getElementById("password");
var _repass = document.getElementById("repassword");

var isname = false;
var isemail = false;
var ispass = false;
var isRepass = false;

function validateUsername() {
    let message = document.getElementById("name_msg");
    const isChar = /^[a-zA-Zآ-ی\s]+$/.test(_name.value);
    const isValidLength = _name.value.length >= 3;

    message.classList.toggle("hidden", _name.value === "");
    message.classList.toggle("flex", _name.value !== "");

    if (isValidLength && isChar) {
        _name.classList.add("border-green-400");
        _name.classList.remove("border-red-400");
        message.classList.add("hidden");
        isname = true;
    } else {
        _name.classList.add("border-red-400");
        _name.classList.remove("border-green-400");
        isname = false;
    }
    sign();
}

function validateemail() {
    let message = document.getElementById("email_msg");
    const isValidLength = _email.value.length >= 5;

    message.classList.toggle("hidden", _email.value === "");
    message.classList.toggle("flex", _email.value !== "");

    if (isValidLength ) {
        _email.classList.add("border-green-400");
        _email.classList.remove("border-red-400");
        message.classList.add("hidden");
        isemail = true;
    } else {
        _email.classList.add("border-red-400");
        _email.classList.remove("border-green-400");
        isemail = false;
    }
    sign();
}

function pass() {
    const message = document.getElementById("pass_msg");
    const hasSymbol = /[!@#$%^&*(),.?":{}|<>]/.test(_pass.value);
    const hasCharacter = /[a-zA-Z]/.test(_pass.value);
    const isValidLength = _pass.value.length >= 8;

    message.classList.toggle("hidden", _pass.value === "");
    message.classList.toggle("flex", _pass.value !== "");

    if (isValidLength && hasSymbol && hasCharacter) {
        _pass.classList.add("border-green-400");
        _pass.classList.remove("border-red-400");
        message.classList.add("hidden");
        ispass = true;
    } else {
        _pass.classList.add("border-red-400");
        _pass.classList.remove("border-green-400");
        ispass = false;
    }
    
    if (_repass.value !== "") {
        repass();
    }

    sign();
}

function repass() {
    let message = document.getElementById("repass_msg");

    message.classList.toggle("hidden", _repass.value === "");
    message.classList.toggle("flex", _repass.value !== "");

    if (_repass.value === _pass.value && _pass.value !== "") {
        _repass.classList.add("border-green-400");
        _repass.classList.remove("border-red-400");
        message.classList.add("hidden");
        isRepass = true;
    } else {
        _repass.classList.add("border-red-400");
        _repass.classList.remove("border-green-400");
        isRepass = false;
    }
    sign();
}

function sign() {
    let btn = document.getElementById("sign_btn");
    btn.disabled = !(isname && ispass && isRepass);
}
