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


class CategoryController extends AppController {

	public function initialize() {
		parent::initialize();
		$this->loadComponent('Cookie');		
	}

	public function index() {
		if (!empty($this->request->query)) {
			$this->request->session()->write('sorting_query', $this->request->query);
		}
		$this->set('title_for_layout', __('List Category'));
		$this->request->session()->delete('sorting_query');
		if(!empty($this->request->query)) {
			$this->request->session()->write('sorting_query', $this->request->query);
		}
		$limit = Configure::read('ADMIN_PAGE_LIMIT');
		
		if ($this->request->is('Ajax')) {
			$this->viewBuilder()->layout(false);
		}
		
		$query		=	$this->Category->find('search', ['search' => $this->request->query]);
		// pr($query);
		$category	=	$this->paginate($query, ['limit' => $limit, 'order' => ['Category.created DESC']]);
		$this->set(compact('category'));
	}

	public function add() {
		$this->set('title_for_layout', __('Add Category'));
		$category	=	$this->Category->newEntity();
		if ($this->request->is(['patch', 'post', 'put'])) {
			$this->request->data['category_name']	=	trim($this->request->getData('category_name'));
			$this->request->data['description']		=	trim($this->request->getData('description'));
			$category	=	$this->Category->patchEntity($category, $this->request->getData(), ['validate' => 'addEditCategory']);
			// pr($category->errors());die;
			if(empty($category->errors())) {
				if(isset($this->request->data['image']) && !empty($this->request->data['image']['name'])){
					$file		=	$this->request->getData('image');
					$fileArr	=	explode('.',$file['name']);
					$ext		=	end($fileArr);
					$fileName	=	'background_'.time().'.'.$ext;
					$rootPath	=	WWW_ROOT .'uploads/category_image/';
					$filePath	=	$rootPath.$fileName;
					if(move_uploaded_file($file['tmp_name'],$filePath)) {
						$category->image	=	$fileName;
					}
				}
				$category->created	=	date('Y-m-d H:i:s');
				$category->status	=	ACTIVE;
				if ($this->Category->save($category)) {
					$this->Flash->success(__('Category has been added'));
					return $this->redirect(['action' => 'index']);
				}
				$this->Flash->error(__('Category could not be added, please try again.'));
			} else {
				$this->Flash->error(__('Please correct errors listed as below'));
			}
		}
		$this->set(compact('category'));
	}

	public function edit($id = NULL) {
		$this->set('title_for_layout', __('Edit Category'));
		try {
			$category = $this->Category->get($id);
		} catch (\Throwable $e) {
			$this->Flash->error(__('Invaild attempt.'));
			return $this->redirect(['action' => 'index']);
		} catch (\Exception $e) {
			$this->Flash->error(__('Invaild attempt.'));
			return $this->redirect(['action' => 'index']);
		}
		// $oldImg	=	$category['image'];
		if($this->request->is(['patch', 'post', 'put'])) {
			$categoryImage		=	$category->image;
			$this->request->data['category_name']	=	trim($this->request->getData('category_name'));
			$this->request->data['description']		=	trim($this->request->getData('description'));
			$this->Category->patchEntity($category, $this->request->getData(), ['validate' => 'EditCategory']);
			$category['modified']	=	date('Y-m-d H:i:s');
			
			// pr($category->errors());die;
			if(empty($category->errors())) {			
				if(isset($this->request->data['image']) && !empty($this->request->data['image']['name'])){
					$file		=	$this->request->getData('image');
					$fileArr	=	explode('.',$file['name']);
					$ext		=	end($fileArr);
					$fileName	=	'background_'.time().'.'.$ext;
					$rootPath	=	WWW_ROOT .'uploads/category_image/';
					$filePath	=	$rootPath.$fileName;
					if(!empty($categoryImage) && file_exists($rootPath.$categoryImage)) {
						unlink($rootPath.$categoryImage);
					}
					if(move_uploaded_file($file['tmp_name'],$filePath)) {
						$category->image	=	$fileName;
					}
				} else {
					$category->image	=	$categoryImage;
				}
				if ($this->Category->save($category)) {
					$this->Flash->success(__('Category has been updated'));
					return $this->redirect(['action' => 'index']);
				}
				$this->Flash->error(__('Category could not be added, please try again.'));
			} else {
				$this->Flash->error(__('Please correct errors listed as below.'));
			}
		}
		$this->set(compact('category'));
	}

	public function status($id = NULL) {
		$category = $this->Category->get($id);
		$status = ($category->status == 0) ? 1 : 0;
		$category->status = $status;
		//pr($category);die;
		if ($this->Category->save($category)) {
			$this->Flash->success(__('Category status has been changed'));
			return $this->redirect($this->referer());
		}
		$this->Flash->error(__('Category status could not be changed, please try again.'));
	}

	public function detail($id = NULL) {
		$this->set('title_for_layout', __('Category Detail'));
		try {
			$category	=	$this->Category->get($id);
		} catch (\Throwable $e) {
			$this->Flash->error(__('Invaild attempt.'));
			return $this->redirect(['action' => 'index']);
		} catch (\Exception $e) {
			$this->Flash->error(__('Invaild attempt.'));
			return $this->redirect(['action' => 'index']);
		}
		$this->set(compact('category'));

	}

	public function delete($id = null) { 
		$this->loadModel('Contest');
		if (isset($id) && !empty($id))
		{
			$entity = $this->Category->get($id); 
			$cat = $this->Contest->find()->where(['category_id'=>$entity['id']])->first();
			if($cat==''){
				if($this->Category->delete($entity)) {
					$this->Flash->success(__('Category has been successfully deleted .'));
					return $this->redirect(['action' => 'index']);
				}else
				{
					$this->Flash->error(__('Unable to delete Category, please try again.'));
					return $this->redirect(['action' => 'index']);
				}
			}else{
				$this->Flash->error(__('Unable to delete category, this category is used in some contest.'));
				return $this->redirect(['action' => 'index']);
			}
		}else
		{
			$this->Flash->error(__('Unable to delete category, please try again.'));
			return $this->redirect(['action' => 'index']);
		}
	}

}
