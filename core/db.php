<?php

// делаем подключение к БД
// описываем исключение в блоке try catch
try {
	$dbh = new \PDO(
    'mysql:host=localhost;dbname=world',
    'root',
    '',
        [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"]
    );
} catch (PDOException $e) {
    // останавливаем скрипт в случаи ошибки и выводим сообщение 
	die($e->getMessage());
}



