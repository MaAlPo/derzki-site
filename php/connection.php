<?php

try{
    $pdo = new PDO('mysql:dbname=derzki;host=localhost', 'root', '');
    $pdo->query('SET NAMES "UTF-8"');
}catch (PDOException $e){
    $now_date = new DateTime();
    file_put_contents('log', 'Возникла ошибка в файле '.__FILE__.' в строке '
        .__LINE__.PHP_EOL.'Дата возникновения ошибки: '.$now_date->format('d.m.Y H:i:s')
        .PHP_EOL.'Текст ошибки ['.$e->getCode().'] - '.$e->getMessage().PHP_EOL
        .str_repeat('-', 80).PHP_EOL, FILE_APPEND);
    exit;
}
