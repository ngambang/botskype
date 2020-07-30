<?php
require_once("Class_crud.php"); 
class SendMessage extends Database{
    
    function sendText($update,$jawaban = null){
            
            if($jawaban == null){

                $jawaban = $update['from']['name'].' menulis '.$update['text'];

            }

            $conversation = $update['conversation']['id'];
            $user         = $update['from']['id'];
            $token        = $this->cek_token();
            // print_r($token);
            $url_message  = "https://smba.trafficmanager.net/apis/v3/conversations/".$conversation."/activities/".$user;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$url_message);
            curl_setopt($ch, CURLOPT_POST, 1);
            
            $headers = array();
            $headers = ['Authorization: Bearer '.$token];
            
            $params=array(
                'type' =>'message' , 
                'from'=>array(
                    'id'   => $update['from']['id'], 
                    'name' => $update['from']['name']
                    ),
                'conversation'=>array(
                    'id'    => $update['conversation']['id']
                    ),
                'recipient' => array(
                    'id'    => $update['recipient']['id'], 
                    'name'  => $update['recipient']['name'] 
                ),  
                'text'      => $jawaban,
                'replyToId' => $user
            );
            
            $params=json_encode($params);
            curl_setopt($ch, CURLOPT_POSTFIELDS,$params);  //Post Fields
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            
            $res = curl_exec ($ch);
            if(curl_errno($ch)){
                var_dump(curl_error($ch));
            }
            
            curl_close ($ch);

            //simpan terkirim
            $arr_chat     = array("user" => $user,"type"=>"Terkirim","pesan"=>$jawaban);
            $this->simpan_chat($arr_chat);

           $res=json_decode($res,true);
           
           if(!isset($res['id'])){

                //jika error waktu pengiriman chat, token akan diupdate 
                $this->up_token();
                $this->sendText($update,"Ada pembaruan token, boleh dikirim ulang pesannya?");
           }
            
    }

    function ambil_token_baru(){

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,"https://login.microsoftonline.com/botframework.com/oauth2/v2.0/token");
            curl_setopt($ch, CURLOPT_POST, 1);
            
            $msID = "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx";
            $msSc = "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx";
    
            $params ="grant_type=client_credentials&client_id={$msID}";
            $params.="&client_secret={$msSc}";
            $params.="&scope=https://api.botframework.com/.default";
            
            curl_setopt($ch, CURLOPT_POSTFIELDS,$params);  //Post Fields
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $headers = array();
            $headers = ['Content-Type: application/x-www-form-urlencoded'];
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $result = curl_exec ($ch);
            if(curl_errno($ch)){
                var_dump(curl_error($ch));
            }
            
            $result=json_decode($result);



            return $result->access_token;
    }

    function cek_token(){

        $token     = $this->getToken();
        $tokenBaru = '';
        if($token['row'] == 0){

            $tokenBaru = $this->ambil_token_baru();
            $this->simpan_token($tokenBaru,"simpan");
        
        }else{

            $tokenBaru = $token['data']['token'];

        }


        return $tokenBaru;
    }

    function up_token(){
  
       $tokenBaru = $this->ambil_token_baru();
       $this->simpan_token($tokenBaru,"update");
        

    }
 
    function control_pesan($update2){
        
        $user_id      = $update2['from']['id'];
        $user_name    = $update2['from']['name'];
        $conversation = $update2['conversation']['id'];
        $recipient_id = $update2['recipient']['id'];
        $recipient    = $update2['recipient']['name'];
        $dataJson     = json_encode($update2);
        $arr_user     = array("name_skype" => $user_name,"user_id_skype" => $user_id,"json_skype" =>$dataJson );
        $arr_chat     = array("user" => $user_id,"type"=>"Diterima","pesan"=>$update2['text']);
        
        $cek_user     = $this->getUser($user_id);
        if($cek_user['row'] > 0){

            $this->simpan_chat($arr_chat);


        }else{
            // print_r($arr_user);
            // echo "<p>";
            // print_r($arr_chat);
            $this->simpan_user($arr_user);
            $this->simpan_chat($arr_chat);
            
        }


    }


}  

?>