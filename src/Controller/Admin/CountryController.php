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

class CountryController  extends AppController {

	public function initialize() {
		parent::initialize();
		$this->loadComponent('Cookie');
	}
	
	public function index() {
		 $country= TableRegistry::get('Countries');
		$this->set('title_for_layout', __('Countries List'));
		$limit	=	Configure::read('ADMIN_PAGE_LIMIT');
		 $query = $country->find();
		$result = $this->paginate($query, [
			'limit'		=>	$limit,
			// 'order'		=>	['Countries.id'=>'DESC'],
		]);
		// $result	=	$this->Contents->find('all')->where(['content_type'=>2])->toArray();
		$this->set(compact('result'));
	}
	
	public function edit($id= null) {
		$this->viewBuilder()->setLayout('ajax');
		$Country = TableRegistry::get('Countries');
		$this->set('title_for_layout', __('Edit Faq Faq'));
		$eid = $_POST['eid'];
		$country	=	$Country->get($eid);
		if( $this->request->getData('action') == 'editsCountry') {
			//pr($Faq);pr($this->request->getData());die;
			$iso2 =$this->request->getData('iso2');
			$iso =$this->request->getData('iso');
			$eid = $this->request->getData('eid');
			$country	=	$Country->get($eid);
			$country	=	$Country->patchEntity($country, $this->request->getData());
			$country->extra = json_encode(['iso'=>$iso,'iso2'=>$iso2]);
 			if($Country->save($country)) {
				$this->Flash->success(__('Country has been updated successfully.'));
				return $this->redirect(['action' => 'index']);
			}
			
			
		}
		// pr($country);die;
		$this->set(compact('country'));
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
