<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SeriesTable
 *
 * @author Gayas
 */

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

class TransactionsTable extends Table {
    

    public function initialize(array $config) {
        $this->setTable('transactions');
		$this->addBehavior('Search.Search');
		$this->belongsTo('Users'); 
    }
	
	public function searchManager() {

        $searchManager = $this->behaviors()->Search->searchManager();
        $searchManager
                ->value('added_type', ['before' => true, 'after' => true, 'field' => 'Transactions.added_type'])
                ->value('phone', ['before' => true, 'after' => true, 'field' => 'Users.phone'])
                ->value('email', ['before' => true, 'after' => true, 'field' => 'Users.email']);
        return $searchManager;
    }

	public function validationDefault(Validator $validator) {
		return $validator;
	}
    

}
