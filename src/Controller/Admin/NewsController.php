<?php

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Cake\Routing\Router;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Cake\Collection\Collection;
use Cake\ORM\TableRegistry;

class NewsController extends AppController {

	public function initialize() {
		parent::initialize();
		$this->loadComponent('Cookie');
	}
	
	public function index() {
		 $news = TableRegistry::get('News');
		$this->set('title_for_layout', __('News List'));
		$limit	=	Configure::read('ADMIN_PAGE_LIMIT');
		 $query = $news->find();
		$result = $this->paginate($query, [
			'limit'		=>	$limit,
		]);
		// $result	=	$this->Contents->find('all')->where(['content_type'=>2])->toArray();
		$this->set(compact('result'));
	}
	
	public function add() {
		$this->set('title_for_layout', __('Add Faq'));
		$content	=	$this->Contents->newEntity();
		if($this->request->is(['patch', 'post', 'put'])) {
			$content	=	$this->Contents->patchEntity($content, $this->request->getData(), ['validate' => 'Default']);
			if(!$content->errors()) {
				$content->status	=	1;
				$content->content_type	=	2;
				if($this->Contents->save($content)) {
					$this->Flash->success(__('Faq Content has been added successfully.'));
					return $this->redirect(['action' => 'index']);
				}
			} else {
				$this->Flash->error(__('Please correct errors listed as below'));
			}
		}
		$this->set(compact('content'));
	}
	
	public function edit($id= null) {
		$Faqs = TableRegistry::get('faqs');
		$this->set('title_for_layout', __('Edit Faq Faq'));
		$faq	=	$Faqs->get($id);
		if($this->request->is(['patch', 'post', 'put'])) {
			//pr($Faq);pr($this->request->getData());die;
			$faq	=	$Faqs->patchEntity($faq, $this->request->getData(), ['validate' => 'Default']);
			if(!$faq->errors()) {
				$title = trim($faq->title);
				if($title != ''){
					$faq->status	=	1;
					if($Faqs->save($faq)) {
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
		$this->set(compact('faq'));
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
		$Faq = TableRegistry::get('faqs');
		$result	=	$Faq->get($id);
		$this->set(compact('result'));
	}
    

}
