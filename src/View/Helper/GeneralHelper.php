<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\View\Helper;

use Cake\Core\App;
use Cake\Core\Exception\Exception;
use Cake\View\Helper;
use Cake\View\View;
use Cake\ORM\TableRegistry;
use Cake\Cache\Cache;
 
class GeneralHelper extends Helper {

    /**
     * This method is used to check nav opt is active or not
     * @method activeNav
     * @param  string    $controller controller name
     * @param  Array    $action    contains action names
     * @return string
     */
    public function activeNav($controller, $actions = NULL) {
		$response = FALSE;

		$curr_controller = isset($this->request->params['controller']) ? $this->request->params['controller'] : '';
		$curr_action = isset($this->request->params['action']) ? $this->request->params['action'] : '';
		 
		if (!is_array($actions)) {
			$actions = array($actions);
		}
		if (!is_array($controller)) {
			$controller = array($controller);
		}

		if (is_array($controller) && in_array($curr_controller, $controller)&& is_array($actions) && in_array($curr_action, $actions)) {
			$response = TRUE;
		}
		return $response ? 'active' : '';
    }
}
