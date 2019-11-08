<?php
try {
//        находим дату последнего розыгрыша
    $last_comp_date = find_last_comp_date($pdo);
//        дата для запроса
    if ($last_comp_date) {
        $date_query = date_format($last_comp_date, "Y-m-d");
//        прибавляем к ней 6 дней(1 про запас)
        $end_comp_date = $last_comp_date->add(new DateInterval("P6D"));
        $now_date = new DateTime();

        if ($end_comp_date >= $now_date) {

            $sql = "SELECT * FROM winner
                WHERE winner.comp_date = '$date_query'";
            $result = $pdo->query($sql);
            if ($result) {
                $records = $result->fetchAll(PDO::FETCH_ASSOC);
                echo <<<HERE
                    <table>
                        <thead>
                        <tr>
                            <th>Фото</th>
                            <th>Описание</th>
                            <th>Победитель</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
HERE;
                foreach ($records as $winner) {
                    echo <<<HERE
                        <td><img src='{$winner['photo_link']}' alt='prize_photo'></img></td>
                        <td>{$winner['description']}</td>
                        <td><a href='{$winner['winner_page']}'>{$winner['winner_name']}</a></td>
                    </tr>
HERE;
                }
                echo <<<HERE
                        </tbody>
                    </table>
HERE;
            }else{
                throw new PDOException;
            }
        }
    }else{
        $msg = "Не удалось вычислить дату последнего конкурса";
        throw new MyException($msg);
    }
}catch(MyException $e){

}catch (PDOException $ee){

}
?>