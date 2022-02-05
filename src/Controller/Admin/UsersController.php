<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UsersController
 *
 * @author GayasuddinK
 */

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Cake\Routing\Router;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Cake\Collection\Collection;

class UsersController extends AppController {

    public function initialize() {
        parent::initialize();
        $this->Auth->allow(['forgotPassword','resetPassword']);
        $this->Auth->allow('resetForgotPassword');
        $this->loadComponent('Cookie');	
    }
	
	public function login() {
		$this->viewBuilder()->layout('admin_login');
		if($this->request->session()->check('Auth.Admin')) {
			return $this->redirect($this->Auth->redirectUrl());
		}
		$user	=	$this->Users->newEntity();
		if ($this->request->is('post')) {
			$user	=	$this->Users->newEntity();
			$user	=	$this->Users->patchEntity($user, $this->request->getData(), ['validate' => 'adminLogin']);
			//pr($user);die;
			if (empty($user->errors())) {
					$user	=	$this->Auth->identify();
					//echo "tttt";pr($user);die;
					if($user['role_id'] == 2) {
						$user['is_login']	=	1;
						$user['last_login']	=	date('Y-m-d H:i:s');
						$subAdmin	=	$this->Users->newEntity();
						$this->Users->patchEntity($subAdmin, $user);
						$this->Users->save($subAdmin);
					}
					if($user) {
						if ($this->request->data['remember'] == 1) {
							$cookie = array();
							$cookie['username'] = $this->request->data['email'];
							$cookie['password'] = $this->request->data['password'];
							$cookie['remember'] = $this->request->data['remember'];
							$this->Cookie->write('rememberMe', $cookie, true, "1 week");
							$cookie = $this->Cookie->read('rememberMe');
						} else {
							$this->Cookie->delete('rememberMe');
						}
						$this->Auth->setUser($user);
						return $this->redirect($this->Auth->redirectUrl());
					}
					// pr(__('Invalid email or password, try again'));die;
					$this->Flash->error(__('Invalid email or password, try again'));
					$cookie = $this->Cookie->read('rememberMe');

			} else {
				$this->Flash->error(__('Please correct errors listed below'));
			}
		}
		$cookie = $this->Cookie->read('rememberMe');
		if (!empty($cookie)) {
			if ($cookie['remember'] == 1) {
				$this->request->data['email'] = $cookie['username'];
				$this->request->data['password'] = $cookie['password'];
				$this->request->data['remember'] = $cookie['remember'];
			} else {
				$this->Cookie->delete('rememberMe');
			}
		}

		$this->set(compact('user'));
	}

    public function dashboard() {
		

		/* if($_SERVER['REMOTE_ADDR']=='103.82.80.173'){
			$date = '2019-05-26';
			$time = '18:30';
			echo strtotime("$date $time");
			echo '<br>';
			//echo time();
			echo $combinedDT = date('Y-m-d H:i:s', strtotime("$date $time"));
			die;
		} */

		$this->loadModel('Users');
		$authUser		=	$this->Auth->user();
		$subadminUser	=	$this->Users->find()->where(['role_id'=>2,'is_login'=>1])->toArray();
		$this->set(compact('authUser','subadminUser'));
    }

    public function logout() {
       // print_r($this->Auth->logout());die;
        return $this->redirect($this->Auth->logout());
    }

    public function userList() {

        $this->set('title_for_layout', __('Add User'));
        $user = $this->Users->newEntity();
         $this->set(compact('user'));

        
    }
	
	public function export() {
    	$this->loadModel('PenAadharCard');
    	$data = array();
    	$role_id=	Configure::read('ROLES.User');
    	$data = $this->Users->find()->where(['Users.role_id' => $role_id])->order(['Users.id'=>'DESC'])->group(['Users.id'])->contain(['PenAadharCard','BankDetails'])->toArray();

    	$header[] = 'S.No';
		$header[] = 'First Name';
		$header[] = 'Last Name';
		$header[] = 'Email';
		$header[] = 'Phone';
		$header[] = 'Gender';
		$header[] = 'City';
		$header[] = 'State';
		$header[] = 'Country';
		$header[] = 'Postal Code';
		$header[] = 'Cash Balance';
		$header[] = 'Winning Balance';
		$header[] = 'Bonus Amount';
		$header[] = 'Pan Card';
		//$header[] = 'Aadhar Card';
		$header[] = 'Account Number';
		$header[] = 'Ifsc Code';
		$header[] = 'Bank Name';
		$header[] = 'Branch';
		$header[] = 'Status';



		$delimiter = "\t";
		$filename = 'USERS_SHEET_'.time().'_' . date("Y-m-d") . ".xls";
		$count = 0;
		$count = 1;
		if (!empty($data)) {
			foreach ($data as $row) {
				$panAadhar = $this->PenAadharCard->find()->where(['user_id'=>$row->id])->select(['pan_card','aadhar_card'])->first();
				if(!empty($panAadhar)) {
					$panNumber  = $panAadhar['pan_card'];
					//$aadharCard = $panAadhar['aadhar_card'];
				}else{
					$panNumber  = '';
					//$aadharCard = '';
				}

				$accountNumber 	= !empty($row->bank_detail) ? $row->bank_detail->account_number : '';
				$ifscCode 		= !empty($row->bank_detail) ? $row->bank_detail->ifsc_code : '';
				$bankName 		= !empty($row->bank_detail) ? $row->bank_detail->bank_name : '';
				$branch 		= !empty($row->bank_detail) ? $row->bank_detail->branch : '';
				
				$dara_arr = array();
				$dara_arr['count'] 				= 	$count;
				$dara_arr['first_name']  		= 	$row->first_name;
				$dara_arr['last_name']  		= 	$row->last_name;
				$dara_arr['email'] 				=  	$row->email;
				$dara_arr['phone'] 				=  	$row->phone;
				$dara_arr['gender'] 			=  	$row->gender=='2'?'Female':'Male';
				$dara_arr['city'] 				=  	$row->city;
				$dara_arr['state'] 				=  	$row->state;
				$dara_arr['country'] 			=  	$row->country;
				$dara_arr['postal_code'] 		=  	$row->postal_code;
				$dara_arr['cash_balance'] 		=  	$row->cash_balance;
				$dara_arr['winning_balance'] 	=  	$row->winning_balance;
				$dara_arr['bonus_amount'] 		=  	$row->bonus_amount;
				$dara_arr['pan_number'] 		=  	$panNumber;
				//$dara_arr['aadhar_card'] 		=  	$aadharCard;
				$dara_arr['account_number'] 	=  	$accountNumber;
				$dara_arr['ifsc_code'] 			=  	$ifscCode;
				$dara_arr['bank_name'] 			=  	$bankName;
				$dara_arr['branch'] 			=  	$branch;
				$dara_arr['status'] 			=  	$row->gender=='0'?'Inactive':'Active';
				
				$globle_arr[] = $dara_arr;

				$count++;  
			}
			
			if(!empty($globle_arr)){
				$this->ExportExcel($header , $globle_arr , $filename);
			}
		}

    	
    	$this->set(compact('export','data'));
    	
    }
	
	public function add() {
        $this->set('title_for_layout', __('Add User'));
        $user = $this->Users->newEntity();
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData(), ['validate' => 'AddEditUser']);
            if (empty($user->errors())) {
                $user->role_id = '3';
                $user->status = 1;
                $user->refer_id = $this->Users->getReferId();
                $user->otp = $this->Users->getOTP();
				$user->created	=	date("Y-m-d H:i:s");
				$user->modified	=	date("Y-m-d H:i:s");
                if ($this->Users->save($user)) {
                    
                    $this->Flash->success(__('User has been added'));
                    return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error(__('User could not be added. Please, try again.'));
            } else {
                $this->Flash->error(__('Please correct errors listed as below.'));
            }
        }
        $this->set(compact('user'));

    }

    public function index() {
        if (!empty($this->request->query)) {
            $this->request->session()->write('sorting_query', $this->request->query);
        }
        $this->set('title_for_layout', __('List Users'));
        $this->request->session()->delete('sorting_query');
        if (!empty($this->request->query)) {
			$this->request->session()->write('sorting_query', $this->request->query);
        }
        $role_id=	Configure::read('ROLES.User');
        $limit	=	Configure::read('ADMIN_PAGE_LIMIT');
		
        if ($this->request->is('Ajax')) {
            $this->viewBuilder()->layout(false);
        }
	
		$reqData=$this->request->query;
		
		$condition='';
		if(!empty($reqData)){
			 //$condition=['OR'=>[['full_name'=>$reqData['full_name']],['email'=>$reqData['email']],['phone'=>$reqData['phone']],['created'=>$reqData['created']],['modified'=>$reqData['modified']]]];
			 if(!empty($reqData['full_name'])){
				 $condition=['full_name'=>$reqData['full_name']];
			 }
			 if(!empty($reqData['email'])){
				$condition=['email'=>$reqData['email']];
			}
			if(!empty($reqData['phone'])){
				$condition=['phone'=>$reqData['phone']];
			}
			if(!empty($reqData['created'])){
				$condition=['created'=>$reqData['created']];
			}
		}
	
        $query = $this->Users->find()->where([$condition,'Users.role_id' => $role_id,'Users.delete_status !='=>1]);

		if(isset($this->request->query) && $this->request->query('unverified') != '') {
			$unverified	=	$this->request->query('unverified');
		} else {
			$unverified	=	'';
		}
        
		if(isset($this->request->query['unverified']) && ($this->request->query['unverified']=='checked')){
        	//$query = $this->Users->find('search', ['search' => $this->request->query])->where(['Users.role_id' => $role_id,'OR'=>['PenAadharCard.is_verified'=>INACTIVE,'BankDetails.is_verified'=>INACTIVE]])->contain(['PenAadharCard','BankDetails']);
        	$query = $this->Users->find('search', ['search' => $this->request->query])->where([$condition,'Users.role_id' => $role_id,'Users.delete_status !='=>1,'Users.role_id' => $role_id,'OR'=>['PenAadharCard.is_verified'=>INACTIVE,'BankDetails.is_verified'=>INACTIVE]])->contain(['PenAadharCard','BankDetails']);
        }
		

        $users = $this->paginate($query, [
			'limit'		=>	$limit,
			'order'		=>	['Users.id'=>'DESC'],
			'group'		=>	['Users.id'],
			'contain'	=>	[]
		]);
		//pr($users);die;

		if(isset($this->request->params['?']['page'])){
			$page = $this->request->params['?']['page'];
		}else{
			$page = '';
		}
		
        $this->set(compact('users','page','unverified'));
    }

	public function userdetail() {
        if (!empty($this->request->query)) {
            $this->request->session()->write('sorting_query', $this->request->query);
        }
        $this->set('title_for_layout', __('List Users'));
        $this->request->session()->delete('sorting_query');
        if (!empty($this->request->query)) {
			$this->request->session()->write('sorting_query', $this->request->query);
        }
        $role_id=	Configure::read('ROLES.User');
        $limit	=	Configure::read('ADMIN_PAGE_LIMIT');
		
        if ($this->request->is('Ajax')) {
            $this->viewBuilder()->layout(false);
        }
	
		$reqData=$this->request->query;
		$condition='';
		if(!empty($reqData)){
			 //$condition=['OR'=>[['full_name'=>$reqData['full_name']],['email'=>$reqData['email']],['phone'=>$reqData['phone']],['created'=>$reqData['created']],['modified'=>$reqData['modified']]]];
			 if(!empty($reqData['full_name'])){
				 $condition=['full_name'=>$reqData['full_name']];
			 }
			 if(!empty($reqData['email'])){
				$condition=['email'=>$reqData['email']];
			}
			if(!empty($reqData['phone'])){
				$condition=['phone'=>$reqData['phone']];
			}
			if(!empty($reqData['created'])){
				$condition=['created'=>$reqData['created']];
			}
		}
	
        $query = $this->Users->find()->where([$condition,'Users.role_id' => $role_id,'Users.delete_status !='=>1]);

		if(isset($this->request->query) && $this->request->query('unverified') != '') {
			$unverified	=	$this->request->query('unverified');
		} else {
			$unverified	=	'';
		}
        
		if(isset($this->request->query['unverified']) && ($this->request->query['unverified']=='checked')){
        	//$query = $this->Users->find('search', ['search' => $this->request->query])->where(['Users.role_id' => $role_id,'OR'=>['PenAadharCard.is_verified'=>INACTIVE,'BankDetails.is_verified'=>INACTIVE]])->contain(['PenAadharCard','BankDetails']);
        	$query = $this->Users->find('search', ['search' => $this->request->query])->where([$condition,'Users.role_id' => $role_id,'Users.delete_status !='=>1,'Users.role_id' => $role_id,'OR'=>['PenAadharCard.is_verified'=>INACTIVE,'BankDetails.is_verified'=>INACTIVE]])->contain(['PenAadharCard','BankDetails']);
        }
		

        $users = $this->paginate($query, [
			'limit'		=>	$limit,
			'order'		=>	['Users.winning_wallet'=>'DESC'],
			'group'		=>	['Users.id'],
			'contain'	=>	[]
		]);
		//pr($users);die;

		if(isset($this->request->params['?']['page'])){
			$page = $this->request->params['?']['page'];
		}else{
			$page = '';
		}
		
        $this->set(compact('users','page','unverified'));
    }

	public function subscriber() {
        if (!empty($this->request->query)) {
            $this->request->session()->write('sorting_query', $this->request->query);
        }
        $this->set('title_for_layout', __('Subscriber List'));
       
		
        $role_id=	Configure::read('ROLES.User');
        $limit	=	Configure::read('ADMIN_PAGE_LIMIT');
		
        $this->loadModel('SubscribeUsers');
		
        $query = $this->SubscribeUsers->find()->where();
		
        $users = $this->paginate($query, [
			'limit'		=>	$limit,
			'order'		=>	['SubscribeUsers.created'=>'DESC'],
		]);
		if(isset($this->request->params['?']['page'])){
			$page = $this->request->params['?']['page'];
		}else{
			$page = '';
		}
		
		//pr($users);die;
        $this->set(compact('users'));
    }

    public function status($id = NULL) {
        $user = $this->Users->get($id);
        $status = ($user->status == 0) ? 1 : 0;
        $user->status = $status;
        //pr($user);die;
        if ($this->Users->save($user)) {
            $this->Flash->success(__('User status has been changed'));
            return $this->redirect($this->referer());
        }
        $this->Flash->error(__('User status could not be changed, please try again.'));
    }

	public function approvestatus($id = NULL) {
        $user = $this->Users->get($id);
		
        $approve_status = ($user->approve_status == 0) ? 1 : 0;
        $user->approve_status = $approve_status;
		if($approve_status==1){
			$msg="Your account with user name ".$user->user_name." is approved please use your login details to enter the contest.";
			$this->sendSmsMsg($msg,$user->phone,$user->country_code);	// send SMS
		}
        //pr($user);die;
        if ($this->Users->save($user)) {
            $this->Flash->success(__('User approved status has been changed'));
            return $this->redirect($this->referer());
        }
        $this->Flash->error(__('User status could not be changed, please try again.'));
    }

    public function edit($id = NULL) {
        $this->set('title_for_layout', __('Edit User'));
        try {
            $user = $this->Users->get($id);
        } catch (\Throwable $e) {
            $this->Flash->error(__('Invaild attempt.'));
            return $this->redirect(['action' => 'index']);
        } catch (\Exception $e) {
            $this->Flash->error(__('Invaild attempt.'));
            return $this->redirect(['action' => 'index']);
        }
        if ($this->request->is(['patch', 'post', 'put'])) {
			
			$getData=$this->request->getData();
			if(!empty($getData['ch_password'])){
				$getData['password']=$getData['ch_password'];
			}
			//pr($getData);die;
            $user = $this->Users->patchEntity($user, $getData, ['validate' => 'AddEditUser']);
            if (empty($user->errors())) {
				$user->modified	=	date("Y-m-d H:i:s");
                if ($this->Users->save($user)) {
                    $this->Flash->success(__('User has been updated'));
                    return $this->redirect(['action' => 'index', '?' => $this->request->session()->read('sorting_query')]);
                }
                $this->Flash->error(__('User could not be added, please try again.'));
            } else {
                $this->Flash->error(__('Please correct errors listed as below.'));
            }
        }
        $this->set(compact('user'));
    }

    public function detail($id = NULL) {
        $this->set('title_for_layout', __('User Detail'));
        try {
            $user = $this->Users->get($id);

        } catch (\Throwable $e) {
            $this->Flash->error(__('Invaild attempt.'));
            return $this->redirect(['action' => 'index']);
        } catch (\Exception $e) {
            $this->Flash->error(__('Invaild attempt.'));
            return $this->redirect(['action' => 'index']);
        }
        //$history = $this->getSeriesRankingAdmin($id);
		$history='';
        //$this->loadModel('ReferalCodeDetails');
        //$friends = $this->ReferalCodeDetails->find()->where(['refered_by'=>$id])->count();
		$friends='';
        //pr($user);die;
        $this->set(compact('user','history','friends'));

    }

    public function delete($id = null) {
        if (isset($id) && !empty($id))
        {
            $entity = $this->Users->get($id);
			$entity->delete_status=1;
            if($this->Users->save($entity)) {
                $this->Flash->success(__('User has been successfully deleted.'));
                return $this->redirect(['action' => 'index']);
            }else
            {
                $this->Flash->error(__('Unable to delete User, please try again.'));
                return $this->redirect(['action' => 'index']);
            }
        }else
        {
            $this->Flash->error(__('Unable to delete User, please try again.'));
            return $this->redirect(['action' => 'index']);
        }
    }
	
	public function forgotPassword() {
		$this->viewBuilder()->layout('admin_login');
		if($this->request->session()->check('Auth.Admin')) {
			return $this->redirect($this->Auth->redirectUrl());
		}
		$this->loadModel('EmailTemplates');
		if($this->request->is(['post'])) {
			$email		=	$this->request->getData('email');
			if(!empty($email)) {
				$userData	=	$this->Users->find()->where(['email LIKE'=>$email,'role_id IN'=>[1,2],'status'=>ACTIVE])->first();
				if(!empty($userData)) {
					$template	=	$this->EmailTemplates->find()->where(['subject'=>'forgot_password'])->first();
					
					$to			=	$email;
					$from		=	Configure::read('Admin.setting.admin_email');
					$subject	=	$template->email_name;
					$validString=	time().base64_encode($email);
					$resetUrl	=	SITE_URL.'admin/users/reset-password/'.$validString;
					$resetLink	=	'<a href="'.$resetUrl.'">Click Here</a>';
					$message	=	str_replace(['{{user}}','{{link}}'],[$userData->first_name,$resetLink],$template->template);
					$this->sendMail($to, $subject, $message, $from);
					// if($this->sendMail($to, $subject, $message, $from)) {
						$userData->verify_string	=	$validString;
						$this->Users->save($userData);
						$this->Flash->success(__('Reset link has been sent on your email, please check your mail to reset password.',true));
					// } else {
						// $this->Flash->error(__('Could not send email.',true));
					// }
					$this->redirect(['controller'=>'users','action'=>'login']);
				} else {
					$this->Flash->error(__('Email does not exists.',true));
				}
			} else {
				$this->Flash->error(__('Email is required.',true));
			}
			
		}
	}
	
	public function resetPassword($validString = null) {
		$this->viewBuilder()->layout('admin_login');
		if(empty($validString)) {
			$this->Flash->error(__('Invalid reset url',true));
			$this->redirect(['controller'=>'users','action'=>'login']);
		}
		$user	=	$this->Users->find()->where(['verify_string LIKE'=>$validString,'role_id IN'=>[1,2],'status'=>ACTIVE])->select(['verify_string'])->first();
		if(empty($user)) {
			$this->Flash->error(__('Invalid reset url',true));
			$this->redirect(['controller'=>'users','action'=>'login']);
		}
		if($this->request->session()->check('Auth.Admin')) {
			return $this->redirect($this->Auth->redirectUrl());
		}
		$userData = '';
		$this->loadModel('EmailTemplates');
		if($this->request->is(['post','put','patch']))  {
			// $email		=	$validString;
			$userData	=	$this->Users->find()->where(['verify_string LIKE'=>$validString,'role_id IN'=>[1,2],'status'=>ACTIVE])->first();
			if(!empty($userData)) {
				$this->Users->patchEntity($userData,$this->request->getData(), ['validate'=>'AdminResetPassword']);
				if(!$userData->getErrors())  {
					$userData->verify_string	=	'';
					if($this->Users->save($userData))  {
						$this->Flash->success(__('Password has been reset successfully.',true));
						$this->redirect(['controller'=>'users','action'=>'login']);
					}
				} else {
					$this->Flash->error(__('Correct below detail.',true));
				}
				
			}
		}
		$this->set(compact('userData'));
	}
	
	public function changePassword() {
		$this->set('title_for_layput','Change Password');
		$session	=	$this->request->session();
		$authUserId	=	$session->read('Auth.Admin.id');
		$user		=	$this->Users->get($authUserId);
		if($this->request->is(['post','put','patch'])) {
			$user		=	$this->Users->get($authUserId);
			$this->Users->patchEntity($user, $this->request->getData(), ['validate'=>'adminChangePassword']);
			if(!$user->getErrors()) {
				if($this->Users->save($user)) {
					$this->Flash->success(__('Password has been changed successfully.',true));
					$this->redirect(['controller'=>'users','action'=>'changePassword']);
				}
			} else {
				$this->Flash->error(__('Please correct errors listed as below.'));
			}
		}
		$this->set(compact('authUser','user'));
	}
	
	public function profile() {
		$this->set('title_for_layput','Change Password');
		$session	=	$this->request->session();
		$authUser	=	$session->read('Auth.Admin.id');
		$user		=	$this->Users->get($authUser);
		if($this->request->is(['post','put'])) {
			$this->Users->patchEntity($user, $this->request->getData(), ['validate'=>'updateProfile']);
			if(!$user->getErrors()) {
				if($this->Users->save($user)) {
					$this->Flash->success(__('Profile has been updated successfully.',true));
					$this->redirect(['controller'=>'users','action'=>'profile']);
				}
			} else {
				$this->Flash->error(__('Please correct errors listed as below.'));
			}
		}
		$this->set(compact('user'));
	}
	
	public function verifyAccountEmail($verifyStr = null) {
		$this->loadModel('Users');
		$user	=	$this->Users->find()->where(['verify_string'=>$verifyStr,'status'=>ACTIVE])->first();
		if(!empty($user)) {
			$user->verify_string	=	'';
			$user->email_verified	=	true;
			$this->Users->save($user);
			$this->Flash->success('Your account verified successfully.');
			$this->redirect(['controller'=>'Users','action'=>'login']);
		} else {
			$this->Flash->error('Verification link is not valid.');
			$this->redirect(['controller'=>'Users','action'=>'login']);
		}
	}

	public function panDetail() {
		$this->viewBuilder()->layout(false);
		$userId	=	$this->request->data['uset_id'];
		$type	=	$this->request->data['type'];
		$page	=	$this->request->data['page'];
		$this->set(compact('userId','type','page'));
	}

	public function addPanDetail() {
		$this->viewBuilder()->layout(false);
		$this->loadModel('PenAadharCard');

		$action	=	$this->request->data['action'];

		if( $action == 'addPanDetail' || $action == 'savePandDetail' ){
			$pan = $this->PenAadharCard->newEntity();
			$userId	=	$this->request->data['uset_id'];
			$type	=	$this->request->data['type'];
			$page	=	$this->request->data['page'];
		} else {
			$type	=	'pan_card';
			$page	=	'';
			$userId	=	(isset($this->request->data['user_id'])) ? $this->request->data['user_id'] : $this->request->data['uset_id'] ;
			$pan	=	$this->PenAadharCard->find()->where(['user_id'=>$userId])->first();
			$pan->date_of_birth	=	date('Y-m-d',strtotime($pan->date_of_birth));
		}

		
        if ($this->request->is(['patch', 'post', 'put']) && $action != 'addPanDetail' && $action != 'editPanDetail' ) {

			$pan = $this->PenAadharCard->patchEntity($pan, $this->request->getData());

			if( !empty($this->request->getData('pan_image_upload') )) {
				$file		=	$this->request->getData('pan_image_upload');
				if(!empty($file['name'])){
					$fileArr	=	explode('.',$file['name']);
					$ext		=	end($fileArr);
					$fileName	=	time().$userId.'.'.$ext;
					$filePath	=	$filePath	=	WWW_ROOT .'uploads/pan_image/'.$fileName;
					move_uploaded_file($file['tmp_name'],$filePath);
					if(!empty( $pan->pan_image )){
						unlink( WWW_ROOT .'uploads/pan_image/'.$pan->pan_image );
					}
					$pan->pan_image	=	$fileName;
				}
			}
			
            if (empty($pan->errors())) {
                $pan->user_id = $userId;
                $pan->is_verified = 1;
				
				if( $action =='updatePandDetail' ){
					$pan->modified	=	date("Y-m-d H:i:s");
				} else {
					$pan->created	=	date("Y-m-d H:i:s");
				}
				
				//pr($pan);die;
                if ($this->PenAadharCard->save($pan)) {
					
					if( $action =='updatePandDetail' ){
						$this->Flash->success(__('Pan Detail has been updated'));
					} else {
						$this->Flash->success(__('Pan Detail has been added'));
					}
                    return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error(__('Pan Detail could not be added/Updated. Please, try again.'));
            } else {
                $this->Flash->error(__('Please correct errors listed as below.'));
            }
		}
		
		$this->set(compact('pan','userId','type','page','action'));
	}

	public function verifyPan($userId = null,$page=null) {
		$this->viewBuilder()->layout();
		$this->loadModel('PenAadharCard');
		$userId	=	$this->request->data['user_id'];
		$user	=	$this->PenAadharCard->find()->where(['user_id'=>$userId])->first();
		if(!empty($user)) {
			if($user->is_verified == false) {
				$user->is_verified	=	true;
				$this->loadModel('Users');
				$usersData	=	$this->Users->find()->where(['id'=>$userId])->first();
				if($this->PenAadharCard->save($user)) {
					$user_id     	=   $usersData->id;
					$deviceType     =   $usersData->device_type;
					$deviceToken    =   $usersData->device_id;
					$notiType       =   '10';
					
					$title = 'Verified  Pan Detail';
					$notification = 'Your  pan detail has been verified.';
					if(($deviceType=='Android') && ($deviceToken!='')){
						$this->sendNotificationFCM($user_id,$notiType,$deviceToken,$title,$notification,'');
					} 
					if(($deviceType=='iphone') && ($deviceToken!='') && ($deviceToken!='device_id')){
						$this->sendNotificationAPNS($user_id,$notiType,$deviceToken,$title,$notification,'');
					}
					$this->Flash->success(__('Pan detail updated successfully.',true));
				} else {
					$this->Flash->error(__('Pan card detail could not update.',true));
				}
			} else {
				$this->Flash->error(__('Pan card detail already verified.',true));
			}
		} else {
			$this->Flash->error(__('Pan card detail does not exists',true));
		}
		// $this->redirect(['controller'=>'users','action'=>'index?page='.$page.'']);
		// $this->Flash->error(__('Pan card detail does not exists',true));
		exit;
	}
	
	public function cancelPan($userId = null,$page=null) {
		$this->viewBuilder()->layout();
		$this->loadModel('PenAadharCard');
		
		$userId	=	$this->request->data['user_id'];
		$user	=	$this->PenAadharCard->find()->where(['user_id'=>$userId])->first();
		if(!empty($user)) {
			if($user->is_verified == false) {
				$user->is_verified	=	2;
				$this->loadModel('Users');
				$usersData	=	$this->Users->find()->where(['id'=>$userId])->first();
				if($this->PenAadharCard->save($user)) {
					$user_id     	=   $usersData->id;
					$deviceType     =   $usersData->device_type;
					$deviceToken    =   $usersData->device_id;
					$notiType       =   '10';
					
					$title = 'Cancel Bank Detail';
					$notification = 'Your pan detail has been cancelled, please update again.';
					if(($deviceType=='Android') && ($deviceToken!='')){
						$this->sendNotificationFCM($user_id,$notiType,$deviceToken,$title,$notification,'');
					} 
					if(($deviceType=='iphone') && ($deviceToken!='') && ($deviceToken!='device_id')){
						$this->sendNotificationAPNS($user_id,$notiType,$deviceToken,$title,$notification,'');
					}
					$this->Flash->success(__('Pan detail cancelled successfully.',true));
				} else {
					$this->Flash->error(__('Pan card detail could not cancel.',true));
				}
			} else {
				$this->Flash->error(__('Pan card detail already cancelled.',true));
			}
		} else {
			$this->Flash->error(__('Pan card detail does not exists',true));
		}
		// $this->redirect(['controller'=>'users','action'=>'index?page='.$page.'']);
		exit;
	}


	
	public function addBankDetail() {
		$this->viewBuilder()->layout(false);
		$this->loadModel('BankDetails');

		$action	=	$this->request->data['action'];

		if( $action == 'addBankDetail' || $action == 'saveBankDetail' ){
			$bank = $this->BankDetails->newEntity();
			$userId	=	$this->request->data['uset_id'];
			$type	=	$this->request->data['type'];
			$page	=	$this->request->data['page'];
		} else {
			$type	=	'bank_detail';
			$page	=	'';
			$userId	=	(isset($this->request->data['user_id'])) ? $this->request->data['user_id'] : $this->request->data['uset_id'] ;
			$bank	=	$this->BankDetails->find()->where(['user_id'=>$userId])->first();
		}

		
        if ($this->request->is(['patch', 'post', 'put']) && $action != 'addBankDetail' && $action != 'editBankDetail' ) {

			$bank = $this->BankDetails->patchEntity($bank, $this->request->getData());

			if( !empty($this->request->getData('bank_image_upload') )) {
				$file		=	$this->request->getData('bank_image_upload');
				if(!empty($file['name'])){
					$fileArr	=	explode('.',$file['name']);
					$ext		=	end($fileArr);
					$fileName	=	time().$userId.'.'.$ext;
					$filePath	=	$filePath	=	WWW_ROOT .'uploads/bank_proof/'.$fileName;
					move_uploaded_file($file['tmp_name'],$filePath);
					if(!empty( $bank->bank_image )){
						unlink( WWW_ROOT .'uploads/bank_proof/'.$bank->bank_image );
					}
					$bank->bank_image	=	$fileName;
				}
			}
			
            if (empty($bank->errors())) {
                $bank->user_id = $userId;
                $bank->is_verified = 1;
				
				if( $action =='updateBankDetail' ){
					$bank->modified	=	date("Y-m-d H:i:s");
				} else {
					$bank->created	=	date("Y-m-d H:i:s");
				}
				
				//pr($bank);die;
                if ($this->BankDetails->save($bank)) {
					
					if( $action =='updateBankDetail' ){
						$this->Flash->success(__('Bank Detail has been updated'));
					} else {
						$this->Flash->success(__('Bank Detail has been added'));
					}
                    return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error(__('Bank Detail could not be added/Updated. Please, try again.'));
            } else {
                $this->Flash->error(__('Please correct errors listed as below.'));
            }
		}
		
		$this->set(compact('bank','userId','type','page','action'));
    }
	
	public function verifyBank($userId = null,$page=null) {
		$this->viewBuilder()->layout();
		$this->loadModel('BankDetails');
		$userId	=	$this->request->data['user_id'];
		$user	=	$this->BankDetails->find()->where(['user_id'=>$userId])->first();
		if(!empty($user)) {
			if($user->is_verified == false) {
				$user->is_verified	=	true;
				$this->loadModel('Users');
				$usersData	=	$this->Users->find()->where(['id'=>$userId])->first();
				if($this->BankDetails->save($user)) {
					$user_id     	=   $usersData->id;
					$deviceType     =   $usersData->device_type;
					$deviceToken    =   $usersData->device_id;
					$notiType       =   '10';
					
					$title = 'Verified Bank Detail';
					$notification = 'Your bank detail has been verified.';
					if(($deviceType=='Android') && ($deviceToken!='')){
						$this->sendNotificationFCM($user_id,$notiType,$deviceToken,$title,$notification,'');
					} 
					if(($deviceType=='iphone') && ($deviceToken!='') && ($deviceToken!='device_id')){
						$this->sendNotificationAPNS($user_id,$notiType,$deviceToken,$title,$notification,'');
					}
					$this->Flash->success(__('Bank Detail updated successfully.',true));
				} else {
					$this->Flash->error(__('Bank detail could not update.',true));
				}
			} else {
				$this->Flash->error(__('Bank detail already verified.',true));
			}
		} else {
			$this->Flash->error(__('Bank detail does not exists',true));
		}
		// $this->redirect(['controller'=>'users','action'=>'index?page='.$page.'']);
		exit;
	}
	
	public function cancelBank($userId = null,$page=null) {
		$this->viewBuilder()->layout();
		$this->loadModel('BankDetails');
		$userId	=	$this->request->data['user_id'];
		$user	=	$this->BankDetails->find()->where(['user_id'=>$userId])->first();
		if(!empty($user)) {
			if($user->is_verified == false) {
				$user->is_verified	=	2;
				$this->loadModel('Users');
				$usersData	=	$this->Users->find()->where(['id'=>$userId])->first();
				if($this->BankDetails->save($user)) {
					$user_id     	=   $usersData->id;
					$deviceType     =   $usersData->device_type;
					$deviceToken    =   $usersData->device_id;
					$notiType       =   '10';
					
					$title = 'Cancel Bank Detail';
					$notification = 'Your bank detail has been cancelled, please update again.';
					if(($deviceType=='Android') && ($deviceToken!='')){
						$this->sendNotificationFCM($user_id,$notiType,$deviceToken,$title,$notification,'');
					} 
					if(($deviceType=='iphone') && ($deviceToken!='') && ($deviceToken!='device_id')){
						$this->sendNotificationAPNS($user_id,$notiType,$deviceToken,$title,$notification,'');
					}
					$this->Flash->success(__('Bank detail cancelled successfully.',true));
				} else {
					$this->Flash->error(__('Bank detail could not cancel.',true));
				}
			} else {
				$this->Flash->error(__('Bank detail already cancelled.',true));
			}
		} else {
			$this->Flash->error(__('Bank detail does not exists',true));
		}
		// $this->redirect(['controller'=>'users','action'=>'index?page='.$page.'']);
		exit;
	}

	public function addAadharDetail() {
		$this->viewBuilder()->layout(false);
		$this->loadModel('AadharCard');

		$action	=	$this->request->data['action'];

		if( $action == 'addAadharDetail' || $action == 'saveAadharDetail' ){
			$bank = $this->AadharCard->newEntity();
			$userId	=	$this->request->data['uset_id'];
			$type	=	$this->request->data['type'];
			$page	=	$this->request->data['page'];
		} else {
			$type	=	'aadhar_detail';
			$page	=	'';
			$userId	=	(isset($this->request->data['user_id'])) ? $this->request->data['user_id'] : $this->request->data['uset_id'] ;
			$bank	=	$this->AadharCard->find()->where(['user_id'=>$userId])->first();
		}

		
        if ($this->request->is(['patch', 'post', 'put']) && $action != 'addAadharDetail' && $action != 'editAadharDetail' ) {

			$bank = $this->AadharCard->patchEntity($bank, $this->request->getData());

			if( !empty($this->request->getData('aadhar_image_upload') )) {
				$file		=	$this->request->getData('aadhar_image_upload');
				if(!empty($file['name'])){
					$fileArr	=	explode('.',$file['name']);
					$ext		=	end($fileArr);
					$fileName	=	time().$userId.'.'.$ext;
					$filePath	=	$filePath	=	WWW_ROOT .'uploads/pan_image/'.$fileName;
					move_uploaded_file($file['tmp_name'],$filePath);
					if(!empty( $bank->image )){
						unlink( WWW_ROOT .'uploads/pan_image/'.$bank->image );
					}
					$bank->image	=	$fileName;
				}
			}
			
            if (empty($bank->errors())) {
                $bank->user_id = $userId;
                $bank->is_verified = 1;
				
				if( $action =='updateAadharDetail' ){
					$bank->modified	=	date("Y-m-d H:i:s");
				} else {
					$bank->created	=	date("Y-m-d H:i:s");
				}
				
				//pr($bank);die;
                if ($this->AadharCard->save($bank)) {
					
					if( $action =='updateAadharDetail' ){
						$this->Flash->success(__('Aadhar Detail has been updated'));
					} else {
						$this->Flash->success(__('Aadhar Detail has been added'));
					}
                    return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error(__('Aadhar Detail could not be added/Updated. Please, try again.'));
            } else {
                $this->Flash->error(__('Please correct errors listed as below.'));
            }
		}
		
		$this->set(compact('bank','userId','type','page','action'));
    }
	
	public function verifyAadhar($userId = null,$page=null) {
		$this->viewBuilder()->layout();
		$this->loadModel('AadharCard');
		$userId	=	$this->request->data['user_id'];
		$user	=	$this->AadharCard->find()->where(['user_id'=>$userId])->first();
		if(!empty($user)) {
			if($user->is_verified == false) {
				$user->is_verified	=	true;
				$this->loadModel('Users');
				$usersData	=	$this->Users->find()->where(['id'=>$userId])->first();
				if($this->AadharCard->save($user)) {
					$user_id     	=   $usersData->id;
					$deviceType     =   $usersData->device_type;
					$deviceToken    =   $usersData->device_id;
					$notiType       =   '10';
					
					$title = 'Verified Address ID Detail';
					$notification = 'Your Address ID has been verified.';
					if(($deviceType=='Android') && ($deviceToken!='')){
						$this->sendNotificationFCM($user_id,$notiType,$deviceToken,$title,$notification,'');
					} 
					if(($deviceType=='iphone') && ($deviceToken!='') && ($deviceToken!='device_id')){
						$this->sendNotificationAPNS($user_id,$notiType,$deviceToken,$title,$notification,'');
					}
					$this->Flash->success(__('Address ID updated successfully.',true));
				} else {
					$this->Flash->error(__('Address ID could not update.',true));
				}
			} else {
				$this->Flash->error(__('Address ID already verified.',true));
			}
		} else {
			$this->Flash->error(__('Address ID does not exists',true));
		}
		// $this->redirect(['controller'=>'users','action'=>'index?page='.$page.'']);
		exit;
	}
	
	public function cancelAadhar($userId = null,$page=null) {
		$this->viewBuilder()->layout();
		$this->loadModel('AadharCard');
		$userId	=	$this->request->data['user_id'];
		$user	=	$this->AadharCard->find()->where(['user_id'=>$userId])->first();
		if(!empty($user)) {
			if($user->is_verified == false) {
				$user->is_verified	=	2;
				$this->loadModel('Users');
				$usersData	=	$this->Users->find()->where(['id'=>$userId])->first();
				if($this->AadharCard->save($user)) {
					$user_id     	=   $usersData->id;
					$deviceType     =   $usersData->device_type;
					$deviceToken    =   $usersData->device_id;
					$notiType       =   '10';
					
					$title = 'Cancel Address ID';
					$notification = 'Your Address ID has been cancelled, please update again.';
					if(($deviceType=='Android') && ($deviceToken!='')){
						$this->sendNotificationFCM($user_id,$notiType,$deviceToken,$title,$notification,'');
					} 
					if(($deviceType=='iphone') && ($deviceToken!='') && ($deviceToken!='device_id')){
						$this->sendNotificationAPNS($user_id,$notiType,$deviceToken,$title,$notification,'');
					}
					$this->Flash->success(__('Address ID cancelled successfully.',true));
				} else {
					$this->Flash->error(__('Address ID could not cancel.',true));
				}
			} else {
				$this->Flash->error(__('Address ID already cancelled.',true));
			}
		} else {
			$this->Flash->error(__('Address ID does not exists',true));
		}
		// $this->redirect(['controller'=>'users','action'=>'index?page='.$page.'']);
		exit;
	}
	
}
