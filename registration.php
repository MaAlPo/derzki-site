<?php
session_start();

require_once "php/vk-auth.php";
require_once "php/connection.php";
require_once "php/user.php";
require_once "php/myException.php";
if(isset($_GET['registration']) && !isset($_GET['err'])) {
    $data = check_winner_data();
    if ($data){
        if (!save_winner_data($data, $pdo)) {
            $msg = "Не удалось сохранить данные";
            header("location: ./registration.php?new_adress&err=$msg");
        }
    }
}
?>

<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Призы от Дерзкого Шопоголика</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <meta name="viewport" content="width=device-width">
    <link rel="shortcut icon" href="content/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" type="text/css" href="css/style.css" media="screen, projection, print">

    <script src="lib/modernizr-2.6.2-respond-1.1.0.min.js"></script>
</head>
<body>

<!--[if lt IE 7]>
<p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
<![endif]-->

<div class = "wrapper">
    <div class="container">
        <div class="logo">
            <img src="content/лого-сайт.png" alt="logo"/>
        </div>
        <div class="menu">
            <ul>
                <li><a href="index.php">главная</a></li>
                <li><a href="registration.php">получить приз</a></li>
            </ul>
        </div>
<!--        Прошел конкурс, данные по победителям загружены-->
        <div class="content">
            <div class="under_menu">
<?php
    include "php/login.php";
    echo "</div>";
    if(isset($_GET['err'])){
        echo <<<HERE
            <div class='warning'>
                <h3>{$_GET['err']}</h3>
            </div>
HERE;
    }
    if(isset($_GET['new_address'])){
        include "php/r_form.php";
    }

// зачем вторая проверка???????????
if(!isset($_GET['new_address']) && !isset($_GET['registration'])){
//        находим дату последнего розыгрыша
    $last_comp_date = find_last_comp_date($pdo);
    if ($last_comp_date) {
        $date_query = date_format($last_comp_date, "Y-m-d");
//        прибавляем к ней 6 дней(1 про запас)
        $end_comp_date = $last_comp_date;
        $end_comp_date->add(new DateInterval("P6D"));
        $now_date = new DateTime();

        if (isset($_SESSION['user_name']) && $end_comp_date >= $now_date) {
            $user = $_SESSION['user_link'];
//        проверяем пользователя на наличие в списке победителей и данных для доставки
            $address_data = 0;
            $prizes = find_my_prize($pdo);
            if($prizes){
                foreach($prizes as $prize){
                    $win_data = find_my_data($prize, $pdo);
                    if($win_data) {
                        if ($prize['comp_date'] == $date_query) {
                            $address_data = 2;
                        }
                    }else{
                        if ($prize['comp_date'] == $date_query) {
                            if($address_data != 2){
                                $address_data = 1;
                            }
                        }
                    }
                }
                if($address_data == 2){
                    include "php/r_show_cong.php";
                }elseif($address_data == 1){
                    foreach($prizes as $prize){
                        $win_data = find_my_data($prize, $pdo);
                        if($win_data){
                            include "php/r_show_winner_data.php";
                        }
                    }
                    echo <<<HERE
                        <a class='add_new_address' href="/registration.php?new_address">Указать новые данные</a>
HERE;
                }else{
                    require_once "php/r_form.php";
                }
            }
        }
    }
}
    echo "<h3>ЗАСТАВКА: следующий конкурс будет такого-то числа, участвуй и все будет ништяк</h3>";
?>
        </div> <!-- content -->
    </div> <!-- container -->

    <div class = "empty"></div>
</div> <!-- wrapper -->

<footer>
    &copy; 2015. Дерзкий Шопоголик. Все права защищены.
    <!-- Yandex.Metrika informer -->
    <a href="https://metrika.yandex.ru/stat/?id=35072785&amp;from=informer"
       target="_blank" rel="nofollow"><img src="https://informer.yandex.ru/informer/35072785/2_1_FFFFFFFF_FFFFFFFF_0_pageviews"
                                           style="width:80px; height:31px; border:0; float:right" alt="Яндекс.Метрика" title="Яндекс.Метрика: данные за сегодня (просмотры)" /></a>
    <!-- /Yandex.Metrika informer -->

    <!-- Yandex.Metrika counter -->
    <script type="text/javascript">
        (function (d, w, c) {
            (w[c] = w[c] || []).push(function() {
                try {
                    w.yaCounter35072785 = new Ya.Metrika({
                        id:35072785,
                        clickmap:true,
                        trackLinks:true,
                        accurateTrackBounce:true,
                        webvisor:true
                    });
                } catch(e) { }
            });

            var n = d.getElementsByTagName("script")[0],
                s = d.createElement("script"),
                f = function () { n.parentNode.insertBefore(s, n); };
            s.type = "text/javascript";
            s.async = true;
            s.src = "https://mc.yandex.ru/metrika/watch.js";

            if (w.opera == "[object Opera]") {
                d.addEventListener("DOMContentLoaded", f, false);
            } else { f(); }
        })(document, window, "yandex_metrika_callbacks");
    </script>
    <noscript><div><img src="https://mc.yandex.ru/watch/35072785" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    <!-- /Yandex.Metrika counter -->
</footer>

<script src="lib/jquery-1.8.3.min.js"></script>
<script src="js/script.js"></script>
</body>
</html>