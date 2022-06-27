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

class PoolController  extends AppController {

	public function initialize() {
		parent::initialize();
		$this->loadComponent('Cookie');
	}
	
	public function index() {
		 $team= TableRegistry::get('UserPool');
		$this->set('title_for_layout', __('Teams List'));
		$limit	=	Configure::read('ADMIN_PAGE_LIMIT');
		 $query = $team->find()
		 			->contain('Users');
		$result = $this->paginate($query, [
			'limit'		=>	$limit,
			// 'order'		=>	['Countries.id'=>'DESC'],
		]);
		// pr($result);die;
		// $result	=	$this->Contents->find('all')->where(['content_type'=>2])->toArray();
		$this->set(compact('result'));
	}
	
	public function edit($id= null) {
		$this->viewBuilder()->setLayout('ajax');
		$Teams = TableRegistry::get('Teams');
		$country = TableRegistry::get('Countries');
		$eid = $_POST['eid'];
		$team	=	$Teams->get($eid);
		$countryoption = $country->find('list',[
			'keyField' => 'id',
                'valueField' => ['name']
		])->toArray();
		// pr($countryoption);die;
		if( $this->request->getData('action') == 'editsTeam') {
			// pr($this->request->getData('editsTeam'));die;

			$eid = $this->request->getData('eid');
			$team	=	$Teams->get($eid);
			$Teams->patchEntity($team, $this->request->getData());
			if($Teams->save($team)) {
				$this->Flash->success(__('Team has been updated successfully.'));
				return $this->redirect(['action' => 'index']);
			}else{
				$this->Flash->error(__('Please correct errors listed as below'));
			}
		}
		$this->set(compact('team','countryoption'));
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
