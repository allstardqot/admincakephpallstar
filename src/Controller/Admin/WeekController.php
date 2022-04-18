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

class WeekController extends AppController {

	public function initialize() {
		parent::initialize();
		$this->loadComponent('Cookie');
        $this->loadModel('Week');
	}
	
	public function index() {
		 $Week = TableRegistry::get('Week');
		$this->set('title_for_layout', __('Week List'));
		$limit	=	Configure::read('ADMIN_PAGE_LIMIT');
		 $query = $Week->find();
// pr($this->request->getData('action'));die;
        if($this->request->getData('action') == 'addWeek'){
            $week	=	$this->Week->newEntity();
			
			$this->Week->patchEntity($week, $this->request->getData());
            $week->starting_at = date('Y-m-d',strtotime($this->request->getData('starting_at')));
            $week->ending_at   = date('Y-m-d',strtotime($this->request->getData('ending_at')));
			if($this->Week->save($week)){
                // pr($this->request->getData());die;
				$this->Flash->success(__('Week has been added.'));
				return $this->redirect(['action' => 'index']);
			}
         }
		$result = $this->paginate($query, [
			'limit'		=>	$limit,
		]);
		// $result	=	$this->Week->find('all')->where(['week_type'=>2])->toArray();
		$this->set(compact('result'));
	}
	
	
	
	public function edit($id= null) {
		$this->viewBuilder()->setLayout('ajax');
		$Week = TableRegistry::get('Week');
		$eid = $_POST['eid'];
        // echo $eid;die;
		$weeks	=	$Week->get($eid);
		
        // pr()
		// pr($weeks);die;
		if( $this->request->getData('action') == 'editsWeek') {
			// pr($this->request->getData('editsTeam'));die;

			$eid = $this->request->getData('eid');
			$weeks	=	$Week->get($eid);
            $weeks->starting_at = date('Y-m-d',strtotime($this->request->getData('starting_at')));
            $weeks->ending_at   = date('Y-m-d',strtotime($this->request->getData('ending_at')));
			if($Week->save($weeks)) {
				$this->Flash->success(__('Week has been updated successfully.'));
				return $this->redirect(['action' => 'index']);
			}else{
				$this->Flash->error(__('Please correct errors listed as below'));
			}
		}
		$this->set(compact('weeks'));
	}
	
    public function status($id = NULL) {
        $result	=	$this->Week->get($id);
        $status	=	($result->status == 0) ? 1 : 0;
        $result->status =	$status;
        if($this->Week->save($result)) {
			$this->Flash->success(__('week status has been changed'));
			return $this->redirect($this->referer());
        }
        $this->Flash->error(__('week status could not be changed, please try again.'));
    }
	
	public function delete($id = null) {
		$result	=	$this->Week->get($id);
		if($this->Week->delete($result)) {
			$this->Flash->success(__('week has been deleted.'));
			return $this->redirect($this->referer());
		}
		$this->Flash->error(__('week could not be deleted, please try again.'));
	}
	
	
    

}
