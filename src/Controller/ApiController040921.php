<?php
/**
 * Webservices controller.
 *
 * This file will render views from views/pages/
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Controller;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Http\ServerRequest;
use Cake\Routing\Router;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Log\Log;

class ApiController extends AppController
{	
	public function initialize() {
		parent::initialize();
		$this->Auth->allow();
		$this->loadComponent('General');
    }
	
	public function signup() {
		
		header('Content-Type: application/json');
		$status		=	false;
		$message	=	NULL;
		$data		=	array();
		$data1		=	(object) array();
		$data_row	=	file_get_contents("php://input");
		$decoded    =	json_decode($data_row, true);
		$this->loadModel('Users');
		if($decoded) {

			if( empty($decoded['user_name']) ) {
				$message	=	__("Please enter user name.", true);
				$response_data	=	array('status'=>$status,'message'=>$message,'data'=>$data1);
				echo json_encode(['response'=>$response_data]);
				die;
			}

			if( empty($decoded['phone']) ) {
				$message	=	__("Please enter phone number.", true);
				$response_data	=	array('status'=>$status,'message'=>$message,'data'=>$data1);
				echo json_encode(['response'=>$response_data]);
				die;
			}

			$userExist	=	$this->Users->find()->where( [ 'user_name'=>$decoded['user_name'], 'role_id'=>3 ] )->count();

			if( $userExist > 0 ) {
				$message	=	__("Username already exist, please try another one.", true);
				$response_data	=	array('status'=>$status,'message'=>$message,'data'=>$data1);
				echo json_encode(['response'=>$response_data]);
				die;
			}

			$users	=	$this->Users->find()->where( [ 'phone'=>$decoded['phone'], 'role_id'=>3 ] )->first();
			
			if( empty($users) || $users->otp_verified == 0 ) {
				
				if (empty($users)){
					$users					=	$this->Users->newEntity();
				}
				
				$otp					=	$this->generateOPT(6);
				$data['otp']			=	$otp;
				$data['otp_time'] 		=	date('Y-m-d H:i:s');
				$data['user_name']		=	$decoded['user_name'];
				$data['phone']			=	$decoded['phone'];
				$data['password']		=	$decoded['password'];
				$data['referral_code']	=	$this->createUserReferal(6);
				$data['role_id']		=	3;
				$data['status']			=	0;
				$data['created'] 		=	date('Y-m-d H:i:s');
				$data['modified']		=	date('Y-m-d H:i:s');
				
				$users = $this->Users->patchEntity($users,$data);

				if($result = $this->Users->save($users)) {

					$this->sendSms($result->otp,$result->phone);	// send SMS
					$message=	__("Please enter OTP sent to your mobile.", true);

					$random_val = rand();
					$secure_id = $random_val.'###'.$result->id.'##'.APP_SECURE_KEY; 
					$encrypted = $this->General->encrypt_decrypt('encrypt', $secure_id);

					$user_id_encrypted = $this->General->encrypt_decrypt('encrypt', $result->id);

					$data1->token			=	$encrypted;
					$data1->user_id			=	$user_id_encrypted;
					$data1->full_name		=	$result->full_name;
					$data1->phone			=	$result->phone;
					$data1->referral_code	=	$result->referral_code;
					$data1->otp				=	$otp;
					$status	=	true;
					
				} else {
					$error_msg = [];
					foreach( $users->getErrors() as $errors){
						if(is_array($errors)){
							foreach($errors as $error){
								$error_msg[]    =   $error;
							}
						}else{
							$error_msg[]    =   $errors;
						}
					}
					
					$message	=	__(implode("\n \r", $error_msg), true);
				}
				
			} else {
				$message	=	'Phone number already exists.';
			}
			
		} else {
			$message	=	__("You are not authenticated user.", true);	
		}
		$response_data	=	array('status'=>$status,'message'=>$message,'data'=>$data1);
		echo json_encode(['response'=>$response_data]);
		die;
	}

	

	public function login() {
		header('Content-Type: application/json');
		$status		=	false;
		$message	=	NULL;
		$data		=	array();
		$data1		=	(object) array();
		$data_row	=	file_get_contents("php://input");
		$decoded    =	json_decode($data_row, true);
		//$otp_verified = 	true;
		$this->loadModel('Users');
		if($decoded) {
			if( !empty($decoded['phone']) && !empty($decoded['password']) ) {
				$users	=	$this->Users->find()->where(['phone'=>$decoded['phone']])->first(); // ,'status'=>ACTIVE
				
				if(!empty($users)) {
					
					$this->Users->patchEntity($users,$decoded,['validate'=>'loginPassword']);
					if(!$users->getErrors()) {	
						
						if( $users->status ){

							$users['device_id']		=	(isset($decoded['device_token'])) ? $decoded['device_token'] : '';
							$users['device_type'] 	=	(isset($decoded['device_type'])) ? $decoded['device_type'] : '';
							if($result = $this->Users->save($users)) {

								$random_val = rand();
								$secure_id = $random_val.'###'.$result->id.'##'.APP_SECURE_KEY; 
								$encrypted = $this->General->encrypt_decrypt('encrypt', $secure_id);

								$user_id_encrypted = $this->General->encrypt_decrypt('encrypt', $result->id);

								$data1->token			=	$encrypted;
								$data1->user_id			=	$user_id_encrypted;
								$data1->full_name		=	$result->full_name;
								$data1->phone			=	$result->phone;
								$data1->email			=	$result->email;
								$data1->referral_code	=	$result->referral_code;
								$data1->profile_image	=	$result->profile_image;

								

								$status	=	true;
								$message=	__("Login successfully.", true);
							} else {
								$message	=	__("Login Error.", true);
							}

						} else if( $users->otp_verified == 0 ){
							$message	=	__("This phone number does not exist, please signup first.", true);
						}  else {
							$message	=	__("Account disabled.", true);
						}

					}else{
						$error_msg = [];
						foreach( $users->getErrors() as $errors){
							if(is_array($errors)){
								foreach($errors as $error){
									$error_msg[]    =   $error;
								}
							}else{
								$error_msg[]    =   $errors;
							}
						}
						
						$message	=	__(implode("\n \r", $error_msg), true);
					}
				} else {
					$message	=	__("This phone number does not exist,please try again.", true);
				}
			} else {
				$message	=	__("Phone, password are Empty.", true);
			}
		} else {
			$message	=	__("You are not authenticated user.", true);	
		}

		//$data1->otp_verified = $otp_verified;
		$response_data	=	array('status'=>$status,'message'=>$message,'data'=>$data1);
		echo json_encode(['response'=>$response_data]);
		die;
	}

	/* public function loginOtp() {
		header('Content-Type: application/json');
		$status		=	false;
		$message	=	NULL;
		$data		=	array();
		$data1		=	(object) array();
		$data_row	=	file_get_contents("php://input");
		$decoded    =	json_decode($data_row, true);
		$this->loadModel('Users');
		//$otp_verified = 	true;
		if($decoded) {
			if( !empty($decoded['phone']) ) {
				$users	=	$this->Users->find()->where([ 'phone'=>$decoded['phone'] ])->first();
				if(!empty($users)) {

					if( $users->status || $users->otp_verified ==0 ){
						$users['otp']	=	$this->generateOPT(6);
						$users['otp_time'] 	=	date('Y-m-d H:i:s');
						if($result = $this->Users->save($users)) {
							$this->sendSms($result->otp,$result->phone);	// send SMS
							
							$random_val = rand();
							$secure_id = $random_val.'###'.$result->id.'##'.APP_SECURE_KEY; 
							$encrypted = $this->General->encrypt_decrypt('encrypt', $secure_id);

							$user_id_encrypted = $this->General->encrypt_decrypt('encrypt', $result->id);

							$data1->token			=	$encrypted;
							$data1->user_id			=	$user_id_encrypted;
							$data1->full_name		=	$result->full_name;
							$data1->phone			=	$result->phone;
							$data1->email			=	$result->email;
							$data1->referral_code	=	$result->referral_code;
							$data1->profile_image	=	$result->profile_image;

							$status	=	true;
							$message=	__("Enter OTP.", true);
						} else {
							$message	=	__("Login Error.", true);
						}
					} else {
						$message	=	__("Account is deactivated.", true);
					}
				} else {
					$message	=	__("User data not found.", true);
				}
			} else {
				$message	=	__("Phone number Empty.", true);
			}
		} else {
			$message	=	__("You are not authenticated user.", true);	
		}

		//$data1->otp_verified = $otp_verified;
		$response_data	=	array('status'=>$status,'message'=>$message,'data'=>$data1);
		echo json_encode(['response'=>$response_data]);
		die;
	} */
	
	public function verifyOtp() {
		$status		=	false;
		$message	=	NULL;
		$data		=	array();
		$data1		=	(object) array();
		$data_row	=	file_get_contents("php://input");
		$decoded    =	json_decode($data_row, true);
		
		if($decoded) {
			if( !empty($decoded['user_id']) && !empty($decoded['otp']) ) {
				$this->loadModel('Users');
				$userId = $this->getDecryptUserId($decoded['user_id']);
				$users	=	$this->Users->find()->where(['id'=>$userId ])->first();
				if(!empty($users)) {
					$date = date("Y-m-d H:i:s");
					$currentDate = strtotime($date);
					$pastDate = $currentDate-(120);
					$formatDate = date("Y-m-d H:i:s", $pastDate);
					$rslt	=	$this->Users->find()->where(['id'=>$userId,'otp_time >='=>$formatDate])->first();
					if(!empty($rslt)) {
						if($users->otp == $decoded['otp']) {
							
							$users->otp				=	'';
							$users->otp_verified	=	1;
							$users->status			=	ACTIVE;
							$users->device_id		=	(isset($decoded['device_token'])) ? $decoded['device_token'] : '';
							$users->device_type 	=	(isset($decoded['device_type'])) ? $decoded['device_type'] : '';
							
							if($result = $this->Users->save($users)) {
								$random_val = rand();
								$secure_id = $random_val.'###'.$result->id.'##'.APP_SECURE_KEY; 
								$encrypted = $this->General->encrypt_decrypt('encrypt', $secure_id);

								$user_id_encrypted = $this->General->encrypt_decrypt('encrypt', $result->id);

								//$data1->token		=	$encrypted;
								//$data1->user_id		=	$user_id_encrypted;

								$data1->token			=	$encrypted;
								$data1->user_id			=	$user_id_encrypted;
								$data1->full_name		=	$result->full_name;
								$data1->phone			=	$result->phone;
								$data1->referral_code	=	$result->referral_code;
							
								$status	=	true;
								$message=	__("OTP verified successfully.", true);

							} else {
								$status	= 	false;
								$message= 	__("Some error occur.", true);
							}
						} else {
							$status	=	false;
							$message=	__("Wrong OTP.", true);
						}
					}else{
						$status	=	false;
						$message=	__("OTP has been expired.", true);
					}
				} else {
					$message	=	__("User not available.", true);
				}
			} else {
				$message	=	__("OTP is empty.", true);
			}
		} else {
			$message	=	__("You are not authenticated user.", true);
		}
		$response_data	=	array('status'=>$status,'message'=>$message,'data'=>$data1);
		echo json_encode(['response'=>$response_data]);
		die;
	}
	
	public function resendOtp() {
		$status		=	false;
		$message	=	NULL;
		$data		=	array();
		$data1		=	(object) array();
		$data_row	=	file_get_contents("php://input");
		$decoded    =	json_decode($data_row, true);
		$this->loadModel('Users');
		
		if(!empty($decoded)) {
			$phone	=	(!empty($decoded['phone'])) ? $decoded['phone'] : 0;
			$users	=	$this->Users->find()->where(['phone'=>$phone])->first();
			if(!empty($users)) {
				$users->otp		=	$this->generateOPT(6);
				$users['otp_time'] 	=	date('Y-m-d H:i:s');
				if($result = $this->Users->save($users)){
					$this->sendSms($result->otp,$result->phone);	// send SMS

					$random_val = rand();
					$secure_id = $random_val.'###'.$result->id.'##'.APP_SECURE_KEY;
					$encrypted = $this->General->encrypt_decrypt('encrypt', $secure_id);

					$user_id_encrypted = $this->General->encrypt_decrypt('encrypt', $result->id);

					$data1->token		=	$encrypted;
					$data1->user_id		=	$user_id_encrypted;
					
					$status	=	true;
					$message=	__("Please enter OTP sent to your mobile.", true);
				} else {
					$status		= 	false;
					$message 	= 	__("Some error occur.", true);
				}
			} else {
				$message	=	__("Mobile number not registered.");
			}
		} else {
			$message	=	__("Invalid Access.", true);
		}
		$response_data	=	array('status'=>$status,'message'=>$message,'data'=>$data1);
		echo json_encode(['response'=>$response_data]);
		die;
	}
	
	public function forgotPassword() {
		$status		=	false;
		$message	=	NULL;
		$data		=	array();
		$data1		=	(object) array();
		$data_row	=	file_get_contents("php://input");
		$decoded    =	json_decode($data_row, true);
		$this->loadModel('Users');
		
		if(!empty($decoded)) {
			$phone	=	(!empty($decoded['phone'])) ? $decoded['phone'] : 0;
			$users	=	$this->Users->find()->where(['phone'=>$phone])->first();
			if(!empty($users)) {
				$verify_token			=	time().base64_encode( $users->full_name.$users->phone );
				$users->otp				=	$this->generateOPT(6);
				$users->verify_token	=	$verify_token;
				$users->otp_time 		=	date('Y-m-d H:i:s');
				if($result = $this->Users->save($users)){
					$this->sendSms($result->otp,$result->phone);	// send SMS

					$random_val = rand();
					$secure_id = $random_val.'###'.$result->id.'##'.APP_SECURE_KEY;
					$encrypted = $this->General->encrypt_decrypt('encrypt', $secure_id);

					$user_id_encrypted = $this->General->encrypt_decrypt('encrypt', $result->id);

					$data1->token		=	$encrypted;
					$data1->user_id		=	$user_id_encrypted;
					$data1->verify_token=	$verify_token;
					
					$status	=	true;
					$message=	__("Please enter OTP sent to your mobile.", true);
				} else {
					$status		= 	false;
					$message 	= 	__("Some error occur.", true);
				}
			} else {
				$message	=	__("Mobile number not registered.");
			}
		} else {
			$message	=	__("Invalid Access.", true);
		}
		$response_data	=	array('status'=>$status,'message'=>$message,'data'=>$data1);
		echo json_encode(['response'=>$response_data]);
		die;
	}
	
	public function changePasword() {
		header('Content-Type: application/json');
		$status		=	false;
		$message	=	NULL;
		$data		=	array();
		$data1		=	(object) array();
		$data_row	=	file_get_contents("php://input");
		$decoded    =	json_decode($data_row, true);
		$this->loadModel('Users');
		if($decoded) {
			if( !empty($decoded['password']) && !empty($decoded['old_password']) ) {

				$type			=	$decoded['type'];
				$userId = $this->getDecryptUserId($decoded['user_id']);

				if ($type == 1) {  // Change password
					$users	=	$this->Users->find()->where(['id'=>$userId,'Users.status'=>ACTIVE])->first();
				} else { // New password
					$verify_token	=	(isset($decoded['verify_token'])) ? $decoded['verify_token'] : '';
					$users	=	$this->Users->find()->where(['id'=>$userId,'verify_token'=>$verify_token,'status'=>ACTIVE])->first();
				}

				if(!empty($users)) {
					$decoded['user_id'] = $userId;
					if ($type == 1) {  // Change password
						$this->Users->patchEntity($users, $decoded,['validate'=>'changePassword']);
					} else { // New password
						$this->Users->patchEntity($users, $decoded, ['validate' => null]);
					}
					
					if(!$users->getErrors()) {
						$users['password']	=	$decoded['password'];
						if($result = $this->Users->save($users)) {
							$status	=	true;
							if ($type == 1) {
								$message=	__("Password changed successfully.", true);
							} else {
								$message=	__("Password reset successfully.", true);
							}
						}else{
							$message	=	__("Error in password change.", true);
						}
					} else {
						$message	=	__("Current password is not correct.", true);
					}
				} else {
					$message	=	__("Invalid User Id OR You account is deactived.", true);
				}
			} else {
				$message	=	__("Password and old password are Empty.", true);
			}
		} else {
			$message	=	__("You are not authenticated user.", true);	
		}
		$response_data	=	array('status'=>$status,'message'=>$message,'data'=>$data1);
		echo json_encode(['response'=>$response_data]);
		die;
	}
	
	public function logout() {
		$status		=	false;
		$message	=	NULL;
		$data		=	array();
		$data1		=	(object) array();
		$data_row	=	file_get_contents("php://input");
		$decoded    =	json_decode($data_row, true);
		$this->loadModel('Users');
		$userId = $this->getUserId();
		if(!empty($userId)) {
			$users		=	$this->Users->find()->where(['id'=>$userId,'Users.status'=>ACTIVE])->first(); 
			if(!empty($users)) {
				$users->device_id	=	'';
				$users->device_type	=	'';
				if($this->Users->save($users)) {
					$status	=	true;
					$message=	__('You has been logout successfully.',true);
				}
			} else {
				$message	=	__("Invalid User id.", true);
			}
		} else {
			$message	=	__("User id is Empty.", true);
		}
		$response_data	=	array('status'=>$status,'message'=>$message,'data'=>$data1);
		echo json_encode(['response'=>$response_data]);
		die;
	}

	/*
	 * Function to generate unique team name
	 */
	public function createUsername($userName = null,$isEmail=1) {

		if($isEmail){
			$userName	=	explode('@',$userName);
			$userName	=	$userName[0];
		}
		
		$name		=	str_replace(' ','',$userName);
		$nameStr	=	substr($name,0,4);
		
		$string		=	'0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ9876543210';
		$strShuffled=	str_shuffle($string);
		$shuffleCode=	substr($strShuffled, 1, 3);
		$teamName	=	strtoupper($nameStr.$shuffleCode);
		return $teamName;
		exit;
	}

	public function notificationList() {
		$status		=	false;
		$message	=	NULL;
		$data		=	[];
		$data1		=	(object) array();
		$data_row	=	file_get_contents("php://input");
		$decoded    =	json_decode($data_row, true);

		$this->loadModel('Notifications');
		$this->loadModel('Users');

		$userId = $this->getUserId();
		if(!empty($userId)) {

			$users		=	$this->Users->find()->select(['id','referral_code'])->where(['id'=>$userId,'Users.status'=>ACTIVE])->first(); 
			if(!empty($users)) {

				$replace_space = '-*-';
				$referral_code = $users->referral_code;
				$url_referral_code = str_replace(' ',$replace_space,$referral_code);
				
				$invite_url		=	SITE_URL.'invite?refer_id='.$url_referral_code;

				$this->Notifications->find()
				->update()
				->set(['is_read' => 1])
				->where(['user_id' => $userId ])
				->execute();

				$notification	=	$this->Notifications->find()
				->select(['id','type','title','description','created'])
				->where(['OR'=>[['user_id'=>$userId],['user_id IS'=>null]]/* ,'Notifications.status'=>ACTIVE */])
				->order(['Notifications.created'=>'DESC'])
				->toArray();

				if(!empty($notification)) {
					foreach($notification as $record) {
						$record->date				=	date('d F,Y',strtotime($record->created));
						$record->sharing_banner		=	BANNER_PATH.'sharingapp1.png';
						$record->sharing_content	=	'Hi, check out my winning message :'.$record->description.', You can also win, download now '.$invite_url;
						unset($record->id);
						unset($record->created);
					}
				}
				$status		=	true;
				$data1		=	$notification;
				
			} else {
				$message	=	__("Invalid User id.", true);
			}
		} else {
			$message	=	__("Invalid Token.", true);
		}

		$response_data	=	array( 'status'=>$status, 'message'=>$message, 'data'=>$data1 );
		echo json_encode(['response'=>$response_data]);
		die;
	}

	public function getappsetting() {
		header('Content-Type: application/json');
		$status		=	false;
		$message	=	NULL;
		$data		=	array();
		$data1		=	(object) array();
		$data_row	=	file_get_contents("php://input");
		$decoded    =	json_decode($data_row, true);
		$userId = $this->getUserId();

		$data1->min_required_version_code	=	1;

		/* if ($_SERVER['REMOTE_ADDR'] == '183.83.45.226' ) {
			echo $userId;die; 
		} */

		if (  in_array($userId,[0]) ) {
			$data1->version_code	=	5;
		} else {
			$data1->version_code	=	APK_CURRENT_VERSION_CODE;
		}
		
		$data1->apk_url			=	SITE_URL.DOWNLOAD_APK_NAME;
		$data1->update_type		=	1; //1 
		$data1->update_text		=	'<ul><li>Add new feature.</li><li>Fixed some bugs.</li></ul>';

		//$data1	=	$types;
		$status	=	true;
		$message=	__("Success.", true);
		
		$response_data	=	array('status'=>$status,'message'=>$message,'data'=>$data1);
		echo json_encode(['response'=>$response_data]);
		die;
	}

	public function getusersetting() {

		header('Content-Type: application/json');
		$status		=	false;
		$message	=	NULL;
		$data		=	array();
		$data1		=	(object) array();
		$data_row	=	file_get_contents("php://input");
		$decoded    =	json_decode($data_row, true);
		$userId = $this->getUserId();

		if($userId){
			$this->loadModel('Users');
			$users	=	$this->Users->find()->where(['id'=>$userId ])->first();
			if(!empty($users)) {

				if( isset( $decoded['app_version'] )  ) {
					$users->app_version	=	$decoded['app_version'];
					if( $this->Users->Save($users) ){

					}
				}


				$device_id		=	(isset($decoded['device_token'])) ? $decoded['device_token'] : '';
				$device_type 	=	(isset($decoded['device_type'])) ? $decoded['device_type'] : '';

				$is_logout = 0;
				if ( $users->status == 0) {
					$is_logout = 1;
				}

				if ( $device_id != $users->device_id ) {
					$is_logout = 1;
				}
				$data1->is_logout	=	$is_logout;


				$user_id_encrypted 			= 	$this->General->encrypt_decrypt('encrypt', $users->id);
				$data1->user_id				=	$user_id_encrypted;
				$data1->full_name			=	$users->full_name;
				$data1->phone				=	$users->phone;
				
				$data1->email				=	$users->email;
				$data1->referral_code		=	$users->referral_code;
				$data1->profile_image		=	U_IMAGE_PATH.$users->profile_image;
				$data1->app_version			=	$users->app_version;
				$data1->winning_wallet		=	$users->winning_wallet;
				$data1->deposit_wallet		=	$users->deposit_wallet;
				$data1->bonus_wallet		=	$users->bonus_wallet;
				
				$this->loadModel('Notifications');
				$unread_notification = $this->Notifications->find()
				->select(['id'])
				->where([ 'user_id' => $userId, 'is_read' => 0 ])
				->count();
				$data1->unread_notification	=	$unread_notification;

				$data1->profile_status	=	0;
				if ( !empty($users->full_name) ) {
					$data1->profile_status	=	1;
				}

				$status	=	true;
				$message=	__("Success.", true);

			} else {
				$message	=	__("User not available.", true);
			}
		} else {
			$message	=	__("Invalid token.", true);
		}

		$response_data	=	array('status'=>$status,'message'=>$message,'data'=>$data1);
		echo json_encode(['response'=>$response_data]);
		die;
	}

	public function updateusersetting() {
		header('Content-Type: application/json');
		$status		=	false;
		$message	=	NULL;
		$data		=	array();
		$data1		=	(object) array();
		$data_row	=	file_get_contents("php://input");
		$decoded    =	json_decode($data_row, true);

		if(!empty($decoded)) {
			if( isset( $decoded['app_version'] )  ) {
				$this->loadModel('Users');
				$userId = $this->getUserId();
				$user	=	$this->Users->find()->where(['Users.status'=>ACTIVE,'id'=>$userId])->first();
				if(!empty($user)) {
					$user->app_version	=	$decoded['app_version'];
					if($user = $this->Users->Save($user)){
						$status	=	true;
						$message	=	__('User setting updated successfully.');
					} else {
						$message	=	__('User setting not updated, Please try again.',true);
					}
				} else {
					$message	=	__('Invalid user id.',true);
				}
			} else {
				$message	=	__('Please fill all fields.',true);
			}
		} else {
			$message	=	__("You are not authenticated user.", true);
		}
		
		$response_data	=	array('status'=>$status,'message'=>$message,'data'=>$data1);
		echo json_encode(['response'=>$response_data]);
		die;
	}

	public function uploadUserImage()
    {
        $status     = false;
        $message    = null;
        $data       = [];
        $data1      = (object) array();
		$decoded	=	$this->request->getData();
        $this->loadModel('Users');
        
        if(!empty($decoded)) {
			
			if( !empty($decoded['profile_image2']) ) {
				$this->loadModel('Users');
				$userId = $this->getUserId();
				$user	=	$this->Users->find()->select(['id','profile_image'])->where(['Users.status'=>ACTIVE,'id'=>$userId])->first();
				if(!empty($user)) {

					if( !empty($this->request->getData('profile_image2') )) {
						$file		=	$this->request->getData('profile_image2');
						if(!empty($file['name'])){
							$fileArr	=	explode('.',$file['name']);
							$ext		=	end($fileArr);
							$fileName	=	time().$userId.'.'.$ext;
							$filePath	=	$filePath	=	U_IMAGE_ROOT_PATH. $fileName;
							move_uploaded_file($file['tmp_name'],$filePath);
							if(!empty( $user->profile_image )){
								unlink( U_IMAGE_ROOT_PATH.$user->profile_image );
							}
							$user->profile_image = $fileName;
						}
					}

					if($user = $this->Users->Save($user)) {
						$status	=	true;
						$data1->image 	=  U_IMAGE_PATH.$user->profile_image;
						$message		=	__('Profile image updated successfully.');
					} else {
						$message	=	__('There are some error, Please try again.',true);
					}
				} else {
					$message	=	__('Invalid user id OR Account deactivated.',true);
				}
			} else {
				$message	=	__('Please fill all fields.',true);
			}
		} else {
			$message	=	__("You are not authenticated user.", true);
		}
		
        $response_data = array('status' => $status,'tokenexpire'=>0, 'message' => $message, 'data' => $data1);
        echo json_encode(array('response' => $response_data));
        die;
    }

	public function bannerlist() {
		$status		=	false;
		$message	=	NULL;
		$data		=	[];
		$data1		=	(object) array();
		$data_row	=	file_get_contents("php://input");
		$decoded    =	json_decode($data_row, true);
		$this->loadModel('Banners');

		if(!empty($decoded)) {
			$banners = $this->Banners->find()->where(['status'=>'1'])->order(['sequence'=>'ASC'])->toArray();
			if(!empty($banners)){
				foreach ($banners as $key => $value) {
					$value->image = BANNER_PATH.$value->image;
				}
			}
			$status	=	true;
			$data1	=	$banners;
		} else {
			$message	=	__("You are not authenticated user.", true);
		}
		$response_data	=	array('status'=>$status,'message'=>$message,'data'=>$data1);
		echo json_encode(['response'=>$response_data]);
		die;
	}

	public function homeupcoming() {
		$status		=	false;
		$message	=	'';
		$data		=	[];
		$data1		=	(object) array();
		$data_row	=	file_get_contents("php://input");
		$decoded    =	json_decode($data_row, true);
		$this->loadModel('Users');
		$this->loadModel('Companies');
		$this->loadModel('UserAccounts');
		$this->loadModel('CompanyAccounts');

		$user_wallet = 0;
		$current_referral_amount 	= 0;
		$last_month_referral_amount = 0;

		if(!empty($decoded)) {

			$type = ( isset( $decoded['type'] ) ) ? $decoded['type'] : 1;

			$userId = $this->getUserId();
			if(!empty($userId)) {

				$user_info = $this->Users->find()
				->select(['id','deposit_wallet','full_name','phone'])
				->where(['id'=>$userId])
				->first();

				
				if ( !empty($user_info) ){
					$user_wallet = $user_info->deposit_wallet;
				}

				//$referral_count				=	$this->General->getReferralCount( $userId );
				$current_referral_amount		=	$this->General->getCurrentMonthAmount( $userId );
				$last_month_referral_amount		=	$this->General->getlastMonthAmount( $userId );

				$query = $this->UserAccounts->find('list', ['keyField'=>'company_id','valueField'=>'status']);
				$query->select(['company_id','status']);
				$query->where([ 'user_id'=>$userId, 'status != '=> 2 ]);
				$accounts	=	$query->group(['company_id'])->toArray();

				$query = $this->CompanyAccounts->find();
				$query->select(['id','company_id','username','password']);
				$query->where([ 'CompanyAccounts.type' => 'Demo' ]);
				$demoaccounts	=	$query->order(['RAND()'])->toArray(); // ->group(['company_id'])

				/* if ( $_SERVER['REMOTE_ADDR'] == '183.83.45.203' ) {
					pr($demoaccounts);die;
				} */

				$demoaccountslist = [];
				if (!empty($demoaccounts)) {
					foreach($demoaccounts AS $key => $value) {
						if (!isset($demoaccountslist[$value->company_id])) {
							$demoaccountslist[$value->company_id] = [ 'username' => $value->username, 'password' => $value->password ];
						}
					}
				}

				
				$user_accounts_id = [];
				$user_accounts_other_values = [];
				$conditions = ['status'=>'1','is_deleted' => 0];
				if ( $type == 2 ) {
					
					$query = $this->UserAccounts->find();
					$query->select(['id','company_id','user_name','phone','status']);
					$query->where([ 'user_id'=>$userId, 'status'=> 1 ]);
					$usersaccounts	=	$query->order(['company_id'])->toArray();

					if ( !empty( $usersaccounts ) ) {
						
						$index_company_id = 0;
						foreach($usersaccounts AS $ua){

							if($index_company_id!=$ua->company_id){
								$index = 0;
								$index_company_id = $ua->company_id;
							}

							$user_accounts_other_values[$ua->company_id][$index]['user_account_id'] = $ua->id;
							$user_accounts_other_values[$ua->company_id][$index]['username'] = $ua->user_name;
							$user_accounts_other_values[$ua->company_id][$index]['phone'] = $ua->phone;
							$user_accounts_other_values[$ua->company_id][$index]['company_id'] = $ua->company_id;

							$user_accounts_id[] = $ua->company_id;

							$index++;
						}
					}

					if (!empty($user_accounts_id)){
						//$conditions[] = ['id IN '=>$user_accounts_id];
					} else {
						//$conditions[] = ['id'=>0];
					}
					
				}

				if ( $_SERVER['REMOTE_ADDR'] == '183.83.45.198' ) {
					//echo '<pre>';
					//print_r($user_accounts_other_values);die;
				}

				$query = $this->Companies->find();
				$exchange = $query
				->select(['id','name','image','home_image','url','left_text','right_text','bottom_text'])
				->where($conditions) // 
				->toArray();
				
				if( !empty($exchange) ){
					foreach( $exchange AS $key => $value ){
						
						if(isset($accounts[$value->id])){
							$value->status = $accounts[$value->id];
						} else {
							$value->status = 9;
						}

						$value->user_accounts = [];
						if( isset( $user_accounts_other_values[$value->id] ) ) {
							$user_accounts_array = $user_accounts_other_values[$value->id];

							
  
							if(!empty($user_accounts_array)){
								foreach($user_accounts_array AS $key => $user_accounts_val){
									$user_accounts_array[$key]['company_name'] = $value->name;
								}
							}
							/* if( $_SERVER['REMOTE_ADDR'] == '183.83.45.201' ) {
								echo '<pre>';
								print_r($user_accounts_array);die;
							} */
							//$value->user_accounts = $user_accounts_other_values[$value->id];
							$value->user_accounts = $user_accounts_array;
						}


						//Set username
						$value->username = '';

						//Set phone
						$value->phone = '';

						if ( !empty($user_info) ){
							$value->username = $user_info->full_name;
							$value->phone = $user_info->phone;
						}

						$value->image 		= BANNER_PATH.$value->image;
						$value->home_image 	= BANNER_PATH.$value->home_image;

						$value->demo_id = '';
						$value->demo_pass = '';
						if ( isset($demoaccountslist[$value->id]) ) {
							$value->demo_id = (isset( $demoaccountslist[$value->id]['username'] )) ? $demoaccountslist[$value->id]['username'] : '';
							$value->demo_pass = (isset( $demoaccountslist[$value->id]['password'] )) ? $demoaccountslist[$value->id]['password'] : '';
						}
						
					}
				} else {
					$message	=	__("Please create exchange account on my exchange page.", true);	
				}

				$data1	=	$exchange;
				$status	=	true;	
			} else {
				$message	=	__("User id is Empty.", true);
			}

		} else {
			$message	=	__("You are not authenticated user.", true);
		}

		$response_data	=	array('status'=>$status, 'message'=>$message, 'data'=>$data1,'user_wallet'=>$user_wallet, 'current_referral_amount' => $current_referral_amount, 'last_month_referral_amount'=>$last_month_referral_amount);
		echo json_encode(array('response' => $response_data));
		die;
	}

	public function userprofile() {
		header('Content-Type: application/json');
		$status		=	false;
		$message	=	NULL;
		$data		=	array();
		$data1		=	(object) array();
		$data_row	=	file_get_contents("php://input");
		$decoded    =	json_decode($data_row, true);
		$this->loadModel('Users');
		$userId = $this->getUserId();
		if(!empty($userId)) {
			$users		=	$this->Users->find()
			->select(['id','full_name','phone','email','address','city','state','pincode','winning_wallet','deposit_wallet','bonus_wallet'])
			->where(['id'=>$userId,'Users.status'=>1])
			->first(); 
			if(!empty($users)) {

				$user_id_encrypted 			= 	$this->General->encrypt_decrypt('encrypt', $users->id);
				$result['user_id']			=	$user_id_encrypted;
				$result['full_name']		=	$users->full_name;
				$result['phone']			=	$users->phone;
				$result['address']			=	$users->address;
				$result['city']				=	$users->city;
				$result['state']			=	$users->state;
				$result['pincode']			=	$users->pincode;
				$result['dob']				=	$users->dob;
				$result['winning_wallet']	=	$users->winning_wallet;
				$result['deposit_wallet']	=	$users->deposit_wallet;
				$result['bonus_wallet']		=	$users->bonus_wallet;
				
				$data1	=	$result;
				$status	=	true;
				$message=	__("Success.", true);
			} else {
				$message	=	__("Invalid User id.", true);
			}
		} else {
			$message	=	__("User id is Empty.", true);
		}
		
		$response_data	=	array('status'=>$status,'message'=>$message,'data'=>$data1);
		echo json_encode(['response'=>$response_data]);
		die;
	}

	public function editProfile() {

		$status		=	false;
		$message	=	NULL;
		$data		=	[];
		$data1		=	(object) array();
		$data_row	=	file_get_contents("php://input");
		$decoded    =	json_decode($data_row, true);

		if(!empty($decoded)) {
			
			if( !empty($decoded['full_name']) ) {
				$this->loadModel('Users');
				$userId = $this->getUserId();
				$user	=	$this->Users->find()->select(['id'])->where(['Users.status'=>ACTIVE,'id'=>$userId])->first();
				if(!empty($user)) {
					$user->full_name		=	$decoded['full_name'];
					$user->email			=	(isset($decoded['email'])) ? $decoded['email'] : '';
					$user->state			=	$decoded['state'];
					$user->pincode			=	$decoded['pincode'];
					$user->dob				=	$decoded['dob'];
					$user->phone			=	$decoded['phone'];
					if($user = $this->Users->Save($user)) {
						$status	=	true;
						$message	=	__('Profile updated successfully.');
					} else {
						$message	=	__('Profile not updated, Please try again.',true);
					}
				} else {
					$message	=	__('Invalid user id or Account deactivated.',true);
				}
			} else {
				$message	=	__('Please fill all fields.',true);
			}
		} else {
			$message	=	__("You are not authenticated user.", true);
		}

		$response_data	=	array('status'=>$status,'message'=>$message,'data'=>$data1);
		echo json_encode(['response'=>$response_data]);
		die;
	}

	public function userwallet() {
		header('Content-Type: application/json');
		$status		=	false;
		$message	=	NULL;
		$data		=	array();
		$data1		=	(object) array();
		$data_row	=	file_get_contents("php://input");
		$decoded    =	json_decode($data_row, true);
		$this->loadModel('Users');
		$userId = $this->getUserId();
		if(!empty($userId)) {
			$users		=	$this->Users->find()
			->select(['id','full_name','phone','email','winning_wallet','deposit_wallet','bonus_wallet'])
			->where(['id'=>$userId,'Users.status'=>1])
			->first(); 
			if(!empty($users)) {

				$user_id_encrypted 			= 	$this->General->encrypt_decrypt('encrypt', $users->id);
				$result['user_id']			=	$user_id_encrypted;
				$result['winning_wallet']	=	$users->winning_wallet;
				$result['deposit_wallet']	=	$users->deposit_wallet;
				$result['bonus_wallet']		=	$users->bonus_wallet;
				
				$data1	=	$result;
				$status	=	true;
				$message=	__("Success.", true);
			} else {
				$message	=	__("Invalid User id.", true);
			}
		} else {
			$message	=	__("User id is Empty.", true);
		}
		
		$response_data	=	array('status'=>$status,'message'=>$message,'data'=>$data1);
		echo json_encode(['response'=>$response_data]);
		die;
	}

	public function transactions() {
		$status		=	false;
		$message	=	NULL;
		$data		=	[];
		$data1		=	(object) array();
		$data_row	=	file_get_contents("php://input");
		$decoded    =	json_decode($data_row, true);
		
		$this->loadModel('Transactions');
		$userId = $this->getUserId();
		if(!empty($userId)) {

			$tran_type = (isset($decoded['type'])) ? $decoded['type'] : '';

			$query 	=	$this->Transactions->find('list', ['keyField'=>'id','valueField'=>'txn_date_formatted']);
			$dateformat = $query->func()->date_format([
				'txn_date' => 'identifier',
				"'%Y-%m-%d'" => 'literal'
			]);
			
			$dateArr	=	$query
			->select(['id','txn_date_formatted'=> $dateformat])
			->where(['user_id'=>$userId])
			->order(['txn_date'=>'DESC'])
			->group(['txn_date_formatted'])
			->toArray();

			$txnsArr	=	[];
			if(!empty($dateArr)) {
				$flag	=	0;
				foreach($dateArr as $dates) {

					$startDate	=	$dates.' 00:00:00';
					$endDate	=	$dates.' 23:59:59';
					$conditions = 	['user_id'=>$userId,'txn_date >='=>$startDate,'txn_date <='=>$endDate];

					if (isset($tran_type)) {
						if ( $tran_type == 0 || $tran_type == '' ){
							$start_week	=	date('Y-m-d 00:00:00');
							$end_week	=	date('Y-m-d 23:59:59');
							$conditions[] = [ 'created >= '=> $start_week,'created <= '=> $end_week ]; 
						} else if ($tran_type == 999) {
							$conditions[] = ['added_type NOT IN'=>[1,2,3,4,5]]; 
						} else {
							$conditions[] = ['added_type'=>$tran_type]; 
						}
					}

					$txnHistory	=	$this->Transactions->find()
									->select(['Transactions.id','added_type','txn_amount','txn_id','txn_date','status','extra'])
									->where($conditions)
									->order(['txn_date'=>'DESC'])
									->toArray();
									
					if(!empty($txnHistory)) {
						foreach($txnHistory as $key => $txns) {
							//$txnsArr[$flag]['date']			=	$dates;
							$status		=	Configure::read('global_config.withdrawl_type.'.$txns->status);
							$added_type		=	Configure::read('global_config.TRANSACTION_TYPE.'.$txns->added_type);

							$details = '';
							if (!empty($txns->extra)) {
								$extra_array = json_decode($txns->extra,true);
								if ( !empty( $extra_array['details'] ) ) {
									$details = $extra_array['details'].' ';
								}
								if ( !empty( $extra_array['cancel_remarks'] ) ) {
									$details .= ' '.$extra_array['cancel_remarks'];
								}
							}

							$txnsArr[$flag]	=	[
								'amount'			=>	($txns->txn_type == 'Db') ? '- '.$txns->txn_amount : '+ '.$txns->txn_amount,
								'txn_type'			=>	$added_type,
								'transaction_id'	=>	$txns->txn_id,
								'status'			=>	$status,
								'details'			=>	$details,
								'txn_date'			=>	$this->General->convertDateTime($txns->txn_date,'Y-m-d H:i:s') //date('d F,h:i:s a',strtotime($txns->txn_date))
							];
						}
						$flag++;
					}
				}
			}
			$data1	=	$txnsArr;
			$status	=	true;
			$message=	__('Transactions History',true);
		} else {
			$message	=	__("You are not authenticated user.", true);
		}

		$response_data	=	array('status'=>$status,'message'=>$message,'data'=>$data1);
		echo json_encode(array('response' => $response_data));
		die;
	}

	public function getreferalcode() {
		$status		=	false;
		$message	=	NULL;
		$data		=	[];
		$data1		=	(object) array();
		$data_row	=	file_get_contents("php://input");
		$decoded    =	json_decode($data_row, true);
		
		//Check referred by
		$this->loadModel('Invites');
		$referedBySponsor = '';
		$referedBySide = '';
		$ip = $_SERVER['REMOTE_ADDR'];

		$invite 	= $this->Invites->newEntity();
		$invite->ip = $ip;
		//$this->Invites->save($invite);

		$refered	=	$this->Invites->find()->select(['id','refer_id','side'])->where(['Invites.ip'=>$ip])->first();
		if(!empty($refered)){
			$status		=	true;
			$referedBySponsor = $refered->refer_id;
			$message	=	__("", true);
		} else {
			$message	=	__("", true);
		}

		$response_data	=	array('status'=>$status,'tokenexpire'=>0,'message'=>$message,'data'=>$referedBySponsor);
		echo json_encode(array('response' => $response_data));
		die;
	}

}
