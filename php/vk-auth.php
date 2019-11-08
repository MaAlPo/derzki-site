<?php
session_start();

if(isset($_GET["signout"])){
    session_destroy();
    unset($_SESSION["user_name"]);
    unset($_SESSION["user_photo"]);
    unset($_SESSION["user_link"]);

    header("location: /");
}else{
    $client_id = '5248342';
    $client_secret = '4ILmlOCxJv1ljWwjiNy3';
    $redirect_uri = 'http://127.0.0.1/derzki-site/';

    $url_auth = 'http://oauth.vk.com/authorize';
    $url_token = 'https://oauth.vk.com/access_token';
    $url_query = 'https://api.vk.com/method/users.get';

    $params = array(
        'client_id' => $client_id,
        'display' => 'page',
        'redirect_uri' => $redirect_uri,
        'response_type' => 'code'
    );

    $vk_auth_link = $url_auth.'?'.urldecode(http_build_query($params));

    if (isset($_GET['code'])) {
        $res = false;
        $userInfo = array();
        $params = array(
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'redirect_uri' => $redirect_uri,
            'code' => $_GET['code']
        );

        $vk_token_link = $url_token.'?'.urldecode(http_build_query($params));

        $token = json_decode(file_get_contents($vk_token_link), true);

        if (isset($token['access_token'])) {
            $params = array(
                'uids' => $token['user_id'],
                'fields' => 'uid,first_name,last_name,photo_big',
                'access_token' => $token['access_token']
            );

            $vk_query_link = $url_query.'?'.urldecode(http_build_query($params));

            $userInfo = json_decode(file_get_contents($vk_query_link), true);

            if (isset($userInfo['response'][0]['uid'])) {
                $userInfo = $userInfo['response'][0];
                $res = true;
            }else{
                echo "<h3>Не получены данные пользователя</h3>";
            }
        }else{
            echo "<h3>Не получен токен</h3>";
        }

        if ($res) {
            $user_name = $userInfo['first_name']." ".$userInfo['last_name'];
            $_SESSION['user_name'] = $user_name;
            $user_link = "https://vk.com/id".$userInfo['uid'];
//            $_SESSION['user_link'] = $user_link;
            $_SESSION['user_link'] = "https://vk.com/teplinskij";
            $user_photo = $userInfo['photo_big'];
            $_SESSION['user_photo'] = $user_photo;
        }
    }else{
    //    echo "<h3>Не получен гет-код</h3>";
    }
}