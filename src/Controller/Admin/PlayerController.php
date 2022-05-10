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

class PlayerController  extends AppController {

	public function initialize() {
		parent::initialize();
		$this->loadComponent('Cookie');
	}
	
	public function index() {
		$player= TableRegistry::get('Player');
		$this->set('title_for_layout', __('Player List'));
		$limit	=	Configure::read('ADMIN_PAGE_LIMIT');
		 $query = $player->find()
		 			->contain(['Teams','Positions']);
		$result = $this->paginate($query, [
			'limit'		=>	$limit,
			// 'order'		=>	['Countries.id'=>'DESC'],
		]);
		// $result	=	$this->Contents->find('all')->where(['content_type'=>2])->toArray();
		$this->set(compact('result'));
	}
	
	public function edit($id= null) {
		$this->viewBuilder()->setLayout('ajax');
		$Player = TableRegistry::get('Player');
		$Positions = TableRegistry::get('Positions');
		$teams= TableRegistry::get('Teams');
		$eid = $_POST['eid'];
		$players	=	$Player->get($eid);
		$team = $teams->find('list',[
			'keyField' => 'id',
                'valueField' => ['name']
		])->toArray();

		$position = $Positions->find('list',[
			'keyField' => 'id',
                'valueField' => ['name']
		])->toArray();
		// pr($countryoption);die;
		if( $this->request->getData('action') == 'editsPlayer') {
			// pr($this->request->getData('editsTeam'));die;

			$eid = $this->request->getData('eid');
			$players	=	$Player->get($eid);
			$Player->patchEntity($players, $this->request->getData());
			$players->birthdate = date("Y-m-d", strtotime($this->request->getData('birthdate')));
			if($Player->save($players)) {
				$this->Flash->success(__('Player has been updated successfully.'));
				return $this->redirect(['action' => 'index']);
			}else{
				$this->Flash->error(__('Please correct errors listed as below'));
			}
		}
		$this->set(compact('players','team','position'));
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
