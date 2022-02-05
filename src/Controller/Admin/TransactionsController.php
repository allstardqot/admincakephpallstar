<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UsersController
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

class TransactionsController extends AppController {

	public function initialize() {
		parent::initialize();
		$this->loadComponent('Cookie');
	}
	
	public function index() {
		if (!empty($this->request->query)) {
            $this->request->session()->write('sorting_query', $this->request->query);
        }
		$this->set('title_for_layout', __('TDS List'));
		$this->request->session()->delete('sorting_query');
        if (!empty($this->request->query)) {
			$this->request->session()->write('sorting_query', $this->request->query);
        }
		if ($this->request->is('Ajax')) {
            $this->viewBuilder()->layout(false);
        }
        if(isset($this->request->data['length'])){
        	$this->request->session()->write('TRANS_PAGE_LIMIT', $this->request->data['length']);
			$limit	=	$this->request->data['length'];
		} else {
			if(!empty($this->request->session()->read('TRANS_PAGE_LIMIT'))) {
				$limit	=	$this->request->session()->read('TRANS_PAGE_LIMIT');
			} else {
				$limit	=	Configure::read('ADMIN_PAGE_LIMIT');
			}
        }
		//pr($this->request->query);die;
		$query	=	$this->Transactions->find('search', ['search' => $this->request->query]);
		if(isset($this->request->params['?']['status'])){
            $pc = $this->request->params['?']['status'];
            if($pc=='today'){
            	$date    =   date('Y-m-d');
            	$query	=	$this->Transactions->find('all')->where(['added_type' => CASH_DEPOSIT,'DATE(txn_date)'=>$date]);
            }
        }
        if(isset($this->request->params['?']['status'])){
            $pc = $this->request->params['?']['status'];
            if($pc=='month'){
            	$startDate = date('Y-m-01');
        		$endDate   = date('Y-m-t');
            	$query	=	$this->Transactions->find('all')->where(['added_type' => TRANSACTION_CONFIRM,'DATE(txn_date) >='=>$startDate,'DATE(txn_date) <='=>$endDate]);
            }
        }
        if(isset($this->request->params['?']['status'])){
            $pc = $this->request->params['?']['status'];
            if($pc=='deposits'){
            	$query	=	$this->Transactions->find('all')->where(['added_type' => CASH_DEPOSIT]);
            }
        }
		$result	=	$this->paginate($query, [
			'conditions'=>	['Transactions.status'=>ACTIVE],
			'contain'	=>	['Users'],
			'limit'		=>	$limit,
			'order'		=>	['Transactions.id'=>'DESC']
		]);
		//pr($result);die;
		$this->set(compact('result','limit'));
	}


	public function export() {
    	ini_set('max_execution_time', 1500);
    	$data = array();
    	$data = $this->Transactions->find()->where([])->order(['Transactions.id'=>'DESC'])->group(['Transactions.id'])->contain(['Users'])->toArray();

    	$header[] = 'S.No';
		$header[] = 'Transaction Id';
		$header[] = 'Local Transaction Id';
		$header[] = 'User Mobile';
		$header[] = 'User Email';
		$header[] = 'Cr/Db';
		$header[] = 'Payment Mode';
		$header[] = 'Transaction Type';
		$header[] = 'Amount';
		$header[] = 'Date & Time';
		



		$delimiter = "\t";
		$filename = 'TRANSACTION_SHEET_'.time().'_' . date("Y-m-d") . ".xls";
		$count = 0;
		$count = 1;
		if (!empty($data)) {
			foreach ($data as $row) {
				
				$t_type  = '';
				if($row->added_type == JOIN_CONTEST || $row->added_type == TRANSACTION_PENDING || $row->added_type == WITHDRAWAL || $row->added_type == ADMIN_DEDUCTED ){
					$t_type  = "Db";	
				} else if($row->added_type == TRANSACTION_CONFIRM){
					$t_type  = "N/A";	
				}else{ 
					$t_type  = "Cr";
				}

				if($row->gateway_name == 'WALLET'){
					$row->gateway_name = 'PAYTM';
				}

				$added_type = Configure::read('TRANSACTION_TYPE.'.$row->added_type);
				
				$dara_arr = array();
				$dara_arr['count'] 				= 	$count;
				$dara_arr['txn_id']  			= 	$row->txn_id ? $row->txn_id : 'N/A';
				$dara_arr['local_txn_id']  		= 	$row->local_txn_id;
				$dara_arr['phone'] 				=   (!empty($row->user->phone)) ? $row->user->phone : '';
				$dara_arr['email'] 				=  	(!empty($row->user->email)) ? $row->user->email : '';
				$dara_arr['t_type'] 			=  	$t_type;
				$dara_arr['gateway_name'] 		=  	$row->gateway_name ? $row->gateway_name : 'N/A';
				$dara_arr['added_type'] 		=  	$added_type;
				$dara_arr['txn_amount'] 		=  	$row->txn_amount.' INR';
				$dara_arr['txn_date'] 			=  	$row->txn_date;
				
				
				$globle_arr[] = $dara_arr;

				$count++;  
			}
			
			if(!empty($globle_arr)){
				$this->ExportExcel($header , $globle_arr , $filename);
			}
		}

    	
    	$this->set(compact('export','data'));
    	
    }
	
}
