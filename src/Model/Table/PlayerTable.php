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

class PlayerTable extends Table {
    
    public function initialize(array $config) {
        $this->setTable('players');
		$this->addBehavior('Search.Search');
		$this->belongsTo('Teams');
		$this->belongsTo('Positions');
		
    }
	


}
