<?php
require_once 'helpers.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>در حال هدایت...</title>
    <script>
        let seconds = 5; // تعداد ثانیه‌ها
        function countdown() {
            if (seconds > 0) {
                document.getElementById("countdown").innerText = seconds;
                seconds--;
                setTimeout(countdown, 1000); // اجرا مجدد تابع بعد از 1 ثانیه
            } else {
                window.location.href = "<?=url('auth/login.php')?>"; // هدایت به صفحه ورود
            }
        }
        window.onload = countdown; // شروع شمارش معکوس هنگام بارگذاری صفحه
    </script>
    <link rel="stylesheet" href="<?= asset('assets/css/bootstrap.min.css') ?>" media="all" type="text/css">
    <link rel="stylesheet" href="<?= asset('assets/css/style.css') ?>" media="all" type="text/css">
</head>

<body>
<section id="app">
    <section class="container-fluid">
        <section class="row">
            <section class="col-md-12">
                <p class="text-danger font-weight-bolder" style="text-align: center; padding-top: 400px"><?="! دسترسی شما به این صفحه مجاز نیست"?></p>
                <p class="text-danger font-weight-bolder" style="text-align: center; padding-top: 400px"><span id="countdown">5</span><?="تا پنج ثانیه ی دیگر به صفحه ورود برمیگردید"?></p>
            </section>
        </section>
    </section>
</section>

<script src="<?= asset('assets/js/jquery.min.js') ?>"></script>
<script src="<?= asset('assets/js/bootstrap.min.js') ?>"></script>
</body>

</html>