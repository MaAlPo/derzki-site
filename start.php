<?php
session_start();
    require_once "php/connection.php";
    require_once "php/user.php";
    require_once "php/vk-auth.php";
?>

<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Дерзкий Шопоголик</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <meta name="viewport" content="width=1000">
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
                <li><a href="start.php">главная</a></li>
                <li><a href="registration.php">получить приз</a></li>
            </ul>
        </div>

        <div class="content">
            <div class="under_menu">
                <?php
                require_once "php/login.php";

                if(!isset($_GET['show_me']) && !isset($_GET['show_all'])) {
                    echo <<<HERE
                        <form action='/start.php?show_all' method='POST'>
                            <button id='search_all_me' type='submit' >Показать всех</button>
                        </form></div>
HERE;
                }elseif(isset($_GET['show_all'])){
                    echo <<<HERE
                        <form action='/' method='POST'>
                            <button id='search_all_me' type='submit' >Скрыть список</button>
                        </form>
HERE;
                    if(isset($_SESSION['user_name'])){
                        echo <<<HERE
                                <form action='/start.php?show_me' method='POST'>
                                    <button id='search_all_me' type='submit' >Найти меня</button>
                                </form>
HERE;
                    }
                    echo "</div>";
                    require_once "php/s_get_all_prize.php";
                }elseif(isset($_GET['show_me']) ){
                    echo <<<HERE
                        <form action='/start.php?show_all' method='POST'>
                            <button id='search_all_me' type='submit' >Показать всех</button>
                        </form>
HERE;
                    echo "</div>";
                    require_once "php/s_get_user_prize.php";
                }
            ?>
        </div>
            <div>
                <h3>ЗАСТАВКА: мы бест оф зы бест оф зы бест</h3>
            </div>
            <div>
                <h3>ЗАСТАВКА: следующий конкурс будет такого-то числа, участвуй и все будет ништяк</h3>
            </div>

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