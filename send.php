<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    header("Access-Control-Allow-Origin: *");
    require_once("Send_message.php"); 

    $send   = new SendMessage();
    $data   = json_decode(file_get_contents("php://input"),true);
    $to     = $data['to'];
    $txt    = $data['msg'];

    if(!empty($to)){

        $getto = $send->getUser($to);
        
        if($getto['row'] > 0){

            $kirimke = json_decode($getto['data']['json_skype'],true);
            $d = $send->sendText($kirimke,$txt);
			print_r($d);
        }

    }else{

        echo "hello gunakan method /post";

    }

    // if(!empty(file_get_contents("php://input"))){
        
        

        // $send->sendText($update);
        // $send->control_pesan($update);


    // }



?>