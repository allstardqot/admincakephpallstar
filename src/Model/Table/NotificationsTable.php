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

class NotificationsTable extends Table {
	
    public function initialize(array $config) {
        $this->setTable('notifications');
		//$this->addBehavior('Search.Search');
    }
	
	public function validationDefault(Validator $validator) {
		$validator
			->notEmpty('notification', __('Please enter notification.'));
		
		$validator
			->notEmpty('title', __('Please enter title.'));
		
		return $validator;
	}

}
