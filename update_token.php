<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    header("Access-Control-Allow-Origin: *");
    require_once("Send_message.php"); 
    $send   = new SendMessage();
	$send->up_token();
	