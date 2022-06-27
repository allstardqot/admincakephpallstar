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
use Cake\ORM\TableRegistry;
// use Cake\Auth\WeakPasswordHasher;
use Cake\Utility\Security;
use Cake\Auth\DefaultPasswordHasher;

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


		if ($this->request->is('post') && $this->request->getData('action') == 'resestpass' ) { 
               
			$settingsTable = $this->loadModel('Users');
			$sub_id        = $this->request->getData('sub_id');
			// pr($sub_id);die;

			$settings = $this->Users->get($sub_id);
			if ($this->request->is(['post', 'put'])) {

				// $hasher = new DefaultPasswordHasher();
				
				// $settingsTable = $this->Users->get('Users');
				// $settings = $this->Users->get($sub_id);
				$this->Users->patchEntity($settings,$this->request->getData(), ['validate' => true]);
				// $settings->password = password_hash($this->request->getData('password'),PASSWORD_DEFAULT);
				$errros = $settings->getErrors();

				$pass_error = 'Password not updated.';
				if(!empty($errros['confirm_password'])){
					// pr($errros);die;
					$pass_error = $errros['confirm_password']['match'];
				}

				if($this->Users->save($settings)){
					$this->Flash->success(__('Password has been updated.'));
					return $this->redirect(['action' => 'index']);
				}else{
					$this->Flash->error(__($pass_error));
					return $this->redirect(['action' => 'index']);
				}
        	}
			
			
			// if($this->BankDetails->save($bank_data)){
			// 	$this->Flash->success('Bank has been added successfully.');
			// 	return $this->redirect(['action' => 'index']);
			// }	
		}
	
		$reqData=$this->request->query;
		
		$condition='';
		if(!empty($reqData)){
			 //$condition=['OR'=>[['full_name'=>$reqData['full_name']],['email'=>$reqData['email']],['phone'=>$reqData['phone']],['created'=>$reqData['created']],['modified'=>$reqData['modified']]]];
			 if(!empty($reqData['user_name'])){
				 $condition=['user_name'=>$reqData['user_name']];
			 }
			 
		}
			// pr($condition);die;
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

    public function edit() {
		$this->viewBuilder()->setLayout('ajax');
		$this->loadModel('Users');
		$this->loadModel('Payment');

		$eid = $_POST['eid'];
		// echo $eid;die;
		$adminUser	=	$this->Users->get($eid);
		
		if ( $this->request->getData('action') == 'edituser' ) {
			$eid = $this->request->getData('eid');
			$adminUser	=	$this->Users->get($eid);
			$this->Users->patchEntity($adminUser, $this->request->getData());
			
			if ($this->Users->save($adminUser)) {
				$hidden_wallet = $this->request->getData('hidden_wallet');
				$wallet = $this->request->getData('wallet');
				$type  ='';
				
				if($hidden_wallet < $wallet){
					$wallet = $wallet-$hidden_wallet;
					$type = 'ADMIN ADDED';
				}else{
					$type = 'ADMIN DEDUCT';
					$wallet = $hidden_wallet-$wallet;
				}
				$Payment = $this->Payment->newEntity();
				// $Payment = $this->Payment->patchEntity($Payment);
				$Payment->user_id = $eid;
				$Payment->amount = $wallet;
				//$Payment->status = 1;

				$Payment->transaction_id = uniqid();
				$Payment->type = $type;
				$this->Payment->save($Payment);
				// $Payment->transaction_id = $eid;
				// $Payment->transaction_id = $eid;


				$this->Flash->success(__(' User has been updated'));
				return $this->redirect(['action' => 'index']);
			}
				
		}
		$this->set(compact('adminUser'));
    }

	public function view($id=null){

		$this->set('title_for_layout', __('Users Pool'));
		$this->loadModel('UserPool');
		$query = $this->UserPool->find()->where(['user_id'=>$id])->contain('Users')->toArray();

		
        $this->set(compact('query'));

	}

	public function viewcontest($id=null){
		$this->viewBuilder()->setLayout('ajax');
		$contest = TableRegistry::getTableLocator()->get('user_contests');
		$teams = TableRegistry::getTableLocator()->get('user_teams');
		$players = TableRegistry::getTableLocator()->get('Player');
		$id = $_POST['eid'];
		$innerHtml =''; 
		$usercontest = $contest->find()->where(['pool_id'=>$id])->first();
		if(!empty($usercontest)){
			$userteams   = $teams->get($usercontest->id);

			$palyerId = json_decode($userteams->players,true);
			$player   = $players->find()->where(['Player.id IN'=>$palyerId])->contain('Positions')->toArray();
			// pr($palyerId,true));die;
		
	
			
			foreach($player as $data){
				$innerHtml =  '<div class="player-data">
				<table class="table">
					<tbody>
						<tr>
							<td>
								<div class="user-detail-card">
								
									<img src="'.$data->image_path.'" width="80" class="img-responsive" alt="">
									<div class="contents">
									<h5>'.$data->position->name.'</h5>
									<small>'.$data->fullname.'</small>
									</div>

								</div>
							</td>
						</tr>
						
						
					</tbody>
				</table>
			</div>';
				
			}
				
				// <div><img src="'.$data->image_path.'" alt="" id="img"></div><div><span id="playerName">'.$data->fullname.'</span></div>';
				echo $innerHtml;
			

		}else{
			$innerHtml =  '<div class="player-data">
				<table class="table">
					<tbody>
						<tr>
							<td>
								<div class="user-detail-card">
								
									
									
									<h5>Plz Join Team In This Pool</h5>
									

								</div>
							</td>
						</tr>
						
						
					</tbody>
				</table>
			</div>';
				
				
				
				// <div><img src="'.$data->image_path.'" alt="" id="img"></div><div><span id="playerName">'.$data->fullname.'</span></div>';
				echo $innerHtml;
		}
			die;


		
		// exit();
	}

	public function transection($id=null){
		$this->set('title_for_layout', __('User Transection'));
		$this->loadModel('Payment');
		$query = $this->Payment->find()->where(['user_id'=>$id])->contain('Users')->toArray();

		
        $this->set(compact('query'));
	}

	public function enquiry(){
		$this->set('title_for_layout', __('Enquiry List'));
		$limit = 10;
		$enquiry  = TableRegistry::getTableLocator()->get('enquiry_details');

		$query = $enquiry->find();

        $users = $this->paginate($query, [
			'limit'		=>	$limit,
			
		]);
		//pr($users);die;

		if(isset($this->request->params['?']['page'])){
			$page = $this->request->params['?']['page'];
		}else{
			$page = '';
		}
		
        $this->set(compact('users','page'));

	}

	public function enquirydelete($id){
		$enquiry  = TableRegistry::getTableLocator()->get('enquiry_details');

		$result	=	$enquiry->get($id);
		if($enquiry->delete($result)) {
			$this->Flash->success(__('Enquiry has been deleted.'));
			return $this->redirect($this->referer());
		}
		$this->Flash->error(__('Enquiry could not be deleted, please try again.'));
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
		$authUser		=	$this->Auth->user();
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
				$user->user_name = $this->request->getData('first_name').' '.$this->request->getData('last_name');
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
	
	public function usertransection(){
		if (!empty($this->request->query)) {
            $this->request->session()->write('sortingquery', $this->request->query);
        }
		
		$this->set('title_for_layout', __('User Transection'));
		$this->loadModel('Payment');
		$this->request->session()->delete('sortingquery');
		$condition='';
        if (!empty($this->request->query)) {
			// pr($reqData);die;
			$this->request->session()->write('sortingquery', $this->request->query);
        }
		$limit	=	Configure::read('ADMIN_PAGE_LIMIT');

		$reqData=$this->request->query;
		if(!empty($reqData)){
			// pr($reqData);die;
			//$condition=['OR'=>[['full_name'=>$reqData['full_name']],['email'=>$reqData['email']],['phone'=>$reqData['phone']],['created'=>$reqData['created']],['modified'=>$reqData['modified']]]];
			if(!empty($reqData['user_name'])){
				$condition=['Users.user_name LIKE'=>$reqData['user_name']];
			}
			
	   }
		// pr
		
		$query = $this->Payment->find()->where($condition)->contain('Users');

		if(isset($this->request->query) && $this->request->query('unverified') != '') {
			$unverified	=	$this->request->query('unverified');
		} else {
			$unverified	=	'';
		}

		if(isset($this->request->query['unverified']) && ($this->request->query['unverified']=='checked')){
        
        	$query = $this->Payment->find('search', ['search' => $this->request->query])->where($condition)->contain('Users');
        }
		

		$users = $this->paginate($query, [
			'limit'		=>	$limit,
			
		]);
		//pr($users);die;

		if(isset($this->request->params['?']['page'])){
			$page = $this->request->params['?']['page'];
		}else{
			$page = '';
		}
		
		$this->set(compact('users','page','unverified'));

	}
	
}
