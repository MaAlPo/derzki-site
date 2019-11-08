<?php
//        если пользователь не авторизован, то выводим кнопку авторизации
            if(!isset($_SESSION['user_name']) && !isset($_SESSION['user_link']) && !isset($_SESSION['user_photo'])){
//                    авторизация ВК
                if (!isset($_GET['code'])) {
                    echo $link = '
                            <p class="vk_authorize">
                                <a href="'.$vk_auth_link.'">Авторизация</a>
                            </p>';
                }
//        если пользователь авторизован - проверяем дату окончания конкурса
            }else {
//        данные авторизованного пользователя
                echo <<<HERE
                    <div class='us_data'>
                        <div class='us_logo'>
                            <img src={$_SESSION['user_photo']} alt='user photo'/>
                        </div>
                        <div class='us_name'><p>{$_SESSION['user_name']}</p>
                            <form action='/registration.php?signout' method='post'><button type='submit'>SIGN OUT</button></form>
                        </div>
                    </div>
HERE;
            }
?>