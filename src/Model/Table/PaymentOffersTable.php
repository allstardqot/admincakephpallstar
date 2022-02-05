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

class PaymentOffersTable extends Table {
	
    public function initialize(array $config) {
        $this->setTable('coupon_codes');
		$this->addBehavior('Search.Search');
		$this->hasMany('UserCouponCodes');
    }
	
	public function validationDefault(Validator $validator) {
		$validator
			->notEmpty('coupon_code', __('Please enter coupon code.'))
			->add('coupon_code', [
				'unique'	=>	[
					'rule'		=>	'validateUnique',
					'provider'	=>	'table',
					'message'	=>	__('Coupon code already exist.',true)
				]
			]);
		
		$validator
			->notEmpty('min_amount', __('Please enter minimum amount.'));
		
		$validator
			->notEmpty('max_cashback_amount', __('Please enter maximum cashback amount.'));
		
		$validator
			->notEmpty('max_cashback_percent', __('Please enter maximum cashback percent.'));
		
		$validator
			->notEmpty('usage_limit', __('Please enter usage limit'));
		
		$validator
			->notEmpty('per_user_limit', __('Please enter per user limit'));
		
		$validator
			->notEmpty('expiry_date', __('Please select expiry date'));
		
		$validator
			->notEmpty('status', __('Please select status'));
		
		return $validator;
	}

}
