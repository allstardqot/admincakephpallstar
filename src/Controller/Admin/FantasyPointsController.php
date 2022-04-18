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

class FantasyPointsController extends AppController {

	public function initialize() {
		parent::initialize();
		$this->loadComponent('Cookie');
		$this->loadModel('Users');
	}
	
	public function index() {
		$this->set('title_for_layout', __('Sub Admin List'));
		$role_id=	Configure::read('ROLES.User');
		$limit	=	Configure::read('ADMIN_PAGE_LIMIT');
		$this->loadModel('FantasyPoints');
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
		

		// pr($this->request->query);die;
		$query	=	$this->FantasyPoints->find();
		
		$points = $this->paginate($query, ['limit' => $limit]);
        // pr($points);die;
		$this->set(compact('points'));
	}
	
	
	
	public function edit() {
		$this->viewBuilder()->setLayout('ajax');
		$this->loadModel('FantasyPoints');

		$eid = $_POST['eid'];
		// echo $eid;die;
		$userPoint	=	$this->FantasyPoints->get($eid);
		// pr($this->request->getData('action'));die;
		if ( $this->request->getData('action') == 'editpoint' ) {
			$eid = $this->request->getData('eid');
			$fantasypoint	=	$this->FantasyPoints->get($eid);
			$this->FantasyPoints->patchEntity($fantasypoint, $this->request->getData());
			if ($this->FantasyPoints->save($fantasypoint)) {
				$this->Flash->success(__(' Point has been updated'));
				return $this->redirect(['action' => 'index']);
			}
				// $this->Flash->error(__('Sub admin could not be added, please try again.'));
			// }else {
			// 	$this->Flash->error(__('Please correct errors listed as below.'));
			// }
		}
		$this->set(compact('userPoint'));
	}

	
	public function detail($id = NULL) {
		$this->set('title_for_layout', __('User Detail'));
		
		// $this->set(compact('user'));
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
