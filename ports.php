
<?php


//25
//2525
//587
//465
$port = $_POST['checkPort'];
$server = $_POST['checkServer'];

$fp = @fsockopen($server,$port,$errno,$errstr,5);


function getIp(){
if (!empty($_SERVER['HTTP_CLIENT_IP'])){
        $ip=$_SERVER['HTTP_CLIENT_IP'];
    }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }else{
        $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
$ip = getIp();

?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>
<body>
<style>
    .inpPort, .inpServer{width:310px; height:50px;}

</style>

<form action="" method="POST">
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
                        <button type="button" class="btn btn-outline-info btn-sm enterIP">Ввести мой IP<?$ip?></button>
                    </div>
                </div>
                <p><?if($fp){ echo 'Порт '.$port.' открыт на сервере: '.$server.' '; fclose($fp); }
//Если неудачное соединение
                    else{
                        echo 'Порт '.$port.' не открыт на сервере: '.$server.' ';
                    } ?></p>
                <div class="mt-5">
                    <button type="submit" class="btn btn-primary btn-lg w-25">CHECK
                </div>
            </div>
        </div>
    </div>


</form>
<!--<script-->
<!--        src="https://code.jquery.com/jquery-3.6.0.js"-->
<!--        integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="-->
<!--        crossorigin="anonymous"></script>-->


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="ports.js"></script>
</body>
</html>