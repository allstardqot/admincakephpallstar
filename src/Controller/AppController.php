<?php

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Controller;

require_once($_SERVER['DOCUMENT_ROOT'] . '/vendor/twilio/vendor/autoload.php');

//App::import('Vendor', 'twilio/vendor/Search/Lucene');
use Twilio\Rest\Client;
use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Core\Configure;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Mailer\Email;
use Cake\I18n\I18n;
use Cake\Utility\Inflector;
use Cake\Mailer\TransportFactory;
use Cake\Log\Log;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */

class AppController extends Controller {

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */

    public function initialize() {
        parent::initialize();
		header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, HEAD');
        header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Engaged-Auth-Token, AuthToken, language');
       
	  
		$session = $this->request->session();
		
		$this->loadComponent('RequestHandler');
		$this->loadComponent('Flash');
		$this->loadComponent('Cookie');
		$this->viewBuilder()->helpers(['General']);
        
		/*
         * Enable the following components for recommended CakePHP security settings.
         * see https://book.cakephp.org/3.0/en/controllers/components/security.html
         */
        //$this->loadComponent('Security');
        //$this->loadComponent('Csrf');
        $this->loadComponent('Auth');
        $this->loadComponent('Upload');
        if (isset($this->request->params['prefix']) && $this->request->params['prefix'] == 'admin') {

            $this->viewBuilder()->layout('admin');
            $this->Auth->config('authenticate', [
				'Form' => [
					'fields' => [
						'username'	=>	'email',
						'password'	=>	'password'
					],
					'scope' => [
						'role_id IN'=>	[1,2],
						'status'	=>	1
					]
				]
			]);
            $this->Auth->config('authError', 'Did you really think you are allowed to see that?');
            $this->Auth->config('loginAction', [
				'prefix'	=>	'admin',
				'controller'=>	'users',
				'action'	=>	'login'
			]);
            $this->Auth->config('loginRedirect', [
				'prefix'	=>	'admin',
				'controller'=>	'users',
				'action'	=>	'dashboard'
			]);
            $this->Auth->config('logoutRedirect', [
				'prefix'	=>	'admin',
				'controller'=>	'users',
				'action'	=>	'login'
			]);
            $this->Auth->config('storage', [
				'className'	=>	'Session',
				'key'		=>	'Auth.Admin'
			]);
        } else {

			

			//$this->viewBuilder()->enableAutoLayout(false);
			//$this->viewBuilder()->setLayout(false);
			//$this->viewBuilder()->layout(false);
			//return $this->redirect(['controller' => 'Home','action' => 'index']);

			$this->Auth->config('storage', [
				'className'	=>	'Session',
				'key'		=>	'Auth.User'
			]);
            $this->Auth->config('authenticate', [
				'Form' => [
					'fields' => [
						'username'	=>	'email',
						'password'	=>	'password'
					],
					'scope'	=>	['role_id' => 2]
				]
			]);
            $this->Auth->config('loginRedirect', [
				'controller'=>	'Home',
				'action'	=>	'index'
			]);
            $this->Auth->config('logoutRedirect', [
				'controller'=>	'Home',
				'action'	=>	'index'
			]);
			$this->Auth->config('loginAction', [
				'controller'=>	'Home',
				'action'	=>	'index'
			]);
        }

        $auth = $this->Auth->user();
        $this->set('auth', $auth);
        if (isset($auth['role_id']) && !empty($auth['role_id']) && $auth['role_id'] == 2) {
            $this->loadModel('Users');
            $user = $this->Users->get($this->Auth->user('id'));
            $this->set('user', $user);
        } else {
            $this->set('user', $auth);
        }
        /*
         * Load helpers
         */
        $this->viewBuilder()->helpers(['Custom']);
        $meta_title = "";
        $meta_keyword = "";
        $meta_desc = "";
        $this->set(compact('meta_title', 'meta_keyword', 'meta_desc'));
		
		$settingData	=	[];
		$controller 	= 	$this->request->params['controller'];
		$action 		= 	$this->request->params['action'];
		$data_row		=	file_get_contents("php://input");
		$decoded    	=	json_decode($data_row, true);
		//$this->log("Web Api ".$this->request->here." = ".$controller."-".print_r($decoded, true), 'debug');

		
		if($controller != 'CricketApi'){
			$settingData	=	$this->settingData();
		}
		$this->set(compact('settingData'));
		
		if( $controller == 'CricketApi' && !in_array($action,['login','logout','loginPassword','signup','socialSignup','socialLogin','verifyOtp','resendOtp','forgotPassword','getMatchList','contestList','contestListAll','contestDetail','playerList','verifyAccountEmail','bannerList','beforJoinContest','playerTeamList','checkUserExistance','getsponsorcode','getwebtoken','stateList','checkstate'])) {
			
			// Temporary add playerTeamList
			$this->loadComponent('General');
			$status		=	false;
			$message	=	NULL;
			$data		=	[];
			$data1		=	(object) array();

			$token = $this->request->getHeader('Authorization');
			//$this->log("Appcontroller token : - ".print_r($token, true), 'debug');
			if(!empty($token[0])){
				$token = $token[0];
				$key = $this->General->encrypt_decrypt('decrypt', $token);
				$keyval1 = explode('###',$key);

				$data_row	=	file_get_contents("php://input");
				$decoded    =	json_decode($data_row, true);
				$reqestedUser = (isset($decoded['user_id'])) ? $decoded['user_id'] : '';
				if(!empty($reqestedUser)){
					$rootUserId = '';
					$secure_key = '';
					if(isset($keyval1[1])){
						$keyval2 = explode('##',$keyval1[1]);
						if( isset($keyval2[0]) && isset($keyval2[1]) ){
							$rootUserId = $keyval2[0];
							$secure_key = $keyval2[1];
						}	
					}
					if ($secure_key != APP_SECURE_KEY || $rootUserId != $reqestedUser ) {
						$response_data	=	array('status'=>false,'tokenexpire'=>1,'message'=>'Please re-login or update your app to latest version.','data'=>$data1);
						echo json_encode(array('response' => $response_data));
						die;
					}
				}
			} else {
				$response_data	=	array('status'=>false,'tokenexpire'=>1,'message'=>'Please re-login or update your app to latest version.','data'=>$data1);
				echo json_encode(array('response' => $response_data));
				die;
			}
		}
		
		// remove leading and trailing whitespace from posted data
		array_walk_recursive($this->request->data, array($this, 'trimItem'));
	}
	

	public function beforeFilter(Event $event)
    {
		
		if (isset($this->request->params['prefix']) && $this->request->params['prefix'] == 'admin') {
			$loggedInUser = $this->Auth->user();
			if(!empty($loggedInUser)){
				$this->_checkPermission();
			}
		}
		
    }

    function ExportExcel($headers = array(), $data_arr = array(),$filename=null){
		
		$header_string = '';
		$delimiter = "\t";
		if(empty($filename)  && $filename ==''){
			$filename = 'DATA_SHEET_'.time().'_' . date("Y-m-d") . ".xls";
		}
			 
		$count = 0;
		header("Content-Disposition: attachment; filename=$filename");
		header("Pragma: no-cache");
		header("Expires: 0");
		if(!empty($headers)){
			foreach($headers as $key => $header_label){
				print $header_label."\t";
			} 
			print "\r\n"; 
		}
		$count = 1; 
		if (!empty($data_arr)) {
			foreach ($data_arr as $key=> $eval_data) {
				if(!empty($eval_data)){
					$row_data = '';
					foreach($eval_data as $key => $value){
						print $value."\t"; 
					}
					print "\r\n"; 
				}
			} 
		} 
		die;
	}
	
	function trimItem(&$item,$key){
		if (is_string($item)){
			$item = trim($item);    
		}
	}
	
	

    protected function calculateAge($dateOfBirth = NULL) {
        $today = date("Y-m-d");
        $diff = date_diff(date_create($dateOfBirth), date_create($today));
        return $diff->format('%y');
    }

    public function getLanguages() {

        $this->loadModel('Languages');
        $languages = $this->Languages->find('list', [
                    'keyField' => 'id',
                    'valueField' => 'name'
                ])->where(['Languages.status' => 1])->toArray();
        $this->set(compact('languages'));
    }

    protected function checkRecaptchaResponse($response) {
        // verifying the response is done through a request to this URL
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        // The API request has three parameters (last one is optional)
        $data = array('secret' => Configure::read('GoogleCaptchaSecretKey') ,
            'response' => $response,
            'remoteip' => $_SERVER['REMOTE_ADDR']);

        // use key 'http' even if you send the request to https://...
        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data),
            ),
        );

        // We could also use curl to send the API request
        $context = stream_context_create($options);
        $json_result = file_get_contents($url, false, $context);
        $result = json_decode($json_result);
        return $result->success;
        if (!empty($response)) {
            return true;
        } else {
            return false;
        }
    }

	function createSlug($string = NULL, $separtor = '-') {
		$string = substr($string, 0, 50);
		$slug = Inflector::slug(strtolower($string), $separtor);
		return $slug;
    }

	public function pushnotification($token=null,$message=null,$title=null){

 	  $url = 'https://fcm.googleapis.com/fcm/send';
		$apiKey = FCM;
		$registrationIds =$token;
		$msgSuccess = true;
		$msg = array
				(
					'body' => $message,
					'title' => $title,
					'color' => '#db2723',
					'icon' => 'https://gintonico.com/content/uploads/2015/03/fontenova.jpg', /* Default Icon */
					'sound' => 'mySound',/* Default sound */
					'click_action'=>'FCM_PLUGIN_ACTIVITY',
					'icon'=>'fcm_push_icon'
				);
		$fields = array
				(
					'registration_ids' => $registrationIds,
					'notification' => $msg,
					//'data'=>array('name'=>'anil','url'=>'app.product'),

				);
		$headers = array
				(
					'Authorization: key=' . $apiKey,
					'Content-Type: application/json'
				);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		$result = curl_exec($ch);
		$result = json_decode($result);
		curl_close($ch);
		return $msgSuccess;
	 }
	
	public function settingData() {
		$this->loadModel('Settings');
		$result	=	$this->Settings->find()->first();
		return $result;
	}
	
	
	public function generateOPT($length) {
		// if(APP_MODE == 'Live'){
			$string		=	'0123456789';
			$strShuffled=	str_shuffle($string);
			$otp		=	substr($strShuffled, 1, $length);
		// } else {
		// 	$otp = 123456;
		// }
		return $otp;
	}
	
	public function createUserReferal($length) {
		$string		=	'0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ9630125478abcdefghijklmnopqrstuvwxyz9876543210';
		$strShuffled=	str_shuffle($string);
		$referCode	=	substr($strShuffled, 1, $length);
		return $referCode;
	}
	
	public function getInviteCode($matchId,$contestId) {
		$this->loadModel('MatchContest');
		$matchContest	=	$this->MatchContest->find()->where(['contest_id'=>$contestId,'SeriesSquad.match_id'=>$matchId])->contain(['SeriesSquad'])->select(['invite_code'])->first();
		return $matchContest;
	}
	
	public function saveTransaction($userId = null, $txnId=null,$status = null,$txnAmount = null, $order_id = null, $extra = null) {
		$this->loadModel('Transactions');
		$entity	=	$this->Transactions->newEntity();
		$entity->user_id		=	$userId;
		$entity->order_id		=	$order_id;
		$entity->txn_amount		=	$txnAmount;
		$entity->currency		=	"INR";
		$entity->txn_date		=	date('Y-m-d H:i:s');
		$entity->local_txn_id	=	$txnId;
		$entity->added_type		=	$status;
		if(!empty($extra)){
			$extra = json_encode($extra);
		}
		$entity->extra		=	$extra;
		$this->Transactions->save($entity);
	}
	
	public function sendNotificationAPNS($userId,$notiType,$ReceiverDeviceTokens,$title='', $message='',$data=array()) {

		$url = "https://fcm.googleapis.com/fcm/send";
		$token = $ReceiverDeviceTokens;
		$serverKey = 'AAAAJSA2D9A:APA91bE9jJDcnNOJrvtkTr6XdSwSFViP-89Cip05JLqrTZMvsBy_IxTKXOa52nQwbbWssr4BNlC8mUHUHsu7WpO1QHR_7zJ6eld-QNU72UWVot8Z7p4K7MJtBFR6Jet3UMaspt3v2kEZ';
		
		$notification = array('title' =>$title , 'text' => $message, 'sound' => 'default', 'badge' => '1');
		$arrayToSend = array('to' => $token, 'notification' => $notification,'priority'=>'high');
		$json = json_encode($arrayToSend);
		$headers = array();
		$headers[] = 'Content-Type: application/json';
		$headers[] = 'Authorization: key='. $serverKey;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);

		curl_setopt($ch, CURLOPT_CUSTOMREQUEST,

		"POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
		curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
		//Send the request
		$response = curl_exec($ch);
		//Close request
		if ($response === FALSE) {
			die('FCM Send Error: ' . curl_error($ch));
		}
		curl_close($ch);
		
		/* if($ReceiverDeviceTokens=='') {
			return true;
		} else {
			error_reporting(0);


			if( $_SERVER['REMOTE_ADDR']=='103.82.80.203' ){
				error_reporting(E_ALL);
				ini_set('display_errors', 1);
			}

			if(empty($data)){
				$mtDate = '';
			}else{
				$mtDate = serialize($data);
			}

			if($notiType!='1'){
				$this->loadModel('Notifications');
				$entity	=	$this->Notifications->newEntity();
				$entity->user_id				=	$userId;
				$entity->nitification_type		=	$notiType;
				$entity->title					=	$title;
				$entity->notification			=	$message;
				$entity->match_data				=	$mtDate;
				$entity->date					=	date('Y-m-d');
				$entity->status					=	'1';
				$entity->is_send				=	'1';
				$this->Notifications->save($entity);
				
			}

			$deviceToken=	$ReceiverDeviceTokens;
			
			//$passphrase	=	'Appinop@123';
			$passphrase	=	'12345678';
			$message	=	$message;
			//$ckpem 		= 	dirname(__FILE__) . '/Certificates2.pem';
			$ckpem 		= 	WWW_ROOT.'iosnoti01.pem';
			$ctx	=	stream_context_create();
			
			stream_context_set_option($ctx, 'ssl', 'local_cert', $ckpem);
			stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);	//pem,file
			
			// Open a connection to the APNS server
			$fp	=	stream_socket_client(
					'ssl://gateway.sandbox.push.apple.com:2195', $err,
					$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

			if(!$fp) {
				//exit("Failed to connect: $err $errstr" . PHP_EOL);
				exit();
			}
			
			// Create the payload body
			$body['aps']	=	array(
				'title'			=>	$title,
				'alert'			=>	$message,
				'matchData'		=>	$data,
				'type'			=>	$notiType,
				'sound'			=>	'default'
			);
			
			// Encode the payload as JSON
			$payload	=	json_encode($body);
			
			// Build the binary notification
			$msg	=	chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
			
			// Send it to the server
			$result	=	fwrite($fp, $msg, strlen($msg));

			
			if(!$result) {
				return false; //echo 'Message not delivered' . PHP_EOL;
			
			} else {
				return true; //echo 'Message successfully delivered' . PHP_EOL;
			}
			
			// Close the connection to the server
			fclose($fp);

			return true;
		} */	
	}

	public function sendNotificationFCM($userId,$notiType,$ReceiverDeviceTokens,$title='', $message='',$data=array()) {
		if($ReceiverDeviceTokens=='') {
			return true;
		} else {
			if(empty($data)){
				$mtDate = '';
			}else{
				$mtDate = serialize($data);
			}
			if($notiType!='1'){
				$this->loadModel('Notifications');
				$entity	=	$this->Notifications->newEntity();
				$entity->user_id				=	$userId;
				$entity->nitification_type		=	$notiType;
				$entity->title					=	$title;
				$entity->notification			=	$message;
				$entity->match_data				=	$mtDate;
				$entity->date					=	date('Y-m-d');
				$entity->status					=	'1';
				$entity->is_send				=	'1';
				$this->Notifications->save($entity);
			}
			//$ReceiverDeviceTokens =	'eGrNbN4bj7k:APA91bFuJ0ZW974gE6OBLltZCdki7g4TVCdb5whfGVm-M_piqoQCOGMj6GKaQrFC5-TrVIHc2-CWsTGlqDzqRhiZTRKMTTYg78TcAyYteYMgt7bZ2FTnSzD-dy8XrJgon2HlgRbSiTRL';
			
			
			$key	=	FCM_KEY;

			$badge_count	=	1;

			$sound_type	= 0;
			if( $notiType == 3 ){
				$sound_type	= 1;
			}
			
			$msg=	array(
				'body'        => trim(strip_tags($message)),
                'title'       => $title,
                'matchData'   => $data,
                'data'   	  => [],
                'type'        => $notiType,
                'sound'       => 'mySound',
                'image'       => '',
				'badge_count' => $badge_count,
				'sound_type'  => $sound_type,
			);

			if(is_array($ReceiverDeviceTokens)){
				$ReceiverDeviceTokens = array_values($ReceiverDeviceTokens);
				$fields	=	array(
					'registration_ids'	=>	$ReceiverDeviceTokens,
					'data'	=>	$msg
				);
			} else {
				$fields	=	array(
					'to'	=>	$ReceiverDeviceTokens,
					'data'	=>	$msg
				);
			}


			/* $fields	=	array(
				'to'	=>	$ReceiverDeviceTokens,
				'data'	=>	$msg
			); */
			
			$headers=	array(
				'Authorization: key=' . $key,
				'Content-Type: application/json'
			);
			
			$ch	=	curl_init();
			curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
			curl_setopt( $ch,CURLOPT_POST, true );
			curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
			curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
			curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ));
			$result	=	curl_exec($ch ); //print_r($result);die();
			curl_close( $ch );
			#echo Result Of FireBase Server
			// echo $result; die();
			//echo 'QWERTY';
			/* if( $_SERVER['REMOTE_ADDR'] == '183.83.42.110' ){
				echo '<pre>';
				print_r($result);die;
			} */

			return true;
		}		
	}

	public function sendNotificationFCMNew($userId,$message='',$title='') {
			
			$this->loadModel("Users");
			if(!empty($userId)){
				$device_id='';
				if($userId != 'all' && $this->Users->get($userId)){
					$userData=$this->Users->get($userId);
					$device_type=!empty($userData->device_type)?$userData->device_type:'';
					$device_id=!empty($userData->device_id)?$userData->device_id:'';
				}elseif($userId == 'all'){
					$query = $this->Users->find('list', ['keyField'=>'id','valueField'=>'device_id'])->where(['device_id !='=>''])->limit(1000);
					$device_id	=	$query->toArray();
				}
				
				//pr($device_id);die;
				if(!empty($device_id)){
					$key	=	FCM_KEY;
					
					$msg= array(
						'body' =>$message,
						'title' =>$title
					);

					if(is_array($device_id)){
						$device_id = array_values($device_id);
						$fields	=	array(
							'registration_ids'	=>	$device_id,
							'notification'	=>	$msg
						);
					} else {
						$fields	=	array(
							'to'	=>	$device_id,
							'notification'	=>	$msg
						);
					}
					//pr($fields);die;
					
					$headers=	array(
						'Authorization: key=' . $key,
						'Content-Type: application/json'
					);
					
					$ch	=	curl_init();
					curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
					curl_setopt( $ch,CURLOPT_POST, true );
					curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
					curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
					curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
					curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ));
					$result	=	curl_exec($ch ); 
					//print_r($result);die;
					curl_close( $ch );
					#echo Result Of FireBase Server
					// echo $result; die();
					//echo 'QWERTY';
					/* if( $_SERVER['REMOTE_ADDR'] == '183.83.42.110' ){
						echo '<pre>';
						print_r($result);die;
					} */
					return true;
			}		
		}
	}

	public function sendNotificationFCMT($userId, $notiType, $ReceiverDeviceTokens,$title,$notification){
        $userId=$userId;
        $notiType='10';
        $ReceiverDeviceTokens=$ReceiverDeviceTokens;
        $title = $title;
        $notification = $notification;

      
        $upload_image= SITE_URL.'webroot/dist/img/lineups.png';

        if ($ReceiverDeviceTokens == '') {
            return true;
        } else {
            if (empty($notification)) {
                $mtDate = '';
            } else {
                $mtDate = serialize($notification);
            }
            if ($notiType != '1') {
                $this->loadModel('Notifications');
                $entity                    = $this->Notifications->newEntity();
                $entity->user_id           = $userId;
                $entity->nitification_type = $notiType;
                $entity->title             = $title;
                $entity->notification      = $notification;
                $entity->match_data        = $mtDate;
                $entity->date              = date('Y-m-d H:i:s');
                $entity->status            = '1';
                $entity->is_send           = '1';
                $this->Notifications->save($entity);
            }
            
            $key            = FCM_KEY;
            $badge_count    = 1;
            $image          = $upload_image;

            $msg         = array(
                'body'   => $notification,
                'title'  => $title,
                'type'   => $notiType,
                'sound'  => 'mySound',
                'badge_count' => $badge_count,
                'image'  => SITE_URL.'webroot/dist/img/lineups.png'
            );

            $fields = array(
                'to'   => $ReceiverDeviceTokens,
                'data' => $msg,
            );

            $headers = array(
                'Authorization: key=' . $key,
                'Content-Type: application/json',
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            $result = curl_exec($ch);
            curl_close($ch);
            return true;
        }
    }
	
	public function sendSmsold($otp = null, $mobileNo = null,$code = null) {
		$api_key = '560C5C13FBBD29';
        $from = 'SJSMTH';
        $template_id= '';
        $sms_text = urlencode("Your OTP is ".$otp." on ".SITE_TITLE." account.");

        $api_url = "http://sms.textmysms.com/app/smsapi/index.php?key=".$api_key."&campaign=0&routeid=26&type=text&contacts=".$mobileNo."&senderid=".$from."&msg=".$sms_text;

        //Submit to server
        $response = file_get_contents( $api_url);
        //echo $response;
	}

	public function sendSmsMsg($sms_text = null, $mobileNo = null,$code = null) {
        
		$sid='ACf70023a6b2f698bf7f20fc9c726f1f5d';
		$token='5ba29ea09472e12bc03cfba67126aa5d';

		$client=new Client($sid,$token);
		$mobileNo="+".$code.$mobileNo;
		$msg='';
		try{
			$client->messages->create($mobileNo,array(
								'from'=>'+18482223270',
								'body'=>$sms_text
							)
			);
		} catch(\Exception $ex){
			$msg=$ex->getMessage();
		}
		return $msg;
	}

	public function sendSms($otp = null, $mobileNo = null,$code = null) {
        //$sms_text = urlencode("Your OTP is ".$otp." on ".SITE_TITLE." account.");
        $sms_text = "Your OTP is ".$otp." on ".SITE_TITLE." account.";
		$sid='ACf70023a6b2f698bf7f20fc9c726f1f5d';
		$token='5ba29ea09472e12bc03cfba67126aa5d';

		$client=new Client($sid,$token);
		$mobileNo="+".$code.$mobileNo;
		$msg='';
		try{
			$client->messages->create($mobileNo,array(
								'from'=>'+18482223270',
								'body'=>$sms_text
							)
			);
		} catch(\Exception $ex){
			$msg=$ex->getMessage();
		}
		return $msg;
	}

	public function sendSms2($mobileNo = null,$code = null) {
		$mobileNo = SMS_NOTIFICATION_NUMBER;

		if (!empty($mobileNo)){
			$api_key 		= '560C5C13FBBD29';
			$from 			= 'SJSMTH';
			$template_id	= '';
			$sms_text 		= urlencode("Hi, there is some request, please check.");
			$api_url 		= "http://sms.textmysms.com/app/smsapi/index.php?key=".$api_key."&campaign=0&routeid=26&type=text&contacts=".$mobileNo."&senderid=".$from."&msg=".$sms_text;

			//Submit to server
			$response = file_get_contents( $api_url);
			//echo $response;die;
		}
		
	}

	public function getUserId() {
		$rootUserId = 0;
		$token = $this->request->getHeader('Authorization');
		
		if(!empty($token[0])) {
			$token = $token[0];
			if (!empty($token)) {
				$key = $this->General->encrypt_decrypt('decrypt', $token);
				if (!empty($key)) {
					$keyval1 = explode('###',$key);
					$rootUserId = '';
					$secure_key = '';
					if(isset($keyval1[1])) {
						$keyval2 = explode('##',$keyval1[1]);
						if( isset($keyval2[0]) && isset($keyval2[1]) ){
							$rootUserId = $keyval2[0];
							$secure_key = $keyval2[1];
						}	
					}
				}
			}
		}
		if(!$rootUserId){
			$status		=	false;
			$message	=	'Invalid Token.';
			$data		=	[];
			$data1		=	(object) array();
			$response_data	=	array('status'=>$status,'message'=>$message,'data'=>$data1);
			echo json_encode(['response'=>$response_data]);
			die;
		}
		return $rootUserId;
	}

	public function getDecryptUserId($user_id_encrypted) {
		$rootUserId = 0;
		$key = $this->General->encrypt_decrypt('decrypt', $user_id_encrypted);
		if (!empty($key)) {
			$rootUserId = $key;
		}
		if(!$rootUserId){
			$status		=	false;
			$message	=	'Invalid User Token.';
			$data		=	[];
			$data1		=	(object) array();
			$response_data	=	array('status'=>$status,'message'=>$message,'data'=>$data1);
			echo json_encode(['response'=>$response_data]);
			die;
		}
		return $rootUserId;
	}
	
	function _checkPermission(){
		
		$controller = $this->request->getParam('controller');
		$action 	= $this->request->getParam('action');
		$loggedInUser = $this->Auth->user();
		$role_id  = (isset($loggedInUser['role_id'])) ? $loggedInUser['role_id'] : 0;
		$subadmin_roles  = (!empty($loggedInUser['subadmin_roles'])) ? explode(',',$loggedInUser['subadmin_roles']) : [];
		
		if ($role_id > 2 && $action !='dashboard') {
			$permission_arr = ['Users'=>1,'Warehouses'=>2,'Tax'=>3,'Payment'=>4];
			$current_check = (isset($permission_arr[$controller])) ? $permission_arr[$controller] : 0;
			if(!in_array($current_check,$subadmin_roles)){
				
				$this->Flash->error('You are not authorize to access that location.');
                return $this->redirect(['controller' => 'Users','action' => 'dashboard']);
			}
		}
	}
	
	function defineConfigConstant(){
		
		$this->loadModel('Settings');
		
		$setting_value = 0;//Cache::read('setting_value', 'long');
        if (!$setting_value) {
            $query = $this->Settings->find('all',array('conditions'=>['Settings.id'=>1]));
			$setting_value = $query->first();
            //Cache::write('setting_value', $setting_value, 'long');
        }
		
		$setting_value = json_decode(json_encode($setting_value),true);
		//pr($setting_value);die;
		if(!empty($setting_value)){
			foreach ($setting_value as $key => $value) {
				$constant = strtoupper($key);
				if(!defined($constant)){
					define($constant,$value);
				}
			}
		}
	}
	
	protected function sendMail($to, $subject, $message, $from) {
		
		$email = new Email('production');
		$email->template('default')
				->from($from)
				->emailFormat('html')
				->to($to)
				->subject($subject)
				->send($message);
		//echo '<pre>';
		//print_r($email);die;
	}

	// protected function sendMail($to, $subject, $message, $from) {
	// 	// /$email = new Email('production');
	// 	$email = new Email();
	// 	//$email->transport('gmail');
	// 	$email->setTemplate('default')
	// 			->from($from)
	// 			->emailFormat('html')
	// 			->to($to)
	// 			->subject($subject)
	// 			->send($message);
	// 	 //pr($email);die;
	// }
	
	function download_file($file, $name, $mime_type=''){
		/*
		 This function takes a path to a file to output ($file),  the filename that the browser will see ($name) and  the MIME type of the file ($mime_type, optional).
		 */
		 
		//Check the file premission
		if(!is_readable($file)) die('File not found or inaccessible!');
		 
		$size = filesize($file);
		$name = rawurldecode($name);
		 
		/* Figure out the MIME type | Check in array */
		$known_mime_types=array(
			"pdf" => "application/pdf",
			"txt" => "text/plain",
			"html" => "text/html",
			"htm" => "text/html",
			"exe" => "application/octet-stream",
			"zip" => "application/zip",
			"doc" => "application/msword",
			"xls" => "application/vnd.ms-excel",
			"ppt" => "application/vnd.ms-powerpoint",
			"gif" => "image/gif",
			"png" => "image/png",
			"jpeg"=> "image/jpg",
			"jpg" =>  "image/jpg",
			"php" => "text/plain"
		);
		
		if($mime_type==''){
			 $file_extension = strtolower(substr(strrchr($file,"."),1));
			 if(array_key_exists($file_extension, $known_mime_types)){
				$mime_type=$known_mime_types[$file_extension];
			 } else {
				$mime_type="application/force-download";
			 };
		};
		 
		//turn off output buffering to decrease cpu usage
		@ob_end_clean(); 
		 
		// required for IE, otherwise Content-Disposition may be ignored
		if(ini_get('zlib.output_compression'))
		  ini_set('zlib.output_compression', 'Off');
		 
		header('Content-Type: ' . $mime_type);
		header('Content-Disposition: attachment; filename="'.$name.'"');
		header("Content-Transfer-Encoding: binary");
		header('Accept-Ranges: bytes');
		 
		/* The three lines below basically make the 
			download non-cacheable */
		header("Cache-control: private");
		header('Pragma: private');
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		 
		// multipart-download and download resuming support
		if(isset($_SERVER['HTTP_RANGE']))
		{
			list($a, $range) = explode("=",$_SERVER['HTTP_RANGE'],2);
			list($range) = explode(",",$range,2);
			list($range, $range_end) = explode("-", $range);
			$range=intval($range);
			if(!$range_end) {
				$range_end=$size-1;
			} else {
				$range_end=intval($range_end);
			}
			/*
			------------------------------------------------------------------------------------------------------
			//This application is developed by www.webinfopedia.com
			//visit www.webinfopedia.com for PHP,Mysql,html5 and Designing tutorials for FREE!!!
			------------------------------------------------------------------------------------------------------
			*/
			$new_length = $range_end-$range+1;
			header("HTTP/1.1 206 Partial Content");
			header("Content-Length: $new_length");
			header("Content-Range: bytes $range-$range_end/$size");
		} else {
			$new_length=$size;
			header("Content-Length: ".$size);
		}
		 
		/* Will output the file itself */
		$chunksize = 1*(1024*1024); //you may want to change this
		$bytes_send = 0;
		if ($file = fopen($file, 'r'))
		{
			if(isset($_SERVER['HTTP_RANGE']))
			fseek($file, $range);
		 
			while(!feof($file) && 
				(!connection_aborted()) && 
				($bytes_send<$new_length)
				  )
			{
				$buffer = fread($file, $chunksize);
				print($buffer); //echo($buffer); // can also possible
				flush();
				$bytes_send += strlen($buffer);
			}
			fclose($file);
		} else
			//If no permissiion
			die('Error - can not open file.');
			//die
		die();
	}

}
