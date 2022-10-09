<?php


if(isset($_COOKIE['login']) && isset($_COOKIE['edit']) && $_COOKIE['edit'] == true )
{
    echo 'Скрытая инфа';
} 
else
    echo 'Привет смертный';