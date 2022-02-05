<?php

namespace App\Controller;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Http\ServerRequest;
use Cake\Routing\Router;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Filesystem\File;
use Cake\Event\Event;
use Cake\Datasource\ConnectionManager;


class CronimageController extends AppController {
	
	private $connection;
	public function initialize() {
		parent::initialize();
		$this->Auth->allow();
		$this->loadModel('ApiPlayers');

		$this->connection=ConnectionManager::get('default');
	}

	public function beforeFilter(Event $event) {
		parent::beforeFilter($event);
		$this->loadComponent('General');
    }
	
	public function setImagePlayer() {
		$this->autoRender = false;
		error_reporting(0);
		ini_set('max_execution_time', 1500);

		//$url = 'https://api.sportsdata.io/api/nfl/fantasy/json/PlayerGameStatsByWeek/2020REG/2?key=5689d1870dde484f9358f2b1d9ee3521';
		$url = 'https://api.sportsdata.io/api/nfl/fantasy/json/Players?key=f3f9384b1ef94f368ad04721c82b9573';

        //echo "URL: $url \n";die;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_ENCODING, '');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        $error_msg = '';
        if (curl_error($ch)) {
			$error_msg = curl_error($ch);
        }
        $response = json_decode($response, true);
		
        curl_close($ch);

		
		//echo "<pre>";print_r($response);echo "</pre>";die;
		//$this->loadModel('ApiPlayers');
		$index = 0;
		if(!empty($response)){
			foreach($response AS $key => $item){
				if(!empty($item['PhotoUrl']) && !empty($item['PlayerID'])){
					// $update=$this->connection->update("api_players",['Image'=>$item['PhotoUrl']],['PlayerID'=>$item['PlayerID']]);
					// if($update){
					// 	echo $item['PlayerID'].'----Done';die;
					// }
					$this->ApiPlayers->find()
					->update()
					->set(['Image' => $item['PhotoUrl']])
					->where(['PlayerID' => $item['PlayerID'] ])
					->execute();
				}
			}
		}
	}
	public function setapi() {
		$this->autoRender = false;
		error_reporting(0);
		ini_set('max_execution_time', 1500);

		//$url = 'https://api.sportsdata.io/api/nfl/fantasy/json/PlayerGameStatsByWeek/2020REG/2?key=5689d1870dde484f9358f2b1d9ee3521';
		$url = 'https://www.michaelb.dev/nfl/projections/2021/18/';

        //echo "URL: $url \n";die;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_ENCODING, '');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        $error_msg = '';
        if (curl_error($ch)) {
			$error_msg = curl_error($ch);
        }
        $response = json_decode($response, true);
		pr($response);die;
        curl_close($ch);
	}

	
}
