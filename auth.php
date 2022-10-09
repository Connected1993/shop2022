<?php

if (isset($_POST['email']))
{
    $fName = 'users.json';
    // читаем файл с пользователями
    $data = json_decode( file_get_contents($fName),true); 
    
    $email = $_POST['email'];
    $pass1 = $_POST['pass1'];

    if ( isset($data[$email]) && $data[$email]['password'] == $pass1 )
    {
        echo "Вы {$data[$email]['login']}, успешно авторизовались!";
    }
    else
    {
        echo "Что-то пошло не так!";
    }
}