<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of WalletsController
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


class WalletsController extends AppController {

    public function initialize() {
        parent::initialize();
        $this->loadComponent('Cookie');		
    }
	
    public function index() {
        $this->loadModel('Users');
        if (!empty($this->request->query)) {
            $this->request->session()->write('sorting_query', $this->request->query);
        }
        $this->set('title_for_layout', __('List Users'));
        $this->request->session()->delete('sorting_query');
        if (!empty($this->request->query)) {
            $this->request->session()->write('sorting_query', $this->request->query);
        }
        $role_id=   Configure::read('ROLES.User');
        $limit  =   Configure::read('ADMIN_PAGE_LIMIT');
        
        if ($this->request->is('Ajax')) {
            $this->viewBuilder()->layout(false);
        }
        
        $query = $this->Users->find('search', ['search' => $this->request->query])->where(['Users.role_id' => $role_id]);

        $users = $this->paginate($query, [
            'limit'     =>  $limit,
            'order'     =>  ['Users.id'=>'DESC'],
            'contain'   =>  ['PenAadharCard','BankDetails']
        ]);
        $this->set(compact('users'));
    }

    public function updateWallet(){
        $post = '';
        $this->loadModel('Users');
        $post = $this->request->is('ajax');
        if($post == 1){
            if(!empty($this->request->data)){
                $userData   =   $this->Users->find()->where(['id'=>$this->request->data['uid']])->first();
                if(!empty($userData)) {
                    $type   =   $this->request->data['type'];
                    $upType =   $this->request->data['up_type'];
                    $amount = $this->request->data['amount'];
                    if($type == 'bonus') {
                        $userData->bonus_amount =   ($upType == 1) ? $userData->bonus_amount + $amount : $userData->bonus_amount - $amount;
                    } else if($type == 'deposit') {
                        $userData->cash_balance =   ($upType == 1) ? $userData->cash_balance + $amount : $userData->cash_balance - $amount;
                    } else if($type == 'winning') {
                        $userData->winning_balance =   ($upType == 1) ? $userData->winning_balance + $amount : $userData->winning_balance - $amount;
                    }
                    $this->Users->save($userData);
                    $txnId  =   'AU'.date('Ymd').time().$userData->id;
                    if($upType == 1) {
                        $this->saveTransaction($userData->id,$txnId,ADMIN_ADDED,$this->request->data['amount']);
                    } else {
                        $this->saveTransaction($userData->id,$txnId,ADMIN_DEDUCTED,$this->request->data['amount']);
                    }
                    echo json_encode(array('class'=>'success','message'=>'Amount has been updated successfully.')); die;
                }else{
                    echo json_encode(array('class'=>'error','message'=>'User not found.')); die;
                }
            }else{
                echo json_encode(array('class'=>'error','message'=>'Invalid request.')); die;
            }
        }else{
            echo json_encode(array('class'=>'error','message'=>'Invalid request.')); die;
        }
    }

}
