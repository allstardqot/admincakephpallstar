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
		if($this->request->is('Ajax')) {
			$this->viewBuilder()->layout(false);
		}
		
		$query	=	$this->Users->find('search', ['search' => $this->request->query])->where(['Users.role_id' => 2]);
		
		$users = $this->paginate($query, ['limit' => $limit, 'order' => ['Users.id DESC']]);
		$this->set(compact('users'));
	}
	
	public function add() {
		$this->set('title_for_layout', __('Add Sub Admin'));
		$this->loadModel('Users');
		$this->loadModel('EmailTemplates');
		$adminUser	=	$this->Users->newEntity();
		if ($this->request->is(['patch', 'post', 'put'])) {
			$this->request->data['current_password']	=	$this->request->getData('password');
			$this->Users->patchEntity($adminUser, $this->request->getData(), ['validate' => 'AddSubAdmin']);
			if(empty($adminUser->errors())) {
				if($this->request->getData('image')['name']) {
					$file	=	$this->request->getData('image');
					$fileArr	=	explode('.',$file['name']);
					$ext		=	end($fileArr);
					$fileName	=	'user_'.time().'.'.$ext;
					$filePath	=	WWW_ROOT .'uploads/users/'.$fileName;
					if(move_uploaded_file($file['tmp_name'],$filePath)) {
						$adminUser->image	=	$fileName;
					}
				} else {
					$adminUser->image	=	$adminUser->image;
				}
				$adminUser->role_id	=	'2';
				$adminUser->status	=	1;
				$adminUser->created	=	date("Y-m-d h:i:s");
				$adminUser->modified=	date("Y-m-d h:i:s");
				if($result = $this->Users->save($adminUser)) {
					if(!empty($result)) {
						$emailTemplate	=	$this->get_email_template('sub_admin_user');
						$to		=	$result->email;
						$from		=	Configure::read('Admin.setting.admin_email');	
						$subject=	$emailTemplate->email_name;
						$message=	str_replace(['{{user}}','{{email}}','{{password}}'],[$adminUser->first_name,$adminUser->email,$adminUser->current_password],$emailTemplate->template);
						$this->sendMail($to, $subject, $message, $from);
					}
					$this->Flash->success(__('Sub admin has been added.'));
					return $this->redirect(['action' => 'index']);
				}
				$this->Flash->error(__('Sub admin could not be added, please try again.'));
			} else {
				$this->Flash->error(__('Please correct errors listed as below.'));
			}
		}
		$this->set(compact('adminUser'));
	}
	
	public function edit($id = NULL) {
		$this->set('title_for_layout', __('Edit User'));
		$this->loadModel('Users');
		try {
			$adminUser	=	$this->Users->get($id);
		} catch (\Throwable $e) {
			$this->Flash->error(__('Invaild attempt.'));
			return $this->redirect(['action' => 'index']);
		} catch (\Exception $e) {
			$this->Flash->error(__('Invaild attempt.'));
			return $this->redirect(['action' => 'index']);
		}
		if ($this->request->is(['patch', 'post', 'put'])) {
			$this->Users->patchEntity($adminUser, $this->request->getData(), ['validate' => 'EditSubAdmin']);
			if (empty($adminUser->errors())) {
				if($this->request->getData('image')['name']) {
					$file	=	$this->request->getData('image');
					$fileArr	=	explode('.',$file['name']);
					$ext		=	end($fileArr);
					$fileName	=	'user_'.time().'.'.$ext;
					$filePath	=	WWW_ROOT .'uploads/users/'.$fileName;
					if(move_uploaded_file($file['tmp_name'],$filePath)) {
						$adminUser->image	=	$fileName;
					}
				}
				$adminUser->modified=	date("Y-m-d h:i:s");
				if($adminUser->password == ''){
					unset($adminUser->password);
				}
				if($adminUser->image == ''){
					unset($adminUser->image);
				}
				if ($this->Users->save($adminUser)) {
					$this->Flash->success(__('Sub Admin has been updated'));
					return $this->redirect(['action' => 'index']);
				}
				$this->Flash->error(__('Sub admin could not be added, please try again.'));
			} else {
				$this->Flash->error(__('Please correct errors listed as below.'));
			}
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
