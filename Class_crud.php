<?php
 
  class Database{

    private $host = "localhost";
    private $user = "root";
    private $pass = "root";
    private $db   = "db_botSkype";
    public $mysql;

    function __construct(){


        $this->mysql = new mysqli($this->host,$this->user,$this->pass,$this->db);


    }

    function getToken(){

        $query    = "select*from token where id_token = '1' limit 1";
        $getQuery =  $this->mysql->query($query);
        // print_r($getQuery->fetch_assoc());
        return array("data"=>$getQuery->fetch_assoc(),"row"=>$getQuery->num_rows);

    }

    function simpan_token($token,$status = "simpan"){

      if($status == "simpan"){

        $query    = "insert into token values (null,'{$token}')";
        $getQuery =  $this->mysql->query($query);

      }else{

        $query    = "update token set token = '{$token}' where id_token = 1";
        $getQuery =  $this->mysql->query($query);

      }

    }

    function simpan_user($data){

      $query    = "insert into user values (null,'{$data['user_id_skype']}','{$data['name_skype']}','{$data['json_skype']}')";
      $getQuery =  $this->mysql->query($query);

    }

    function getUser($id){

      $query    = "select*from user where user_id_skype = '{$id}' limit 1";
      $getQuery =  $this->mysql->query($query);
      // print_r($getQuery->fetch_assoc());
      return array("data"=>$getQuery->fetch_assoc(),"row"=>$getQuery->num_rows);

    }

    function getAll(){

      $query    = "select*from user ";
      $getQuery =  $this->mysql->query($query);
      $darr     = array();
      while($f  = $getQuery->fetch_assoc()){

          array_push($darr,array("user_id_skype"=>$f['user_id_skype'],"name_skype"=>$f['name_skype']));

      }
      // print_r($getQuery->fetch_assoc());
      return array("data"=>$darr,"row"=>$getQuery->num_rows);

    }

    function simpan_chat($data){
      $tgl = date("Y-m-d H:i:s");
      $query    = "insert into chat values (null,'{$data['user']}','{$data['type']}','{$data['pesan']}','{$tgl}')";
      $getQuery =  $this->mysql->query($query);

    }

  }