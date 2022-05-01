<?php
set_time_limit(0);
// $servername = "Localhost";
// $username   = "service_dev_user";
// $password   = "aY2dS7yU7y";
// $dbname     = "service_dev";

$connect = mysqli_connect($servername, $username, $password, $dbname);
mysqli_set_charset($connect , "utf8");
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

$result = mysqli_query($connect, "SELECT * FROM `forma` ");
    for ($arr = []; $row = mysqli_fetch_assoc($result); $arr[] = $row);

foreach ($arr as $key => $value) {
        $siteName = $value['name_site'];
        $protocol = $value['protocol_site'];


    //**if(isset($siteName) && !empty($siteName)){

        if(help($protocol, $siteName ) == (200 <= 399)){

            $status = 0;//"работает";
             $sql =  mysqli_query($connect,"INSERT INTO `log` (`name_site_log`, `date_log`, `status`) VALUES ('".$siteName."', '".time()."', '".$status."' )");
            echo "INSERT INTO `log` (`name_site_log`, `date_log`, `status`) VALUES ('".$siteName."', '".time()."', 'работает' ).<br>";
        } else {
            $status = 1; //"не работает";
$sql =  mysqli_query($connect,"INSERT INTO `log` (`name_site_log`, `date_log`, `status`) VALUES ('".$siteName."', '".time()."', '".$status."' )");

           echo "INSERT INTO `log` (`name_site_log`, `date_log`, `status`) VALUES ('".$siteName."', '".time()."', 'не работает' ).<br>";
        }
    //**}
};

function help($protocol, $url) {

    if($protocol == 2 ){
        $url = "http://".$url;
    }else if($protocol == 3){
        $url = "https://".$url;
    }else if($protocol == 4){
    }
    $ch = curl_init($url);//Инициализирует сеанс cURL

    curl_setopt($ch, CURLOPT_HEADER, true);// we want headers    true для включения заголовков в вывод.
    curl_setopt($ch, CURLOPT_NOBODY, true);// we don't need body   true для исключения тела ответа из вывода. Метод запроса устанавливается в HEAD. Смена этого параметра в false не меняет его обратно в GET.
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//true для возврата результата передачи в качестве строки из curl_exec() вместо прямого вывода в браузер.
    curl_setopt($ch, CURLOPT_TIMEOUT, 1);//Максимально позволенное количество секунд для выполнения cURL-функций.
    $output = curl_exec($ch);//Выполняет запрос cURL

    $httpcode = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    curl_close($ch);//закрываем сеанс

    return $httpcode;

}

