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

class SubscribeUsersTable extends Table {
	/*
	 * Login Validation Rules
	 */

	public function initialize(array $config) {
		$this->setTable('subscribe_users');
	}

	/*
	 * Update Mobile Validation
	 */
	public function validationSubscribe(Validator $validator) 
	{
		$validator
			->notEmpty('email', __('Email is required'))
			->add('email', 'validFormat', ['rule' => 'email', 'message' => __('Valid email is required')])
			->add('email', 'unique', ['rule' => 'validateUnique', 'provider' => 'table', 'message' => __('E-mail already exist')])
			
			->notEmpty('password', __('Password is required'));
		return $validator;
	}
	public function validationContact(Validator $validator) 
	{
		$validator
				->notEmpty('name', __('Name is required'));
		$validator
				->notEmpty('email', __('Email is required'))
				->add('email', 'validFormat', ['rule' => 'email', 'message' => __('E-mail must be valid')]);
		$validator
				->notEmpty('subject', __('Subject is required'));
		$validator
				->notEmpty('message', __('Message is required'));
		return $validator;
	}

	public function validationSendLink(Validator $validator) {
		$validator
			->notEmpty('mobile_number', 'Mobile number cannot be empty')
			->add('mobile_number', 'unique', ['rule' => 'validateUnique', 'provider' => 'table', 'message' => __('You are already subscribed to our newsletter.')])
			->add('mobile_number', [
				'numeric' => [
					'rule' => ['numeric'],
					'message' => __('Mobile number should be numeric.')
				],
				'minLength' => [
					'rule' => ['minLength', 10],
					'last' => true,
					'message' => __('Mobile number must be at least 10 digit.')
				],
				'maxLength' => [
					'rule' => ['maxLength', 10],
					'message' => __('Mobile number must not be longer than 10 digits.')
				]
			]);
		return $validator;
    }
}
