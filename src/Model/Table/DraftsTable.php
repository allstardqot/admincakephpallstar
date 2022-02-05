<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UsersTable
 *
 * @author vijayj
 */

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use Cake\Auth\DefaultPasswordHasher;

class DraftsTable extends Table {

    /*
     * Login Validation Rules
     */
    public function initialize(array $config) {
        $this->setTable('drafts');
        $this->addBehavior('Search.Search');
		$this->hasMany('PlayerTeams');
    }

	/*
     * Update Mobile Validation
     */
    public function searchManager() {
        $searchManager = $this->behaviors()->Search->searchManager();
        $searchManager
			->like('name', ['before' => true, 'after' => true, 'field' => 'Drafts.name']);
        return $searchManager;
    }
	
    public function validationAddEdit(Validator $validator) {
		$validator
			->notEmpty('name', __('Draft name is required'));
			//->add('name', 'unique', ['rule' => 'validateUnique', 'provider' => 'table', 'message' => __('Draft already exist.')]);
		$validator
			->notEmpty('week', __('Please select week first.'));
        $validator
			->notEmpty('season', __('Please select season first.'));		
        $validator
			->notEmpty('seasontype', __('Please select seasonType first.'));
		return $validator;
	}

}
