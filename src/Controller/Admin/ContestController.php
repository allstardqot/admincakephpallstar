<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CategoryController
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


class ContestController extends AppController {

	public function initialize() {
		parent::initialize();
		$this->loadComponent('Cookie');		
	}

	public function index() {
		if (!empty($this->request->query)) {
			$this->request->session()->write('sorting_query', $this->request->query);
		}
		$this->set('title_for_layout', __('List Contest'));
		$this->request->session()->delete('sorting_query');
		if (!empty($this->request->query)) {

			$this->request->session()->write('sorting_query', $this->request->query);
		}
		$limit = Configure::read('ADMIN_PAGE_LIMIT');

		if ($this->request->is('Ajax')) {
			$this->viewBuilder()->layout(false);
		}

		$query = $this->Contest->find('search', ['search' => $this->request->query])->where(['Contest.is_archive' => 0]);
		

		if(isset($this->request->params['?']['status'])){
            $pc = $this->request->params['?']['status'];
            if($pc=='deactive'){
            	$query = $this->Contest->find('search', ['search' => $this->request->query])->where(['Contest.status' => '0']);
            }
            if($pc=='active'){
            	$query = $this->Contest->find('search', ['search' => $this->request->query])->where(['Contest.status' => '1']);
            }
            if($pc=='new'){
            	$date    =   date('Y-m-d');
            	$query = $this->Contest->find('search', ['search' => $this->request->query])->where(['Contest.status' => '1','DATE(Contest.created)' => $date]);
            }
        }

        $contest = $this->paginate($query, ['conditions'=>['is_auto_create'=>1],'limit' => $limit, 'order' => ['Contest.created'=>'DESC']]);
		
		$this->loadModel('Category');
		$categoryList	=	$this->Category->find('list', ['keyField'=>'id','valueField'=>'category_name'])->where(['Category.status'=>ACTIVE])->toArray();
		$this->set(compact('contest','categoryList'));
	}

	public function add() {
		$this->set('title_for_layout', __('Add Contest'));
		$contest	=	$this->Contest->newEntity();
		$response	=	array();

		$this->loadModel('Category');
		$errorCategory = '';
		$list	=	$this->Category->find('list', ['keyField'=>'id','valueField'=>'category_name'])->where(['Category.status'=>ACTIVE])->toArray();
		if ($this->request->is(['patch', 'post', 'put'])) {
			//pr($this->request->getData());die;
			$this->Contest->patchEntity($contest, $this->request->getData(), ['validate' => 'AddEditContest']);
			if(empty($contest->errors())) {
				$contest['created']	=	date('Y-m-d H:i:s');
				
				if($this->Contest->save($contest)) {
					$id	=	$contest->id;
					return $this->redirect(['action' => 'price',$id]);
				}
				$this->Flash->error(__('Contest could not be added, please try again.'));
			} else {
				$this->Flash->error(__('Please correct errors listed as below.'));
			}
		}
		$this->set(compact('contest','list','errorCategory'));
	}

	

	public function status($id = NULL) {
		$contest = $this->Contest->get($id);
		$status = ($contest->status == 0) ? 1 : 0;
		$contest->status = $status;
		//pr($contest);die;
		if ($this->Contest->save($contest)) {
			$this->Flash->success(__('Contest status has been changed'));
			return $this->redirect($this->referer());
		}
		$this->Flash->error(__('Contest status could not be changed, please try again.'));
	}

	public function archive($id = NULL) {
		$contest = $this->Contest->get($id);
		$is_archive = ($contest->is_archive == 0) ? 1 : 0;
		$contest->is_archive = $is_archive;
		if ($this->Contest->save($contest)) {
			$this->Flash->success(__('Contest archived successfully.'));
			return $this->redirect($this->referer());
		}
		$this->Flash->error(__('There is some error, please try again.'));
	}

	public function detail($id = NULL) {
		$this->set('title_for_layout', __('Contest Detail'));
		try {
			$contest = $this->Contest->get($id);

		} catch (\Throwable $e) {
			$this->Flash->error(__('Invaild attempt.'));
			return $this->redirect(['action' => 'index']);
		} catch (\Exception $e) {
			$this->Flash->error(__('Invaild attempt.'));
			return $this->redirect(['action' => 'index']);
		}

		$this->loadModel('Category');
		$this->loadModel('CustomBreakup');
		$response = array();
		$list = $this->Category->find('all',array('conditions'=>array('Category.id'=>$contest->category_id)))->first();
		$priceBreak = $this->CustomBreakup->find('all',array('conditions'=>array('contest_id'=>$id)))->toArray();

		$this->set(compact('contest','list','priceBreak'));

	}
	
	public function delete($id = null) {
		$this->loadModel('MatchContest');		
		if (isset($id) && !empty($id)) {
			$entity = $this->Contest->get($id);
			$match = $this->MatchContest->find()->where(['contest_id'=>$entity['id']])->first();

			if($match==''){
				if($this->Contest->delete($entity)) {
					$this->Flash->success(__('Contest has been successfully deleted.'));
					return $this->redirect(['action' => 'index']);
				}else
				{
					$this->Flash->error(__('Unable to delete contest, please try again.'));
					return $this->redirect(['action' => 'index']);
				}
			}else{
				$this->Flash->error(__('Unable to delete contest, this contest is used in some matches.'));
				return $this->redirect(['action' => 'index']);
			}
		} else {
			$this->Flash->error(__('Unable to delete contest, please try again.'));
			return $this->redirect(['action' => 'index']);
		}
	}

	public function price($contest_id = NULL) {
		$this->loadModel('Contest');
		$this->loadModel('CustomBreakup');
		$contest	=	$this->Contest->find('all',array('conditions'=>array('Contest.id'=>$contest_id)))->first();
		
		$this->set('title_for_layout', __('Add Price BreakDown'));
		$data	=	$this->Contest->newEntity();
		if($this->request->is(['patch', 'post', 'put'])) {
			
				$data	=	$this->Contest->patchEntity($data, $this->request->data);
				$count	=	count($data['start']);
				$count	=	$count-1;
				$set['contest_id']	=	$data['contest_id'];
				for($i=0; $i <= $count ; $i++) {
					if($data['end'][$i]=='0'){ $ed = ''; }else{ $ed=$data['end'][$i]; }
					$nm	=	'Rank '.$data['start'][$i].'-'.$ed;
					$set['name']		=	rtrim($nm, '-');
					$set['start']		=	$data['start'][$i];
					$set['end']			=	$data['end'][$i];
					$set['percentage']	=	rtrim($data['percentage'][$i], '%');
					$set['price']		=	$data['price'][$i]; //print_r($set);die;
					$save_array			=	$this->CustomBreakup->newEntity($set);
					$this->CustomBreakup->save($save_array);
				}
				$contest1	=	$this->Contest->get($data['contest_id']);
				$contest1['price_breakup']	=	'1';
				if ($this->Contest->save($contest1)) {
					
				}
				return $this->redirect(['action' => 'index']);
			
		}
		$this->set(compact('contest','data'));
	}

}
