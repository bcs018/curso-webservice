<?php
namespace src\models;
use \core\Model;

class Jwt extends Model {

    private function base64url_encode( $data ){
        return rtrim( strtr( base64_encode( $data ), '+/', '-_'), '=');
    }
      
    private function base64url_decode( $data ){
        return base64_decode( strtr( $data, '-_', '+/') . str_repeat('=', 3 - ( 3 + strlen( $data )) % 4 ));
    }

    public function create($data){
        // Header
        $header = json_encode(array("typ"=>"JWT","alg"=>"HS256"));

        // Payload 
        $payload = json_encode($data);
        
        // Signature - Transformação para base64 url
        
        // Base do header
        $hbase = $this->base64url_encode($header);
        // Base do payload
        $pbase = $this->base64url_encode($payload);
        
        //GErando assinatura
        $signature = hash_hmac("sha256", $hbase.".".$pbase, JWT_SECRET_KEY, true);
        $bsig = $this->base64url_encode($signature);

        $jwt = $hbase.".".$pbase.".".$bsig;

        return $jwt;
       
    }

    public function validate($jwt){
        $array = array();

        $jwt_splits = explode('.', $jwt);

        if(count($jwt_splits) == 3){
            $signature = hash_hmac("sha256", $jwt_splits[0].".".$jwt_splits[1], JWT_SECRET_KEY, true);

            $bsig = $this->base64url_encode($signature);

            if($bsig == $jwt_splits[2]){
                $array = json_decode($this->base64url_decode($jwt_splits[1]));
            }
        }

        return $array;
    }

}