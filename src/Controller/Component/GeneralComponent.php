<?php
namespace App\Controller;
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Configure;
use Cake\Utility\Inflector;
use Cake\Utility\Text;
use Cake\ORM\TableRegistry;

use APNS;

class GeneralComponent extends Component {

    public $components = array('Auth');

    public  function safe_b64encode($string) {
	
        $data = base64_encode($string);
        $data = str_replace(array('+','/','='),array('-','_',''),$data);
        return $data;
    }
 
	public function safe_b64decode($string) {
        $data = str_replace(array('-','_'),array('+','/'),$string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }
	
	function encrypt($value){
		
		$key = Configure::read('global_config.encrypt_decrypt_key');
		if(!$value){return false;}
        $text = $value;
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $text, MCRYPT_MODE_ECB, $iv);
        return trim($this->safe_b64encode($crypttext));  
	}
	
	function decrypt($value){
		
		$key = Configure::read('global_config.encrypt_decrypt_key');
		if(!$value){return false;}
        $crypttext = $this->safe_b64decode($value); 
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $crypttext, MCRYPT_MODE_ECB, $iv);
        return trim($decrypttext);
    }
    

    function encrypt_decrypt($action, $string) {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key = Configure::read('global_config.encrypt_decrypt_key');
        $secret_iv = Configure::read('global_config.secret_iv');
        // hash
        $key = hash('sha256', $secret_key);
        
        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        if ( $action == 'encrypt' ) {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } else if( $action == 'decrypt' ) {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
        return $output;
    }
    
    function auth() {

        if(ES_mode == 'Live'){
            $fields = array(
                'access_key' => ES_access_key,
                'secret_key' => ES_secret_key
            );	
            $fields_string = '';
            
            foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
            $fields_string=rtrim($fields_string, '&');
             
            $ch = curl_init();
             
            curl_setopt($ch, CURLOPT_URL, LZ_url.'auth/');
            curl_setopt($ch, CURLOPT_POST, true);
             
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            $error_msg = '';
            if (curl_error($ch)) {
                $error_msg = curl_error($ch);
            }
            $response = json_decode($response, true);
            curl_close($ch);
            return $response['token'];
        } else {
            return ES_test_token;
        }

        
    }

    function getData($req_url, $fields){
        $url = ES_url. $req_url. '/?' . http_build_query($fields);
        //echo "URL: $url \n";die;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_ENCODING, '');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        $error_msg = '';
        if (curl_error($ch)) {
			$error_msg = curl_error($ch);
        }
        $response = json_decode($response, true);
        curl_close($ch);
        return $response;
    }


    // Kabbadi functions

    function getKCompetition($access_token){
        $fields = array(
            'token' => $access_token
        );
        $url = 'competitions';
        $response = $this->getData($url, $fields);
        return $response;
    }

    function getKSeriesSquadList($access_token, $cid, $page){
        $fields = array(
            'token' => $access_token
        );

        if($page){
            $fields['paged'] = $page;
        }

        $url = 'competition/'.$cid.'/matches';
        $response = $this->getData($url, $fields);
        return $response;
    }


    function getKSeriesTeamList($access_token, $cid){
        $fields = array(
            'token' => $access_token
        );
        $url = 'competition/'.$cid.'/info';
        $response = $this->getData($url, $fields);
        return $response;
    }

    function getKPlayers($access_token,$page){
        $fields = array(
            'token' => $access_token,
            'per_page' => 300
        );

        if($page){
            $fields['paged'] = $page;
        }

        $url = 'players';
        $response = $this->getData($url, $fields);
        return $response;
    }

    function getKMatchInfo($access_token,$matchId){
        $fields = array(
            'token' => $access_token
        );

        $url = 'matches/'.$matchId.'/info';
        $response = $this->getData($url, $fields);
        return $response;
    }

    function getKMatchStats($access_token,$matchId){
        $fields = array(
            'token' => $access_token
        );

        $url = 'matches/'.$matchId.'/stats';
        $response = $this->getData($url, $fields);
        return $response;
    }


    // Cricket Apis start from here

    function authCricketApi() {

        if(ES_C_mode == 'Live'){
            $fields = array(
                'access_key' => ES_C_access_key,
                'secret_key' => ES_C_secret_key
            );	
            $fields_string = '';
            
            foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
            $fields_string=rtrim($fields_string, '&');
             
            $ch = curl_init();
             
            curl_setopt($ch, CURLOPT_URL, ES_C_url . '/v2/auth/');
            curl_setopt($ch, CURLOPT_POST, true);
             
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            $error_msg = '';
            if (curl_error($ch)) {
                $error_msg = curl_error($ch);
            }
            $response = json_decode($response, true);
           // echo '';
           // print_r($response);die;
            curl_close($ch);
            return $response [response]['token'];
        } else {
            return ES_C_test_token;
        }

        
    }

    function getDataCricketApi($req_url, $fields){
        $url = ES_C_url. $req_url. '/?' . http_build_query($fields);
        //echo "URL: $url \n";die;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_ENCODING, '');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        $error_msg = '';
        if (curl_error($ch)) {
			$error_msg = curl_error($ch);
        }
        $response = json_decode($response, true);
        curl_close($ch);
        return $response;
    }

    function getCrickSeriesList($access_token,$series_status){
        $fields = array(
            'token' => $access_token,
            'per_page' => 300,
            'status' => $series_status
        );

        //status=fixture

        $url = '/v2/competitions';
        $response = $this->getDataCricketApi($url, $fields);
        return $response;
    }


    function getSeriesSquadList($access_token, $cid, $page){
        $fields = array(
            'token' => $access_token,
        );

        if($page){
            $fields['paged'] = $page;
        }

        $url = '/v2/competitions/'.$cid.'/matches/';
        $response = $this->getDataCricketApi($url, $fields);
        return $response;
    }

    function getSeriesMatchList($access_token, $cid){
        $fields = array(
            'token' => $access_token
        );
        $url = '/v2/competitions/'.$cid.'/squads/';
        $response = $this->getDataCricketApi($url, $fields);
        return $response;
    }

    function getMatchPlayerPoints($access_token, $cid, $matchId){
        $fields = array(
            'token' => $access_token
        );
        $url = '/v2/competitions/'.$cid.'/squads/'.$matchId;
        $response = $this->getDataCricketApi($url, $fields);
        return $response;
    }

    function getPlayerStats($access_token,$playerId){
        $fields = array(
            'token' => $access_token
        );

        $url = '/v2/players/'.$playerId.'/stats';
        $response = $this->getDataCricketApi($url, $fields);
        return $response;
    }

    function getMatchScorecard($access_token,$matchId){
        $fields = array(
            'token' => $access_token
        );

        $url = '/v2/matches/'.$matchId.'/scorecard';
        $response = $this->getDataCricketApi($url, $fields);
        return $response;
    }

    function getMatchSquads($access_token,$matchId){
        $fields = array(
            'token' => $access_token
        );

        $url = '/v2/matches/'.$matchId.'/squads';
        $response = $this->getDataCricketApi($url, $fields);
        return $response;
    }

    function getMatchPoints($access_token,$matchId){
        $fields = array(
            'token' => $access_token
        );

        $url = '/v2/matches/'.$matchId.'/point';
        $response = $this->getDataCricketApi($url, $fields);
        return $response;
    }

    function getUpcomingMatchList($access_token, $page){
        $fields = array(
            'token' => $access_token,
            'per_page' => 300,
            'status' => 1,
            'pre_squad' => 'true',
        );

        if($page){
            $fields['paged'] = $page;
        }

        $url = '/v2/matches/';
        $response = $this->getDataCricketApi($url, $fields);
        return $response;
    }

    function getUpcomingMatchInfo($access_token, $match_id){
        $fields = array(
            'token' => $access_token,
        );

        $url = '/v2/matches/'.$match_id.'/info';
        $response = $this->getDataCricketApi($url, $fields);
        return $response;
    }

    // Soccer functions

    function authSoccer() {

        if(ES_S_mode == 'Live'){
            $fields = array(
                'access_key' => ES_S_access_key,
                'secret_key' => ES_S_secret_key
            );	
            $fields_string = '';
            
            foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
            $fields_string=rtrim($fields_string, '&');
             
            $ch = curl_init();
             
            curl_setopt($ch, CURLOPT_URL, ES_B_url.'/v2/auth/');
            curl_setopt($ch, CURLOPT_POST, true);
             
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            $error_msg = '';
            if (curl_error($ch)) {
                $error_msg = curl_error($ch);
            }
            $response = json_decode($response, true);
           
            curl_close($ch);
            return $response['token'];
        } else {
            return ES_S_test_token;
        }

        
    }

    function getDataSoccer($req_url, $fields){
        $url = ES_S_url. $req_url. '/?' . http_build_query($fields);
       // echo "URL: $url \n";die;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_ENCODING, '');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        $error_msg = '';
        if (curl_error($ch)) {
			$error_msg = curl_error($ch);
        }
        $response = json_decode($response, true);
       
        curl_close($ch);
        return $response;
    }

    function getSCompetition($access_token){
        $fields = array(
            'token' => $access_token,
            'status'=>3
        );
        $url = 'competitions';
        $response = $this->getDataSoccer($url, $fields);

        return $response;

    }

    function getSSeriesSquadList($access_token, $cid, $page){
        $fields = array(
            'token' => $access_token
        );

        if($page){
            $fields['paged'] = $page;
        }

        $url = 'competition/'.$cid.'/matches';
        $response = $this->getDataSoccer($url, $fields);
        return $response;
    }

    function getSPreSquadMatch($access_token){
        $fields = array(
            'token' => $access_token,
            'status' => 1,
            'pre_squad' => 'true'
        );
        $url = 'matches';
        $response = $this->getDataSoccer($url, $fields);
        return $response;
    }

    function getSSeriesPlayerList($access_token, $cid){
        $fields = array(
            'token' => $access_token,
            'per_page'=>300
        );
        $url = 'competition/'.$cid.'/squad';
        $response = $this->getDataSoccer($url, $fields);
        return $response;
    }

    function getSMatchPlayerPoints($access_token, $matchId){
        $fields = array(
            'token' => $access_token
        );
        $url = 'matches/'.$matchId.'/fantasysquad';
        $response = $this->getDataSoccer($url, $fields);
        return $response;
    }

    function getSMatchLineup($access_token,$matchId){
        $fields = array(
            'token' => $access_token
        );

        $url = 'matches/'.$matchId.'/info';
        $response = $this->getDataSoccer($url, $fields);
        return $response;
    }

    function getSMatchStats($access_token,$matchId){
        $fields = array(
            'token' => $access_token
        );

        $url = 'matches/'.$matchId.'/stats';
        $response = $this->getDataSoccer($url, $fields);
        return $response;
    }

    function getSMatchFantasyPoint($access_token,$matchId){
        $fields = array(
            'token' => $access_token
        );

        $url = 'matches/'.$matchId.'/fantasy';
        $response = $this->getDataSoccer($url, $fields);
        return $response;
    }

    public function convertDateTime( $date = null, $format = 'Y-m-d H:i:s' ) {

        $IST	=	new \DateTimeZone("Asia/Kolkata");
        $GMT	=	new \DateTimeZone("GMT");
        $time		=	new \DateTime($date, $GMT);
        $time->setTimezone($IST);
        $created	=	$time->format($format);
        return $created;
	}
    
}