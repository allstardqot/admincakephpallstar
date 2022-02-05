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
use Cake\Network\Email\Email;

class ApiController extends AppController
{	
	public function initialize() {
		parent::initialize();
		$this->Auth->allow();
		$this->loadComponent('General');
		$this->loadModel('PlayerTeams');
		$this->loadModel('Drafts');

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
		$default_winnnipPoint=5000;
		$uCountry_code=$uPhone="";
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

			if( empty($decoded['country_code']) ) {
				$message	=	__("Please enter Country Code.", true);
				$response_data	=	array('status'=>$status,'message'=>$message,'data'=>$data1);
				echo json_encode(['response'=>$response_data]);
				die;
			}

			$users = $this->Users->find()->where( [ 'phone'=>$decoded['phone'], 'role_id'=>3,'delete_status !='=>1 ] )->first();
			
			if( empty($users) || $users->otp_verified == 0 ) {

				$users = $this->Users->find()->where( [ 'user_name'=> $decoded['user_name'], 'role_id'=>3 ] )->first();

				if( empty($users) || $users->otp_verified == 0 ) {
					
					if (empty($users)){
						$users					=	$this->Users->newEntity();
					}
					
					$otp					=	$this->generateOPT(6);
					$data['otp']			=	$otp;
					$data['otp_time'] 		=	date('Y-m-d H:i:s');
					$data['user_name']		=	$decoded['user_name'];
					$data['country_code']	=	(isset($decoded['country_code'])) ? $decoded['country_code'] : '';
					$data['phone']			=	$decoded['phone'];
					$data['password']		=	$decoded['password'];
					$data['referral_code']	=	$this->createUserReferal(6);
					$data['role_id']		=	3;
					$data['winning_wallet']		=	$default_winnnipPoint;
					$data['status']			=	0;
					$data['approve_status']			=	0;
					$data['created'] 		=	date('Y-m-d H:i:s');
					$data['modified']		=	date('Y-m-d H:i:s');
					
					$users = $this->Users->patchEntity($users,$data);
	
					if($result = $this->Users->save($users)) {

						$this->loadModel('Transactions');
							$record	=	$this->Transactions->find()
								->where(['user_id'=>$users->id,'added_type'=>MOBILE_VERIFY])
								->first();
							if(!empty($record->id)){
								$transData	=	$this->Transactions->get($record->id);
							}else{
								$transData	=	$this->Transactions->newEntity();
							}
							$transData['created']		=	date('Y-m-d H:i:s');
							$transData['txn_date']		=	date('Y-m-d H:i:s');
							$transData['txn_amount']		=	$default_winnnipPoint;
							$transData['user_id']		=	$users->id;
							$transData['local_txn_id']		=	date('dmY').time();
							$transData['added_type']		=	MOBILE_VERIFY;
							$transData['amount_status']		=	Add;
							if($this->Transactions->save($transData)){

							}
						$response=$this->sendSms($result->otp,$result->phone,$decoded['country_code']);	// send SMS
						$uCountry_code=$decoded['country_code'];
						$uPhone=$result->phone;
						$userName=$decoded['user_name'];
						if(!empty($response)){
							$message=	__("Please enter OTP sent to your mobile.", true);
		
							$random_val = rand();
							$secure_id = $random_val.'###'.$result->id.'##'.APP_SECURE_KEY; 
							$encrypted = $this->General->encrypt_decrypt('encrypt', $secure_id);
		
							$user_id_encrypted = $this->General->encrypt_decrypt('encrypt', $result->id);
		
							$data1->token			=	$encrypted;
							$data1->user_id			=	$user_id_encrypted;
							$data1->full_name		=	$result->full_name;
							$data1->user_name		=	$result->user_name;

							if(!empty($result->profile_image)) {
								$data1->profile_image	=	U_IMAGE_PATH.$result->profile_image;
							} else {
								$data1->profile_image	=	'';
							}

							$data1->country_code	=	$result->country_code;
							$data1->phone			=	$result->phone;
							$data1->referral_code	=	$result->referral_code;
							$data1->country			=	$result->country;
							$data1->state			=	$result->state;
							$data1->city			=	$result->city;
							$data1->otp				=	$otp;
							$status	=	true;
						}else{
							$message=	__("Please enter OTP sent to your mobile.", true);
		
							$random_val = rand();
							$secure_id = $random_val.'###'.$result->id.'##'.APP_SECURE_KEY; 
							$encrypted = $this->General->encrypt_decrypt('encrypt', $secure_id);
		
							$user_id_encrypted = $this->General->encrypt_decrypt('encrypt', $result->id);
		
							$data1->token			=	$encrypted;
							$data1->user_id			=	$user_id_encrypted;
							$data1->full_name		=	$result->full_name;
							$data1->user_name		=	$result->user_name;

							if(!empty($result->profile_image)) {
								$data1->profile_image	=	U_IMAGE_PATH.$result->profile_image;
							} else {
								$data1->profile_image	=	'';
							}

							$data1->country_code	=	$result->country_code;
							$data1->phone			=	$result->phone;
							$data1->referral_code	=	$result->referral_code;
							$data1->country			=	$result->country;
							$data1->state			=	$result->state;
							$data1->city			=	$result->city;
							$data1->otp				=	$otp;
							$status	=	true;
						}
						
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
					$message	=	'Username already exist, please try another one.';
				}
				
			} else {
				$message	=	'Phone number already exists.';
			}
			
		} else {
			$message	=	__("You are not authenticated user.", true);	
		}
		if($status && !empty($uCountry_code) && !empty($uPhone) && !empty($userName)){
			$msg="Your account with ".$userName." had be successfully registered on lfgdraft. Please wait while we approve your account";
			$this->sendSmsMsg($msg,$result->phone,$uCountry_code);	// send SMS
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
		
		file_put_contents("logg.php", $data_row,FILE_APPEND);
		$decoded    =	json_decode($data_row, true);
		
		$this->loadModel('Users');
		if($decoded) {
			if( !empty($decoded['country_code']) && !empty($decoded['phone']) && !empty($decoded['password']) ) {
				$users	=	$this->Users->find()->where(['country_code'=>$decoded['country_code'],     'phone'=>$decoded['phone']])->first(); // ,'status'=>ACTIVE
				
				if(!empty($users)) {
					
					$this->Users->patchEntity($users,$decoded,['validate'=>'loginPassword']);
					if(!$users->getErrors()) {	
						if( $users->approve_status ){
							if( $users->status ){
								if(!empty($decoded['device_token']) && !empty($decoded['device_type'])){
									$users['device_id']		=	(isset($decoded['device_token'])) ? $decoded['device_token'] : '';
									$users['device_type'] 	=	(isset($decoded['device_type'])) ? $decoded['device_type'] : '';
								}
								if($result = $this->Users->save($users)) {

									$random_val = rand();
									$secure_id = $random_val.'###'.$result->id.'##'.APP_SECURE_KEY; 
									$encrypted = $this->General->encrypt_decrypt('encrypt', $secure_id);

									$user_id_encrypted = $this->General->encrypt_decrypt('encrypt', $result->id);

									$data1->token			=	$encrypted;
									$data1->user_id			=	$user_id_encrypted;
									$data1->full_name		=	$result->full_name;
									$data1->user_name		=	$result->user_name;
									$data1->winning_wallet 		=	$result->winning_wallet;

									if(!empty($result->profile_image)) {
										$data1->profile_image	=	U_IMAGE_PATH.$result->profile_image;
									} else {
										$data1->profile_image	=	'';
									}
									
									$data1->country_code	=	$result->country_code;
									$data1->phone			=	$result->phone;
									$data1->email			=	$result->email;
									$data1->referral_code	=	$result->referral_code;
									$data1->country			=	$result->country;
									$data1->state			=	$result->state;
									$data1->city			=	$result->city;

									

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
							$message	=	__("Your Account pending for approvel.", true);

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

								$data1->token			=	$encrypted;
								$data1->user_id			=	$user_id_encrypted;
								$data1->full_name		=	$result->full_name;
								$data1->country_code	=	$result->country_code;
								$data1->phone			=	$result->phone;
								$data1->referral_code	=	$result->referral_code;
								$data1->winning_wallet 		=	$result->winning_wallet;

							
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
			$country_code	=	(!empty($decoded['country_code'])) ? $decoded['country_code'] : 0;
			$phone	=	(!empty($decoded['phone'])) ? $decoded['phone'] : 0;
			$users	=	$this->Users->find()->where(['phone'=>$phone])->first();
			if(!empty($users)) {
				$users->otp		=	$this->generateOPT(6);
				$users['otp_time'] 	=	date('Y-m-d H:i:s');
				if($result = $this->Users->save($users)){
					$response=$this->sendSms($result->otp,$result->phone,$result->country_code);	// send SMS
					if(!empty($response)){
						
						$random_val = rand();
						$secure_id = $random_val.'###'.$result->id.'##'.APP_SECURE_KEY;
						$encrypted = $this->General->encrypt_decrypt('encrypt', $secure_id);

						$user_id_encrypted = $this->General->encrypt_decrypt('encrypt', $result->id);

						$data1->token		=	$encrypted;
						$data1->user_id		=	$user_id_encrypted;
						$data1->winning_wallet 		=	$result->winning_wallet;

						
						$status	=	true;
						$message=	__("Please enter OTP sent to your mobile.", true);
					}else{
						$random_val = rand();
						$secure_id = $random_val.'###'.$result->id.'##'.APP_SECURE_KEY;
						$encrypted = $this->General->encrypt_decrypt('encrypt', $secure_id);

						$user_id_encrypted = $this->General->encrypt_decrypt('encrypt', $result->id);

						$data1->token		=	$encrypted;
						$data1->user_id		=	$user_id_encrypted;
						$data1->winning_wallet 		=	$result->winning_wallet;
						

						$status	=	true;
						$message=	__("Please enter OTP sent to your mobile.", true);
					}
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
			
			$country_code	=	(!empty($decoded['country_code'])) ? $decoded['country_code'] : 0;
			$phone	=	(!empty($decoded['phone'])) ? $decoded['phone'] : 0;
			$users	=	$this->Users->find()->where(['phone'=>$phone])->first();
			if(!empty($users)) {
				$verify_token			=	time().base64_encode( $users->full_name.$users->phone );
				$users->otp				=	$this->generateOPT(6);
				$users->verify_token	=	$verify_token;
				$users->otp_time 		=	date('Y-m-d H:i:s');
				if($result = $this->Users->save($users)){
					$response=$this->sendSms($result->otp,$result->phone,$result->country_code);	// send SMS
					if(!empty($response)){
						$random_val = rand();
						$secure_id = $random_val.'###'.$result->id.'##'.APP_SECURE_KEY;
						$encrypted = $this->General->encrypt_decrypt('encrypt', $secure_id);

						$user_id_encrypted = $this->General->encrypt_decrypt('encrypt', $result->id);

						$data1->token		=	$encrypted;
						$data1->user_id		=	$user_id_encrypted;
						$data1->verify_token=	$verify_token;
						$data1->winning_wallet 		=	$result->winning_wallet;
						
						$status	=	true;
						$message=	__("Please enter OTP sent to your mobile.", true);
					}else{
						$random_val = rand();
						$secure_id = $random_val.'###'.$result->id.'##'.APP_SECURE_KEY;
						$encrypted = $this->General->encrypt_decrypt('encrypt', $secure_id);

						$user_id_encrypted = $this->General->encrypt_decrypt('encrypt', $result->id);

						$data1->token		=	$encrypted;
						$data1->user_id		=	$user_id_encrypted;
						$data1->verify_token=	$verify_token;
						$data1->winning_wallet 		=	$result->winning_wallet;
						
						$status	=	true;
						$message=	__("Please enter OTP sent to your mobile.", true);
					}
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
		date_default_timezone_set("Asia/Bangkok");

		
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

		if (  in_array($userId,[0]) ) {
			$data1->version_code	=	5;
		} else {
			$data1->version_code	=	APK_CURRENT_VERSION_CODE;
		}
		
		$data1->apk_url			=	SITE_URL.DOWNLOAD_APK_NAME;
		$data1->update_type		=	1; //1 
		$data1->update_text		=	'<ul><li>Add new feature.</li><li>Fixed some bugs.</li></ul>';

		
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
				$data1->country_code		=	$users->country_code;
				$data1->phone				=	$users->phone;
				$data1->email				=	$users->email;
				$data1->referral_code		=	$users->referral_code;

				if(!empty($users->profile_image)) {
					$data1->profile_image		=	U_IMAGE_PATH.$users->profile_image;
				} else {
					$data1->profile_image		=	'';
				}
				
				$data1->app_version			=	$users->app_version;
				$data1->winning_wallet		=	$users->winning_wallet;
				$data1->deposit_wallet		=	$users->deposit_wallet;
				$data1->bonus_wallet		=	$users->bonus_wallet;
				$data1->country				=	$result->country;
				$data1->state				=	$result->state;
				$data1->city				=	$result->city;
				
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

	public function uploadUserImage() {
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
							if(!empty( $user->profile_image)){
								if(unlink( U_IMAGE_ROOT_PATH.$user->profile_image )){

								}
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


	public function homeactive() {
		date_default_timezone_set('US/Eastern');
		
		$status		=	false;
		$message	=	'';
		$data		=	[];
		$data1		=	(object) array();
		$data_row	=	file_get_contents("php://input");
		$decoded    =	json_decode($data_row, true);

		$this->loadModel('DraftContests');
		$this->loadModel('PlayerTeams');
		$this->loadModel('ApiPlayers');

		
		$userId = $this->getUserId();
		if(!empty($userId)) {

			$draft_info = $this->DraftContests->find()
			->select(['DraftContests.id','Drafts.id','Drafts.name','Drafts.week','Drafts.season','Drafts.seasontype','Drafts.startdate','Drafts.starttime','Drafts.enddate','Drafts.endtime','Drafts.draft_data','Contest.category_id','Contest.contest_name','Contest.id','Contest.winning_amount','Contest.contest_size','Contest.contest_type','Contest.entry_fee'])
			->where(['Drafts.status'=>1])
			->contain(['Drafts','Contest'=>['Category'=>['fields'=>['category_name','image']]]])
			->toArray();
			
			$draft_array = [];
			if( !empty($draft_info) ) {
				foreach( $draft_info AS $key => $value ){
					$already_draft=0;
					$queryp=$this->PlayerTeams->find();
					
					$contest_count=$queryp
						->where(['contest_id'=>$value->contest->id,'draft_id'=>$value->draft->id])
						->count();
						if($value->contest->contest_size<=$contest_count){
							continue;
							$already_draft=1;
						}
					if(!empty($value->draft->draft_data)){
						$draft_ar=json_decode($value->draft->draft_data);
						$palyeridGet=[];
						foreach($draft_ar as $fkey=>$fvalue){
							foreach($fvalue->team_member as $key=>$lvalue){
								$palyeridGet[]=$lvalue;
							}
						}
					}
					if(!empty($value->draft->week)){
						$players_date = $this->ApiPlayers->find()
						->select(['max_date'=>'MAX(GameDate)', 'min_date'=>'MIN(GameDate)'])
						->where(['Week'=> $value->draft->week,'Season'=>$value->draft->season,'SeasonType'=>$value->draft->seasontype, 'PlayerID IN'=> $palyeridGet] )
						->first();
						if(date('Y-m-d H:i:s') > $players_date['min_date']){
						
							continue;
							$already_draft=1;
						}
					}
					$playtem_info=$this->PlayerTeams->find()
						->where(['user_id'=>$userId,'contest_id'=>$value->contest->id,'draft_id'=>$value->draft->id])
						->first();
				
					if(!empty($playtem_info)){
						continue;
						$already_draft=1;
					}

					$sdayname=date('D',strtotime($value->draft->startdate.' '.$value->draft->starttime));
					$sdaydate= date('d',strtotime($value->draft->startdate.' '.$value->draft->starttime));
					$sdaymonth=date('M',strtotime($value->draft->startdate.' '.$value->draft->starttime));
					$syear=date('Y',strtotime($value->draft->startdate.' '.$value->draft->starttime));
					$startDate=$sdayname." ".$sdaymonth." ".$sdaydate." ".$syear;
					
					$edayname=date('D',strtotime($value->draft->enddate.' '.$value->draft->endtime));
					$edaydate= date('d',strtotime($value->draft->enddate.' '.$value->draft->endtime));
					$edaymonth=date('M',strtotime($value->draft->enddate.' '.$value->draft->endtime));
					$eyear=date('Y',strtotime($value->draft->enddate.' '.$value->draft->endtime));
					$endtDate=$edayname." ".$edaymonth." ".$edaydate." ".$eyear;
					$draft_array[] = [
						'draft_contest_id'=>$value->id,
						'contest_id'=>$value->contest->id,
						'winning_points'=>$value->contest->winning_amount,
						'contest_size'=>$value->contest->contest_size,
						'contest_type'=>$value->contest->contest_type,
						'entry_points'=>$value->contest->entry_fee,
						'draft_id'=>$value->draft->id,
						'draft_name'=>$value->draft->name,
						'already_draft'=>$already_draft,
						'week'=>$value->draft->week,
						'start_date_time'=>$startDate,
						'end_date_time'=>$endtDate,
						
						'category_name'=>$value->category['category_name'],
						'image'=>SITE_URL."uploads/category_image/".$value->category['image'],

					];
				}
			}

			$data1	=	$draft_array;
			$status	=	true;	
		} else {
			$message	=	__("User id is Empty.", true);
		}

		$response_data	=	array('status'=>$status, 'message'=>$message, 'data'=>$data1);
		echo json_encode(array('response' => $response_data));
		die;
	}


	public function homeupcoming() {
		$status		=	false;
		$message	=	'';
		$data		=	[];
		$data1		=	(object) array();
		$data_row	=	file_get_contents("php://input");
		$decoded    =	json_decode($data_row, true);

		$this->loadModel('DraftContests');
		$this->loadModel('PlayerTeams');
		
		$userId = $this->getUserId();
		
		if(!empty($userId)) {

			$draft_info = $this->DraftContests->find()
			->select(['DraftContests.id','Drafts.id','Drafts.name','Drafts.week','Drafts.startdate','Drafts.starttime','Drafts.enddate','Drafts.endtime','Drafts.draft_data','Contest.category_id','Contest.contest_name','Contest.id','Contest.winning_amount','Contest.contest_size','Contest.contest_type','Contest.entry_fee'])
			->where(['Drafts.status'=>1])
			->contain(['Drafts','Contest'=>['Category'=>['fields'=>['category_name','image']]]])
			->toArray();

			$draft_array = [];
			if( !empty($draft_info) ) {
				foreach( $draft_info AS $key => $value ){
					$playtem_info=$this->PlayerTeams->find()
						->where(['user_id'=>$userId,'contest_id'=>$value->contest->id,'draft_id'=>$value->draft->id])
						->first();
				
					$already_draft=0;
					if(!empty($playtem_info)){
						$already_draft=1;
						continue;

					}
					$draft_array[] = [
						'draft_contest_id'=>$value->id,
						'contest_id'=>$value->contest->id,
						'winning_points'=>$value->contest->winning_amount,
						'contest_size'=>$value->contest->contest_size,
						'contest_type'=>$value->contest->contest_type,
						'entry_points'=>$value->contest->entry_fee,
						'draft_id'=>$value->draft->id,
						'draft_name'=>$value->draft->name,
						'already_draft'=>$already_draft,
						'week'=>$value->draft->week,
						'start_date_time'=>date('d M,Y H:i',strtotime($value->draft->startdate.' '.$value->draft->starttime)),
						'end_date_time'=>date('d M,Y H:i',strtotime($value->draft->enddate.' '.$value->draft->endtime)),
						
						'category_name'=>$value->category['category_name'],
						'image'=>SITE_URL."uploads/category_image/".$value->category['image'],

					];
				}
			}

			$data1	=	$draft_array;
			$status	=	true;	
		} else {
			$message	=	__("User id is Empty.", true);
		}

		$response_data	=	array('status'=>$status, 'message'=>$message, 'data'=>$data1);
		echo json_encode(array('response' => $response_data));
		die;
	}

	public function draftteam() {
		$status		=	false;
		$message	=	'';
		$data		=	[];
		$data1		=	(object) array();
		$data_row	=	file_get_contents("php://input");
		$decoded    =	json_decode($data_row, true);
		if(!empty($decoded)) {
			if ( $decoded['draft_id'] ) {

				$this->loadModel('DraftContests');
				$userId = $this->getUserId();
				
				if(!empty($userId)) {

					$draft_info = $this->DraftContests->find()
					->select(['DraftContests.id','Drafts.id','Drafts.name','Drafts.week','Drafts.season','Drafts.seasontype','Drafts.startdate','Drafts.starttime','Drafts.enddate','Drafts.endtime','Drafts.draft_data','Contest.category_id','Contest.contest_name','Contest.winning_amount','Contest.contest_size','Contest.contest_type','Contest.entry_fee'])
					->where(['Drafts.status'=>1,'Drafts.id'=>$decoded['draft_id']])
					->contain(['Drafts','Contest'=>['Category'=>['fields'=>['category_name']]]])
					->first();
					
					if( !empty($draft_info) ) {

						$week = 1;
						$player_id_array = [];
						$week = $draft_info->draft->week;
						$season = $draft_info->draft->season;
						$seasontype = $draft_info->draft->seasontype;
						$draft_data = json_decode($draft_info->draft->draft_data);
						

						if(!empty($draft_data)){
							foreach($draft_data AS $key => $value ){
								foreach($value AS $ikey => $ivalue ){
									if(is_array($ivalue)){
										foreach($ivalue AS $pkey => $pvalue ){
											$player_id_array[] = $pvalue;
										}
									}
								}
							}
						}
						
						$this->loadModel('ApiPlayers');
						$players_info = $this->ApiPlayers->find()
						->select(['id','Week','Team','Opponent','PlayerID','Name','Position','FantasyPoints'])
						->where(['PlayerID IN '=> $player_id_array, 'Week'=> $week,'Season'=>$season,'SeasonType'=>$seasontype] )
						->toArray();

						$player_info_list = [];
						if(!empty($players_info)){
							foreach($players_info AS $key => $val){
								$player_info_list[$val->PlayerID] = $val;
							}
						}

						$draft_data_new = [];
						if(!empty($draft_data)){
							foreach($draft_data AS $key => $dfvalue ){
								foreach($dfvalue AS $tkey => $value ){
									
									if($tkey=='display_name'){
										$draft_data_new[] = $value;
									}
								}

							}
						}
						
						$draft_array = [
							'winning_points'=>$draft_info->contest->winning_amount,
							'contest_size'=>$draft_info->contest->contest_size,
							'contest_type'=>$draft_info->contest->contest_type,
							'entry_points'=>$draft_info->contest->entry_fee,
							'draft_id'=>$draft_info->draft->id,
							'draft_name'=>$draft_info->draft->name,
							'week'=>$draft_info->draft->week,
							'start_date_time'=>date('d M,Y H:i',strtotime($draft_info->draft->startdate.' '.$draft_info->draft->starttime)),
							'end_date_time'=>date('d M,Y H:i',strtotime($draft_info->draft->enddate.' '.$draft_info->draft->endtime)),
							'draft_data'=> $draft_data_new,
							'category_name'=>$draft_info->category['category_name']
						];
						$data1	=	$draft_array;
					$status	=	true;	
					}
				} else {
					$message	=	__("User id is Empty.", true);
				}

			} else {
				$message	=	__('Draft ID Requried.',true);
			}
		} else {
			$message	=	__("You are not authenticated user.", true);
		}

		

		$response_data	=	array('status'=>$status, 'message'=>$message, 'data'=>$data1);
		echo json_encode(array('response' => $response_data));
		die;
	}

	public function draftplayesold() {
		$status		=	false;
		$message	=	'';
		$data		=	[];
		$data1		=	(object) array();
		$data_row	=	file_get_contents("php://input");
		$decoded    =	json_decode($data_row, true);
		if(!empty($decoded)) {
			if ( $decoded['draft_id'] && $decoded['draftteam']) {
				$this->loadModel('DraftContests');
				$userId = $this->getUserId();
				if(!empty($userId)) {

					$draft_info = $this->DraftContests->find()
					->select(['DraftContests.id','Drafts.name','Drafts.week','Drafts.startdate','Drafts.starttime','Drafts.enddate','Drafts.endtime','Drafts.draft_data','Contest.category_id','Contest.contest_name','Contest.winning_amount','Contest.contest_size','Contest.contest_type','Contest.entry_fee'])
					->where(['Drafts.status'=>1,'Drafts.id'=>$decoded['draft_id']])
					->contain(['Drafts','Contest'=>['Category'=>['fields'=>['category_name']]]])
					->first();
				
					$draft_array=array();
					if( !empty($draft_info) ) {

						$week = 1;
						$player_id_array = [];
						$week = json_decode($draft_info->draft->week);
						$draft_data = json_decode($draft_info->draft->draft_data,true);
						
							if(!empty($draft_data)){
								foreach($draft_data AS $key => $value ){
									foreach($value AS $ikey => $ivalue ){
										if(is_array($ivalue)){
											foreach($ivalue AS $pkey => $pvalue ){
												$player_id_array[] = $pvalue;
											}
										}
									}
								}
							}
						

						$this->loadModel('ApiPlayers');
						$players_info = $this->ApiPlayers->find()
						->select(['id','Week','Team','Opponent','PlayerID','Name','Position','FantasyPoints'])
						->where(['PlayerID IN '=> $player_id_array, 'Week'=> $week] )
						->toArray();

						$player_info_list = [];
						if(!empty($players_info)){
							foreach($players_info AS $key => $val){
								$player_info_list[$val->PlayerID] = $val;
							}
						}

						$draft_data_new = [];
						
							if(!empty($draft_data)){
								foreach($draft_data AS $key => $value ){
									if($value['display_name']==$decoded['draftteam']){

										foreach($value['team_member'] AS $ikey => $ivalue ){
											$player_id = $ivalue;
											$pinfo = $player_info_list[$player_id];
											$draft_data_new[$ikey] = [
												'week'=> $pinfo->Week,
												'team'=> $pinfo->Team,
												'opponent'=> $pinfo->Opponent,
												'player_id'=> $pinfo->PlayerID,
												'name'=> $pinfo->Name,
												'position'=> $pinfo->Position,
												'projected_points'=> $pinfo->FantasyPoints,
											];
										}
									}
								}
							}
						
						$draft_array = [
							'winning_points'=>$draft_info->contest->winning_amount,
							'contest_size'=>$draft_info->contest->contest_size,
							'contest_type'=>$draft_info->contest->contest_type,
							'entry_points'=>$draft_info->contest->entry_fee,
							'draft_id'=>$draft_info->draft->id,
							'draft_name'=>$draft_info->draft->name,
							'week'=>$draft_info->draft->week,
							'start_date_time'=>date('d M,Y H:i',strtotime($draft_info->draft->startdate.' '.$draft_info->draft->starttime)),
							'end_date_time'=>date('d M,Y H:i',strtotime($draft_info->draft->enddate.' '.$draft_info->draft->endtime)),
							'draft_data'=> $draft_data_new,
							'category_name'=>$draft_info->category['category_name']
						];
					}
					$data1	=	$draft_array;
					$status	=	true;	
				} else {
					$message	=	__("User id is Empty.", true);
				}

			} else {
				$message	=	__('Draft ID Requried.',true);
			}
		} else {
			$message	=	__("You are not authenticated user.", true);
		}

		

		$response_data	=	array('status'=>$status, 'message'=>$message, 'data'=>$data1);
		echo json_encode(array('response' => $response_data));
		die;
	}

	public function draftplayer() {
		date_default_timezone_set('US/Eastern');

		$status		=	false;
		$message	=	'';
		$draft_data_new=$data		=	[];
		$data1		=	(object) array();
		$data_row	=	file_get_contents("php://input");
		$decoded    =	json_decode($data_row, true);
		if(!empty($decoded)) {
			if ( !empty($decoded['draft_id']) && !empty($decoded['draftteam'])) {
				$this->loadModel('DraftContests');
				$userId = $this->getUserId();
				if(!empty($userId)) {

					$draft_info = $this->DraftContests->find()
					->select(['DraftContests.id','Drafts.name','Drafts.week','Drafts.seasontype','Drafts.season','Drafts.startdate','Drafts.starttime','Drafts.enddate','Drafts.endtime','Drafts.draft_data','Contest.category_id','Contest.contest_name','Contest.winning_amount','Contest.contest_size','Contest.contest_type','Contest.entry_fee'])
					->where(['Drafts.status'=>1,'Drafts.id'=>$decoded['draft_id']])
					->contain(['Drafts','Contest'=>['Category'=>['fields'=>['category_name']]]])
					->first();
					
					$draft_array=array();
					if( !empty($draft_info) ) {

						$week = 1;
						$player_id_array = [];
						$week = $draft_info->draft->week;
						$draft_data = json_decode($draft_info->draft->draft_data,true);
						$season = $draft_info->draft->season;
						$seasontype = $draft_info->draft->seasontype;
												
							if(!empty($draft_data)){
								foreach($draft_data AS $key => $value ){
									foreach($value AS $ikey => $ivalue ){
										if(is_array($ivalue)){
											foreach($ivalue AS $pkey => $pvalue ){
												$player_id_array[] = $pvalue;
											}
										}
									}
								}
							}
						
						$this->loadModel('ApiPlayers');
						$players_info = $this->ApiPlayers->find()
						->select(['id','Week','Team','Opponent','PlayerID','Name','Position','FantasyPoints','GameDate','FantasyPointsPPR','Played','Image','HomeOrAway'])
						->where(['PlayerID IN '=> $player_id_array, 'Week'=> $week,'SeasonType'=> $seasontype,'Season'=> $season] )
						->toArray();

						$player_info_list = [];
						if(!empty($players_info)){
							foreach($players_info AS $key => $val){
								$player_info_list[$val->PlayerID] = $val;
							}
						}
						
						$draft_data_new = [];
						
							if(!empty($draft_data)){
								foreach($draft_data AS $key => $value ){
									if($value['display_name']==$decoded['draftteam']){

										foreach($value['team_member'] AS $ikey => $ivalue ){
											$player_id = $ivalue;
											if(!empty($player_info_list[$player_id])){
												$pinfo = $player_info_list[$player_id];
												$opponentInfo=$pinfo->Opponent;
												if($pinfo->HomeOrAway=='AWAY'){
													$opponentInfo="@".$pinfo->Opponent;
												}
												$draft_data_new[$ikey] = [
													'week'=> $pinfo->Week,
													'team'=> $pinfo->Team,
													
													'opponent'=> $opponentInfo,
													'player_id'=> $pinfo->PlayerID,
													'apitable_id'=> $pinfo->id,
													'image'=> $pinfo->Image,
													'name'=> $pinfo->Name,
													'position'=> $pinfo->Position,
													'display_name'=>$value['display_name'],
													'projected_points'=> $pinfo->FantasyPointsPPR,
													'match_time'=> date('d M,Y H:i',strtotime($pinfo->GameDate)),
													'status'=> $pinfo->Played,
												];
											}
										}
									}
								}
							}
						
						
						$draft_array = [
							'winning_points'=>$draft_info->contest->winning_amount,
							'contest_size'=>$draft_info->contest->contest_size,
							'contest_type'=>$draft_info->contest->contest_type,
							'entry_points'=>$draft_info->contest->entry_fee,
							'draft_id'=>$draft_info->draft->id,
							'draft_name'=>$draft_info->draft->name,
							'week'=>$draft_info->draft->week,
							'start_date_time'=>date('d M,Y H:i',strtotime($draft_info->draft->startdate.' '.$draft_info->draft->starttime)),
							'end_date_time'=>date('d M,Y H:i',strtotime($draft_info->draft->enddate.' '.$draft_info->draft->endtime)),
							'draft_data'=> $draft_data_new,
							'category_name'=>$draft_info->category['category_name']
						];
					}
					$data1	=	$draft_array;
					$status	=	true;	
				} else {
					$message	=	__("User id is Empty.", true);
				}

			} else {
				$message	=	__('Draft ID and Draft Team Requried.',true);
			}
		} else {
			$message	=	__("You are not authenticated user.", true);
		}

		

		$response_data	=	array('status'=>$status, 'message'=>$message, 'data'=>$data1);
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
		$this->loadModel('PlayerTeams');
		$userId = $this->getUserId();
		
		if(!empty($userId)) {
			$users		=	$this->Users->find()
			->select(['id','full_name','user_name','country_code','phone','email','dob','gender','address','city','state','country','pincode','winning_wallet','deposit_wallet','bonus_wallet','profile_image'])
			->where(['id'=>$userId,'Users.status'=>1])
			->first();
			
			$totalContest		=	$this->PlayerTeams->find()
							->where(['user_id'=>$userId])
							->count();
			
			$winningContest		=	$this->PlayerTeams->find()
							->where(['user_id'=>$userId,'winning_coins >'=>0])
							->count();
			
			if(!empty($users)) {

				$user_id_encrypted 			= 	$this->General->encrypt_decrypt('encrypt', $users->id);
				$result['user_id']			=	$user_id_encrypted;
				$result['full_name']		=	$users->full_name;
				$result['user_name']		=	$users->user_name;
				$result['total_contest']		=	$totalContest;
				$result['winning_contest']		=	$winningContest;

				if(!empty($users->profile_image)) {
					$result['profile_image']	=	U_IMAGE_PATH.$users->profile_image;
				} else {
					$result['profile_image']	=	'';
				}
				
				$result['country_code']		=	$users->country_code;
				$result['phone']			=	$users->phone;
				$result['email']			=	$users->email;
				$result['dob']				=	$users->dob;
				$result['gender']			=	$users->gender;
				$result['address']			=	$users->address;
				$result['country']			=	$users->country;
				$result['state']			=	$users->state;
				$result['city']				=	$users->city;
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

				$user	=	$this->Users->find()
				->select(['id'])
				->where(['Users.status'=>ACTIVE,'id'=>$userId])
				->first();

				if(!empty($user)) {
					
					$user->full_name		=	(isset($decoded['full_name'])) ? $decoded['full_name'] : '';
					$user->email			=	(isset($decoded['email'])) ? $decoded['email'] : '';
					$user->dob				=	(isset($decoded['dob'])) ? $decoded['dob'] : '';
					$user->gender			=	(isset($decoded['gender'])) ? $decoded['gender'] : '';
					$user->country_code		=	(isset($decoded['country_code'])) ? $decoded['country_code'] : '';
					$user->phone			=	(isset($decoded['phone'])) ? $decoded['phone'] : '';
					$user->address			=	(isset($decoded['address'])) ? $decoded['address'] : '';
					$user->city				=	(isset($decoded['city'])) ? $decoded['city'] : '';
					$user->state			=	(isset($decoded['state'])) ? $decoded['state'] : '';
					$user->country			=	(isset($decoded['country'])) ? $decoded['country'] : '';
					$user->pincode			=	(isset($decoded['pincode'])) ? $decoded['pincode'] : '';
					
					
					if($result = $this->Users->Save($user)) {
						
						$random_val = rand();
						$secure_id = $random_val.'###'.$result->id.'##'.APP_SECURE_KEY; 
						$encrypted = $this->General->encrypt_decrypt('encrypt', $secure_id);
	
						$user_id_encrypted = $this->General->encrypt_decrypt('encrypt', $result->id);
	
						$data1->token			=	$encrypted;
						$data1->user_id			=	$user_id_encrypted;
						$data1->full_name		=	$result->full_name;
						$data1->user_name		=	$result->user_name;

						if(!empty($result->profile_image)) {
							$data1->profile_image	=	U_IMAGE_PATH.$result->profile_image;
						} else {
							$data1->profile_image	=	'';
						}
						

						$data1->dob				=	$result->dob;
						$data1->gender			=	$result->gender;
						$data1->country_code	=	$result->country_code;
						$data1->phone			=	$result->phone;
						$data1->address			=	$result->address;
						$data1->city			=	$result->city;
						$data1->state			=	$result->state;
						$data1->country			=	$result->country;
						$data1->pincode			=	$result->pincode;
						$data1->referral_code	=	$result->referral_code;


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

	public function usertransaction() {
		$status		=	false;
		$message	=	NULL;
		$data		=	[];
		$data1		=	(object) array();
		$data_row	=	file_get_contents("php://input");
		$decoded    =	json_decode($data_row, true);
		
		$this->loadModel('Transactions');
		$userId = $this->getUserId();
		
		if(!empty($userId)) {
				$flag	=	0;
			
					$conditions=['user_id'=>$userId];

					$txnHistory	=	$this->Transactions->find()
									->select(['Transactions.id','added_type','txn_amount','local_txn_id','txn_date','status','amount_status'])
									->where($conditions)
									->order(['txn_date'=>'DESC'])
									->toArray();
					if(!empty($txnHistory)) {
						foreach($txnHistory as $key => $txns) {
							$status		=	Configure::read('global_config.withdrawl_type.'.$txns->status);
							$added_type		=	Configure::read('TRANSACTION_TYPE.'.$txns->added_type);

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
								'amount'			=>	($txns->amount_status == 'Deduct') ? '- '.$txns->txn_amount : '+ '.$txns->txn_amount,
								'txn_type'			=>	$added_type,
								'transaction_id'	=>	$txns->local_txn_id,
								'txn_date'			=>	$this->General->convertDateTime($txns->txn_date,'Y-m-d h:i A') //date('d F,h:i:s a',strtotime($txns->txn_date))
							];
						$flag++;

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
									->select(['Transactions.id','added_type','txn_amount','txn_id','txn_date','status'/* ,'extra' */])
									->where($conditions)
									->order(['txn_date'=>'DESC'])
									->toArray();
									
					if(!empty($txnHistory)) {
						foreach($txnHistory as $key => $txns) {
							$added_type		=	Configure::read('TRANSACTION_TYPE.'.$txns->added_type);

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
								
								'txn_date'			=>	$this->General->convertDateTime($txns->txn_date,'Y-m-d h:i A') //date('d F,h:i:s a',strtotime($txns->txn_date))
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

	public function getCountryList() {
		$status		=	false;
		$message	=	NULL;
		$data		=	[];
		$data1		=	(object) array();
		$data_row	=	file_get_contents("php://input");
		$decoded    =	json_decode($data_row, true);
		
		//Check referred by
		$this->loadModel('Countries');

		//'list',['keyField'=>'countryID','valueField'=>'countryName']
		$countries	=	$this->Countries->find()
		->select(['countryID','countryName'])
		->toArray();

		if(!empty($countries)){
			$status		=	true;
			$message	=	__("", true);
		} else {
			$message	=	__("", true);
		}

		$response_data	=	array('status'=>$status,'tokenexpire'=>0,'message'=>$message,'data'=>$countries);
		echo json_encode(array('response' => $response_data));
		die;
	}

	public function getStateList() {
		$status		=	false;
		$message	=	NULL;
		$data		=	[];
		$data1		=	(object) array();
		$data_row	=	file_get_contents("php://input");
		$decoded    =	json_decode($data_row, true);
		
		if (!empty($decoded)) {
			$this->loadModel('States');
			$countryID  =   (!empty($decoded['country_id'])) ? $decoded['country_id'] : "";
			//'list',['keyField'=>'stateID','valueField'=>'stateName']
			$countries	=	$this->States->find()
			->select(['stateID','stateName'])
			->where(['countryID'=>$countryID])
			->toArray();

			if(!empty($countries)){
				$status		=	true;
				$message	=	__("", true);
			} else {
				$message	=	__("", true);
			}
		} else {
			$message	=	__("Please select country first.", true);
		}
		

		$response_data	=	array('status'=>$status,'tokenexpire'=>0,'message'=>$message,'data'=>$countries);
		echo json_encode(array('response' => $response_data));
		die;
	}


	public function getCityList() {
		$status		=	false;
		$message	=	NULL;
		$data		=	[];
		$data1		=	(object) array();
		$data_row	=	file_get_contents("php://input");
		$decoded    =	json_decode($data_row, true);
		
		if (!empty($decoded)) {
			$this->loadModel('Cities');
			$stateID  =   (!empty($decoded['state_id'])) ? $decoded['state_id'] : "";
			//'list',['keyField'=>'cityID','valueField'=>'cityName']
			$countries	=	$this->Cities->find()
			->select(['cityID','cityName'])
			->where(['stateID'=>$stateID])
			->order(['cityName'])
			->toArray();

			if(!empty($countries)){
				$status		=	true;
				$message	=	__("", true);
			} else {
				$message	=	__("", true);
			}
		} else {
			$message	=	__("Please select state first.", true);
		}

		$response_data	=	array('status'=>$status,'tokenexpire'=>0,'message'=>$message,'data'=>$countries);
		echo json_encode(array('response' => $response_data));
		die;
	}

	public function getPage( $slug = '' ) {
		$status		=	false;
		$message	=	NULL;
		$data		=	[];
		$data1		=	(object) array();
		$data_row	=	file_get_contents("php://input");
		$decoded    =	json_decode($data_row, true);
		
		
		$this->loadModel('Contents');
		$this->autoRender = false;
		$content	=	$this->Contents->find()
		->where(['slug'=>$slug])
		->first();
		
		if(!empty($content)) {
			echo '<div style="font-size: 35px;
			position: relative;">'.$content->content.'</div>';die;
		}
	}

	public function getFaq() {
		$status		=	false;
		$message	=	NULL;
		$data		=	[];
		$data1		=	(object) array();
		$data_row	=	file_get_contents("php://input");
		$decoded    =	json_decode($data_row, true);
		$this->loadModel('Faq');

		$faq = $this->Faq->find()
		->select(['title','description'])
		->where(['status'=>'1'])->toArray();
		$status	=	true;
		$data1	=	$faq;
		$response_data	=	array('status'=>$status,'message'=>$message,'data'=>$data1);
		echo json_encode(['response'=>$response_data]);
		die;
	}

	public function contactus() {
		$status		=	false;
		$message	=	NULL;
		$data		=	array();
		$data1		=	(object) array();
		$data_row	=	file_get_contents("php://input");
		$decoded    =	json_decode($data_row, true);
		$this->loadModel('Users');
		$this->loadModel('ContactUs');
		$this->loadModel('EmailTemplates');

		$userId = $this->getUserId();
		if(!empty($userId)) {
			$users		=	$this->Users->find()->where(['id'=>$userId,'Users.status'=>ACTIVE])->first(); 
			$template	=	$this->EmailTemplates->find()->where(['subject'=>'Contact us'])->first();
			
			if(!empty($users)) {
				$email=$users->email;
				
				$contact = $this->ContactUs->newEntity();
				$contact->user_id	=	$userId;
				$contact->message	=	$decoded['message'];
				
				if($this->ContactUs->save($contact)) {
					$to			=	$email;
					$from		=	Configure::read('Admin.setting.admin_email');
					
					$subject	=	$template->email_name;
					$validString=	time().base64_encode($email);
					$resetUrl	=	SITE_URL.'admin/users/reset-password/'.$validString;
					$message	=	str_replace('{{user}}',$users->user_name,$template->template);
					$this->sendMail($to, $subject, $message, $from);
					$template	=	$this->EmailTemplates->find()->where(['subject'=>'user_contact_us'])->first();

					$message	=	str_replace('{{message}}',$decoded['message'],$template->template);
					$this->sendMail($from, $subject, $message, $from);

					$status	=	true;
					$message=	__('Your message submit successfully.',true);
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


	public function mycontestupcoming() {
		date_default_timezone_set('US/Eastern');

		
		$status		=	false;
		$message	=	'';
		$data		=	[];
		$data1		=	(object) array();
		$data_row	=	file_get_contents("php://input");
		$decoded    =	json_decode($data_row, true);

		$this->loadModel('PlayerTeams');
		
		$userId = $this->getUserId();
		
		if(!empty($userId)) {
			
			$playerTeams = $this->PlayerTeams->find()
			->select(['id','contest_id','draft_id'])
			->where(['PlayerTeams.user_id'=>$userId])
			->toArray();
			
			$draft_data = [];
			if( !empty($playerTeams) ) {
				$this->loadModel('Contest');
				$this->loadModel('Category');
				$this->loadModel('Drafts');
				$this->loadModel('DraftContests');
				
				foreach( $playerTeams AS $key => $value ){

					$contestData	=	$this->Contest->find()
					->select(['id','category_id','winning_amount','entry_fee'])
					->where(['id'=>$value['contest_id']])
					->first();
					
					$CategoryData	=	$this->Category->find()
					->select(['category_name','image'])
					->where(['id'=>$contestData['category_id']])
					->first();

					$draftsTabData	=	$this->Drafts->find()
					->select(['id','name','startdate','starttime','enddate','endtime'])
					->where(['id'=>$value['draft_id'],'status'=>1])
					->first();

					$draftsCont	=	$this->DraftContests->find()
					->select(['id'])
					->where(['draft_id'=>$value['draft_id'],'contest_id'=>$value['contest_id']])
					->first();

					$enteredContest		=	$this->PlayerTeams->find()
							->where(['draft_id'=>$value['draft_id'],'contest_id'=>$value['contest_id']])
							->count();
					
					$date1=strtotime(date('Y-m-d H:i:s'));
					$date2=strtotime($draftsTabData['startdate'].' '.$draftsTabData['starttime']);
					

					if($date1<$date2){
						
						$draft_data[]=array('draft_name'=>$draftsTabData['name'],'draft_id'=>$draftsTabData['id'],'draft_contest_id'=>$draftsCont['id'],'winning_points'=>$contestData['winning_amount'],'entry_points'=>$contestData['entry_fee'],'entered_user'=>$enteredContest,'category_name'=>$CategoryData['category_name'],'image'=>SITE_URL."uploads/category_image/".$CategoryData['image'],'contest_id'=>$contestData['id'],'start_date_time'=>$draftsTabData['startdate'].' '.$draftsTabData['starttime'],'end_date_time'=>$draftsTabData['enddate'].' '.$draftsTabData['endtime']);
					}
				}
			}
			$data1	=	$draft_data;
			$status	=	true;	
		} else {
			$message	=	__("User id is Empty.", true);
		}

		$response_data	=	array('status'=>$status, 'message'=>$message, 'data'=>$data1);
		echo json_encode(array('response' => $response_data));
		die;
	}

	public function mycontestlive() {
		date_default_timezone_set('US/Eastern');
		$status		=	false;
		$message	=	'';
		$data		=	[];
		$data1		=	(object) array();
		$data_row	=	file_get_contents("php://input");
		$decoded    =	json_decode($data_row, true);

		$this->loadModel('PlayerTeams');
		
		$userId = $this->getUserId();
		if(!empty($userId)) {
			
			$playerTeams = $this->PlayerTeams->find()
			
			->where(['PlayerTeams.user_id'=>$userId])
			->toArray();
			
			$draft_data = [];
			if( !empty($playerTeams) ) {
				$this->loadModel('Contest');
				$this->loadModel('Category');
				$this->loadModel('Drafts');
				$this->loadModel('DraftContests');

				foreach( $playerTeams AS $key => $value ){

					$contestData	=	$this->Contest->find()
					->select(['id','category_id','winning_amount','entry_fee'])
					->where(['id'=>$value['contest_id']])
					->first();

					$totalselect=count(json_decode($value['player_data']));
					
					$CategoryData	=	$this->Category->find()
					->select(['category_name','image'])
					->where(['id'=>$contestData['category_id']])
					->first();

					$draftsTabData	=	$this->Drafts->find()
					->select(['name','startdate','starttime','enddate','endtime'])
					->where(['id'=>$value['draft_id'],'status'=>1])
					->first();

					$draftsCont	=	$this->DraftContests->find()
					->select(['id'])
					->where(['draft_id'=>$value['draft_id'],'contest_id'=>$value['contest_id']])
					->first();
					
					$enteredContest		=	$this->PlayerTeams->find()
							->where(['draft_id'=>$value['draft_id'],'contest_id'=>$value['contest_id']])
							->count();
							
					$date1=strtotime(date('Y-m-d H:i:s'));
					$date2=strtotime($draftsTabData['startdate'].' '.$draftsTabData['starttime']);
					$date3=strtotime($draftsTabData['enddate'].' '.$draftsTabData['endtime']);
					
					if($date1>$date2 && $date1<$date3){
						$draft_data[]=array('draft_name'=>$draftsTabData['name'],'draft_contest_id'=>$draftsCont['id'],'winning_points'=>$contestData['winning_amount'],'entry_points'=>$contestData['entry_fee'],'userrank'=>$this->ordinal($value['rank']),'entered_user'=>$enteredContest,'category_name'=>$CategoryData['category_name'],'image'=>SITE_URL."uploads/category_image/".$CategoryData['image'],'correct'=>$value['points'],'totalselect'=>$totalselect,'contest_id'=>$contestData['id'],'start_date_time'=>$draftsTabData['startdate'].' '.$draftsTabData['starttime'],'end_date_time'=>$draftsTabData['enddate'].' '.$draftsTabData['endtime']);
					}
				}
			}
			$data1	=	$draft_data;
			$status	=	true;	
		} else {
			$message	=	__("User id is Empty.", true);
		}

		$response_data	=	array('status'=>$status, 'message'=>$message, 'data'=>$data1);
		echo json_encode(array('response' => $response_data));
		die;
	}



	public function mycontestcompleted() {
		date_default_timezone_set('US/Eastern');

		
		$status		=	false;
		$message	=	'';
		$data		=	[];
		$data1		=	(object) array();
		$data_row	=	file_get_contents("php://input");
		$decoded    =	json_decode($data_row, true);

		$this->loadModel('PlayerTeams');
		$this->loadModel('Drafts');
		
		$userId = $this->getUserId();
		if(!empty($userId)) {
			$playerTeams = $this->PlayerTeams->find()
			->contain(['Drafts'])
			->where(['PlayerTeams.user_id'=>$userId])
			->order(['Drafts.enddate'=>'DESC'])
			->toArray();
			
			$draft_data = [];
			if( !empty($playerTeams) ) {
				$this->loadModel('Contest');
				$this->loadModel('Category');
				
				$this->loadModel('DraftContests');

				foreach( $playerTeams AS $key => $value ){

					$contestData	=	$this->Contest->find()
					->select(['id','category_id','winning_amount','entry_fee'])
					->where(['id'=>$value['contest_id']])
					->first();

					$totalselect=count(json_decode($value['player_data']));
					
					
					$CategoryData	=	$this->Category->find()
					->select(['id,category_name','image'])
					->where(['id'=>$contestData['category_id']])
					->first();

					$draftsTabData	=	$this->Drafts->find()
					->select(['name','startdate','starttime','enddate','endtime'])
					->where(['id'=>$value['draft_id'],'status'=>1])
					->first();
					
					$enteredContest		=	$this->PlayerTeams->find()
							->where(['draft_id'=>$value['draft_id'],'contest_id'=>$value['contest_id']])
							->count();

					$draftsCont	=	$this->DraftContests->find()
					->select(['id'])
					->where(['draft_id'=>$value['draft_id'],'contest_id'=>$value['contest_id']])
					->first();
					
					$date1=strtotime(date('Y-m-d H:i:s'));
					$date2=strtotime($draftsTabData['enddate'].' '.$draftsTabData['endtime']);
					
					if($date1>$date2){
						
						$draft_data[]=array('draft_name'=>$draftsTabData['name'],'draft_contest_id'=>$draftsCont['id'],'winning_points'=>$contestData['winning_amount'],'entry_points'=>$contestData['entry_fee'],'userrank'=>$this->ordinal($value['rank']),'entered_user'=>$enteredContest,'category_name'=>$CategoryData['category_name'],'image'=>SITE_URL."uploads/category_image/".$CategoryData['image'],'correct'=>$value['points'],'totalselect'=>$totalselect,'contest_id'=>$contestData['id'],'start_date_time'=>$draftsTabData['startdate'].' '.$draftsTabData['starttime'],'end_date_time'=>$draftsTabData['enddate'].' '.$draftsTabData['endtime'],'user_winnning_coin'=>$value['winning_coins']);
					}
				}
			}
			$data1	=	$draft_data;
			$status	=	true;	
		} else {
			$message	=	__("User id is Empty.", true);
		}

		$response_data	=	array('status'=>$status, 'message'=>$message, 'data'=>$data1);
		
		echo json_encode(array('response' => $response_data));
		die;
	}

	public function ordinal($number) {
		$ends = array('th','st','nd','rd','th','th','th','th','th','th');
		if ((($number % 100) >= 11) && (($number%100) <= 13)){
			if($number==0){
				return "NA";
			}else{
				return $number. 'th';
			}
		}else{
			if($number==0){
				return "NA";
			}else{
				return $number. $ends[$number % 10];
			}
		}
	}

	public function contestDetail(){ 
		
		$status		=	false;
		$message	=	NULL;
		$data		=	[];
		$data1		=	(object) array();
		$data_row	=	file_get_contents("php://input");
		$decoded    =	json_decode($data_row, true);
		$dataDraft='';
		$contestDetail=array();
		if (!empty($decoded['draft_contests_id'])) {
			$this->loadModel('DraftContests');
			$draft_contests_id  =   $decoded['draft_contests_id'];
			
			
			$dataDraft	=	$this->DraftContests->find()
			->select(['DraftContests.draft_id','DraftContests.contest_id','Drafts.name','Drafts.draft_data','Drafts.week','Drafts.season','Drafts.seasontype'])
			->where(['DraftContests.id'=>$draft_contests_id])
			->contain(['Drafts'])
			->first();
			
			$contestDetail['name']=$dataDraft['draft']['name'];
			$contestDetail['contest_id']=$dataDraft['contest_id'];
			$contestDetail['draft_id']=$dataDraft['draft_id'];

			if(!empty($dataDraft['contest_id'])){
				$this->loadModel('Contest');
				$contestData	=	$this->Contest->find()
					->select(['category_id','winning_amount'])
					->where(['id'=>$dataDraft['contest_id']])
					->first();
				
					$this->loadModel('Category');
					$CategoryData	=	$this->Category->find()
					->select(['category_name','image'])
					->where(['id'=>$contestData['category_id']])
					->first();
					$contestDetail['category_name']=$CategoryData['category_name'];
					$contestDetail['winning_amount']=$contestData['winning_amount'];
					$contestDetail['image']=SITE_URL."uploads/category_image/".$CategoryData['image'];
					
					$this->loadModel('CustomBreakup');
					$customBreakup	=	$this->CustomBreakup->find()
					->select($this->CustomBreakup)
					->where(['contest_id'=>$dataDraft['contest_id']])
					->toArray();

					$draft_arr=json_decode($dataDraft['draft']['draft_data'],true);
					$season=$dataDraft['draft']['season'];
					$seasontype=$dataDraft['draft']['seasontype'];
					$draft_week=isset($dataDraft['draft']['week'])?$dataDraft['draft']['week']:'';
					$final_points=$this->getplayerrank($draft_arr,$draft_week,$season,$seasontype);
					

					$this->loadModel('PlayerTeams');
					$playerTeams	=	$this->PlayerTeams->find()
					->select(['user_id','points','rank'])
					->where(['contest_id'=>$dataDraft['contest_id'],'draft_id'=>$dataDraft['draft_id']])
					->order('rank')
					->toArray();
					$zeroRank=$boardData=array();
					$this->loadModel('Users');
					foreach($playerTeams as $key => $value){
						if(!empty($value['user_id'])){
							$users = $this->Users->find()
							->select(['user_name'])
							->where( [ 'id'=> $value['user_id']] )->first();
							if($users['user_name']!=''){
								if($value['points']==0){
									
									$value['rank']='-';
									$zeroRank[]=array("rank"=>$value['rank'],"name"=>$users['user_name'],"user_id"=>$value['user_id'],"points"=>$value['points']);
								}else{
									$boardData[]=array("rank"=>$value['rank'],"name"=>$users['user_name'],"user_id"=>$value['user_id'],"points"=>$value['points']);
								}
							}
						}
					}
					if(!empty($zeroRank)){
						$boardData=array_merge($boardData,$zeroRank);
					}
					

					$breakup=array();
					foreach($customBreakup as $key => $value){
						$breakup[]=array("name"=>$value['name'],"price"=>$value['price']);
					}
					$contestDetail['leaderboard']=$boardData;
					$contestDetail['prize_pool']=$breakup;
					
				$status		=	true;
				$message	=	__("", true);
			} else {
				$message	=	__("Draft contest id not found.", true);

			}
		} else {
			$message	=	__("Please select category id.", true);
		}
		

		$response_data	=	array('status'=>$status,'tokenexpire'=>0,'message'=>$message,'data'=>$contestDetail);
		echo json_encode(array('response' => $response_data));
		die;
	}

	public function getplayerrank($draft_arr,$draft_week,$season,$seasontype){
		$this->loadModel('ApiPlayers');
		$display_name=$player_rank=[];
			foreach($draft_arr as $dkey=>$dValue){
				$players_points=[];
				$display_name[$dValue['filter_listing']]=$dValue['display_name'];

				foreach($dValue['team_member'] as $key=>$value){
					$players_inf = $this->ApiPlayers->find()
							->select(['id','FantasyPoints','Position'])
							->where(['PlayerID'=> $value,'Week'=>$draft_week,'Season'=>$season,'SeasonType'=>$seasontype] )
							->first();
					$players_points[$value]=$players_inf['FantasyPoints'];
				}
				arsort($players_points);
				$player_rank[$dValue['filter_listing']]=$players_points;
			}
			
			$final_points=[];
			foreach($player_rank as $tkey=>$tValue){
				$tmpPoint=$ranks=0;
				$pointsArr=[];
				foreach($tValue as $key=>$value){
					$pointOfTeam=$value;
						
						if($tmpPoint == $pointOfTeam) {
							$rank	=	$ranks;
						} else {
								$ranks++;
							$rank	=	$ranks;
						}
						$tmpPoint	=	$pointOfTeam;
						$pointsArr[$key]=$rank;	
				}
				$final_points[$tkey]=$pointsArr;
			}
		return $final_points;
	}

	public function playerData(){
		
		$status		=	false;
		$message	=	NULL;
		$data		=	[];
		$data1		=	(object) array();
		$data_row	=	file_get_contents("php://input");
		$decoded    =	json_decode($data_row, true);
		
		$playerData=array();
		if (!empty($decoded['week'])) {

			$this->loadModel('ApiPlayers');
			$players_info = $this->ApiPlayers->find()
			->select(['id','Week','Team','GameDate','PlayerID','Name','FantasyPointsPPR','Played','Opponent','Image'])
			->where(['Week'=> $decoded['week']] )
			->toArray();
					
					foreach($players_info as $key => $value){
					
							if(!empty($value)){
								
								$playerData[$value['Team']][]=[
									'team'=>$value->Team,
									'player_name'=>$value->Name,
									'image'=>$value->Image,
									'player_team'=>$value->Team,
									'projected_point'=>$value->FantasyPointsPPR,
									'status'=>$value->Played,
									'opp_team'=>$value->Opponent,
									'match_time'=>$value->GameDate,
								];
							}
					}
					
				$status		=	true;
				$message	=	__("", true);
			
		} else {
			$message	=	__("Please week number.", true);
		}
		

		$response_data	=	array('status'=>$status,'tokenexpire'=>0,'message'=>$message,'data'=>$playerData);
		echo json_encode(array('response' => $response_data));
		die;
	}

	public function joinContestWalletAmount() {
		$status		=	false;
		$message	=	NULL;
		$data		=	[];
		$data1		=	(object) array();
		$data_row	=	file_get_contents("php://input");
		$decoded    =	json_decode($data_row, true);

		$this->loadModel('Users');
		$this->loadModel('Contest');
		$userId = $this->getUserId();
		if(!empty($decoded)) {
			if(!empty($decoded['draft_contests_id'])) {
				$userdata	=	$this->Users->find()
				->select(['winning_wallet'])
				->where([ 'id'=>$userId, 'Users.status'=>ACTIVE ])
				->first();
				if(!empty($userdata)) {

						$this->loadModel('DraftContests');
						$draft_contests_id  =   $decoded['draft_contests_id'];
						
						$dataDraft	=	$this->DraftContests->find()
						->select(['DraftContests.draft_id','DraftContests.contest_id','Drafts.name'])
						->where(['DraftContests.id'=>$draft_contests_id])
						->contain(['Drafts'])
						->first();
						
						$entryFee	=	0;
						if(!empty($dataDraft['contest_id'])){
							$this->loadModel('Contest');
							$contestData	=	$this->Contest->find()
								->select(['category_id','entry_fee'])
								->where(['id'=>$dataDraft['contest_id']])
								->first();
							if( !empty($contestData) ){
								$entryFee	=	$contestData->entry_fee;
							}
						}

						
						$data['winning_wallet']	=	$userdata->winning_wallet;
						$data['entry_point']		=	$entryFee;

						$status	=	true;
						$data1	=	$data;
					
				} else {
					$message	=	__('Invalid user id.',true);
				}
			} else {
				$message	=	__("Draft Id required.", true);
			} 
		} else {
			$message	=	__("You are not authenticated user.", true);
		}

		$response_data	=	array('status'=>$status,'message'=>$message,'data'=>$data1);
		echo json_encode(array('response' => $response_data));
		die;
	}

	public function selectteam(){
		date_default_timezone_set('US/Eastern');
		
		$status		=	false;
		$message	=	NULL;
		$data		=	[];
		$data1		=	(object) array();
		$data_row	=	file_get_contents("php://input");
		$decoded    =	json_decode($data_row, true);
		
		$this->loadModel('Users');
		$playerData=array();
		$decoded['user_id'] = $this->getDecryptUserId($decoded['user_id']);
		
		if (!empty($decoded) && !empty($decoded['user_id']) && !empty($decoded['contest_id']) && !empty($decoded['draft_id']) && !empty($decoded['player_data'])) {
			
		
			$draft_contest_id='';
			$this->loadModel('Drafts');
			$recordContests	=	$this->Drafts->find()
				->where(['Drafts.id'=>$decoded['draft_id']])
				->first();
			$date1=strtotime(date('Y-m-d H:i:s'));
			$date2=strtotime($recordContests['startdate'].' '.$recordContests['starttime']);
			
				$this->loadModel('DraftContests');
				$recordContests	=	$this->DraftContests->find()
					->where(['DraftContests.contest_id'=>$decoded['contest_id'],'DraftContests.draft_id'=>$decoded['draft_id']])
					->contain(['Contest'])
					->first();
				$draft_contest_id=!empty($recordContests->id)?$recordContests->id:'';
			if($date1>=$date2){
				$status=true;
				$message	=	__("Data can't editable as the starting date has passed out.", true);
			}else{
				$entry_fee=$recordContests->contest->entry_fee;
				$this->loadModel('PlayerTeams');
				$record	=	$this->PlayerTeams->find()
					->where(['user_id'=>$decoded['user_id'],'contest_id'=>$decoded['contest_id'],'draft_id'=>$decoded['draft_id']])
					->first();

				if($record){
					$seriesData	=	$this->PlayerTeams->get($record->id); 
					$seriesData['created']=	date('Y-m-d H:i:s'); 
				}  else{ 
					$seriesData	=	$this->PlayerTeams->newEntity();
					$seriesData['created']		=	date('Y-m-d H:i:s');
					if($entry_fee>0){
						$users = $this->Users->get($decoded['user_id']);
						$users['winning_wallet']=$users->winning_wallet-$entry_fee;
						if($result = $this->Users->save($users)){
								$this->loadModel('Transactions');
								$transData	=	$this->Transactions->newEntity();
								$transData['created']		=	date('Y-m-d H:i:s');
								$transData['txn_date']		=	date('Y-m-d H:i:s');
								$transData['txn_amount']		=	$entry_fee;
								$transData['user_id']		=	$decoded['user_id'];
								$transData['local_txn_id']		=	date('dmY').time();
								$transData['added_type']		=	JOIN_CONTEST;
								$transData['amount_status']		=	Deduct;

								if($result = $this->Transactions->save($transData)){

								}

						}
					}
				}
				
						$seriesData['user_id']		=	$decoded['user_id'];
						$seriesData['contest_id']	=	$decoded['contest_id'];
						$seriesData['draft_id']			=	$decoded['draft_id'];
						$seriesData['player_data']		=	json_encode($decoded['player_data']);
						$seriesData['created']		=	date('Y-m-d H:i:s');
								
						if($result = $this->PlayerTeams->save($seriesData)){
							$status=true;
								$message	=	__("Data Save successfully.", true);
						}
			}
		} else {
			$message	=	__("Please enter valid data.", true);
		}
		

		$response_data	=	array('status'=>$status,'tokenexpire'=>0,'message'=>$message,"draft_contest_id"=>$draft_contest_id);
		echo json_encode(array('response' => $response_data));
		die;
	}

	public function playerselected(){
		$status		=	false;
		$message	=	NULL;
		$data		=	[];
		$data1		=	(object) array();
		$data_row	=	file_get_contents("php://input");
		$decoded    =	json_decode($data_row, true);
				
		$playerResult=array();
		$this->loadModel('Users');
		$this->loadModel('ApiPlayers');
		$this->loadModel('Drafts');

		$userId = $this->getUserId();
		
		if(!empty($decoded['user_id'])){
			if($this->General->encrypt_decrypt('decrypt', $decoded['user_id'])){
				$decoded['user_id'] = $this->getDecryptUserId($decoded['user_id']);
			}else{
				$decoded['user_id'] = $decoded['user_id'];
			}
		}
		
		if (!empty($decoded['contest_id']) && !empty($decoded['user_id']) || !empty($decoded['draft_id'])) {
			$this->loadModel('PlayerTeams');
			$playerTeams = $this->PlayerTeams->find()
				->select(['player_data','draft_id'])
				->where(['user_id'=>$decoded['user_id'],'contest_id'=>$decoded['contest_id']])
				->first();
			if(!empty($decoded['draft_id'])){
				$playerTeams = $this->PlayerTeams->find()
				->select(['player_data','draft_id'])
				->where(['user_id'=>$decoded['user_id'],'contest_id'=>$decoded['contest_id'],'draft_id'=>$decoded['draft_id']])
				->first();
			}
			
			$users = $this->Users->find()
			->select(['full_name','profile_image','user_name'])
			->where(['id'=>$decoded['user_id']])
			->first();

			$draft_data = $this->Drafts->find()
			->select(['week','draft_data','season','seasontype'])
			->where(['id'=>$playerTeams['draft_id']])
			->first();
			//pr($draft_data);die;
			$draft_arr=json_decode($draft_data['draft_data'],true);
			
			$display_name=$player_rank=[];
			foreach($draft_arr as $dkey=>$dValue){
				$players_points=[];
				$display_name[$dValue['display_name']]=$dValue['display_name'];

				foreach($dValue['team_member'] as $key=>$value){
					$players_inf = $this->ApiPlayers->find()
							->select(['id','FantasyPoints','Position'])
							->where(['PlayerID'=> $value,'Week'=>$draft_data['week'],'Season'=>$draft_data['season'],'SeasonType'=>$draft_data['seasontype']] )
							->first();
					//pr($players_inf);die;
					$players_points[$value]=$players_inf['FantasyPoints'];
				}
				arsort($players_points);
				$player_rank[$dValue['display_name']]=$players_points;
			}
			//pr($player_rank);die;
			$final_points=[];
			foreach($player_rank as $tkey=>$tValue){
				$tmpPoint=$ranks=0;
				$pointsArr=[];
				foreach($tValue as $key=>$value){
					$pointOfTeam=$value;
						
						if($tmpPoint == $pointOfTeam) {
							$rank	=	$ranks;
						} else {
								$ranks++;
							$rank	=	$ranks;
						}
						$tmpPoint	=	$pointOfTeam;
						$pointsArr[$key]=$rank;	
				}
				$final_points[$tkey]=array("data"=>$pointsArr);
			}
			$unameData=!empty($users['user_name'])?$users['user_name']:$users['full_name'];
			$playerResult['user']=array('username'=>$unameData,'user_image'=>!empty($users['profile_image'])?U_IMAGE_PATH.$users['profile_image']:'');
			$playerData=json_decode($playerTeams['player_data'],true);
		
			if(!empty($playerData)){
				foreach($playerData as $key=>$value){
					
					$players_info = $this->ApiPlayers->find()
							->select(['id','Week','Team','Opponent','PlayerID','Name','Position','FantasyPoints','FantasyPointsPPR','image'])
							->where(['id'=> $value['apitable_id']] )
							->first();
					$rank=!empty($final_points[$value['team']]['data'][$value['player_id']])?$final_points[$value['team']]['data'][$value['player_id']]:0;
					if($rank==0){
						continue;
					}
					if(!empty($display_name[$value['team']])){
						$playerResult['player_data'][]=array("name"=>$players_info['Name'],"image"=>$players_info['image'],'position'=>$players_info['Position'],'display_position'=>$display_name[$value['team']],'opponent'=>$players_info['Opponent'],'projected_point'=>$players_info['FantasyPointsPPR'],'score'=>$players_info['FantasyPoints'],'rank'=>$rank);
					}
				}
			}
			$status=true;
		} else {
			$message	=	__("Please enter valid data.", true);
		}
		

		$response_data	=	array('status'=>$status,'tokenexpire'=>0,'message'=>$message,'data'=>$playerResult);
		echo json_encode(array('response' => $response_data));
		die;
	}

	public function editplayerselected() {
		$status		=	false;
		$message	=	'';
		$draft_data_new=$data		=	[];
		$data1		=	(object) array();
		$data_row	=	file_get_contents("php://input");
		$decoded    =	json_decode($data_row, true);
		$this->loadModel('PlayerTeams');
		
		
		if(!empty($decoded)) {
			if ( !empty($decoded['draft_contest_id']) && !empty($decoded['draftteam'])) {
				$this->loadModel('DraftContests');
				$userId = $this->getUserId();
				
				if(!empty($userId)) {

					$draft_info = $this->DraftContests->find()
					->select(['DraftContests.id','DraftContests.contest_id','Drafts.name','Drafts.week','Drafts.season','Drafts.seasontype','Drafts.startdate','Drafts.starttime','Drafts.enddate','Drafts.endtime','Drafts.draft_data','Drafts.id','Contest.category_id','Contest.contest_name','Contest.winning_amount','Contest.contest_size','Contest.contest_type','Contest.entry_fee'])
					->where(['Drafts.status'=>1,'DraftContests.id'=>$decoded['draft_contest_id']])
					->contain(['Drafts','Contest'=>['Category'=>['fields'=>['category_name']]]])
					->first();
					
					$draft_array=array();
					if( !empty($draft_info) ) {

						$week = 1;
						$player_id_array = [];
						$week = $draft_info->draft->week;
						$season = $draft_info->draft->season;
						$seasontype = $draft_info->draft->seasontype;
						$draft_data = json_decode($draft_info->draft->draft_data,true);
						
							if(!empty($draft_data)){
								foreach($draft_data AS $key => $value ){
									foreach($value AS $ikey => $ivalue ){
										if(is_array($ivalue)){
											foreach($ivalue AS $pkey => $pvalue ){
												$player_id_array[] = $pvalue;
											}
										}
									}
								}
							}
						
						$this->loadModel('ApiPlayers');
						$players_info = $this->ApiPlayers->find()
						->select(['id','Week','Team','Opponent','PlayerID','Name','Position','FantasyPoints','GameDate','FantasyPointsPPR','Played','Image'])
						->where(['PlayerID IN '=> $player_id_array, 'Week'=> $week,'SeasonType'=>$seasontype,'season'=>$season] )
						->toArray();

						$player_info_list = [];
						if(!empty($players_info)){
							foreach($players_info AS $key => $val){
								$player_info_list[$val->PlayerID] = $val;
							}
						}
						
						$draft_data_new = [];
						
							if(!empty($draft_data)){
								
								$playerTeams = $this->PlayerTeams->find()
												->select(['player_data'])
												->where(['user_id'=>$userId,'contest_id'=>$draft_info->contest_id,'draft_id'=>$draft_info->draft->id])
												->first();
								$playerData=json_decode($playerTeams['player_data'],true);
								
								$playerTableid=[];
								if(!empty($playerData)){
									foreach($playerData as $key=>$pValue){
										$playerTableid[$pValue['team']]=$pValue['apitable_id'];
									}
								}
								
								$player_teamget=array_keys($playerTableid);
								
								foreach($draft_data AS $key => $value ){
									if($value['display_name']==$decoded['draftteam']){

										foreach($value['team_member'] AS $ikey => $ivalue ){
									
											$player_id = $ivalue;
											if(!empty($player_info_list[$player_id])){
												$pinfo = $player_info_list[$player_id];
												$select_status=0;
												if(!empty($playerTableid[$value['display_name']]) && $playerTableid[$value['display_name']]==$pinfo->id){
													$select_status=1;
												}
												$draft_data_new[] = [
													'week'=> $pinfo->Week,
													'team'=> $pinfo->Team,
													'opponent'=> $pinfo->Opponent,
													'player_id'=> $pinfo->PlayerID,
													'apitable_id'=> $pinfo->id,
													'image'=> $pinfo->Image,
													'name'=> $pinfo->Name,
													'select_status'=>$select_status,
													'position'=> $pinfo->Position,
													'display_name'=>$value['display_name'],
													'projected_points'=> $pinfo->FantasyPointsPPR,
													'match_time'=> $pinfo->GameDate,
													'status'=> $pinfo->Played,
												];
											}
										}
									}
								}
							}
							
						$draft_array = [
							'winning_points'=>$draft_info->contest->winning_amount,
							'contest_size'=>$draft_info->contest->contest_size,
							'contest_type'=>$draft_info->contest->contest_type,
							'entry_points'=>$draft_info->contest->entry_fee,
							'draft_id'=>$draft_info->draft->id,
							'draft_name'=>$draft_info->draft->name,
							'week'=>$draft_info->draft->week,
							'start_date_time'=>date('d M,Y H:i',strtotime($draft_info->draft->startdate.' '.$draft_info->draft->starttime)),
							'end_date_time'=>date('d M,Y H:i',strtotime($draft_info->draft->enddate.' '.$draft_info->draft->endtime)),
							'draft_data'=> $draft_data_new,
							'category_name'=>$draft_info->category['category_name']
						];
						
					}
					$data1	=	$draft_array;
					$status	=	true;	
				} else {
					$message	=	__("User id is Empty.", true);
				}

			} else {
				$message	=	__('Draft ID and Draft Team Requried.',true);
			}
		} else {
			$message	=	__("You are not authenticated user.", true);
		}

		$response_data	=	array('status'=>$status, 'message'=>$message, 'data'=>$data1);
		echo json_encode(array('response' => $response_data));

		die;
	}

	public function userdata(){
        		
		$status		=	false;
		$message	=	NULL;
		$userData		=	[];
		$data1		=	(object) array();
		
		$this->loadModel('Users');
		$role_id=	Configure::read('ROLES.User');
        
        $limit	=	10;
		$query = $this->Users->find()
				->where(['role_id'=> $role_id] )
				->order(['winning_wallet'=>'DESC']);

			$queryCount = $this->Users->find()
				->where(['role_id'=> $role_id] )
				->order(['winning_wallet'=>'DESC'])->count();
			
			$number_of_page = ceil ($queryCount / $limit);  
			
				$Users = $this->paginate($query, [
					'limit'		=>	$limit,
					'order'		=>	['Users.winning_wallet'=>'DESC'],
					'group'		=>	['Users.id'],
					'contain'	=>	[]
				]);
				
				$tmpPoint=$ranks=0;
				foreach($Users as $key => $value){
					
					if(!empty($value)){
						$userData[]=[
							'Name'=>$value->user_name,
							'rank'=>$value->rank,
							'image'=>!empty($value->profile_image)?U_IMAGE_PATH.$value->profile_image:'',
							'username'=>$value->user_name,
							'winning_wallet'=>$value->winning_wallet,
						];		
					}
				}
			$status		=	true;
			$message	=	__("", true);

		$response_data	=	array('status'=>$status,'tokenexpire'=>0,'message'=>$message,'data'=>$userData,'number_of_page'=>$number_of_page);		
		echo json_encode(array('response' => $response_data));
		die;
	}

	
}
