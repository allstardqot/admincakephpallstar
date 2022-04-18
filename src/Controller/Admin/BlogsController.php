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

class BlogsController extends AppController {

	public function initialize() {
		parent::initialize();
		$this->loadComponent('Cookie');
	}
	
	public function index() {
		 $blog = TableRegistry::get('Blogs');
		$this->set('title_for_layout', __('Blogs List'));
		$limit	=	Configure::read('ADMIN_PAGE_LIMIT');
		 $query = $blog->find();
		$result = $this->paginate($query, [
			'limit'		=>	$limit,
			'order'		=>	['Faq.created'=>'DESC'],
		]);
		// $result	=	$this->Contents->find('all')->where(['content_type'=>2])->toArray();
		$this->set(compact('result'));
	}
	
	public function add() {
		$this->set('title_for_layout', __('Add Blog'));
		$this->loadModel('Blogs');
		$blog	=	$this->Blogs->newEntity();
		if($this->request->is(['patch', 'post', 'put'])) {
			$blog	=	$this->Blogs->patchEntity($blog, $this->request->getData(), ['validate' => 'Default']);
			if(!$blog->errors()) {

				if( !empty($this->request->getData('image') )) {
					$file		=	$this->request->getData('image');
					if(!empty($file['name'])){
						$fileArr	=	explode('.',$file['name']);
						$ext		=	end($fileArr);
						$fileName	=	time().'blog.'.$ext;
						$filePath	=	WWW_ROOT .'uploads/blogs/'.$fileName;
						move_uploaded_file($file['tmp_name'],$filePath);
						if(!empty( $blog->image )){
							unlink( WWW_ROOT .'uploads/blogs/'.$blog->image );
						}
						$blog->image	=	$fileName;
					}
				}
				$blog->status	=	1;
				if($this->Blogs->save($blog)) {
					$this->Flash->success(__('Blog has been added successfully.'));
					return $this->redirect(['action' => 'index']);
				}
			} else {
				$this->Flash->error(__('Please correct errors listed as below'));
			}
		}
		$this->set(compact('blog'));
	}
	
	public function edit($id= null) {

		$Blogs = TableRegistry::get('Blogs');
		$this->set('title_for_layout', __('Edit Blog'));
		$blog	=	$Blogs->get($id);
		if($this->request->is(['patch', 'post', 'put'])) {
			//pr($Faq);pr($this->request->getData());die;
			$blog	=	$Blogs->patchEntity($blog, $this->request->getData(), ['validate' => 'Default']);
			if(!$blog->errors()) {
				$title = trim($blog->title);
				if($title != ''){

					$attachment = $this->request->getData('profile_image_file');
					if(!$attachment){
						$blog->image = $blog->getOriginal('image');
					}	
					
					if (!empty($this->request->getData('profile_image_file'))) {
						// pr($attachment);die;
						$fileArr	=	explode('.',$attachment['name']);
						$ext		=	end($fileArr);
						$fileName	=	time().'blog.'.$ext;
						$filePath	=	WWW_ROOT .'uploads/blogs/'.$fileName;
						move_uploaded_file($attachment['tmp_name'],$filePath);
						
							//$this->General->compress_image(U_IMAGE_ROOT_PATH,$imageName);
							
							$old_image = $blog->getOriginal('image');
							// pr($old_image);die;
							if($old_image!=''){
								// unlink($filePath. $old_image);
								unlink( WWW_ROOT .'uploads/blogs/'.$old_image );
							}
							$blog->image = $fileName;
						
					}
					$blog->status	=	1;
					if($Blogs->save($blog)) {
						$this->Flash->success(__('Blog has been updated successfully.'));
						return $this->redirect(['action' => 'index']);
					}
				}else{
					$this->Flash->error(__('Title not be blank.'));
				}
			} else {
				$this->Flash->error(__('Please correct errors listed as below'));
			}
		}
		$this->set(compact('blog'));
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
		$this->set('title_for_layout', __('View Blog'));
		$Faq = TableRegistry::get('Blogs');
		$result	=	$Faq->get($id);
		$this->set(compact('result'));
	}
    

}
