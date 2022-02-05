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


class PaymentOffersController extends AppController {

	public function initialize() {
		parent::initialize();
		$this->loadComponent('Cookie');
	}
	
	public function index() {
		$this->set('title_for_layout', __('Payment Offers List'));
		$limit	=	Configure::read('ADMIN_PAGE_LIMIT');
		$result = $this->paginate('PaymentOffers', [
			'limit'		=>	$limit,
			'order'		=>	['created'=>'DESC']
		]);
		$this->set(compact('result'));
	}
	
	public function add() {
		$this->set('title_for_layout', __('Add Payment Offers'));
		$offers	=	$this->PaymentOffers->newEntity();
		if($this->request->is(['patch', 'post', 'put'])) {
			$this->request->data['coupon_code']	=	trim($this->request->getData('coupon_code'));
			$this->PaymentOffers->patchEntity($offers, $this->request->getData(), ['validate' => 'Default']);
			// pr($offers);die;
			if(!$offers->errors()) {
				if($this->PaymentOffers->save($offers)) {
					$this->Flash->success(__('Payment offer has been added successfully.'));
					return $this->redirect(['action' => 'index']);
				}
			} else {
				$this->Flash->error(__('Please correct errors listed as below'));
			}
		}
		$this->set(compact('offers'));
	}
	
	public function edit($id= null) {
		$this->set('title_for_layout', __('edit Payment Offers'));
		$offers	=	$this->PaymentOffers->get($id);
		if($this->request->is(['patch', 'post', 'put'])) {
			$this->request->data['coupon_code']	=	trim($this->request->getData('coupon_code'));
			$this->PaymentOffers->patchEntity($offers, $this->request->getData(), ['validate' => 'Default']);
			
			if(!$offers->errors()) {
				// $email->status	=	1;
				if($this->PaymentOffers->save($offers)) {
					$this->Flash->success(__('Payment offer has been updated successfully.'));
					return $this->redirect(['action' => 'index']);
				}
			} else {
				$this->Flash->error(__('Please correct errors listed as below'));
			}
		}
		$this->set(compact('offers'));
	}
	
    public function status($id = NULL) {
        $result	=	$this->PaymentOffers->get($id);
        $status	=	($result->status == 0) ? 1 : 0;
        $result->status =	$status;
        if($this->PaymentOffers->save($result)) {
			$this->Flash->success(__('Payment offer status has been changed'));
			return $this->redirect($this->referer());
        }
        $this->Flash->error(__('Payment offer status could not be changed. please try again.'));
    }
	
	public function view($id = null) {
		$this->set('title_for_layout', __('View Content'));
		$result	=	$this->PaymentOffers->find()->where(['id'=>$id])->contain(['UserCouponCodes'=>['Users']])->first();
		// pr($result);die;
		$this->set(compact('result'));
	}
    
	public function delete($id = null) {
		if (isset($id) && !empty($id)) {
			$entity	=	$this->PaymentOffers->get($id);
			if($this->PaymentOffers->delete($entity)) {
				$this->Flash->success(__('Payment offer has been successfully deleted .'));
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error(__('Unable to delete payment offers, please try again.'));
				return $this->redirect(['action' => 'index']);
			}
		} else {
			$this->Flash->error(__('Unable to delete payment offers, please try again.'));
			return $this->redirect(['action' => 'index']);
		}
	}
	

}
