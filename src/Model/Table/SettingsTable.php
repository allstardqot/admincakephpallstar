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

class SettingsTable extends Table {
    

    public function initialize(array $config) {
        $this->setTable('settings');
		$this->addBehavior('Search.Search');
    }
	
	public function validationDefault(Validator $validator) {
		$validator
			->add('admin_email',[
				'valid'	=>	[
					'rule'		=>	'email',
					'message'	=>	__('Please enter valid email address.')
				]
			]);
		
		$validator
			->allowEmpty('admin_background')
			->add('admin_background', [
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
			]);
		
		return $validator;
	}

}
