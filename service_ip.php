<?php
require_once 'db_service.php';
require_once 'SxGeo.php';
//ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL); вывод ошибок

// $result = mysqli_query($connect, "SELECT * FROM `forma` WHERE `id`= 20");
//         for ($arr = []; $row = mysqli_fetch_assoc($result); $arr[] = $row);
//         print_r($arr);
 //$result = mysqli_query($connect, "DELETE FROM `forma`");

function getIp(){

    if (!empty($_SERVER['HTTP_CLIENT_IP'])){
        $ip=$_SERVER['HTTP_CLIENT_IP'];
    }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }else{
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
$ip = getIp();

$sxGeo = new sxGeo('SxGeoCity.dat', SXGEO_BATCH | SXGEO_MEMORY);
$city = $sxGeo->GetCityFull($ip);
$port = $_POST['checkPort'];
$server = $_POST['checkServer'];
$fp = @fsockopen($server,$port,$errno,$errstr,5);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://api-maps.yandex.ru/2.1/?apikey=ac6d972f-0cc4-4ca9-b1a0-7f68fd24bae5&load=package.full&lang=ru_RU" type="text/javascript"></script>
</head>
<body>
    <div class="container">
        <div class="row">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="service-ip-tab" data-bs-toggle="tab" data-bs-target="#service-ip" type="button" role="tab" aria-controls="service-ip" aria-selected="true">Проверка IP</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="port-tab" data-bs-toggle="tab" data-bs-target="#port" type="button" role="tab" aria-controls="port" aria-selected="false">Проверка портов</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="monitoring-tab" data-bs-toggle="tab" data-bs-target="#monitoring" type="button" role="tab" aria-controls="monitoring" aria-selected="false">Мониторинг сайтов на доступность</button>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
            <!--      **********************          ip              *******************************-->
                    <div class="tab-pane fade show active" id="service-ip" role="tabpanel" aria-labelledby="service-ip-tab">
                        <div style="width:900px; margin:0 auto;">
                            <div style="border:1px solid #E1E4E9; box-shadow:0 0 5px #C8C8C8; margin:20px;padding:40px; ">
                            <h2>Geo IP — узнайте местоположение по IP</h2>
                            <div class="container">
                                <div class="row">
                                    <div class="col-6">

                                        <form id="form_ip" method="POST"  name="servIP">
                                            <div class="formaIp">
                                                <p>IP-адрес или хостнейм</p>
                                                <input type="text" id="input_ip" value="<?=$ip;?>" name="check_ip" data-ip="<?=$ip?>"><br><br>
                                                    <p id="output" name="ipip"></p>
                                                    <button type="button" class="btn btn-primary" id="verify_ip">Проверить</button>
                                                    <button type="button" class="btn btn-primary" id="my_ip">Мой IP</button><br><br>

                                                <p>Сервис позволяет выполнить поиск адреса<br>
                                                    по IPV4 с точностью до города.</p>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-6">
                                        <div class="formaIpOutput">
            <!--                         вывод переменных массива-->
                                            <p id="test"></p>
                                            <p class="ip_address" >IP-адрес: <sapn id="address" name="address"><?=$ip;?></sapn></p>
                                            <p class="ip_country" >Страна: <sapn id="country" name="country"><?= $city['country']['name_ru'];?></sapn>
                                                <span id="imag" name="imag">
                                                    <?=  '<img
                                                        src="https://flagcdn.com/28x21/'.strtolower($city['country']['iso']).'.png"
                                                        srcset="https://flagcdn.com/56x42/'.strtolower($city['country']['iso']).'.png 2x,https://flagcdn.com/84x63/'.strtolower($city['country']['iso']).'.png 3x"
                                                        width="28"
                                                        height="21"
                                                        alt="South Africa">';?>
                                                </span>
                                            </p>
                                            <p class="ip_town" >Город:  <sapn id="city" name="city"><?= $city['city']['name_ru'];?></sapn></p>
                                            <p class="ip_region" >Регион:  <sapn id="region" name="region"><?=  $city['region']['name_ru'];?></sapn></p>
                                            <p class="ip_latitude" >Широта:  <sapn id="lat" name="lat"><?= $city['city']['lat'];?></p></sapn></p>
                                            <p class="ip_longitude" >Долгота:  <sapn id="lon" name="lon"><?= $city['city']['lon'];?></p></sapn></p>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
<!--***********************-->
<!--                            50.7.142.181-->
                            <script type="text/javascript">
                                ymaps.ready(init);
                                var myMap;
                                function init(){
                                     myMap = new  ymaps.Map("map", {
                                        center: [<?= $city['city']['lat']?>, <?=$city['city']['lon']?>],
                                        zoom: 10
                                    });
                                    myMap.behaviors.disable('scrollZoom');
                                }
                            </script>
                            <div id="map" style="width: 900px; height: 400px"></div>
<!--*****************************-->
                        </div>
                </div>
                <!--   ***********************    end ip      *******************************  -->
                <div class="tab-pane fade" id="port" role="tabpanel" aria-labelledby="port-tab">
                    <style>
                        .inpPort, .inpServer{width:310px; height:50px;}
                    </style>
                    <form action="./requests_ip.php" method="POST" name="port" class="port" id="PortCeck">
                        <div class="container">
                            <div style="width:900px; margin:0 auto;">
                                <div style="border:1px solid #E1E4E9; box-shadow:0 0 5px #C8C8C8; margin:20px;padding:40px; ">
                                    <div class="row ">
                                        <h2>Проверка доступных портов</h2>
                                        <div class="col-7">
                                            <p>Port:</p>
                                            <input type="text" name="checkPort" class="inpPort">
                                            <div class="w-btn-port">
                                                <button type="button" class="btn btn-outline-info btn-sm 25565" style="font-size:10px;">25565</button>
                                                <button type="button" class="btn btn-outline-info btn-sm 27015" style="font-size:10px;">27015</button>
                                                <button type="button" class="btn btn-outline-info btn-sm 8621" style="font-size:10px;">8621</button>
                                                <button type="button" class="btn btn-outline-info btn-sm 80" style="font-size:10px;">80</button>
                                                <button type="button" class="btn btn-outline-info btn-sm 7777" style="font-size:10px;">7777</button>
                                                <button type="button" class="btn btn-outline-info btn-sm 27016" style="font-size:10px;">27016</button>
                                                <button type="button" class="btn btn-outline-info btn-sm 8080" style="font-size:10px;">8080</button>
                                            </div>
                                        </div>
                                        <div class="col-5">
                                            <p>Server:</p>
                                            <input type="text" name="checkServer" class="inpServer">
                                            <button type="button" class="btn btn-outline-info btn-sm enterIP" data-ip="<?=$ip?>">Ввести мой IP</button>
                                        </div>
                                    </div>
                                                    <p id="outputPort"></p>
                                    <div class="mt-5">
                                        <button type="submit" class="btn btn-primary btn-lg w-25" id="checkPort">CHECK
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
<!--                    **********************************************-->
                </div>
<!--                *********************     monitoring         ****************************-->
              <!--   minute hour day month dayofweek command -->
                <div class="tab-pane fade" id="monitoring" role="tabpanel" aria-labelledby="monitoring-tab">
                    <div class="conteiner">
                        <div style="width:900px; margin:0 auto;">
                            <div style="border:1px solid #E1E4E9; box-shadow:0 0 5px #C8C8C8; margin:20px;padding:40px; ">
                                <h3 class="mb-5">Проверить доступность сайта</h3>
                                <div class="contain-form mb-5">
                                    <form action="./requests_ip.php" method="POST" id="form_monitoring" name="form_check_monitoring">
                                        <input type="text" style="width:70%;" name="saiteIP" class="monitoring" id="checkMonitoring" maxlength = "50" placeholder="Введите имя сайта.   Пример: http://yandex.ru, https://google.com">
                                        <input class="btn-primary" type="submit" value="MONITORING" id="checkMonitoringInp">
                                        <p id="resultMonitoring" style="font-size:20px; font-weight: bold;"></p>
                                        <div id="output_form_monitoring">
                                            <p><span id="answer"> </span></p>
                                        </div>
                                    </form>
                                    <!-- **********************   end monitoring   **************************-->
                                    <!--     ********************** запись на мониторинг**************************-->
                                    <hr>
                                    <div class="d-none pin">
                                        <h3>Добавьте свой сайт на мониторинг</h3>
                                        <form action="./requests_ip.php" method="POST" id="form_ping" name="form_check_ping">
                                            <div>
                                                <input type="text" style="width:50%;" name="saitePing" class="pingInput" id="checkPing" placeholder="Введите ip сайта. 50.7.142.181">
                                                <div class="mt-3">
                                                    <select id="protocol" name="selectProtocol" style="width:50%;">
                                                        <option selected disabled >Выберите протокол</option>
                                                        <option id="http"  value= '1' name='HTTP'>HTTP</option>
                                                        <option id="https" value ='2' name='HTTPS'>HTTPS</option>
                                                        <option id="ping"  value= '3' name='PING'>PING</option>
                                                    </select>
                                                    <p id="outputResPing1"></p>
                                                    <select id="time_request" name="selectTime_request" style="width:50%;">
                                                        <option selected disabled >Выберите интервал проверки</option>
                                                        <option value = '5' name='5min'>5 мин</option>
                                                        <option value = '10' name='10min'>10 мин</option>
                                                        <option value = '15' name='15min'>15 мин</option>
                                                    </select>
                                                    <p id="outputResPing2"></p>
                                                    <br><br>
                                                    <div style="border:1px solid #E1E4E9; padding:20px;"><h4>Способ отправки</h4>
                                                        <p>
                                                            <label for="mail">Отправлять сообщения на Mail </label>
                                                            <input id="mail" type="checkbox" name="mail"   style="transform:scale(2.0); width:75px;">
                                                        </p><!-- value="mail" -->
                                                        <div class="hiddenDivMail d-none">
                                                            <input id="mailIHiddenInp" type="email" name="mailIHiddenInpName"  placeholder="Введите свою почту: yandex@yan.ru" style="width:50%;"><!-- value="mail" -->
                                                        </div>
                                                        <p>
                                                            <label for="telega">Отправлять сообщения в Telegram</label>
                                                            <input id="telega" type="checkbox" name="telega" value="telegram" style="transform:scale(2.0); width:30px;"title = "инструкция для телеги" >
                                                        </p>
                                                        <div class="hiddenDivTelega d-none">
                                                            <input id="telegaIHiddenKey" type="text"name="telegaIHiddenIpNameKey" placeholder="Введите ключ телеграм бота" style="width:50%;">
                                                            <input id="telegaIHiddenIp"type="text" name="telegaIHiddenIpNameIp" placeholder="Введите IP телеграм бота" style="width:50%;">
                                                        </div>
                                                    </div>
                                                    <p id="checkResult"></p>
                                                </div>
                                            </div>
                                            <p id="outputResPing"></p>
                                            <input class="btn-primary" type="submit" value="Поставить сайт на мониторинг" id="checkPingBtn">
                                        </form>
                                    </div>
                                    <!-- ********************** end запись на мониторинг  **************************-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="./service_ip.js"></script>
    <script src="ports.js"></script>
</body>
</html>
