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

class SubAdminsController extends AppController {

	public function initialize() {
		parent::initialize();
		$this->loadComponent('Cookie');
		$this->loadModel('Users');
	}
	
	public function index() {
		$this->set('title_for_layout', __('Sub Admin List'));
		$role_id=	Configure::read('ROLES.User');
		$limit	=	Configure::read('ADMIN_PAGE_LIMIT');
		$this->loadModel('Users');
		if($this->request->is('Ajax')) {
			$this->viewBuilder()->layout(false);
		}
		$reqData=$this->request->query;
		
		$condition='';
		if(!empty($reqData)){
			 //$condition=['OR'=>[['full_name'=>$reqData['full_name']],['email'=>$reqData['email']],['phone'=>$reqData['phone']],['created'=>$reqData['created']],['modified'=>$reqData['modified']]]];
			 if(!empty($reqData['user_name'])){
				 $condition=['user_name' => $reqData['user_name']];
			 }
		}
		if ( $this->request->is('post') && $this->request->getData('action') == 'addsubadmin'  ) { 
			$adminUser	=	$this->Users->newEntity();
			// pr($this->request->getData());die;
			$this->Users->patchEntity($adminUser, $this->request->getData());
			$adminUser->role_id = 2;
			$adminUser->user_name = $this->request->getData('first_name').' '.$this->request->getData('last_name');
			// pr($this->request->getData('password'));die;
			$adminUser->password = $this->request->getData('pass');
			// pr($adminUser);die;
			if($this->Users->save($adminUser)){
				$this->Flash->success(__('Admin has been added.'));
				return $this->redirect(['action' => 'index']);
			}

		}

		// pr($this->request->query);die;
		$query	=	$this->Users->find()->where([$condition,'Users.role_id' => 2]);
		
		$users = $this->paginate($query, ['limit' => $limit, 'order' => ['Users.id DESC']]);
		$this->set(compact('users'));
	}
	
	public function add() {
		$this->set('title_for_layout', __('Add Admin'));
		$this->loadModel('Users');
		$this->loadModel('EmailTemplates');
		$adminUser	=	$this->Users->newEntity();
		if ($this->request->is(['patch', 'post', 'put'])) {
			// $this->request->data['current_password']	=	$this->request->getData('password');
			$this->Users->patchEntity($adminUser, $this->request->getData());
			// if(empty($adminUser->errors())) {
				
				$adminUser->role_id	=	'2';
				$adminUser->status	=	1;
				$adminUser->created	=	date("Y-m-d h:i:s");
				$adminUser->modified=	date("Y-m-d h:i:s");
				if($result = $this->Users->save($adminUser)) {
					
					$this->Flash->success(__('Admin has been added.'));
					return $this->redirect(['action' => 'index']);
				}
				// $this->Flash->error(__('Admin could not be added, please try again.'));
		} else {
			$this->Flash->error(__('Please correct errors listed as below.'));
		}
		// }
		$this->set(compact('adminUser'));
	}
	
	public function edit() {
		$this->viewBuilder()->setLayout('ajax');
		$this->loadModel('Users');

		$eid = $_POST['eid'];
		// echo $eid;die;
		$adminUser	=	$this->Users->get($eid);
		// pr($this->request->getData('action'));die;
		if ( $this->request->getData('action') == 'editsubadmin' ) {
			$eid = $this->request->getData('eid');
			$adminUser	=	$this->Users->get($eid);
			$this->Users->patchEntity($adminUser, $this->request->getData());
			
			
			$adminUser->modified =	date("Y-m-d h:i:s");
			$adminUser->user_name = $this->request->getData('first_name').' '.$this->request->getData('last_name');
			if ($this->Users->save($adminUser)) {
				$this->Flash->success(__(' Admin has been updated'));
				return $this->redirect(['action' => 'index']);
			}
				// $this->Flash->error(__('Sub admin could not be added, please try again.'));
			// }else {
			// 	$this->Flash->error(__('Please correct errors listed as below.'));
			// }
		}
		$this->set(compact('adminUser'));
	}

	public function status($id = NULL) {
		$this->loadModel('Users');
		$user	=	$this->Users->get($id);
		$status	=	($user->status == 0) ? 1 : 0;
		$user->status = $status;
		if($this->Users->save($user)) {
			$this->Flash->success(__('Sub admin status has been changed.'));
			return $this->redirect($this->referer());
		}
		$this->Flash->error(__('Sab admin status could not be change, please try again.'));
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
		$this->set(compact('user'));
	}

	public function delete($id = null) {
		$this->loadModel('Users');
		if (isset($id) && !empty($id)) {
			$entity	=	$this->Users->get($id);
			if($this->Users->delete($entity)) {
				$this->Flash->success(__('Sub admin has been successfully deleted .'));
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error(__('Unable to delete sub admin, please try again.'));
				return $this->redirect(['action' => 'index']);
			}
		} else {
			$this->Flash->error(__('Unable to delete sub admin, please try again.'));
			return $this->redirect(['action' => 'index']);
		}
	}

}
