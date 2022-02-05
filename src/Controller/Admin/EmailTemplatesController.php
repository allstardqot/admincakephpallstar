<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SeriesController
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


class EmailTemplatesController extends AppController {

	public function initialize() {
		parent::initialize();
		$this->loadComponent('Cookie');
	}
	
	public function index() {
		$this->set('title_for_layout', __('Email Templates List'));
		$limit	=	Configure::read('ADMIN_PAGE_LIMIT');
		$result = $this->paginate('EmailTemplates', [
			'limit'		=>	$limit,
			'order'		=>	['EmailTemplates.created'=>'DESC'],
		]);
		$this->set(compact('result'));
	}
	
	public function add() {
		$this->set('title_for_layout', __('Add Email Template'));
		$email	=	$this->EmailTemplates->newEntity();
		if($this->request->is(['patch', 'post', 'put'])) {
			$email	=	$this->EmailTemplates->patchEntity($email, $this->request->getData(), ['validate' => 'Default']);
			if(!$email->errors()) {
				$email->status	=	1;
				if($this->EmailTemplates->save($email)) {
					$this->Flash->success(__('Email has been added successfully.'));
					return $this->redirect(['action' => 'index']);
				}
			} else {
				$this->Flash->error(__('Please correct errors listed as below'));
			}
		}
		$this->set(compact('email'));
	}
	
	public function edit($id= null) {
		$this->set('title_for_layout', __('edits Email Template'));
		// $email	=	$this->EmailTemplates->newEntity();
		$email	=	$this->EmailTemplates->get($id);
		$errorTemplate = '';
		if($this->request->is(['patch', 'post', 'put'])) {
			$email	=	$this->EmailTemplates->patchEntity($email, $this->request->getData(), ['validate' => 'Default']);
			if(!$email->errors()) {
				$template = trim($email->template);
				if($template !=''){
					$email->status	=	1;
					if($this->EmailTemplates->save($email)) {
						$this->Flash->success(__('Email has been updated successfully.'));
						return $this->redirect(['action' => 'index']);
					}
				} else {
					$this->Flash->error(__('Template could not be blank.'));
				}
			} else {
				$err = $email->errors();
				if(isset($err['template']))
				{
					$errorTemplate = $err['template']['_empty'];
				}
				$this->Flash->error(__('Please correct errors listed as below.'));
			}
		}
		$this->set(compact('email','errorTemplate'));
	}
	
    public function status($id = NULL) {
        $result	=	$this->EmailTemplates->get($id);
        $status	=	($result->status == 0) ? 1 : 0;
        $result->status =	$status;
        if($this->EmailTemplates->save($result)) {
			$this->Flash->success(__('Email template status has been changed'));
			return $this->redirect($this->referer());
        }
        $this->Flash->error(__('Email template status could not changed. please try again.'));
    }
	
	public function view($id = null) {
		$this->set('title_for_layout', __('View Email Template'));
		$result	=	$this->EmailTemplates->get($id);
		$this->set(compact('result'));
	}
    

}
