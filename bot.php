<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    header("Access-Control-Allow-Origin: *");
    require_once("Send_message.php"); 

    $send   = new SendMessage();

    // if(!empty(file_get_contents("php://input"))){

        $update = json_decode(file_get_contents("php://input"),true);
		$myfile = fopen("read.txt", "w") or die("Unable to open file!");
		$txt = "John Doe\n";
		fwrite($myfile, json_encode($update));
		fclose($myfile);
        $send->sendText($update);
        // $send->control_pesan($update);


    // }



?>