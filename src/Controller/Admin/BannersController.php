<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BannersController
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


class BannersController extends AppController {

    public function initialize() {
        parent::initialize();
        $this->loadComponent('Cookie');		
    }
	
    public function index() {
        $this->loadModel('Banners');
        $list	=	$this->Banners->find('all')->order(['id'=>'DESC'])->toArray();
        $this->set(compact('list'));
    }
	
	public function add() {
        $this->set('title_for_layout', __('Add Banner'));
		$banner	=	$this->Banners->newEntity();
        if ($this->request->is(['patch', 'post', 'put'])) {
			$this->Banners->patchEntity($banner, $this->request->getData(), ['validate'=>'AddBanner']);
            
            if (empty($banner->errors())) {
				if(isset($this->request->data['image']) && !empty($this->request->data['image']['name'])){
					$file	=	$this->request->data['image'];
					if(!empty($file['name']) && $file['tmp_name'] != '' && $file['size'] > 0) {
						$this->Upload->upload($file, WWW_ROOT .'uploads/banner_image/' .DS, '');
						if (!empty($this->Upload->result) && empty($this->Upload->errors)) {
							$banner['image']	=	$this->Upload->result;
						}
					}
				}
                if ($this->Banners->save($banner)) {
                    return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error(__('Banner could not be added. Please, try again'));
            } else {
                $this->Flash->error(__('Please correct errors listed as below'));
            }
        }
        $this->loadModel('CouponCodes'); 
        $currentDate=	date("Y-m-d H:i:s");
        $offer		=	$this->CouponCodes->find()
						->where(['expiry_date > '=>$currentDate,'status'=>ACTIVE])
						->select(['id','coupon_code'])->toArray();
        

        $this->set(compact('banner','offer'));

    }
	
    public function edit($id = NULL) {
        $this->set('title_for_layout', __('Banner Contest'));
        try {
            $banner	=	$this->Banners->get($id);
        } catch (\Throwable $e) {
            $this->Flash->error(__('Invaild attempt.'));
            return $this->redirect(['action' => 'index']);
        } catch (\Exception $e) {
            $this->Flash->error(__('Invaild attempt.'));
            return $this->redirect(['action' => 'index']);
        }
        $oldImg = $banner['image'];
        if ($this->request->is(['patch', 'post', 'put'])) {
			$this->Banners->patchEntity($banner, $this->request->getData(), ['validate' => 'EditBanner']);
            
            if (empty($banner->errors())) {
				if(isset($this->request->data['image']) && !empty($this->request->data['image']['name'])){
					$file = $this->request->data['image'];
					if(!empty($file['name']) && $file['tmp_name'] != '' && $file['size'] > 0) {
						$this->Upload->upload($file, WWW_ROOT .'uploads/banner_image/' .DS, '');;
						if (!empty($this->Upload->result) && empty($this->Upload->errors)) {
							$banner['image'] = $this->Upload->result;
						}
					}
				}
				
				if($banner['image']==''){ $banner['image'] = $oldImg; }
                if ($this->Banners->save($banner)) {
                    $this->Flash->success(__('Banner has been updated'));
                    return $this->redirect(['action' => 'index', '?' => $this->request->session()->read('sorting_query')]);
                }
                $this->Flash->error(__('Banner could not be added. Please, try again'));
            } else {
                $this->Flash->error(__('Please correct errors listed as below'));
            }
        }
		$this->loadModel('CouponCodes');
		$currentDate=	date("Y-m-d H:i:s");
        $offer		=	$this->CouponCodes->find()
						->where(['expiry_date > '=>$currentDate,'status'=>ACTIVE])
						->select(['id','coupon_code'])->toArray();
		//$seriesList	=	$this->upcomingSeriesList();
        $seriesList='';

        $this->set(compact('banner','offer'));
    }
	
    public function getMatch($id = null){
        $this->viewBuilder()->layout(false);
        $series_id     =   $this->request->data['series'];
        $currentDate   =   date("Y-m-d");
        $this->loadModel('SeriesSquad');
        $list	=	$this->SeriesSquad->find()->where(['date >= '=>$currentDate,'series_id'=>$series_id])->select(['match_id','localteam','visitorteam','type','date'])->toArray();
		if(!empty($list)) {
            foreach($list as $matches) {
                $date   =   date('Y-m-d',strtotime($matches->date));
                $matches->match_name    =   $matches->localteam.' vs '.$matches->visitorteam.' '.$matches->type.' @ '.$date;
            }
        }

        $response   =   [
			'status'=>  'success',
			'data'  =>  $list
		];
        echo json_encode($response);
        exit;
    }

    public function status($id = NULL) {
        $banner	=	$this->Banners->get($id);
        $status	=	($banner->status == 0) ? 1 : 0;
        $banner->status	=	$status;
        if($this->Banners->save($banner)) {
			$this->Flash->success(__('Banners status has been changed'));
			return $this->redirect($this->referer());
		}
		$this->Flash->error(__('Banners status could not be changed. Please, try again'));
	}

    public function detail($id = NULL) {
        $this->set('title_for_layout', __('Banner Detail'));
        try {
            $banner = $this->Banners->get($id);

        } catch (\Throwable $e) {
            $this->Flash->error(__('Invaild attempt.'));
            return $this->redirect(['action' => 'index']);
        } catch (\Exception $e) {
            $this->Flash->error(__('Invaild attempt.'));
            return $this->redirect(['action' => 'index']);
        }
        $this->set(compact('banner'));
    }

    public function delete($id = null) {
        if (isset($id) && !empty($id)) {
            $entity = $this->Banners->get($id);
            if($this->Banners->delete($entity)) {
                $this->Flash->success(__('Banner has been successfully deleted .'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Unable to delete banner, please try again.'));
                return $this->redirect(['action' => 'index']);
            }
        } else {
            $this->Flash->error(__('Unable to delete banner, please try again.'));
            return $this->redirect(['action' => 'index']);
        }
    }

}
