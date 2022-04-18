<?php
/**
 * Webservices controller.
 *
 * This file will render views from views/pages/
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Cake\Routing\Router;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Cake\Collection\Collection;
use Cake\ORM\TableRegistry;

/**
 * Cron Controller
 *
 */

class PagesController extends AppController {
	
	public function initialize() {
		parent::initialize();
		$this->Auth->allow();
		$this->loadModel('Pages');
    }
	
	public function index() {
		// pr('index');die;
		$result	=	$this->Pages->find('all')->toArray();
		$this->set(compact('result'));
	}

	
	/*public function static() {
		$this->viewBuilder()->layout(false);
		$result	=	$this->Contents->find()->where(['slug'=>'static'])->first();
		$this->set(compact('result'));
	}*/
	
	public function howItWorks() {
		$this->viewBuilder()->layout(false);
		$result	=	$this->Contents->find()->where(['slug'=>'how-it-works'])->first();
		$this->set(compact('result'));
	}
	
	public function fairPlay() {
		$this->viewBuilder()->layout(false);
		$result	=	$this->Contents->find()->where(['slug'=>'fair-play'])->first();
		$this->set(compact('result'));
	}
	
	public function help() {
		$this->viewBuilder()->layout(false);
		$result	=	$this->Contents->find()->where(['slug'=>'help'])->first();
		$this->set(compact('result'));
	}
	
	public function howToPlay() {
		// how-to-play
		$this->viewBuilder()->layout(false);
		$result	=	$this->Contents->find()->where(['slug'=>'how-to-play'])->first();
		$this->set(compact('result'));
	}
	
	public function pointSystem() {
		// point-system
		$this->viewBuilder()->layout(false);
		$this->loadModel('PointSystem');
		$this->loadModel('Contents');
		$t20data	=	$this->PointSystem->find()->where(['matchType'=>T20_MATCH])->first();
		$odidata	=	$this->PointSystem->find()->where(['matchType'=>ODI_MATCH])->first();
		$testdata	=	$this->PointSystem->find()->where(['matchType'=>TEST_MATCH])->first();
		$t10data	=	$this->PointSystem->find()->where(['matchType'=>T10_MATCH])->first();
		$content	=	$this->Contents->find()->where(['slug'=>'point-system'])->first();
		$this->set(compact('t20data','odidata','testdata','content','t10data'));
	}

	public function kpointSystem() {
		// point-system
		$this->viewBuilder()->layout(false);
		$this->loadModel('KabPointSystem');
		$this->loadModel('Contents');
		$data	=	$this->KabPointSystem->find()->where(['matchType'=>1])->first();
		$content	=	$this->Contents->find()->where(['slug'=>'point-system'])->first();
		$this->set(compact('data','content'));
	}

	public function spointSystem() {
		// point-system
		$this->viewBuilder()->layout(false);
		$this->loadModel('SocPointSystem');
		$this->loadModel('Contents');
		$data	=	$this->SocPointSystem->find()->where(['matchType'=>1])->first();
		$content	=	$this->Contents->find()->where(['slug'=>'point-system'])->first();
		$this->set(compact('data','content'));
	}

	public function edit($id=null){
		$this->set('title_for_layout', __('Edit CMS'));
		$page = $this->Pages->get($id);
		if($this->request->is(['patch', 'post', 'put'])){
			$getData=$this->request->getData();
			
			//pr($getData);die;
            $page = $this->Pages->patchEntity($page, $getData);
			if ($this->Pages->save($page)) {
				$this->Flash->success(__('CMS has been updated'));
				return $this->redirect(['action' => 'index']);
			}
            
        }
        $this->set(compact('page'));
	} 
	
	public function pointSystemWeb() {
		$this->set('title_for_layout', 'Point System');
		$this->viewBuilder()->setLayout('afterlogin');
	}
	
	public function howToPlayWeb() {
		$this->set('title_for_layout', 'How To Play');
		$this->viewBuilder()->setLayout('afterlogin');
	}
	
	public function helpWeb() {
		$this->set('title_for_layout', 'Help');
		$this->viewBuilder()->setLayout('afterlogin');
	}
	public function workWithUsWeb() {
		$this->set('title_for_layout', 'Work With Us');
		$this->viewBuilder()->setLayout('afterlogin');
	}

	public function workWithUs() {
		$this->viewBuilder()->layout(false);
		$result	=	$this->Contents->find()->where(['slug'=>'work-with-us'])->first();
		$this->set(compact('result')); 
	}

	public function fairPlayWeb() {
		$this->set('title_for_layout', 'Fair Play');
		$this->viewBuilder()->setLayout('afterlogin');
	}
	
	public function legality() {
		$this->viewBuilder()->layout(false);
		$result	=	$this->Contents->find()->where(['slug'=>'Legality'])->first();
		$this->set(compact('result')); 
	}
	
	public function legalityWeb() {
		$this->set('title_for_layout', 'Legality');
		$this->viewBuilder()->setLayout('afterlogin');
	}
	
	public function staticTab() {
		// static-tab
		$this->viewBuilder()->layout(false);
		$result	=	$this->Contents->find()->where(['slug'=>'static-tab'])->first();
		$this->set(compact('result')); 
	}
	
	public function aboutUs() {
		// about-us
		$this->viewBuilder()->layout(false);
		$result	=	$this->Contents->find()->where(['slug'=>'about-us'])->first();
		$this->set(compact('result'));
	}
	
	public function fairPlay1() {
		$this->viewBuilder()->layout(false);
		$result	=	$this->Contents->find()->where(['slug'=>'fair-and-play'])->first();
		$this->set(compact('result'));
	}
	
	public function foundation() {
		// foundation
		$this->viewBuilder()->layout(false);
		$result	=	$this->Contents->find()->where(['slug'=>'foundation'])->first();
		$this->set(compact('result'));
	}
	
	public function dream11Champions() {
		$this->viewBuilder()->layout(false);
		$result	=	$this->Contents->find()->where(['slug'=>'app-champions'])->first();
		$this->set(compact('result'));
	}
	
	public function termsConditions() {
		$this->viewBuilder()->layout(false);
		$result	=	$this->Contents->find()->where(['slug'=>'terms-and-condition'])->first();
		$this->set(compact('result'));
	}
	
	public function faq() {
		$this->viewBuilder()->layout(false);
		$tAndC	=	$this->Contents->find()->where(['slug'=>'terms-and-condition'])->first();
		$faq	=	$this->Contents->find()->where(['slug'=>'faq'])->first();
		$this->set(compact('tAndC','faq'));
	}
	
	public function invite() {
		$this->viewBuilder()->layout('home');
		if($this->request->is(['patch', 'post', 'put'])) {
			$this->loadModel('Users');
			$user	=	$this->Users->newEntity();
			$this->Users->patchEntity($user, $this->request->getData(), ['validate'=>'SendLink']);
            if (empty($user->errors())) {
				$mobile_number = $this->request->data['mobile_number'];
				$this->sendAppLink($mobile_number);
				$this->Flash->success(__('Link sent successfully.'));
            } else {
                $this->Flash->error(__('Please correct errors listed as below'));
            }
        }
	}

	public function view($id = null) {
		$this->set('title_for_layout', __('View Content'));
		$Contents = TableRegistry::get('Pages');
		$result	=	$Contents->get($id);
		$this->set(compact('result'));
	}
}
