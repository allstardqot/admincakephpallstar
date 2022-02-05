<?php
/**
 *  HomesController
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

/**
 * Cron Controller
 *
 */

class  HomeController extends AppController {
	
	public function initialize() {
		parent::initialize();
		$this->Auth->allow();
		$this->loadModel('Contents');
		$this->loadModel('SubscribeUsers');
    }

	public function index() {


		echo '<div style="    text-transform: uppercase;
			text-align: center;
			margin-top: 75px;
			font-size: 35px;
			color: brown;">Welcome to LFG DRAFT</div>';die;


		$query = $this->request->query;
		$download = false;
		if( !empty($query['action']) && $query['action'] == 'download' ){
			$download = true;
		}
		$this->set(compact('download'));
		/* echo "<div style='font-size: 45px;
		text-align: center;
		margin-top: 110px;color: darkgoldenrod;'>Welcome to Lions11</div>";die; */
	}

	

	function downloadapk(){
		$file = ROOT.DS.'webroot'.DS.DOWNLOAD_APK_NAME;
		$file_name = DOWNLOAD_APK_NAME;
		//$mime_type = 'application/pdf';
		$mime_type = '';
		$this->download_file($file,$file_name,$mime_type);
	}


	public function page($slug) {
		$pagecontent	=	$this->Contents->find()->where(['slug'=>$slug])->first();
		if(empty($pagecontent)){
			$this->Flash->error(__('This page is not available.'));
			return $this->redirect(['controller' => 'Home', 'action' => 'index']);
		}

		require_once(ROOT . DS  . 'vendor' . DS  . 'Mobile-Detect-2.8.37' . DS .  'Mobile_Detect.php');
		$detect = new \Mobile_Detect();
		$app = 0;
		if ($detect->isMobile()){
			$app = 1;
		}

		$this->set(compact('pagecontent','app'));
	}

	public function pointSystem() {
		// point-system
		$this->viewBuilder()->layout(false);
		$this->loadModel('PointSystem');
		$this->loadModel('Contents');
		$t20data	=	$this->PointSystem->find()->where(['matchType'=>T20_MATCH])->first();
		$odidata	=	$this->PointSystem->find()->where(['matchType'=>ODI_MATCH])->first();
		$testdata	=	$this->PointSystem->find()->where(['matchType'=>TEST_MATCH])->first();
		$t10data	=	$this->PointSystem->find()->where(['matchType'=>T10_MATCH])->first();
		$content	=	$this->Contents->find()->where(['slug'=>'point-system'])->first();
		$this->set(compact('t20data','odidata','testdata','content','t10data'));
	}


	public function contactus() {
		$this->loadModel('SubscribeUsers');
		$subscribe	=	$this->SubscribeUsers->newEntity();

		$success = '';
		if ($this->request->is(['patch', 'post', 'put'])) {	
			//echo 'Contact';
			//pr($this->request->getData());die;
			$this->SubscribeUsers->patchEntity($subscribe, $this->request->getData(), ['validate'=>'Contact']);
			if (empty($subscribe->errors())) {
				$to			=	Configure::read('support_email');
				$email		=	$this->request->getData('email');
				$from		=	$email;
				$subject	=	'Contact Request';
				$message	=	$this->request->getData('message');
				$message	.=	"<br /><br /><b>Regards:</b><br /><b>Name:</b>".$this->request->getData('name')."<br /><b>Email:</b>:".$email;
				$success = 'Contact request sent successfully.';

				$this->sendMail($to, $subject, $message, $from);
				$this->Flash->success(__('Contact request sent successfully.'));
				return $this->redirect(['controller' => 'Home', 'action' => 'index']);
			} else {
				$error_msg = [];
                foreach( $subscribe->errors() as $errors){
                    if(is_array($errors)){
                        foreach($errors as $error){
                            $error_msg[]    =   $error;
                        }
                    }else{
                        $error_msg[]    =   $errors;
                    }
                }

                if(!empty($error_msg)){
                    $this->Flash->error(
                        __("Please fix the following error(s):".implode("<br/>", $error_msg))
                    ,['escape'=>false]);
                }
			}
		}
	}

	public function career() {
		$this->loadModel('Jobs');
		$jobs	=	$this->Jobs->find()->where(['status'=>1])->toarray();
		//pr($jobs);die;
		$this->set(compact('jobs'));
	}

	public function apply( $job_id ) {

		if( !$job_id ){
			return $this->redirect(['controller' => 'Home', 'action' => 'index']);
		}
		$this->loadModel('JobForms');
		$jobfrm	=	$this->JobForms->newEntity();
		if ($this->request->is(['patch', 'post', 'put'])) {	
			
			//pr($this->request->getData());die;
			$this->JobForms->patchEntity($jobfrm, $this->request->getData(), ['validate'=>'default']);
			if (empty($jobfrm->errors())) {
				
				if(isset($this->request->data['cv']) && !empty($this->request->data['cv']['name'])){
					$file	=	$this->request->data['cv'];
					if(!empty($file['name']) && $file['tmp_name'] != '' && $file['size'] > 0) {
						$this->Upload->upload($file, WWW_ROOT .'uploads/cv/' .DS, '');
						if (!empty($this->Upload->result) && empty($this->Upload->errors)) {
							$jobfrm['cv']	=	$this->Upload->result;
						}
					}
				}

				/* $to			=	Configure::read('support_email');
				$email		=	$this->request->getData('email');
				$from		=	$email;
				$subject	=	$this->request->getData('subject');
				$message	=	$this->request->getData('message') ;
				$message	.=	"<br /><br /><b>Regards:</b><br /><b>Name:</b>".$this->request->getData('name')."<br /><b>Email:</b>:".$email;
				$success = 'Contact request sent successfully.';
				$this->sendMail($to, $subject, $message, $from); */
				$this->JobForms->save($jobfrm);
				$this->Flash->success(__('Job apply request sent successfully.'));
				return $this->redirect(['controller' => 'Home', 'action' => 'index']);
			} else {
				//echo '<pre>';
				//print_r($jobfrm->errors());
				$error_msg = [];
                foreach( $jobfrm->errors() as $errors){
                    if(is_array($errors)){
                        foreach($errors as $error){
                            $error_msg[]    =   $error;
                        }
                    }else{
                        $error_msg[]    =   $errors;
                    }
                }

                if(!empty($error_msg)){
                    $this->Flash->error(
                        __("Please fix the following error(s):".implode("<br/>", $error_msg))
                    ,['escape'=>false]);
                }
			}
		}
		$this->set(compact('job_id','jobfrm'));
	}

	public function sendinvitelink() {
		$this->viewBuilder()->layout('home');
		if($this->request->is(['patch', 'post', 'put'])) {

			$mobile_number = $this->request->data['mobile_number'];
			$this->sendAppLink($mobile_number);
			$this->Flash->success(__('Link sent successfully.'));

			/* $this->loadModel('Users');
			$subuser	=	$this->SubscribeUsers->newEntity();
			$this->SubscribeUsers->patchEntity($subuser, $this->request->getData(), ['validate'=>'SendLink']);
            if (empty($subuser->errors())) {
				$subuser->created	=	date('Y-m-d H:i:s');
				$this->SubscribeUsers->save($subuser);
				$this->Flash->success(__('Thanks for subscribing, We will send you Application Download Link very soon.'));
            } else {
				$errors = $subuser->errors();
				$e_ary = [];
				foreach($errors AS $k => $error){
					if(is_array($error)){
						foreach($error AS $r => $e){
							$e_ary[] = $e;
						}
					} else {
						$e_ary[] = $error;
					}
				}
				$this->Flash->error(implode('<br/>',$e_ary),['escape'=>false]);
            } */
		}
		return $this->redirect(['controller' => 'Home', 'action' => 'index']);
	}

	public function newsletter() {
		$this->viewBuilder()->layout('home');
		if($this->request->is(['patch', 'post', 'put'])) {
			//echo 'Contact';
			//pr($this->request->getData());die;
			$this->loadModel('Users');
			$subuser	=	$this->SubscribeUsers->newEntity();
			$this->SubscribeUsers->patchEntity($subuser, $this->request->getData(), ['validate'=>'Subscribe']);
            if (empty($subuser->errors())) {
				//$mobile_number = $this->request->data['mobile_number'];
				//$subuser		=	$this->SubscribeUsers->newEntity();
				//$subuser->mobile_number	=	$mobile_number;
				$subuser->created	=	date('Y-m-d H:i:s');
				$this->SubscribeUsers->save($subuser);
				$this->Flash->success(__('Thanks for subscribe our newsletters.'));
				//$this->sendAppLink($mobile_number);
				//$this->Flash->success(__('Link sent successfully.'));
            } else {
				$errors = $subuser->errors();
				$e_ary = [];
				foreach($errors AS $k => $error){
					if(is_array($error)){
						foreach($error AS $r => $e){
							$e_ary[] = $e;
						}
					} else {
						$e_ary[] = $error;
					}
				}
				$this->Flash->error(implode('<br/>',$e_ary),['escape'=>false]);
                //$this->Flash->error(__('Please correct errors listed as below'));
            }
		}
		return $this->redirect(['controller' => 'Home', 'action' => 'index']);
	}
	
	function validate_mobile($mobile){
		return preg_match('/^[0-9]{10}+$/', $mobile);
	}
	
	public function invite(){
		$this->viewBuilder()->layout(false);
		error_reporting(0);
		$this->layout	=	false;
		$this->autoRender	=	false;

		$queryStr = $this->request->query;
		
		if(!empty($queryStr)){
			$refer_id = (isset($queryStr['refer_id'])) ? $queryStr['refer_id'] : '';
			$side = (isset($queryStr['s'])) ? $queryStr['s'] : '';
			if($refer_id){

				$replace_space = '-*-';
				$refer_id = str_replace($replace_space,' ',$refer_id);
				
				$this->loadModel('Invites');
				$ip = $_SERVER['REMOTE_ADDR'];
				$user_id		=	$this->Auth->user('id');
				$existUtd	=	$this->Invites->find()->select(['id'])->where(['ip'=>$ip])->first();
				if(!empty($existUtd)){
					$existUtd->modified	 =	date('Y-m-d H:i:s');
				} else {
					$existUtd	=	$this->Invites->newEntity();
					$existUtd->created	 =	date('Y-m-d H:i:s');
					$existUtd->user_id	 =	$user_id;
					$existUtd->ip		 =	$ip;
				}

				$existUtd->refer_id	 =	$refer_id;
				if( $side != '' ){
					$existUtd->side	= $side;
				}
				//pr($existUtd);die;
				$this->Invites->save($existUtd);
				unset($this->Invites->id);

				return $this->redirect(['action' => 'index', '?' => ['action'=>'download'] ]);

			} else {
				return $this->redirect(['action' => 'index']);
			}
		} else {
			return $this->redirect(['action' => 'index']);
		}
	}
	
}
