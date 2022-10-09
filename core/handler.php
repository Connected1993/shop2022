<?php
define('CRYPT','g4fFG#3_2$%');
// подключаем файл конфигураций
include_once('../config.php');

// супер глобальные массивы

// $_POST - получаем ответ от POST запроса
// $_GET - получаем ответ от $_GET запроса
// $_COOKIE - получаем куки

// 1) находятся на компьютере пользователя
// 2) можно устанавливать время жизни кук
// 3) не безопасно (могут украсть)
// 4) максимальный размер до 4кб
// 5) при POST или GET запросах отправляется каждый раз 

// $_SESSION
// 1) хранится на сервере (в виде файлика, который хранит идентификацию о каждом пользователе)
// 2) безопасно
// 3) максимальный размер зависит от настроек php.ini (memory_limit)
// 4) идентификатор сессии хранится в куке  

//isset($test) - проверят наличие переменной или ключа у массива
if( isset($_POST['action']) || isset($_GET['action']) )
{
    require_once('db.php');
    // получаем значение ключа action
    $action = $_POST['action'];
    

    // проверям содержимое переменной
    switch($action)
    {
        case 'filters':
            require_once('dbShop.php');
            $params = json_decode($_POST['params'],true);
            $category = (int)$params['c'];
            $size = (int)$params['s'];
            $priceStart = (int)$params['pStart'];
            $priceEnd = (int)($params['pEnd'] == '')? 9999999 : $params['pEnd'];
            $sql = "
                    SELECT prod.*,t.idSize,cat.name,img.url,t.count,s.name FROM products prod 
                    LEFT JOIN category cat ON prod.category = cat.id 
                    LEFT JOIN images img ON prod.id = img.idProduct 
                    LEFT JOIN total t ON prod.id = t.idProduct 
                    LEFT JOIN size s ON s.id = t.idSize
                    WHERE category = :category and idSize = :size and price BETWEEN :priceStart and :priceEnd
            ";
            // создаем подготовленный запрос
            $sth = $dbh->prepare($sql);
            // валидируем данные ( защита от SQL injection)
            // удаляем гадости и заменяем плейсхолдеры на значения из переменных
            // execute - отправляет запрос в базу
            $sth->execute(['category'=>$category,'priceStart'=>$priceStart,'priceEnd'=>$priceEnd,'size'=>$size]);
            // получаем данные с БД 
            $res = $sth->fetchAll(PDO::FETCH_ASSOC);
            
            echo '<pre>';
            var_dump($res);


        break;
        case 'authorization1':
        require_once('dbShop.php');
        
          //echo md5(123);
          $login = $_POST['login'];
          $password = $_POST['pass1'];
          //   запрос на получению пользователя по его логину и паролю
          $sql = " SELECT * FROM users WHERE login='$login' and password='$password' ";
          // SELECT * FROM users WHERE login='admin'#' and password='$password'
          // отправляем запрос!
          $res= $dbh->query($sql)->fetchAll(PDO::FETCH_ASSOC);
          //  выводим ответ 
          echo '<pre>';
          var_dump($res);

        break;

        case 'addProduct':
            // подключаемся к базе магазина shop2022
            require_once('dbShop.php');

            // echo '<pre>';
            // var_dump($_POST);
            // echo '</pre>';

            $name = $_POST['name'];
            $article = $_POST['article'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $category = $_POST['category'];
            
            // запрос на вставку данных в БД
            $sql = "INSERT INTO products SET name='$name', article='$article', price=$price, category='$category',description='$description' ";
            
            $sth = $dbh->query($sql);
            
            // получает id последней вставленной записи
            if ($dbh->lastInsertId())
            {
                echo $dbh->lastInsertId();
            }
            else
            {
                // если товар не добавился
                echo 0;
            }

        break;

        case 'navigation':
            // получаю категорию
            // man,woman,child
            $category = $_POST['page'];

            if (file_exists(PAGES.'/category.php'))
            {
                include_once(PAGES.'/category.php');
            }
           
        break;

        case 'uploadFiles':
            // подключаемся к базе магазина shop2022
            require_once('dbShop.php');

            foreach($_FILES as $file)
            {
                if (file_exists(__DIR__.'/../files'))
                {

                    $path = PROJECT.'/files/'.time().'__'.$file['name'];
                    // перемещаем файл
                    // from - $file['tmp_name']
                    // to - $path
                    $res = move_uploaded_file($file['tmp_name'],$path);
                    // если файл был успешно перемещен
                    if ($res)
                    {
                        // id товара
                        $idProduct = $_POST['idProduct'];
                        // собираем подготовленный запрос
                        $sql = "INSERT INTO files SET idProduct=:id,url=:url,name=:name";
                        $sth = $dbh->prepare($sql);
                        // отпавляем запрос в БД
                        $sth->execute(['id'=>$idProduct,'url'=>$path,'name'=>$file['name']]);
                        // получаем данные с БД 
                        $res = $dbh->lastInsertId();
                        if ($res)
                        {
                            echo $res;
                        }
                        else
                        {
                            echo 0;
                        }
                    }
                    
                }
            }
          
        break;

        case 'search':
                $text = $_POST['search'];
                $sql = "SELECT CONCAT('<li>',name,'</li>') as name FROM `city` WHERE name LIKE :text ORDER BY name ASC LIMIT 0,5";
                // говорим, что запрос будет подготовленный
                $sth = $dbh->prepare($sql);
                // отправляет запрос в БД
                // конкатинируем с % иначе PDO удалит %
                $sth->execute(['text'=>$text.'%']);
                // получаем данные с БД 
                $res = $sth->fetchAll(PDO::FETCH_ASSOC);

                // var_dump($res);

                foreach($res as $arr)
                {
                    // выводим город на каждой итерации!
                    echo $arr['name'];
                    //example -  <li>Moscow</li>
                }

                // $cities = [];
                // // перебираем массив $res с городами 
                // foreach($res as $arr ){
                //     // add new city from $cities
                //     $cities[] = '<li>'.$arr['name'].'</li>';
                // }
                // var_dump($cities);
        break;


        case 'registration':
            
            $login = $_POST['login'];
            $pass1 = $_POST['pass1'];
            $pass2 = $_POST['pass2'];
            $email = $_POST['email'];

            if ($pass1 !== $pass2 )
            {
                die('Введенные пароли не совпадают!');
                // echo 'Введенные пароли не совпадают!';
                // exit;
            } 

            $sql = "INSERT INTO users (login,password,email) VALUES (:l,:p,:e) ";
            // говорим, что запрос будет подготовленный
            $sth = $dbh->prepare($sql);
            // отправляет запрос в БД
            $sth->execute(['l'=>$login,'p'=>crypt($pass1,CRYPT),'e'=>$email]);
            // получаем все данн
            $result = $dbh->lastInsertId();
            if ($result)
            {
                echo $result.'</br>';
                echo 'Вы успешно зарегистрировались!';
            }
        break;
        
        case 'authorization':

            $login = validationStr($_POST['login']);
            $pass1 = crypt(validationStr($_POST['pass1']),CRYPT);

            $sql = " SELECT * FROM `users` WHERE login='$login' AND password = '$pass1' ";
            //echo $sql;
            $sth = $dbh->query($sql);
            
            

            $result = $sth->fetch(PDO::FETCH_ASSOC);
            if ($result)
            {
           
                echo "<h1>Добро пожаловать, {$result['login']} </h1>";
                setcookie('edit','',time()-99999999999,'/');

                setcookie('login',$result['login'],time()+300,'/');

                // добавляем новый ключ в куку
                if ($result['role'] == 1)
                setcookie('edit',true,time()+300,'/');

                var_dump($_COOKIE);
                // setcookie(
                //     string $name,
                //     string $value = "",
                //     int $expires_or_options = 0,
                //     string $path = "",
                //     string $domain = "",
                //     bool $secure = false,
                //     bool $httponly = false
                // ): bool
            }
            else
            {
                die('<h1>Авторизация не удалась!</h1>');
            }

        break;

        default:
            echo " Нет такого действия $action ";    
        break;
    }

}


function validationStr($str)
{
    // замена символов в строке
    return str_replace( ['#','"','\''] ,'',$str);
}

// CRUD - Create Read Update Delete

// Added
// INSERT INTO `users` SET login = 'Admin', password = 123,email='@test.ru'
// INSERT INTO `users` (login,password,email) VALUES ('Admin',12345,'admin@mail.ru')
// INSERT INTO `users` (login,password,email) 
// VALUES 
// ('Dima',465634,'Dima@mail.ru'),
// ('Misha','fg343','Misha@mail.ru'),
// ('Alex','24gh542','Alex@mail.ru'),
// ('Masha','ololo2222','Masha@mail.ru'),
// ('Dasha','f12gdw3453/','Dasha@mail.ru')

// UPDATE `users` SET login = 'NewLogin' WHERE idUsers = 1
// UPDATE `users` SET login = 'NewLogin', password = 111 WHERE idUsers = 1

// SELECT * FROM `users` WHERE idUsers BETWEEN 1 AND 4;
// SELECT * FROM `users` WHERE idUsers>= 1 AND idUsers <=4;

// SELECT * FROM `users` WHERE idUsers IN(1,2,3)
// SELECT Name as 'Имя', District as 'NewField' FROM `city`;

// SELECT * FROM city WHERE name LIKE 'a%' ORDER BY ID DESC;
// SELECT * FROM city WHERE name LIKE 'a%' ORDER BY ID ASC;
// SELECT * FROM city WHERE name LIKE 's%' ORDER BY `city`.`ID` ASC LIMIT 20,40;