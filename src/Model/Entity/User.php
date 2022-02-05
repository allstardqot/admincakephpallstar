<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of User
 *
 * @author vijayj
 */

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Auth\WeakPasswordHasher;

class User extends Entity {

	protected $_virtual = [ 'full_name' ];

	/* protected function _getFullName()
    {
        if(!empty($this->_properties) && isset($this->_properties['first_name']) && isset($this->_properties['last_name'])) {
            return $this->_properties['first_name']. ' ' .$this->_properties['last_name'];
        }
    } */

    //put your code here
    protected function _setPassword($password) {
        if (strlen($password) > 0) {
            return (new WeakPasswordHasher)->hash($password);
        }
    }

    

  

}
