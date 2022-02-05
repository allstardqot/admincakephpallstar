<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SeriesSquadTable
 *
 * @author Gayas
 */
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

class ContentsTable extends Table {
    

    public function initialize(array $config) {
        $this->setTable('contents');
		$this->addBehavior('Timestamp');
		$this->addBehavior('Search.Search');
    }
	
	public function validationDefault(Validator $validator) {
		$validator
			->notEmpty('title', __('Please enter title.'));
		
		$validator
			->notEmpty('content', __('Please enter content.'));
		
		return $validator;
	}

}
