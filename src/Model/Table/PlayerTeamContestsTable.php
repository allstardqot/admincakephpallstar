<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SeriesTable
 *
 * @author Gayas
 */

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
// use Cake\Validation\Validator;

class PlayerTeamContestsTable extends Table {
    

    public function initialize(array $config) {
        $this->setTable('player_team_contests');
		$this->belongsTo('Contest');
		$this->belongsTo('Users');
		$this->belongsTo('PlayerTeams');
		$this->belongsTo('Drafts');
		
    }

}
	