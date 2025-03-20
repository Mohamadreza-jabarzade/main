<?php
global $pdo;
require_once "functions/helpers.php";
require_once "functions/db.php";
require_once "functions/error_handle.php";
session_start();
$err_signin = new handle();
$err_signup = new handle();

//signup handle


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST["signin"])){

        if (isset($_POST['username']) && isset($_POST['password'])) {
            if (!empty($_POST['username']) && !empty($_POST['password'])) {
                    if (strlen($_POST["password"]) < 8 ) {
                        $err_signin->set_validity_err("password","Password must be at least 8 characters long");
                    }else{
                        $query = "select * from users where username = ?";
                        $stmt = $pdo->prepare($query);
                        $stmt->execute([$_POST['username']]);
                        $row = $stmt->fetch();

                        if ($row) {
                            if (password_verify($_POST['password'], $row->password)) {
                                if ($row->admin === 1){
                                    $_SESSION['admin'] = $row->email;
                                    redirect('panel');
                                }
                                else {
                                    $_SESSION['user'] = $row->username;
                                    redirect('index.php');
                                }
                            }
                        }
                        else {
                            $err_signin -> set_validity_err("username","username or password is incorrect");
                        }
                    }

            }
            else{
                $err_signin -> set_validity_err("password","please fill all fields");
            }
        }

    }
    }



    if (isset($_POST["signup"])) {



        if(isset($_POST["email"]) &&
            isset($_POST["password"]) &&
            isset($_POST["confirm"]) &&
            isset($_POST["username"])){
            if(empty($_POST["email"])){
                $err_signup->set_empty_err("email","Email is required");
            }
            if(empty($_POST["password"])){
                $err_signup->set_empty_err("password","Password is required");
            }
            if(empty($_POST["confirm"])){
                $err_signup->set_empty_err("confirm","Confirm password is required");
            }
            if(empty($_POST["username"])){
                $err_signup->set_empty_err("username","username is required");
            }
            if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
                $err_signup->set_validity_err("email","Invalid email format");
            }
            if ($err_signup->count_errors() == 0){

                if ($_POST["password"] == $_POST["confirm"]) {
                    if (strlen($_POST["password"]) < 8 ) {
                        $err_signup->set_validity_err("password","Password must be at least 8 characters long");
                    }
                    else{
                        $query = 'select * from users where email = ? or username = ?';
                        $stmt = $pdo->prepare($query);
                        $stmt->execute([$_POST["email"],$_POST["username"]]);
                        $user = $stmt->fetch();
                        if ($user) {
                            $err_signup -> set_validity_err("email","This email is already taken");
                            $err_signup -> set_validity_err("username","This username is already taken");
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
                    $err_signup->set_validity_err("confirm_password","confirm not matched with password.");
                }
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
    <link rel="icon" type="image/x-icon" href="<?=asset('assets/images/US_one_dollar_bill,_obverse,_series_2009 1 (1).png')?>">
    <meta name="description" content="اخبار">
    <link rel="stylesheet" href="fontawesome-free-6.6.0-web/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?=asset('assets/css/style.css')?>" />
    <title>news</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css">
    <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
</head>

<body class=" dark:bg-zinc-900 dark:text-slate-300 bg-l0" dir="rtl">
<header class="w-full bg-l1 dark:bg-zinc-800 ">
    <div class="h-[65px] md:h-[80px] flex items-center justify-between z-30 size mb-5">
        <button class="block md:hidden md:invisible" onclick="open_nav()"><i class="fa fa-bars"></i></button>

        <div class="flex gap-8 items-center">
            <a class="flex items-center gap-x-1 dark:text-brown-300 text-brown-600 font-MorabbaMedium text-xl"
               href="">
                <img class="w-6 h-6" src="<?=asset('assets/images/logo.png')?>" alt="">
                <h1>
                    خبر
                </h1>
            </a>
            <div class="hidden md:block border-l-[1px]lack/60 dark:border-slate-300 h-8 "></div>
            <nav class="hidden md:flex gap-5 items-center font-semibold text-md">
                <a class="nav_item" href="#">خانه</a>
                <a class="nav_item" href="">جدید ترین خبر ها</a>
                <a class="nav_item" href="">اخبار سیاسی</a>
                <div class="group relative cursor-pointer py-2">
                    <div class="flex items-center justify-between space-x-5 px-4 nav_item">
                        <a class="menu-hover">
                            خبر ها
                        </a>
                        <span>
                                <i class="fa fa-angle-down"></i>
                            </span>
                    </div>

                    <div
                            class="absolute left-[50%] z-50 w-5/4 translate-x-[-50%] scale-y-0 origin-top transform flex flex-col bg-gray-100 dark:bg-zinc-800 py-1 px-4 text-gray-800 shadow-xl transition-transform duration-300 ease-in-out group-hover:scale-y-100">
                        <a class="drop_item">
                            سیاسی
                        </a>
                        <a class="drop_item">
                            اقتصادی
                        </a>
                        <a class="drop_item">
                            عمومی
                        </a>
                    </div>
                </div>

            </nav>

        </div>
        <div class="flex items-center gap-5">
            <a class="hidden md:block bg-red-500 rounded-md text-white px-3 py-2 hover:bg-red-600 duration-300 transition-color"
               href="admin_panel.html">پنل ادمین</a>
            <button onclick="theme()" class="hidden md:block"><i class="fa fa-moon" id="themebtn"></i></button>
            <button onclick="open_sign()"
                    class="hidden md:block bg-red-500 rounded-md text-white px-3 py-2 hover:bg-red-600 duration-300 transition-color"
                    href=""><i class="fa fa-user-o ml-3"></i>حساب کاربری</button>

            <button class="md:hidden" onclick="open_sign()"><i class="fa fa-user-o"></i></button>
        </div>

        <!-- Mobile Nav -->
        <div id="overlay" onclick="close_nav()"
             class="invisible fixed top-0 right-0 w-full h-[100vh] bg-black/80 z-30">
        </div>

        <div id="nav"
             class="invisible fixed top-0 right-[-100%] w-4/6 h-[100vh] bg-l1 z-50 flex flex-col items-center dark:bg-zinc-700 px-4 transition-all duration-300 font-semibold">
            <div class="flex items-center h-[65px] justify-between w-full ">
                <i onclick="close_nav()" class="fa fa-close cursor-pointer"></i>
            </div>
            <hr class="border-b-2 w-full dark:border-white/10 border-gray-200">
            <form class="w-full my-3" action="" method="post">
                <input
                        class="input border-black/10 dark:border-white/10"
                        type="search" name="" id="" placeholder="جستوجو...">
            </form>
            <nav class="flex flex-col items-start justify-center w-full gap-6 py-3 ">
                <a href="#"><i class="fas fa-house-user ml-2"></i> صفحه اصلی</a>

                <!-- Dropdown -->
                <a id="newsBtn" class="cursor-pointer flex items-center justify-between w-full">
                    <span><i class="fa fa-newspaper ml-2"></i> خبر ها</span>
                    <i class="fa fa-angle-down ml-5"></i>
                </a>
                <ul id="newsDropdown"
                    class="w-full  flex-col pr-8 list-disc transform scale-y-0 origin-top transition-all duration-500 ease-in-out hidden">
                    <li><a href="#">item1</a></li>
                    <li><a href="#">item2</a></li>
                    <li><a href="#">item3</a></li>
                </ul>

                <a href="#"><i class="fa fa-info-circle ml-2"></i> درباره ما</a>
                <hr class="border-b-2 w-full dark:border-white/10 border-gray-200">
                <a href="#"><i class="fa fa-user-circle ml-2"></i>حساب کاربری</a>
                <a onclick="theme()"><i class="fa fa-sun ml-2"></i>تم</a>
            </nav>
        </div>
</header>

<!-- Main -->
<main class="w-full px-6 flex flex-col gap-4 size">

    <!-- image slider -->
    <div class="w-full h-[50vh] ">
        <div class="swiper mySwiper h-full rounded-2xl">
            <div class="swiper-wrapper">

                <div class="swiper-slide relative">
                    <img src="<?=asset('assets/images/donald-trump-2016-campaign-gk8d3spb0py2r0v7.jpg')?>" class="w-full h-full object-cover rounded" alt="خبر تکنولوژی">
                    <div class="absolute inset-0 bg-black/50 gap-3 flex items-center pb-10 justify-end flex-col">
                        <a class="text-white text-2xl font-bold animate-pulse">خبر </a>
                        <p class="text-white/70 text-md ">Lorem ipsum dolor sit amet consectetur adipisicing elit. Odit, quod?</p>
                    </div>
                </div>

                <div class="swiper-slide relative">
                    <img src="<?=asset('assets/images/trump-1920-x-1334-background-pylm0uu8elu1z21b.jpg')?>" class="w-full h-full object-cover rounded" alt="خبر تکنولوژی">
                    <div class="absolute inset-0 bg-black/50 gap-3 flex items-center pb-10 justify-end flex-col">
                        <a class="text-white text-2xl font-bold animate-pulse">خبر </a>
                        <p class="text-white/70 text-md ">Lorem ipsum dolor sit amet consectetur adipisicing elit. Odit, quod?</p>
                    </div>
                </div>
            </div>
            <!-- buttons -->
            <div
                    class="slider-btn left-3 next">
                <i class="fa fa-angle-left"></i>
            </div>
            <div
                    class="slider-btn right-3 prev">
                <i class="fa fa-angle-right"></i>
            </div>
            <!-- dots -->
            <div class="swiper-pagination"></div>
        </div>
    </div>




    <!-- titles -->
    <div class="w-full flex justify-between items-center">
        <h2 class="font-bold">اخبار سیاسی</h2>
        <a class=" bg-red-500 rounded-md text-white px-3 py-2 hover:bg-red-600 duration-300 transition-color" href="">همه</a>
    </div>

    <!-- items -->
    <div class="w-full grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- item -->
        <a href="page.html" class="post">
            <div class="w-4/6 flex flex-col">
                <h2 class="text-lg font-semibold">قیمت دلار</h2>
                <b id="desc">لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است</b>
                <div class="post-detail">
                    <i class="fa fa-calendar-check-o text-sm "></i>
                    <span class="text-sm mr-1">1404/1/1</span>
                    .
                    <span class="text-sm ml-1">4k</span>
                    <i class="fa fa-eye text-sm"></i>
                </div>
            </div>
            <div class="post-image">
                <img src="<?=asset('assets/images/US_one_dollar_bill,_obverse,_series_2009 1 (1).png')?>" alt="dollar">
            </div>
        </a>

        <a href="page.html" class="post">
            <div class="w-4/6 flex flex-col">
                <h2 class="text-lg font-semibold">شاه</h2>
                <b id="desc">لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است</b>
                <div class="post-detail">
                    <i class="fa fa-calendar-check-o text-sm "></i>
                    <span class="text-sm mr-1">1404/1/1</span>
                    .
                    <span class="text-sm ml-1">60M</span>
                    <i class="fa fa-eye text-sm"></i>
                </div>
            </div>
            <div class="post-image">
                <img src="<?=asset('assets/images/download.jpg')?>" alt="dollar">
            </div>
        </a>

</main>

<!-- Footer -->
<footer class="w-full dark:bg-zinc-800 dark:text-slate-300 bg-l1">
    <div class="w-full flex flex-col items-center py-5 gap-10 z-30 size size mt-5">
        <a class="" href="">
            <img class="w-10" src="<?=asset('assets/images/logo.png')?>" alt="">
        </a>
        <nav class="flex flex-wrap text-center w-full justify-center gap-y-10 text-black/70 dark:text-slate-300">
            <p class="w-1/2"><a class="px-5" href="">خانه</a></p>
            <p class="w-1/2"><a class="px-5" href="">جدید ترین خبر ها</a></p>
            <p class="w-1/2"><a class="px-5" href="">درباره</a></p>
            <p class="w-1/2"><a class="px-5" href="">حریم خصوصی</a></p>
        </nav>
        <div class="w-full flex flex-col items-center gap-2">
            <p><a href="mailto:info@example.com"
                  class="text-base font-bold whitespace-nowrap text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-red-400 to-yellow-500">info@gmail.com</a>
            </p>
            <hr class="-2 dark:border-white/10 w-full">
            <span class="text-black/50 text-sm font-semibold text-center w-full dark:text-slate-300/40">Lorem ipsum
                    dolor sit amet.</span>
        </div>
    </div>

</footer>


<!-- sign -->
<div id="sign"
     class="fixed top-0 md:w-1/6 right-0 w-full h-[100vh] bg-black/10 backdrop-blur-sm z-30 invisible backdrop-grayscale">
    <form id="signin" action="" method="post"
          class="invisible w-3/4 h-auto rounded-2xl bg-white dark:bg-zinc-800 flex flex-col justify-center items-center fixed z-50 top-[50%] left-[50%] translate-y-[-50%] translate-x-[-50%] py-3 gap-5 px-5">
        <div class=" grid grid-cols-3 w-full content-stretch items-baseline mb-5">
            <i onclick="close_sign()" class="fa fa-close"></i>
            <h1 class="text-2xl font-bold text-center inline">ورود</h1>
            <div class=""></div>
        </div>

        <input class="input" type="text" name="username" placeholder="نام کاربری خود را وارد کنید">
        <input class="input" type="password" name="password" placeholder="رمز عبور خود را وارد کنید">
        <input class="btn" type="submit" value="ورود">
        <p>حساب ندارید <span class="text-red-400" onclick="signup()">ساخت حساب</span></p>
    </form>
    <form id="signup" action="" method="post"
          class="invisible w-3/4 h-auto rounded-2xl bg-white dark:bg-zinc-800 flex flex-col justify-center items-center fixed z-50 top-[50%] left-[50%] translate-y-[-50%] translate-x-[-50%] py-3 gap-5 px-5">
        <div class=" grid grid-cols-3 w-full content-stretch items-baseline mb-5">
            <i onclick="close_sign()" class="fa fa-close"></i>
            <h1 class="text-2xl font-bold text-center inline">ثبت نام</h1>
            <div class=""></div>
        </div>
        <div class="flex flex-col gap-2 w-full">
            <input
                    class="input"
                    type="text" name="username" id="name" oninput="validateUsername()"
                    placeholder="نام کاربری خود را وارد کنید">

            <span id="name_msg" class="text-red-500 text-[10px] hidden items-center">
                    <i class="fa fa-exclamation-circle text-sm mx-1"></i>
                    باید حداقل ۳ حرف باشد و فقط شامل حروف باشد
                </span>
        </div>

        <div class="flex flex-col gap-2 w-full">
            <input
                    class="input"
                    oninput="validateemail()" type="email" id="email" name="email" required
                    title="حداقل 5 نویسه و شامل @ باشد" placeholder="ایمیل خود را وارد کنید">
            <span id="email_msg" class="text-red-500 text-[10px] hidden items-center"><i
                        class="fa fa-exclamation-circle text-sm mx-1"></i>
                    حداقل 5 نویسه و شامل @ باشد
                </span>
        </div>

        <div class="flex flex-col gap-2 w-full">
            <input
                    class="input"
                    type="password" name="password" id="password" oninput="pass()"
                    placeholder="رمز عبور خود را وارد کنید">
            <span id="pass_msg" class="text-red-500 text-[10px] hidden items-center"><i
                        class="fa fa-exclamation-circle text-sm mx-1"></i>
                    حداقل 8 نویسه و یک حرف و یک نماد باشد
                </span>
        </div>

        <div class="flex flex-col gap-2 w-full">
            <input
                    class="input"
                    type="password" name="password" id="repassword" oninput="repass()"
                    placeholder="رمز عبور خود را دوباره وارد کنید">
            <span id="repass_msg" class="text-red-500 text-[10px] hidden items-center"><i
                        class="fa fa-exclamation-circle text-sm mx-1"></i>
                    رمز عبور یکسان نیست
                </span>
        </div>

        <input disabled id="sign_btn"
               class="btn"
               type="submit" value="ثبت نام">
        <p>حساب دارید <span class="text-red-400" onclick="signin()">وارد شوید</span></p>
    </form>
</div>


<script src="<?=asset('assets/js/form.js')?>"></script>
<script src="<?=asset('assets/js/app.js')?>"></script>
</body>

</html>