<?php
    require_once("Send_message.php"); 

    $send   = new SendMessage();
    $user   = $send -> getAll();

    if(isset($_GET['s']) && $_GET['s']=='table'){
        echo "<h5>Data User Skype</h5>";
        echo "<table border='1'><tr><th>nama</th><th>Id</th></tr>";
        foreach ($user['data'] as $key => $value) {
            echo "<tr>";
            echo "<td width='300'>{$value['name_skype']}</td>";
            echo "<td>{$value['user_id_skype']}</td>";
            echo "</tr>";
        }
        echo "</table>";

    }else{

        echo json_encode($user);

    }

?>