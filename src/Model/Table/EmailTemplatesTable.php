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

class EmailTemplatesTable extends Table {
    

    public function initialize(array $config) {
        $this->setTable('email_templates');
		$this->addBehavior('Search.Search');
    }
	
	public function validationDefault(Validator $validator) {
		$validator
			->notEmpty('email_name', __('Email name required.'));
		
		$validator
			->notEmpty('subject', __('Subject name required.'))
			->add('subject', [
				'unique' => [
					'rule'		=>	'validateUnique',
					'message'	=>	__('Subject already exits.'),
					'provider'	=>	'table'
				]
			]);
		
		$validator
			->notEmpty('template', __('Template is required.'));
		
		return $validator;
	}

}
