<?php
// устанавливаем время жизни сесси на сервере
// 5 сек
ini_set('session.cookie_lifetime',15);
ini_set('session.gc_maxlifetime',5);
// говорим серверу создать сессию
session_start();
header('Access-Control-Allow-Origin: *');
require_once('config.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="font-awesome/css/font-awesome.css">
    <link rel="stylesheet" href="css/style.css?v=<?=rand(10000,1000000)?>">
    <link rel="stylesheet" href="css/search.css">
    <script defer src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script defer src="js/search.js?v=<?=filemtime(PROJECT.'/js/search.js')?>"></script>
    <script defer src="js/navigation.js?v=<?=filemtime(PROJECT.'/js/navigation.js')?>"></script>
    <script defer src="js/events.js"></script>
    <script defer src="js/filters.js"></script>

    <title>Document</title>
</head>
<body>
    <div class="wrapper">
        <?php
        require(PAGES.'/header.php');
        ?>
        <main>
        <?php
        // если есть get запрос и есть ключ p
        if (isset($_GET['p'])){
            // получаем значение ключа
            $page = $_GET['p'];
            switch($page)
            {
                case 'man':
                case 'woman':
                case 'child':
                    if ( file_exists(PAGES.'/category.php') )
                    {
                        include(PAGES.'/category.php');
                    }
                break;

                case 'admin':
                    if (file_exists(PAGES.'/admin.php'))
                    {
                        include(PAGES.'/admin.php');
                    }
                break;

                default:
                        include(PAGES.'/404.php');
                break;
            }
        }

        ?>
        </main>
        <?php
        require(PAGES.'/footer.php');
        ?>
    </div>
</body>
</html>