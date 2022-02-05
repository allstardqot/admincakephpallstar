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
use Cake\Auth\WeakPasswordHasher;

class SeasonsTable extends Table {
	/*
	 * Login Validation Rules
	 */

	public function initialize(array $config) {
		$this->setTable('seasons');
		
	}

}
