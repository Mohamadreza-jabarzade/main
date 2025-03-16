
function open_nav() {
    document.getElementById("nav").classList.remove("invisible");
    document.getElementById("nav").classList.add("animate-show");
    document.getElementById("nav").classList.remove("animate-hide");
    document.getElementById("nav").parentElement.classList.remove("invisible");
}

function close_nav() {
    document.getElementById("nav").classList.add("animate-hide");
    document.getElementById("nav").classList.remove("animate-show");
    setTimeout('document.getElementById("nav").parentElement.classList.add("invisible")', 800);

}

function open_sign() {
    document.getElementById("sign").classList.remove("invisible");
    signin();
}

function close_sign() {
    document.getElementById("sign").classList.add("invisible");

}

var _signin = document.getElementById("signin");
var _signup = document.getElementById("signup");

function signup() {
    _signup.classList.remove("invisible");
    _signin.classList.add("invisible");
}

function signin() {
    _signup.classList.add("invisible");
    _signin.classList.remove("invisible");
}


var swiper = new Swiper(".mySwiper", {
    loop: true,
    autoplay: {
        delay: 3000,
        disableOnInteraction: false,
    },
    pagination: {
        el: ".swiper-pagination",
        clickable: true,
    },
    navigation: {
        nextEl: ".next",
        prevEl: ".prev",
    },
});
document.addEventListener("DOMContentLoaded", function () {
    // دریافت پارامترهای URL
    const params = new URLSearchParams(window.location.search);

    // دریافت مقدار id از URL
    const show = params.get("show");


    // بررسی اینکه آیا id در URL وجود دارد یا نه
    if (show == "signup") {
        open_sign();
        signup();

    }
    if (show == "signin") {
        open_sign();
        signin();

    }

});
