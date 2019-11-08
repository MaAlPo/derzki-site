<?php
//  ищем дату последнего розыгрыша
function find_last_comp_date($pdo){
    try {
        $def_date = "0000-00-00";

        $sql = "SELECT DISTINCT winner.comp_date FROM winner";

        $result = $pdo->query($sql);
        $records = $result->fetchAll(PDO::FETCH_NUM);

        foreach ($records as $date) {
            if ($def_date < $date[0]) $def_date = $date[0];
        }
        $last_comp_date = new DateTime($def_date);

        return $last_comp_date;
    }catch(PDOException $e){
//        header("location: 404.php");
    }
}

//  проверяем введенные победителем данные получателя
function check_winner_data(){
    $last_name = htmlspecialchars(stripslashes($_POST["lastname"]));
    $first_name = htmlspecialchars(stripslashes($_POST["firstname"]));
    $second_name = htmlspecialchars(stripslashes($_POST["secondname"]));
    $phone = htmlspecialchars(stripslashes($_POST["phone"]));
    $index = htmlspecialchars(stripslashes($_POST["index"]));
    $country = htmlspecialchars(stripslashes($_POST["country"]));
    $state = htmlspecialchars(stripslashes($_POST["state"]));
    $city = htmlspecialchars(stripslashes($_POST["city"]));
    $address = htmlspecialchars(stripslashes($_POST["address"]));
    $address_info = htmlspecialchars(stripslashes($_POST["address_info"]));
    $prize_info = htmlspecialchars(stripslashes($_POST["prize_info"]));

    $paper = $_POST["paper"];

    if(!$paper){
        $msg = "Регистрация невозможна без принятия <a href=''>Пользовательского Соглашения</a>";
        header("location: registration.php?registration&new_address&err=$msg");
    }elseif($paper && $last_name && $first_name && $second_name && $index && $country && $state && $city && $address){
        $user = array(
            'first_name' =>$first_name,
            'second_name'=>$second_name,
            'last_name'  =>$last_name,
            'country'     =>$country,
            'state'      =>$state,
            'city'       =>$city,
            'address'    =>$address,
            'address_info'=>'',
            'prize_info' =>'',
            'index_num'  =>'',
            'phone'      =>'');
        if($address_info) $user['address_info'] = $address_info;
        if($prize_info) $user['prize_info'] = $prize_info;
        if(filter_var($index, FILTER_SANITIZE_NUMBER_INT)) $user['index_num'] = $index;
        if(filter_var($phone, FILTER_SANITIZE_NUMBER_FLOAT)) $user['phone'] = $phone;

        return $user;
    }else{
//        echo "<div class='warning'><h3>Заполните ВСЕ обязательные поля</h3></div>";
        $msg = "Заполните ВСЕ обязательные поля";
        header("location: registration.php?registration&new_address&err=$msg");
    }
}

//  сохраняем данные получателя в БД
function save_winner_data($user, $pdo){
    try{
        $id = get_id_comp($pdo);

        foreach($id as $item) {
            $item = intval($item);
            $stmt = $pdo->prepare("INSERT INTO data (id, first_name, second_name, last_name, phone, index_num, country,
                                state, city, address, address_info, prize_info)
                                VALUES (:id, :first_name, :second_name, :last_name, :phone, :index_num, :country,
                                :state, :city, :address, :address_info, :prize_info)");
            $stmt->bindParam(':id', $item);
            $stmt->bindParam(':first_name', $user['first_name']);
            $stmt->bindParam(':second_name', $user['second_name']);
            $stmt->bindParam(':last_name', $user['last_name']);
            $stmt->bindParam(':phone', $user['phone']);
            $stmt->bindParam(':index_num', $user['index_num']);
            $stmt->bindParam(':country', $user['country']);
            $stmt->bindParam(':state', $user['state']);
            $stmt->bindParam(':city', $user['city']);
            $stmt->bindParam(':address', $user['address']);
            $stmt->bindParam(':address_info', $user['address_info']);
            $stmt->bindParam(':prize_info', $user['prize_info']);

            $stmt->execute();
        }
        if($stmt){
            return true;
        }else{
            return false;
        }
    }catch(PDOException $e){
//        header("location: 404.php");
    }catch(Exception $ex){
//        header("location: 404.php");
    }
}

// перезаписываем данные пользователя
//function rewrite_winner_data($user, $prizes, $pdo){
//    try{
//        foreach($prizes as $item) {
//            $stmt = $pdo->prepare("UPDATE data SET (first_name, second_name, last_name, phone, index_num, country,
//                                state, city, address, address_info, prize_info)
//                                VALUES (?,?,?,?,?,?,?,?,?,?,?)
//                                WHERE data.prize_link=".$item);
//            $stmt->bindParam(1, $user['first_name']);
//            $stmt->bindParam(2, $user['second_name']);
//            $stmt->bindParam(3, $user['last_name']);
//            if ($user['phone']) $stmt->bindParam(4, $user['phone']);
//            else $stmt->bindParam(4, null);
//            if ($user['index_num']) $stmt->bindParam(5, $user['index_num']);
//            else $stmt->bindParam(5, null);
//            $stmt->bindParam(6, $user['country']);
//            $stmt->bindParam(7, $user['state']);
//            $stmt->bindParam(8, $user['city']);
//            $stmt->bindParam(9, $user['address']);
//            if ($user['address_info']) $stmt->bindParam(10, $user['address_info']);
//            else  $stmt->bindParam(10, null);
//            if ($user['prize_info']) $stmt->bindParam(11, $user['prize_info']);
//            else  $stmt->bindParam(11, null);
//
//            $stmt->execute();
//        }
//        if($stmt){
//            return true;
//        }else{
//            return false;
//        }
//    }catch(PDOException $e){
////        header("location: 404.php");
//    }catch(Exception $ex){
////        header("location: 404.php");
//    }
//}

//  проверяем наличие пользователя в списке победителей и его адреса
function find_my_prize($pdo){
    $prizes = array();
    try {
        $sql = "SELECT winner.id AS id, winner.prize_link AS prize, winner.comp_date AS comp_date
                FROM winner
                WHERE winner.winner_page = '{$_SESSION["user_link"]}'";
        $result = $pdo->query($sql);
        if($result){
            $records = $result->fetchAll(PDO::FETCH_ASSOC);
            if($records){
                foreach ($records as $prize) {
                    $prizes[] = array(
                        'id' => $prize['id'],
                        'prize' => $prize['prize'],
                        'comp_date' => $prize['comp_date']
                    );
                }
            }else{
                $prizes = false;
            }
        }else{
            throw new PDOException();
        }
    }catch(myException $e){

    }catch(PDOException $ee){

    }
    return $prizes;
}

function find_my_data($data, $pdo){
    $addresses = array();
    try {
        $sql = "SELECT *
                FROM data
                WHERE data.id = ".$data['id'];
        $result = $pdo->query($sql);
        if($result) {
            $records = $result->fetchAll(PDO::FETCH_ASSOC);
            if ($records) {
                foreach ($records as $line) {
                    $addresses = array(
                        'id'      => $line['id'],
                        'name'    => $line['last_name'] . " " . $line['first_name'] . " " . $line['second_name'],
                        'phone'   => $line['phone'],
                        'index'   => $line['index_num'],
                        'country' => $line['country'],
                        'city'    => $line['state'] . ", " . $line['city'],
                        'address' => $line['address'],
                        'a_info'  => $line['address_info'],
                        'p_info'  => $line['prize_info']
                    );
                }
            }
        }else{
            $msg = 'Ошибка получения данных';
            throw new myException($msg);
        }
    }catch(myException $e){

    }catch(PDOException $ee){

    }
    return $addresses;
}

function get_id_comp($pdo){
    try{
        $date = date_format(find_last_comp_date($pdo), "Y-m-d");
        $sql = "SELECT id FROM winner WHERE winner_page = '".$_SESSION['user_link']."' AND comp_date = '".$date."'";
        $id = array();

        $result = $pdo->query($sql);
        $records = $result->fetchall(PDO::FETCH_NUM);
        foreach($records as $item){
            $id[] = $item[0];
        }
        return $id;
    }catch (PDOException $e) {

    }

}