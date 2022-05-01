<?php
require_once 'db_service.php';
//****************************** Port ****************************************

if($_POST['checkPort']){
    if(isset($_POST['checkPort']) && !empty($_POST['checkPort'])){
        $port = $_POST['checkPort'];// Формируем массив для JSON ответа
    }

    if(isset($_POST['checkServer']) && !empty($_POST['checkServer'])){
        $server = $_POST['checkServer'];// Формируем массив для JSON ответа
    }

    $fp = @fsockopen($server, $port,$errno,$errstr,3);
    fclose($fp);

    if($fp) {
        echo json_encode(['port'=> $port, 'server'=> $server, 'status'=>'ok']);
    } else {
        echo json_encode(['port'=> $port, 'server'=> $server, 'status'=>'no']);
    }
};
//*********************************  IP  ******************************
//ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);// ключает вывод ошибок
require_once 'SxGeo.php';

if($_POST['check_ip']){
    if(isset($_POST['check_ip']) && !empty($_POST['check_ip'])){

      $ip = $_POST['check_ip'];// Формируем массив для JSON ответа
       if( filter_var($ip, FILTER_VALIDATE_IP)){
            $sxGeo = new sxGeo('SxGeoCity.dat', SXGEO_BATCH | SXGEO_MEMORY);
            $city = $sxGeo->GetCityFull($ip);
            $country = $city['country']['name_ru'];
            $flagContr = '<img
                            src="https://flagcdn.com/28x21/'.strtolower($city['country']['iso']).'.png"
                            srcset="https://flagcdn.com/56x42/'.strtolower($city['country']['iso']).'.png 2x,https://flagcdn.com/84x63/'.strtolower($city['country']['iso']).'.png 3x"
                            width="28"
                            height="21"
                            alt="South Africa">';
            $town = $city['city']['name_ru'];
            $region = $city['region']['name_ru'];
            $latitude = $city['city']['lat'];
            $longitude = $city['city']['lon'];
            echo json_encode(['servIP'=> $ip, 'status'=>'ok','country'=>$country, 'town'=>$town, 'region'=>$region, 'latitude'=>$latitude, 'longitude'=>$longitude, 'flagContr'=>$flagContr]);
       }else{
            echo json_encode([message=>'Введите коректный адрес', 'status'=>'no']);
        }
    }
};

//************************************ monitoring ************************************

function help() {
    $url = $_POST['saiteIP'];
    $ch = curl_init($url);//Инициализирует сеанс cURL
    curl_setopt($ch, CURLOPT_HEADER, true);    // we want headers    true для включения заголовков в вывод.
    curl_setopt($ch, CURLOPT_NOBODY, true);    // we don't need body   true для исключения тела ответа из вывода. Метод запроса устанавливается в HEAD. Смена этого параметра в false не меняет его обратно в GET.
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//true для возврата результата передачи в качестве строки из curl_exec() вместо прямого вывода в браузер.
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);//Максимально позволенное количество секунд для выполнения cURL-функций.
    $output = curl_exec($ch);//Выполняет запрос cURL
    $httpcode = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    curl_close($ch);//закрываем сеанс

    return $httpcode;
}

$siteName = $_POST['saiteIP'];
if(isset($siteName) && !empty($siteName)){
        if(help() == (200<=399)){
            stream_context_set_default(
                array(
                    'http' => array(
                        'method' => 'HEAD'
                    )
                )
            );
            $headers = get_headers($siteName);

            echo json_encode([message => 'Все оки', 'status' => 'ok', 'siteName'=>$siteName, 'answer'=>implode('<br>', $headers)]);
        }else {
            echo json_encode([message=> 'Введите коректный адрес', 'status'=>'no']);
        }
};
//****************************************************************************
if($_POST['saiteIP']){

    $host = $_POST['saitePing'];

    if(isset($host) && !empty($host)){

        if($host>5) {

            $protocol = $_POST['selectProtocol'];
            $time_request = $_POST['selectTime_request'];
            $mail = $_POST['mail'];
            $mailIHiddenInp = $_POST['mailIHiddenInpName'];
            $telega = $_POST['telega'];
            $telegaIHiddenKey = $_POST['telegaIHiddenIpNameKey'];
            $telegaIHiddenIp = $_POST['telegaIHiddenIpNameIp'];

            if($host > 5 && $protocol !== null && $time_request !== null && $mail == 'mail' && $telega == 'telegram' ) {

             exec("ping -c 4 " . $host, $output, $result);//ping -c 4 для Виндовс

                if ($result == 0) {
                    echo json_encode([message => 'Ping successful!', 'status' => 'ok','output' => implode('<br>', $output) ]);
                } else {
                    echo json_encode([message => 'Ping unsuccessful!', 'status' => 'no']);
                }
        }

            echo json_encode(['host'=> $host, 'time_request' => $time_request, 'mail' => $mail, 'mailIHiddenInp' => $mailIHiddenInp, 'telega' => $telega, 'telegaIHiddenKey'=> $telegaIHiddenKey, 'telegaIHiddenIp'=> $telegaIHiddenIp, 'status' => 'ok', 'data' => date('d.m.Y H:i:s')]);
        } else {
            echo json_encode([message => 'НЕ отправлено ', 'status' => 'no']);
        }
    }
}
   // *****************из db*******
if($_POST['saitePing']){
    $date = time();
    $name_site = mysqli_real_escape_string($connect, $_POST['saitePing']);
    $protocol_site = mysqli_real_escape_string($connect, $_POST['selectProtocol']);
    $time_check = mysqli_real_escape_string($connect, $_POST['selectTime_request']);
    $address_mail = mysqli_real_escape_string($connect, $_POST['mail']);
    $id_telegram = mysqli_real_escape_string($connect, $_POST['telegaIHiddenIpNameIp']);
    $key_telegram = mysqli_real_escape_string($connect, $_POST['telegaIHiddenIpNameKey']);


    $sql = "INSERT INTO forma (`name_site`, `protocol_site`, `time_check`, `address_mail`, `id_telegram`, `key_telegram`, `date_add`) VALUES ('".$name_site."', '".$protocol_site."', '".$time_check."', '".$address_mail."', '".$id_telegram."', '".$key_telegram."', '".$date."' )";
    // echo $sql;

    if(mysqli_query($connect, $sql)){
        echo json_encode(['message' => 'Записи успешно добавлены.', 'status' => 'ok' ]);
    } else{
        echo json_encode(['message' => "ERROR: Не удалось выполнить $sql. ". mysqli_error($connect), 'status' => 'no']);
    }
}