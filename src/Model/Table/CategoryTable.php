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

class CategoryTable extends Table {

    /*
     * Login Validation Rules
     */
    public function initialize(array $config) {
        $this->setTable('category');
        $this->addBehavior('Search.Search');
    }

	/*
     * Update Mobile Validation
     */
    public function searchManager() {
        $searchManager = $this->behaviors()->Search->searchManager();
        $searchManager
			->like('name', ['before' => true, 'after' => true, 'field' => 'Category.category_name'])
			->add('start_date', 'Search.Callback', [
                'callback' => function ($query, $args, $filter) {
                    $args	=	$args['start_date'].' 00:00:00';
					$query->where(['created >='=>$args]);
                }]
            )
			->add('end_date', 'Search.Callback', [
                'callback' => function ($query, $args, $filter) {
                    $args	=	$args['end_date'].' 23:59:59';
					$query->where(['created <='=>$args]);
                }]
            );
			// ->add('end_date', 'Search.Compare', ['after' => true,'operator' => '<=', 'field' => ['created']]);
			// ->add('start_date', 'Search.Compare', ['after' => true,'operator' => '>=', 'field' => ['created']])
        return $searchManager;
    }
	
    public function validationAddEditCategory(Validator $validator) {
		$validator
			->notEmpty('category_name', __('Category name is required'))
			->add('category_name', 'unique', ['rule' => 'validateUnique', 'provider' => 'table', 'message' => __('Category already exist.')]);
		$validator
			->notEmpty('description', __('Description is required'));
		
		$validator
			->notEmpty('sequence', __('Sequence number is required'))
			->add('sequence', 'unique', ['rule' => 'validateUnique', 'provider' => 'table', 'message' => __('Sequence number already exist.')]);
			
		$validator
			->notEmpty('image','Please upload image.')
			->add('image',[
				'extension'	=>		[
					'rule'		=>	['extension',['jpeg', 'png', 'jpg']],
					'message'	=>	__('Please upload valid extension(jpg, jpeg, png) image.')
				],
				'fileSize' => [
					'rule' => array('fileSize', '<=', '5MB'),
					'message' => 'Category image size could not be greater than 5MB.',
				],
				'mimeType' => [
					'rule' => array('mimeType', array('image/gif', 'image/png', 'image/jpg', 'image/jpeg')),
					'message' => 'Please upload valid image with extension (gif, png, jpg).',
				],
			]);
		
		return $validator;
	}
	
    public function validationEditCategory(Validator $validator) {
		$validator
			->notEmpty('category_name', __('Category name is required'))
			->add('category_name', 'unique', ['rule' => 'validateUnique', 'provider' => 'table', 'message' => __('Category already exist.')]);
		$validator
			->notEmpty('description', __('Description is required'));
		
		$validator
			->notEmpty('sequence', __('Sequence number is required'))
			->add('sequence', 'unique', ['rule' => 'validateUnique', 'provider' => 'table', 'message' => __('Sequence number already exist.')]);
			
		$validator
			->allowEmpty('image')
			->add('image',[
				'extension'	=>		[
					'rule'		=>	['extension',['jpeg', 'png', 'jpg']],
					'message'	=>	__('Please upload valid extension(jpg, jpeg, png) image.')
				],
				'fileSize' => [
					'rule' => array('fileSize', '<=', '5MB'),
					'message' => 'Category image size could not be greater than 5MB.',
					'allowEmpty' => TRUE,
				],
				'mimeType' => [
					'rule' => array('mimeType', array('image/gif', 'image/png', 'image/jpg', 'image/jpeg')),
					'message' => 'Please upload valid image with extension (gif, png, jpg).',
					'allowEmpty' => TRUE,
				],
			]);
		
		return $validator;
	}

}
