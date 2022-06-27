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


class SettingsController extends AppController {

	public function initialize() {
		parent::initialize();
		$this->loadComponent('Cookie');
	}
	
	public function index() {
		$this->set('title_for_layout', __('Contents List'));
		$setting	=	$this->Settings->find('all')->first();
		if(empty($setting)) {
			$setting	=	$this->Settings->newEntity();
		}
		if($this->request->is(['patch', 'post', 'put'])) {
			// $siteLogo	=	$setting->site_logo;
			// pr($this->request->getData());die;
			// $adminBack	=	$setting->admin_background;
			$this->Settings->patchEntity($setting, $this->request->getData());
			// pr($setting->errors());die;
			if(!$setting->errors()) {
				// if(!empty($this->request->getData('admin_background')['name'])) {
				// 	$file	=	$this->request->getData('admin_background');
				// 	$fileArr	=	explode('.',$file['name']);
				// 	$ext		=	end($fileArr);
				// 	$fileName	=	'background_'.time().'.'.$ext;
				// 	$filePath	=	WWW_ROOT .'uploads/settings/'.$fileName;
				// 	if(move_uploaded_file($file['tmp_name'],$filePath)) {
				// 		$setting->admin_background	=	$fileName;
				// 	}
				// } else {
					$setting->phone	=	$this->request->getData('phone');
				// }
				if($this->Settings->save($setting)) {
					$this->Flash->success(__('Setting has been updated successfully.'));
					return $this->redirect(['action' => 'index']);
				}
			} else {
				$this->Flash->error(__('Please correct errors listed as below'));
			}
		}
		$this->set(compact('setting'));
	}
}
