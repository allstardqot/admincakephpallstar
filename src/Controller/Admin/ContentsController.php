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


class ContentsController extends AppController {

	public function initialize() {
		parent::initialize();
		$this->loadComponent('Cookie');
	}
	
	public function index() {
		$this->set('title_for_layout', __('Contents List'));
		$limit	=	Configure::read('ADMIN_PAGE_LIMIT');
		$result = $this->paginate('Contents', [
			'limit'		=>	$limit,
			'order'		=>	['Contents.created'=>'DESC'],
		]);
		// $result	=	$this->Contents->find('all')->toArray();
		$this->set(compact('result'));
	}
	
	public function add() {
		$this->set('title_for_layout', __('Add Content'));
		$content	=	$this->Contents->newEntity();
		if($this->request->is(['patch', 'post', 'put'])) {
			$content	=	$this->Contents->patchEntity($content, $this->request->getData(), ['validate' => 'Default']);
			if(!$content->errors()) {
				$content->status	=	1;
				if($this->Contents->save($content)) {
					$this->Flash->success(__('Content has been added successfully.'));
					return $this->redirect(['action' => 'index']);
				}
			} else {
				$this->Flash->error(__('Please correct errors listed as below'));
			}
		}
		$this->set(compact('content'));
	}
	
	public function edit($id= null) {
		$this->set('title_for_layout', __('Edit Content'));
		$content	=	$this->Contents->get($id);
		if($this->request->is(['patch', 'post', 'put'])) {
			$content	=	$this->Contents->patchEntity($content, $this->request->getData(), ['validate' => 'Default']);
			if(!$content->errors()) {
				$title = trim($content->title);
				if($title != ''){
					$content->status	=	1;
					if($this->Contents->save($content)) {
						$this->Flash->success(__('Content has been updated successfully.'));
						return $this->redirect(['action' => 'index']);
					}
				}else{
					$this->Flash->error(__('Title not be blank.'));
				}
			} else {
				$this->Flash->error(__('Please correct errors listed as below'));
			}
		}
		$this->set(compact('content'));
	}
	
    public function status($id = NULL) {
        $result	=	$this->Contents->get($id);
        $status	=	($result->status == 0) ? 1 : 0;
        $result->status =	$status;
        if($this->Contents->save($result)) {
			$this->Flash->success(__('Content status has been changed'));
			return $this->redirect($this->referer());
        }
        $this->Flash->error(__('Content status could not be changed, please try again.'));
    }
	
	public function delete($id = null) {
		$result	=	$this->Contents->get($id);
		if($this->Contents->delete($result)) {
			$this->Flash->success(__('Content has been deleted.'));
			return $this->redirect($this->referer());
		}
		$this->Flash->error(__('Content could not be deleted, please try again.'));
	}
	
	public function view($id = null) {
		$this->set('title_for_layout', __('View Content'));
		$result	=	$this->Contents->get($id);
		$this->set(compact('result'));
	}
    

}
