<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of NotificationsController
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


class NotificationsController extends AppController {

    public function initialize() {
		
        parent::initialize();
        $this->loadComponent('Cookie');		
    }
	
	public function send() {
        
        $this->loadModel('Users');
        if(!empty($this->request->query)) {
            $this->request->session()->write('sorting_query', $this->request->query);
        }
        if(isset($this->request->data['length'])){
            $this->request->session()->write('NOTIFICATION_PAGE_LIMIT', $this->request->data['length']);
            $limit  =   $this->request->data['length'];
        } else {
            if(!empty($this->request->session()->read('NOTIFICATION_PAGE_LIMIT'))) {
                $limit  =   $this->request->session()->read('NOTIFICATION_PAGE_LIMIT');
            } else {
                $limit  =   Configure::read('ADMIN_PAGE_LIMIT');
            }
        }
		$notification	=	$this->Notifications->newEntity();
        if($this->request->is(['patch', 'post', 'put'])) {
            $this->Notifications->patchEntity($notification, $this->request->getData(), ['validate' => 'Default']);
            if(!$notification->errors()) {
                $uniqueString = $this->createUserReferal(6);

                $query = $this->Users->find('list', ['keyField'=>'id','valueField'=>'device_id']);
                $query->select([ 'id', 'device_id' ]);
                $query->where( ['status'=>'1','role_id'=>'2','device_type'=>'Android','device_id != '=>''/* ,'id IN'=>[12,619] */] )
                ->group(['id'])
                ->order(['id'])
                ->limit(1000);

                $userCount	=	$query->count();
                $totalPage = ceil($userCount/1000);
                $teamsJoinedContestWise	=	$query->toArray();

                //echo $userCount.'<br/>'.$totalPage;
                //pr($teamsJoinedContestWise);die;

                if(!empty($teamsJoinedContestWise)){
                    $notiType       =   '1';
                    $notifications['title'] = $notification->title;
                    $notifications['notification'] = $notification->notification;

                    $this->sendNotificationFCM(0,$notiType,$teamsJoinedContestWise,$notifications['title'],$notifications['notification'],'');

                    if( $totalPage > 1 ) {
                        for($page=2; $page <= $totalPage; $page++ ){
                            
                            $query2 = $this->Users->find('list', ['keyField'=>'id','valueField'=>'device_id']);
                            $query2->select([ 'id', 'device_id' ]);
                            $query2->where( ['status'=>'1','role_id'=>'2','device_type'=>'Android','device_id != '=>''] )
                            ->group(['id'])
                            ->order(['id'])
                            ->limit(1000)
                            ->page($page);

                            $teamsJoinedContestWise2	=	$query2->toArray();

                            if(!empty($teamsJoinedContestWise2)){
                                $this->sendNotificationFCM(0,$notiType,$teamsJoinedContestWise2,$notifications['title'],$notifications['notification'],'');
                            } 
                        }
                    }
                }

                $this->Flash->success(__('Notification has been added successfully.'));
                return $this->redirect(['action' => 'send']);                
            } else {
                $this->Flash->error(__('Please correct errors listed as below.'));
            }
        }
		
		$query = $this->Notifications->find()->where(['type'=>'1']);

        $notifidata = $this->paginate($query, [
            'limit'     =>  $limit,
            'order'     =>  ['Notifications.id'=>'DESC'],
            'group'     =>  ['title']
        ]);
        $this->set(compact('notification','notifidata','limit'));
    }
	
    public function sendnew() {
        
        $this->loadModel('Users');
        if(!empty($this->request->query)) {
            $this->request->session()->write('sorting_query', $this->request->query);
        }
        if(isset($this->request->data['length'])){
            $this->request->session()->write('NOTIFICATION_PAGE_LIMIT', $this->request->data['length']);
            $limit  =   $this->request->data['length'];
        } else {
            if(!empty($this->request->session()->read('NOTIFICATION_PAGE_LIMIT'))) {
                $limit  =   $this->request->session()->read('NOTIFICATION_PAGE_LIMIT');
            } else {
                $limit  =   Configure::read('ADMIN_PAGE_LIMIT');
            }
        }
		$notification	=	$this->Notifications->newEntity();
        if($this->request->is(['patch', 'post', 'put'])) {
            $this->Notifications->patchEntity($notification, $this->request->getData(), ['validate' => 'Default']);
            if(!$notification->errors()) {
                
                $query = $this->Users->find();
                $query->select(['id']);
                $query->where( ['status'=>'1'] )
                ->group(['id'])
                ->order(['id'])
                ->limit(1000);
                //pr($query);die;
                $userCount	=	$query->count();
                $totalPage = ceil($userCount/1000);
                $teamsJoinedContestWise	=	$query->toArray();
                
                //$this->loadModel('Notifications');

                if(!empty($teamsJoinedContestWise)){
                    $notiType       =   '1';
                    //$entity                    = $this->Notifications->newEntity();
                    
                    // $entity['title'] = $notification->title;
                    // $entity['description'] = $notification->notification;
                    // $entity['type'] = 1;
                    // $entity['created'] = date('Y-m-d H:i:s');
                    // $entity['status'] = 1;
                    $notification['status']=1;
                    $notification['created']=date('Y-m-d H:i:s');
                    $notification['description']=$notification->notification;
                    if($this->Notifications->save($notification)){
		                $this->sendNotificationFCMNew("all",$notification->title,$notification->notification);
                        $this->Flash->success(__('Notification has been added successfully.'));
                        return $this->redirect(['action' => 'sendnew']);                
                    }
                }
            } else {
                $this->Flash->error(__('Please correct errors listed as below.'));
            }
        }
		
		//$query = $this->Notifications->find()->where(['type'=>'1']);
		$query = $this->Notifications->find()->where(['user_id IS'=>null]);
        //pr($query);die;
        $notifidata = $this->paginate($query, [
            'limit'     =>  $limit,
            'order'     =>  ['Notifications.id'=>'DESC'],
            'group'     =>  ['title']
        ]);
        $this->set(compact('notification','notifidata','limit'));
    }

    public function received() {
        $this->loadModel('ReceivedNotifications');

        $query	=	$this->ReceivedNotifications->find()
					->select(['ReceivedNotifications.id','ReceivedNotifications.user_id','ReceivedNotifications.message','ReceivedNotifications.created','users.first_name','users.last_name'])
                    ->join([
                        'users' => [
                            'table' => 'users',
                            'type' => 'LEFT',
                            'conditions' => '(ReceivedNotifications.user_id = users.id)'
                        ]
                    ])
					->order(['ReceivedNotifications.id'=>'DESC']);
        $record	=	$query->toArray();
        $this->set(compact('record'));
    }

    public function delete($id = null) {
        if(isset($id) && !empty($id)) {
            $this->loadModel('ReceivedNotifications');
            $entity	=	$this->ReceivedNotifications->get($id);
            if($this->ReceivedNotifications->delete($entity)) {
                $this->Flash->success(__('Notification has been successfully deleted .'));
                return $this->redirect(['action' => 'received']);
            } else {
                $this->Flash->error(__('Unable to delete notification, please try again.'));
                return $this->redirect(['action' => 'received']);
            }
        }else {
            $this->Flash->error(__('Unable to delete notification, please try again.'));
            return $this->redirect(['action' => 'received']);
        }
    }
	
	public function edit($id = NULL) {
        $this->loadModel('Users');
        $this->set('title_for_layout', __('Edit Notification'));
        try {
            $noti = $this->Notifications->get($id);
        } catch (\Throwable $e) {
            $this->Flash->error(__('Invaild attempt.'));
            return $this->redirect(['action' => 'index']);
        } catch (\Exception $e) {
            $this->Flash->error(__('Invaild attempt.'));
            return $this->redirect(['action' => 'index']);
        }
        if($this->request->is(['patch', 'post', 'put'])) {
            if(isset($this->request->data['length'])){}
            else{
                $this->Notifications->patchEntity($noti, $this->request->getData(), ['validate' => 'Default']);
                if(!$noti->errors()) {
                    $uniqueString = $this->createUserReferal(6);
    
                    $user   =   $this->Users->find()->where(['status'=>'1','role_id'=>'2'])->select(['id','device_type','device_id'])->toArray();
                    foreach ($user as $key => $value) {
						if(!empty($value->device_type) && !empty($value->device_id)){
							$notifications  =   $this->Notifications->newEntity();
							$deviceType     =   $value->device_type;
							$deviceToken    =   $value->device_id;
							$notiType       =   '1';
							$notifications['nitification_type'] =   $notiType;
							$notifications['title'] = $noti->title;
							$notifications['notification'] = $noti->notification;
							$notifications['user_id'] = $value->id;
							$notifications['unique_string'] = $uniqueString;
							$notifications['date']    = date('Y-m-d');
							$notifications['status'] = ACTIVE;
							$notifications['is_send'] = 2;
		
							if(($deviceType=='Android') && ($deviceToken!='')){
								$this->sendNotificationFCM($notifications['user_id'],$notiType,$deviceToken,$notifications['title'],$notifications['notification'],'');
							} 
							if(($deviceType=='iphone') && ($deviceToken!='') && ($deviceToken!='device_id')){
								//$this->sendNotificationAPNS($notifications['user_id'],$notiType,$deviceToken,$notifications['title'],$notifications['notification'],'');
							}
							$this->Notifications->save($notifications);
						}
                    }
                    $this->Flash->success(__('Notification has been added successfully.'));
                    return $this->redirect(['action' => 'send']);
                } else {
                    $this->Flash->error(__('Please correct errors listed as below.'));
                }
            }
        }
        $this->set(compact('edit','noti'));
    }

}
