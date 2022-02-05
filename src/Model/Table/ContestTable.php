<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ContestTable
 *
 * @author GayasuddinK
 */

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use Cake\Auth\DefaultPasswordHasher;

class ContestTable extends Table {
    /*
     * Login Validation Rules
     */

	public function initialize(array $config) {
		$this->setTable('contest');
		$this->addBehavior('Search.Search');
        $this->hasMany('MatchContest');
        $this->hasMany('KmatchContest');
        $this->hasMany('SmatchContest');
		$this->belongsTo('category');
		$this->hasMany('CustomBreakup');
		$this->hasOne('UserContests');
	}

	/*
     * Update Mobile Validation
     */
    public function searchManager() {
        $searchManager = $this->behaviors()->Search->searchManager();
        $searchManager
			->value('category_id', ['before' => true, 'after' => true, 'field' => 'Contest.category_id'])
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
			// ->add('start_date', 'Search.Compare', ['after' => true, 'operator' => '>=', 'field' => ['created']])
			// ->add('end_date', 'Search.Compare', ['after' => true, 'operator' => '<=', 'field' => ['created']]);
        return $searchManager;
    }
	
    public function validationAddEditContest(Validator $validator) {
        $validator
                ->notEmpty('winning_amount', __('Winning amount is required'))
                ->add('winning_amount', [
                    'winning_amount' => [
                        'rule' => ['numeric'],
                        'message' => __('Winning amount should be numeric')
                    ]
                ]);
        $validator
                ->notEmpty('contest_type', __('Contest type is required'));
        $validator
                ->notEmpty('category_id', __('Category name is required'));
        $validator
                ->notEmpty('contest_size', __('Contest size is required'));
        //$validator
                //->notEmpty('min_contest_size', __('MInimum contest size is required'));
        $validator
                ->notEmpty('entry_fee', __('Entry fee is required'))
                ->add('entry_fee', [
                    'entry_fee' => [
                        'rule' => ['numeric'],
                        'message' => __('Entry fee should be numeric')
                    ]
                ]);
			
        return $validator;
    }

}
