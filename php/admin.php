<?php

function cat_login($pdo)
{
    $name = htmlspecialchars(stripslashes($_POST["catname"]));
    $pass = htmlspecialchars(stripslashes($_POST["catpass"]));

    $sql = "SELECT * FROM cats WHERE login='$name'";
    try {
        $result = $pdo->query($sql);

        if (!$result) throw new PDOException;

        $records = $result->fetchAll(PDO::FETCH_ASSOC);

        $salt = $records[0]['salt'];
        $true_pass = $records[0]['pass'];

        $hash = md5($name.$salt.$pass);

        if ($hash == $true_pass) {
            return true;
        }else{
            throw new Exception;
        }
    } catch (PDOException $e) {
        return false;
    } catch (Exception $ex) {
        return false;
    }
}

function load_file(){
//создаем папку для изображений призов с названием от даты конкурса
    $comp_date = $_POST["comp_date_save"];
    $path = "./content/prize_photo/";
    if(!file_exists($path.$comp_date)) mkdir($path.$comp_date);

// Если файл существует, то сохраняем данные в $file_content и удаляем
    if(file_exists($_FILES['winners_save']['tmp_name'])){
        $file_content = str_replace(' ', '', file_get_contents($_FILES['winners_save']['tmp_name']));
        if($file_content != ""){
            return $file_content;
        }
        else {
            echo "<h3 class='warning'>Ошибка! Файл пуст!<h3>";
            exit;
        }
    }else{
        echo "<h3 class='warning'>Ошибка! Файл не найден!<h3>";
        exit;
    }
}

function save_data($data, $date, $pdo){
    //  $description - описание приза
    //  $link - адрес изображения
    //  $winner_name - имя победителя
    //  $date - дата розыгрыша

    if($data){
        require_once "simple_html_dom.php";

        $dir_path = "./content/prize_photo/".$date."/";
        $num_comp = 0;
        $winners = array();
        $err = array();

// Парсим загруженный админом файл, получаем ссылку на пост и ссылку на страницу победителя
        $post_person = explode("\r\n", $data);
        foreach($post_person as $item){
            list($prize_page, $winner_page) = explode('--', $item);
// Извлекаем имя победителя
            $winner_page = mb_strtolower($winner_page);
            $html = file_get_html($winner_page);
            $winner_name = $html->find('title', 0);
            if($winner_name){
                $winner_name = strip_tags($winner_name);
            }else{
                $winner_name = "Имя не найдено";
            }

// Извлекаем описание приза
            $prize_page = mb_strtolower($prize_page);
            $html = file_get_html($prize_page);
            if($html) {
                $desc_item = $html->find('div.pi_text');
                if ($desc_item) {
                    $description = $desc_item[0]->innertext;
                } else {
                    $description = "Описание не найдено";
                }

                // Извлекаем и сохраняем фото приза и делаем дуть до изображения
                $photo = $html->find('img', 1);
                if ($photo) {
                    $url = $photo->src;
                    $file_ext = explode("/", $url);
                    $file_ext = explode(".", end($file_ext));
                    $file_ext = end($file_ext);

                    $photo_link = $dir_path . ++$num_comp . "." . $file_ext;

                    file_put_contents($photo_link, file_get_contents($url));
                } else {
                    $photo_link = "./content/not_image.png";
                }
            }else{
                continue;
            }

            $winners[] = array($prize_page, $photo_link, $description, $winner_page, $winner_name, $date);

            $html->clear();
            unset($html);
        }

        $stmt = $pdo->prepare("  INSERT INTO winner
                                  (prize_link, photo_link, description, winner_page, winner_name, comp_date)
                                  VALUES (?,?,?,?,?,?)");

        foreach($winners as $winner){
            $stmt->bindParam(1, $winner[0]);
            $stmt->bindParam(2, $winner[1]);
            $stmt->bindParam(3, $winner[2]);
            $stmt->bindParam(4, $winner[3]);
            $stmt->bindParam(5, $winner[4]);
            $stmt->bindParam(6, $winner[5]);

            $stmt->execute();

            if(!$stmt) {
                $err[] = $winner[0]." Не записан!";
                continue;
            }
        }
        if($err){
            foreach($err as $er) {
                echo <<<HERE
                    {$err}<br>
HERE;
            }
        }else{
            echo "<a href='../cat.php'>Обновить данные</a>";
        }
    }else{
        echo "<h1 class='warning'>Данные не обнаружены</h1>";
        exit;
    }
}

function get_all_auth_user($pdo){
    try{
        $sql = "SELECT data.last_name as last_name, data.first_name as first_name, data.second_name as second_name,
                    data.index_num as index_num, data.country as country, data.city as city, data.state as state,
                    data.address as address, data.address_info as address_info, data.prize_info as prize_info,
                    winner.prize_link as prize_link, winner.winner_page as winner_page, winner.winner_name as
                    winner_name, winner.comp_date as comp_date
            FROM winner
            JOIN data ON winner.id = data.id";
        $result = $pdo->query($sql);
        $records = $result->fetchAll(PDO::FETCH_ASSOC);

        echo <<<HERE
    <table>
        <thead>
            <tr>
                <th class='fst_tab_col'>Дата розыгрыша</th>
                <th class='fst_tab_col'>Ссылка на приз</th>
                <th class='sec_tab_col'>Победитель</th>
                <th class='trd_tab_col'>Данные получателя</th>
                <th class='sec_tab_col'>Подробности к заказу</th>
            </tr>
        </thead>
        <tbody>
            <tr>
HERE;
        foreach ($records as $recipient) {
            $comp_date = $recipient['comp_date'];
            $prize_link = $recipient['prize_link'];
            $winner_name = $recipient['winner_name'];
            $winner_page = $recipient['winner_page'];
            $recipient_name = $recipient['last_name'] . " " . $recipient['first_name'] . " " . $recipient['second_name']."<br>";
            $recipient_country = $recipient['country'];
            $recipient_state = $recipient['state']."<br>";
            $recipient_city = $recipient['city']."<br>";
            $recipient_address = $recipient['address']."<br>";

            $recipient_address_details = $recipient['address_info'] ? "(".$recipient['address_info'].")<br>" : null;
            $recipient_index = $recipient['index_num']? $recipient['index_num']."<br>" : null;
            $recipient_phone = $recipient['phone'] ? $recipient['phone'] : null;
            $prize_details = $recipient['prize_info'] ? $recipient['prize_info'] : null;

            echo <<<HERE
                <td class='fst_tab_col'>$comp_date</td>
                <td class='fst_tab_col'><a href='$prize_link'>$prize_link</a></td>
                <td class='sec_tab_col'><a href='$winner_page'>$winner_name</a></td>
                <td id='rec_data' class='trd_tab_col'><p>
                    $recipient_name
                    $recipient_country
                    $recipient_index
                    $recipient_state
                    $recipient_city
                    $recipient_address
                    $recipient_address_details
                    $recipient_phone
                </p></td>
                <td class='sec_tab_col'>$prize_details</td>
            </tr>
HERE;
        }
        echo <<<HERE
        </tbody>
        </table>
HERE;
    }catch (PDOException $e){
        header("location: 404-cat.php");
    }catch(Exception $ex){
        header("location: 404-cat.php");
    }
}

function get_specific_auth_user($pdo){
    try{
        require_once "connection.php";

        $comp_date = $_POST['comp_date_get'];

        $sql = "SELECT data.last_name as last_name, data.first_name as first_name, data.second_name as second_name,
            data.index_num as index_num, data.country as country, data.city as city, data.state as state,
            data.address as address, data.address_info as address_info, data.prize_info as prize_info,
            winner.prize_link as prize_link, winner.winner_page as winner_page, winner.winner_name as winner_name
            FROM winner
            JOIN data ON winner.id = data.id
            WHERE winner.comp_date = '$comp_date'";
        $result = $pdo->query($sql);
        $records = $result->fetchAll(PDO::FETCH_ASSOC);

        echo <<<HERE
    <table>
        <thead>
            <tr>
                <th>Ссылка на приз</th>
                <th>Победителя</th>
                <th>Данные получателя</th>
                <th>Подробности к заказу</th>
            </tr>
        </thead>
        <tbody>
            <tr>
HERE;
        foreach ($records as $recipient) {
            $prize_link = $recipient['prize_link'];
            $winner_name = $recipient['winner_name'];
            $winner_page = $recipient['winner_page'];
            $recipient_name = $recipient['last_name'] . " " . $recipient['first_name'] . " " . $recipient['second_name']."<br>";
            $recipient_country = $recipient['country']."<br>";
            $recipient_state = $recipient['state']."<br>";
            $recipient_city = $recipient['city']."<br>";
            $recipient_address = $recipient['address']."<br>";

            $recipient_address_details = $recipient['address_info'] ? "(".$recipient['address_info'].")<br>" : null;
            $recipient_index = $recipient['index_num']? $recipient['index_num']."<br>" : null;
            $recipient_phone = $recipient['phone'] ? $recipient['phone'] : null;
            $prize_details = $recipient['prize_info'] ? $recipient['prize_info'] : null;

            echo <<<HERE
                <td><a href='$prize_link'>$prize_link</a></td>
                <td><a href='$winner_page'>$winner_name</a></td>
                <td id='rec_data'>
                    $recipient_name
                    $recipient_country
                    $recipient_state
                    $recipient_city
                    $recipient_address
                    $recipient_address_details
                    $recipient_index
                    $recipient_phone
                </td>
                <td>$prize_details</td>
            </tr>
HERE;
        }
        echo <<<HERE
        </tbody>
        </table>
HERE;
    }catch (PDOException $e){
        header("location: ../404-cat.php");

    }
}

function get_comp_date($pdo){
    try{
        $sql = "SELECT DISTINCT winner.comp_date
                FROM winner";

        $result = $pdo->query($sql);
        $records = $result->fetchAll(PDO::FETCH_NUM);

        foreach($records as $date){
            echo <<<HERE
                <option value="$date[0]">$date[0]</option>
HERE;
        }
    }catch(PDOException $e){
        header("Location: 404-cat.php");
    }catch(Exception $ex){
        header("Location: 404-cat.php");
    }
}