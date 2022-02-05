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

class BannersTable extends Table {
    /*
     * Login Validation Rules
     */

    public function initialize(array $config) {
        $this->setTable('banners');
        $this->addBehavior('Search.Search');

    }

	/*
     * Update Mobile Validation
     */


    public function searchManager() {

        $searchManager = $this->behaviors()->Search->searchManager();
        $searchManager
                ->like('name', ['before' => true, 'after' => true, 'field' => 'Category.category_name'])
                ->add('start_date', 'Search.Compare', ['after' => true, 'operator' => '>=', 'field' => ['created']])
                ->add('end_date', 'Search.Compare', ['after' => true, 'operator' => '<=', 'field' => ['created']]);
        return $searchManager;
    }

    public function validationAddBanner(Validator $validator) {
        
        $validator
            ->notEmpty('banner_type', __('Please select banner type.'));
        
		$validator
			->notEmpty('sequence', __('Sequence number is required'))
			->add('sequence', 'unique', ['rule' => 'validateUnique', 'provider' => 'table', 'message' => __('Sequence number already exist.')]);
		
		$validator
			->notEmpty('image', __('Banner image is required'))
			->add('image', [
				'validExtension' => [
					'rule' => ['extension',['jpeg', 'png', 'jpg']], // default  ['gif', 'jpeg', 'png', 'jpg']
					'message' => __('Only jpeg, png, jpg file extension is allowed',true)
				],
				'fileSize' => [
					'rule' => array('fileSize', '<=', '5MB'),
					'message' => 'Category image size could not be greater than 5MB.',
					// 'allowEmpty' => TRUE,
				],
				'mimeType' => [
					'rule' => array('mimeType', array('image/gif', 'image/png', 'image/jpg', 'image/jpeg')),
					'message' => 'Please upload valid image with extension (gif, png, jpg).',
					// 'allowEmpty' => TRUE,
				],
                'width'     => [
                    'rule' => [$this, "checkImageSize"],
                    'message' => 'Image size should be 304x64.',

                ]
			])
			;
		
        return $validator;
    }
	
	public function checkImageSize($value,$context) {
		if(!empty($context['data']['image']['name'])) {
			list($width, $height, $type, $attr) = getimagesize($context['data']['image']['tmp_name']);
			if(($width < 304 || $height < 64)) {
				return false;
			} else {
				return true;
			}
		} else {
			return true;
		}
    }

    public function validationEditBanner(Validator $validator) {
        
        $validator
            ->notEmpty('banner_type', __('Please select banner type.'));
        
		$validator
			->notEmpty('sequence', __('Sequence number is required'))
			->add('sequence', 'unique', ['rule' => 'validateUnique', 'provider' => 'table', 'message' => __('Sequence number already exist.')]);
			
        $validator
            ->allowEmpty('image')
            ->add('image', [
                'validExtension' => [
                    'rule' => ['extension',['jpeg', 'png', 'jpg']], // default  ['gif', 'jpeg', 'png', 'jpg']
                    'message' => __('Only jpeg, png, jpg file extension is allowed')
                ],
				'fileSize' => [
					'rule' => array('fileSize', '<=', '5MB'),
					'message' => 'Image size could not be greater than 5MB.',
					'allowEmpty' => TRUE,
				],
				'mimeType' => [
					'rule' => array('mimeType', array('image/gif', 'image/png', 'image/jpg', 'image/jpeg')),
					'message' => 'Please upload valid image with extension (gif, png, jpg).',
					'allowEmpty' => TRUE,
				],
                'width'     => [
                    'rule' => [$this, "checkImageSize"],
                    'message' => 'Image size should be 600x400.',

                ]
            ]);
        
        return $validator;
    }

}
