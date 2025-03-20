document.documentElement.classList.toggle(
    "dark",
    localStorage.theme === "dark" ||
      (!("theme" in localStorage) && window.matchMedia("(prefers-color-scheme: dark)").matches),
  );
function theme(){
    var mytheme = localStorage.theme;
    if(mytheme != "dark"){
        document.getElementById("themebtn").classList.remove("fa-moon");
        document.getElementById("themebtn").classList.add("fa-sun");
        document.documentElement.classList.add("dark");
        localStorage.theme = "dark";
    }else{
        document.getElementById("themebtn").classList.add("fa-moon");
        document.getElementById("themebtn").classList.remove("fa-sun");
        document.documentElement.classList.remove("dark");
        localStorage.theme = "light";
    }
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

const nav = document.getElementById("nav");
const overlay = document.getElementById("overlay");
const newsBtn = document.getElementById("newsBtn");
const newsDropdown = document.getElementById("newsDropdown");

function open_nav() {
    nav.classList.remove("invisible");
    overlay.classList.remove("invisible");
    nav.style.right = "0";
}

function close_nav() {
    nav.style.right = "-100%";
    setTimeout(() => {
        nav.classList.add("invisible");
        overlay.classList.add("invisible");
    }, 300);
}

newsBtn.addEventListener("click", () => {
    newsDropdown.classList.toggle("hidden");
    newsDropdown.classList.toggle("flex");
    newsDropdown.classList.toggle("scale-y-0");
    newsDropdown.classList.toggle("scale-y-100");
});