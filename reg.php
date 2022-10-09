<?php
// для того чтобы посмотреть содержимое(тело) POST запроса
// $_POST - массив с данными post
// $_GET - массив с данными get

// echo '<pre>';
// print_r($_POST);
// echo '<pre>';

// если пришел POST запрос, тогда мы его обрабатываем
// isset() - функция которая проверяет существование переменной или ключа в массиве
if ( isset($_POST['login']) )
{
    $login = $_POST['login'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $pass1 = $_POST['pass1'];
    $pass2 = $_POST['pass2'];
    // название файла
    $fName = 'users.json';


    // если пароли совпадают, тогда регистрируем пользователя
    if ($pass1 == $pass2)
    {
        // если файл существует, тогда мы его читаем!
        if ( file_exists($fName) )
        {
            // json_decode($data,true)- преобразуем JSON в ассоциативный массив
            // file_get_contents() - получает содержимое файла!
            $data = json_decode( file_get_contents($fName),true);
        }
        else
        {
            // если файла не существует!
            // тогда мы его создаем
            file_put_contents($fName,'');
        }

        // если такой пользователь не зарегистрирован
        if (!isset($data[$email]) )
        {
            // в массив $data - добавляем нового пользователя с указанными параметрами
            $data[$email] = [
                'login'=>$login,
                'password'=>$pass1,
                'phone'=>$phone
            ];

            // file_put_contents — Пишет данные в файл
            // https://www.php.net/manual/ru/function.file-put-contents.php
            // json_encode() - преобразовать массив или обьект в строку JSON
            file_put_contents($fName, json_encode($data) );
            echo 'Регистрация прошла Успешно! Может начинать =)';
        }
        else
        {
            // если такой пользователь уже есть
            //echo 'Извините пользователь '.$login.' уже существует';
            echo "Извините email $email уже существует!";
        }

    }
    else
    {
        echo 'Пароли не совпадают!';
    }

}




