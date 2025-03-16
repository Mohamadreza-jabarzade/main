<?php
global $pdo;
require_once "functions/helpers.php";
require_once "functions/db.php";
require_once "functions/error_handle.php";
session_start();

//signup handle
$err = new handle();

if(isset($_POST["email"]) &&
    isset($_POST["password"]) &&
    isset($_POST["confirm"]) &&
    isset($_POST["username"])){
    if(empty($_POST["email"])){
        $err->set_empty_err("email","Email is required");
    }
    if(empty($_POST["password"])){
        $err->set_empty_err("password","Password is required");
    }
    if(empty($_POST["confirm"])){
        $err->set_empty_err("confirm","Confirm password is required");
    }
    if(empty($_POST["username"])){
        $err->set_empty_err("username","username is required");
    }
    if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
        $err->set_validity_err("email","Invalid email format");
    }
    if ($err->count_errors() == 0){

        if ($_POST["password"] == $_POST["confirm"]) {
            if (strlen($_POST["password"]) < 8 ) {
                $err->set_validity_err("password","Password must be at least 8 characters long");
            }
            else{
                $query = 'select * from users where email = ? or username = ?';
                $stmt = $pdo->prepare($query);
                $stmt->execute([$_POST["email"],$_POST["username"]]);
                $user = $stmt->fetch();
                if ($user) {
                    $err -> set_validity_err("email","This email is already taken");
                    $err -> set_validity_err("username","This username is already taken");
                }
                else{
                    $query = 'insert into users (email,password,username, created_at) values (?,?,?,now())';
                    $stmt = $pdo->prepare($query);
                    $hash = password_hash($_POST["password"], PASSWORD_DEFAULT);
                    $stmt->execute([$_POST["email"],$hash ,$_POST["username"]]);
                    redirect('index.php?show=signin');
                }
            }
        }
        else{
            $err->set_validity_err("confirm_password","confirm not matched with password.");
        }
    }
}


//signup handle
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/x-icon" href="<?=asset('assets/images/icons/us.png')?>">
    <meta name="description" content="اخبار">
    <link rel="stylesheet" href="fontawesome-free-6.6.0-web/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?=asset('assets/css/style.css')?>" />
    <title>news</title>
    <meta property="og:title" content="اخبار" />
    <meta property="og:description" content="اخبار ایران" />
    <meta property="og:type" content="video.movie" />
    <meta property="og:url" content="https://www.imdb.com/title/tt0117500/" />
    <meta property="og:image" content="<?=asset('assets/images/icons/us.png')?>" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css">
    <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
</head>

<body class="bg" dir="rtl">
<header class="w-full h-[80px] flex items-center px-6 justify-between z-30 size">

    <button class="block md:hidden md:invisible" onclick="open_nav()"><i class="fa fa-bars"></i></button>

    <div class="flex gap-8 items-center">
        <a class="" href="">
            <img class="w-10" src="<?=asset('assets/images/icons/mic.jpg')?>" alt="">
        </a>
        <div class="hidden md:block border-l-[1px] border-black/60 h-8"></div>
        <nav class="hidden md:flex gap-5 items-center font-semibold">
            <a class="hover:text-red-500 transition-color duration-300" href="">خانه</a>
            <a class="hover:text-red-500 transition-color duration-300" href="">جدید ترین خبر ها</a>
            <a class="hover:text-red-500 transition-color duration-300" href="">اخبار سیاسی</a>
        </nav>

    </div>
    <div class="flex items-center gap-5">
        <a class="hidden md:block bg-red-600/80 rounded-md text-white px-3 py-2 hover:bg-red-700 duration-300 transition-color"
           href="">پست جدید</a>
        <button onclick="open_sign()"><i class="fa fa-user"></i></button>
    </div>

    <!-- mobile nav -->
    <div onclick="close_nav()"
         class="invisible fixed top-0 right-0 w-full h-[100vh] bg-black/10 backdrop-blur-sm z-30">
        <div id="nav"
             class="invisible fixed top-0 right-[-100%] w-4/6 h-[100vh] bg-white z-50 flex flex-col items-center">
            <div class="flex items-center h-[80px] justify-between w-full px-6">
                <i onclick="close_nav()" class="fa fa-close"></i>

            </div>
            <nav class="flex flex-col items-center w-full gap-4">
                <a href="">خانه</a>
                <a href="">جدید ترین خبر ها</a>
                <a href="">اخبار سیاسی</a>
            </nav>
        </div>
    </div>
</header>

<!-- main -->
<main class="w-full px-6 flex flex-col gap-4 size">



    <!-- image slider -->
    <div class="w-full h-[50vh] ">
        <div class="swiper mySwiper h-full rounded-2xl">
            <div class="swiper-wrapper">

                <div class="swiper-slide relative">
                    <img src="<?=asset('assets/images/slider/donald-trump-2016-campaign-gk8d3spb0py2r0v7.jpg')?>"
                         class="w-full h-full object-cover " alt="خبر اقتصادی">
                    <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
                        <h2 class="text-white text-xl md:text-2xl font-bold">خبر ۱: افزایش نرخ ارز</h2>
                    </div>
                </div>

                <div class="swiper-slide relative">
                    <img src="<?=asset('assets/images/slider/funny-donald-trump-tongue-out-b5bbad2ao061giaa.jpg')?>"
                         class="w-full h-full object-cover" alt="خبر بورس">
                    <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
                        <h2 class="text-white text-2xl font-bold">خبر ۲: رشد شاخص بورس</h2>
                    </div>
                </div>

                <div class="swiper-slide relative">
                    <img src="<?=asset('assets/images/slider/trump-1920-x-1334-background-pylm0uu8elu1z21b.jpg')?>"
                         class="w-full h-full object-cover" alt="خبر تکنولوژی">
                    <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
                        <h2 class="text-white text-2xl font-bold">خبر ۳: نوآوری‌های جدید</h2>
                    </div>
                </div>

                <div class="swiper-slide relative">
                    <img src="<?=asset('assets/images/slider/trump-1920-x-1334-background-pylm0uu8elu1z21b.jpg')?>"
                         class="w-full h-full object-cover rounded" alt="خبر تکنولوژی">
                    <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
                        <h2 class="text-white text-2xl font-bold">خبر ۳: نوآوری‌های جدید</h2>
                    </div>
                </div>
            </div>
            <!-- buttons -->
            <div class="z-50 w-10 h-10 absolute left-3 top-[50%] flex items-center justify-center rounded-full translate-y-[-50%] bg-radial from-red-700 to-red-500 next text-white"><i class="fa fa-angle-left"></i></div>
            <div class="z-50 w-10 h-10 absolute right-3 top-[50%] flex items-center justify-center rounded-full translate-y-[-50%] bg-radial from-red-700 to-red-500  prev text-white"><i class="fa fa-angle-right"></i></div>
            <!-- dots -->
            <div class="swiper-pagination"></div>
        </div>
    </div>




    <!-- titles -->
    <div class="w-full flex justify-between items-center">
        <h2 class="font-bold">اخبار سیاسی</h2>
        <a class="bg-red-400 text-white font-bold rounded px-3 py-2" href="">همه</a>
    </div>

    <!-- items -->
    <div class="w-full grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="sec flex items-center p-2 rounded-xl shadow-sm bg-white">
            <div class="w-4/6 grid grid-rows-3">
                <a href="page.html">
                    <h2 class="text-lg font-semibold">قیمت دلار</h2>
                </a>

                <strong class="text-sm text-black/50">1,000,000</strong>
                <div class="flex justify-end items-end gap-2">
                    <span class="text-sm text-gray-600">4k</span>
                    <i class="fa fa-eye text-gray-500"></i>
                </div>
            </div>
            <div class="w-2/6 h-[80px] rounded-xl relative z-10 overflow-hidden">
                <img class="w-full h-full object-cover rounded-xl"
                     src="<?=asset('assets/images/posts/us.png')?>" alt="dollar">
            </div>
        </div>
        <div class="sec flex items-center p-2 rounded-xl shadow-sm bg-white">
            <div class="w-4/6 grid grid-rows-3">
                <h2 class="text-lg font-semibold">قیمت دلار</h2>
                <strong class="text-sm text-black/50">1,000,000</strong>
                <div class="flex justify-end items-end gap-2">
                    <span class="text-sm text-gray-600">4k</span>
                    <i class="fa fa-eye text-gray-500"></i>
                </div>
            </div>
            <div class="w-2/6 h-[80px] rounded-xl relative z-10 overflow-hidden">
                <img class="w-full h-full object-cover rounded-xl"
                     src="<?=asset('assets/images/icons/us.png')?>" alt="dollar">
            </div>
        </div>
        <div class="sec flex items-center p-2 rounded-xl shadow-sm bg-white">
            <div class="w-4/6 grid grid-rows-3">
                <h2 class="text-lg font-semibold">قیمت دلار</h2>
                <strong class="text-sm text-black/50">1,000,000</strong>
                <div class="flex justify-end items-end gap-2">
                    <span class="text-sm text-gray-600">4k</span>
                    <i class="fa fa-eye text-gray-500"></i>
                </div>
            </div>
            <div class="w-2/6 h-[80px] rounded-xl relative z-10 overflow-hidden">
                <img class="w-full h-full object-cover rounded-xl"
                     src="<?=asset('assets/images/icons/us.png')?>" alt="dollar">
            </div>
        </div>
    </div>
    <!-- titles -->
    <div class="w-full flex justify-between items-center">
        <h2 class="font-bold">اخبار اقتصادی</h2>
        <a class="bg-red-400 text-white font-bold rounded px-3 py-2" href="">همه</a>
    </div>
    <!-- items -->
    <div class="w-full grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="sec flex items-center p-2 rounded-xl shadow-sm bg-white">
            <div class="w-4/6 grid grid-rows-3">
                <h2 class="text-lg font-semibold">قیمت دلار</h2>
                <strong class="text-sm text-black/50">1,000,000</strong>
                <div class="flex justify-end items-end gap-2">
                    <span class="text-sm text-gray-600">4k</span>
                    <i class="fa fa-eye text-gray-500"></i>
                </div>
            </div>
            <div class="w-2/6 h-[80px] rounded-xl relative z-10 overflow-hidden">
                <img class="w-full h-full object-cover rounded-xl"
                     src="<?=asset('assets/images/icons/us.png')?>" alt="dollar">
            </div>
        </div>
        <div class="sec flex items-center p-2 rounded-xl shadow-sm bg-white">
            <div class="w-4/6 grid grid-rows-3">
                <h2 class="text-lg font-semibold">قیمت دلار</h2>
                <strong class="text-sm text-black/50">1,000,000</strong>
                <div class="flex justify-end items-end gap-2">
                    <span class="text-sm text-gray-600">4k</span>
                    <i class="fa fa-eye text-gray-500"></i>
                </div>
            </div>
            <div class="w-2/6 h-[80px] rounded-xl relative z-10 overflow-hidden">
                <img class="w-full h-full object-cover rounded-xl"
                     src="<?=asset('assets/images/icons/us.png')?>" alt="dollar">
            </div>
        </div>
        <div class="sec flex items-center p-2 rounded-xl shadow-sm bg-white">
            <div class="w-4/6 grid grid-rows-3">
                <h2 class="text-lg font-semibold">قیمت دلار</h2>
                <strong class="text-sm text-black/50">1,000,000</strong>
                <div class="flex justify-end items-end gap-2">
                    <span class="text-sm text-gray-600">4k</span>
                    <i class="fa fa-eye text-gray-500"></i>
                </div>
            </div>
            <div class="w-2/6 h-[80px] rounded-xl relative z-10 overflow-hidden">
                <img class="w-full h-full object-cover rounded-xl"
                     src="<?=asset('assets/images/icons/us.png')?>" alt="dollar">
            </div>
        </div>
        <div class="sec flex items-center p-2 rounded-xl shadow-sm bg-white">
            <div class="w-4/6 grid grid-rows-3">
                <h2 class="text-lg font-semibold">قیمت دلار</h2>
                <strong class="text-sm text-black/50">1,000,000</strong>
                <div class="flex justify-end items-end gap-2">
                    <span class="text-sm text-gray-600">4k</span>
                    <i class="fa fa-eye text-gray-500"></i>
                </div>
            </div>
            <div class="w-2/6 h-[80px] rounded-xl relative z-10 overflow-hidden">
                <img class="w-full h-full object-cover rounded-xl"
                     src="<?=asset('assets/images/icons/us.png')?>" alt="dollar">
            </div>
        </div>
    </div>
</main>

<!-- Footer -->
<footer class="w-full flex flex-col items-center px-6 py-5 gap-10 z-30 my-light size">
    <a class="" href="">
        <img class="w-10" src="<?=asset('assets/images/icons/mic.jpg')?>" alt="">
    </a>
    <nav class="flex flex-wrap text-center w-full justify-center gap-y-10 text-black/70">
        <p class="w-1/2"><a class="px-5" href="">خانه</a></p>
        <p class="w-1/2"><a class="px-5" href="">جدید ترین خبر ها</a></p>
        <p class="w-1/2"><a class="px-5" href="">درباره</a></p>
        <p class="w-1/2"><a class="px-5" href="">حریم خصوصی</a></p>
    </nav>
    <div class="w-full flex flex-col items-center gap-2">
        <p><a href="mailto:info@example.com"
              class="text-base font-bold whitespace-nowrap text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-red-400 to-yellow-500">info@gmail.com</a>
        </p>
        <hr class="border-t-[1px] border-black/80 w-full">
        <span class="text-black/50 text-md font-semibold">Lorem ipsum dolor sit amet.</span>
    </div>
</footer>

<!-- sign -->
<div id="sign"
     class="fixed top-0 right-0 w-full h-[100vh] bg-black/10 backdrop-blur-sm z-30 invisible backdrop-grayscale">
    <form id="signin" action="<?=url('index.php')?>" method="post"
          class="invisible w-3/4 h-auto rounded-2xl bg-white flex flex-col justify-center items-center fixed z-50 top-[50%] left-[50%] translate-y-[-50%] translate-x-[-50%] py-3 gap-5 px-5">
        <div class=" grid grid-cols-3 w-full content-stretch items-baseline mb-5">
            <i onclick="close_sign()" class="fa fa-close"></i>
            <h1 class="text-2xl font-bold text-center inline">ورود</h1>
            <div class=""></div>
        </div>

        <input type="text" name="username" placeholder="نام کاربری خود را وارد کنید">
        <input type="password" name="password" placeholder="رمز عبور خود را وارد کنید">
        <input class="bg-green-500 w-1/3 font-semibold text-md text-white text-center" type="submit" value="ورود">
        <p>حساب ندارید <span class="text-red-400" onclick="signup()">ساخت حساب</span></p>
    </form>
    <form id="signup" action="<?=url('index.php?show=signup')?>" method="post"
          class="invisible w-3/4 h-auto rounded-2xl bg-white flex flex-col justify-center items-center fixed z-50 top-[50%] left-[50%] translate-y-[-50%] translate-x-[-50%] py-3 gap-5 px-5">
        <div class=" grid grid-cols-3 w-full content-stretch items-baseline mb-5">
            <i onclick="close_sign()" class="fa fa-close"></i>
            <h1 class="text-2xl font-bold text-center inline">ثبت نام</h1>
            <div class=""></div>
        </div>

        <input type="text" name="username" placeholder="نام کاربری خود را وارد کنید">
        <section class="bg-light my-0 px-2"><small class="text-danger"><?php
                if ($err->has("username")) {
                    echo $err->get("username");
                }
                ?></small></section>
        <input type="password" name="password" placeholder="رمز عبور خود را وارد کنید">
        <section class="bg-light my-0 px-2"><small class="text-danger"><?php
                if ($err->has("password")) {
                    echo $err->get("password");
                }
                ?></small></section>
        <input type="password" name="confirm" placeholder="رمز عبور خود را دوباره وارد کنید">
        <section class="bg-light my-0 px-2"><small class="text-danger"><?php
                if ($err->has("confirm")) {
                    echo $err->get("confirm");
                }
                ?></small></section>
        <input type="email" name="email" placeholder="ایمیل خود را وارد کنید">
        <section class="bg-light my-0 px-2"><small class="text-danger"><?php
                if ($err->has("email")) {
                    echo $err->get("email");
                }
                ?></small></section>
        <input class="bg-green-500 w-1/3 font-semibold text-md text-white text-center" type="submit" value="ورود">
        <p>حساب دارید <span class="text-red-400" onclick="signin()">وارد شوید</span></p>
    </form>
</div>











<script src="<?=asset('assets/js/app.js')?>"></script>
</body>

</html>
