<?php
session_start();

require_once "php/connection.php";
require_once "php/admin.php";

if(isset($_GET['in'])){
    if(cat_login($pdo)){
        $_SESSION['cat'] = 'cat';
        $_SESSION['name'] = 'Насяльника';
        header("location: /cat.php");
    }else{
        header("location: /404-cat.php");
    }
}elseif(isset($_GET['out'])){
    unset($_SESSION['cat']);
    unset($_SESSION['name']);

    header("location: /cat.php");
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
    <title>Главная</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <meta name="viewport" content="width=1000">
    <link rel="shortcut icon" href="content/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" type="text/css" href="css/catstyle.css" media="screen, projection, print">

    <script src="lib/modernizr-2.6.2-respond-1.1.0.min.js"></script>
</head>
<body>

<!--[if lt IE 7]>
<p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
<![endif]-->

<div class = "wrapper">

    <div class="container">

        <div class="content">
            <?php if(!isset($_SESSION['cat'])): ?>
                <?php
                    echo <<<HERE
                        <div class="catlog">
                            <form class='adlog' action="cat.php?in" method="POST">
                                <input id="catname" name="catname" type="text"/>
                                <input id="catpass" name="catpass" type="password"/>
                                <button type="submit">GO</button>
                            </form>
                        </div>
HERE;
                    ?>
            <?php endif;?>

            <?php if(isset($_SESSION['cat'])): ?>
                <?php
                    echo <<<HERE
                        <div class="catlog">
                            <form class='adlog' action="cat.php?out" method="POST">
                                <p>Здравствуй, {$_SESSION['name']}</p>
                                <button type="submit">OUT</button>
                            </form>
                        </div>
<!--//сохраняем победителей-->
            <form class='catforma' action="cat.php?save" method="POST" enctype=multipart/form-data>
                <h4>Добавить данные нового розыгрыша</h4>
                <span>Добавьте файл</span>
                <input id="winners_save" name="winners_save" type="file"/><br>
                <span>Укажите дату розыгрыша</span>
                <input id="comp_date_save" name="comp_date_save" type="date"/><br>
                <button type="submit">Сохранить</button>
            </form>
<!--//получаем списки откликнувшихся-->
            <form class='catforma' action="cat.php?show" method="POST">
                <h4>Посмотреть данные получателей</h4>
                <span>Укажите дату розыгрыша</span>
                <select id="comp_date_get" name="comp_date_get" >
                    <option value="">Выбрать</option>
HERE;
                get_comp_date($pdo);

                echo <<<HERE
                </select><br>
                <span>Показать всех</span>
                <input id="show_all" name="show_all" value="1" type="checkbox"/><br>
                <button type="submit">Посмотреть</button>
            </form>

HERE;
            if(isset($_GET['save'])){
                if(isset($_FILES["winners_save"]) && $_FILES["winners_save"]["name"] != ""){
                    if(isset($_POST["comp_date_save"])){

//обработка файла с результатами конкурса, запись в БД
                        save_data(load_file(), $_POST["comp_date_save"], $pdo);

                        unset($_FILES);
                        unset($_POST);
                        unset($_GET);
                    }else{
                        echo <<<HERE
                        <div class="warning">
                            <h3>ОШИБКА! Укажите дату проведения конкурса!</h3>
                        </div
HERE;
                    }
                }else{
                    echo <<<HERE
                    <div class="warning">
                            <h3>ОШИБКА! Загрузите файл с данными!</h3>
                    </div>
HERE;
                }
            }elseif(isset($_GET['show'])) {
                if(isset($_POST["show_all"]) && $_POST["show_all"] == "1"){
                    echo <<<HERE
                    <a class='hide_list' href='cat.php'>Скрыть список</a>
HERE;
                    get_all_auth_user($pdo);
                }elseif(isset($_POST["comp_date_get"]) && $_POST["comp_date_get"] != ""){
                    echo <<<HERE
                    <a class='hide_list' href='cat.php'>Скрыть список</a>
HERE;
                    get_specific_auth_user($pdo);
                }
            }
            unset($_POST);

                ?>
            <?php endif; ?>

        </div>
    </div> <!-- container -->

    <div class = "empty"></div>
</div> <!-- wrapper -->

<footer>
    &copy; 2015. Дерзкий Шопоголик. Все права защищены.
</footer>

<script src="lib/jquery-1.8.3.min.js"></script>
<script src="js/script.js"></script>
</body>
</html>