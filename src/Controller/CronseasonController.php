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


class CronseasonController extends AppController {
	
	public function initialize() {
		$this->loadModel('Seasons');
		parent::initialize();
		$this->Auth->allow();
	}

	public function beforeFilter(Event $event) {
		parent::beforeFilter($event);
		$this->loadComponent('General');
    }
	
	//current season
	public function getseason() {	
		$this->autoRender = false;
		error_reporting(0);
		ini_set('max_execution_time', 1500);

		$url = 'https://api.sportsdata.io/api/nfl/fantasy/json/Timeframes/current?key=f3f9384b1ef94f368ad04721c82b9573';
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
		//$ApiSeason=!empty($response[0]['ApiSeason'])?$response[0]['ApiSeason']:'';
		$Season=!empty($response[0]['Season'])?$response[0]['Season']:'';
		$week=!empty($response[0]['Week'])?$response[0]['Week']:'';

		$this->seasonWeek($Season,$week);
		//$this->getPlayerData($Season,$week);
	}

	//get week using season
	public function seasonWeek($Season,$week){
		if(!empty($Season)){
			$url1 = 'https://api.sportsdata.io/api/nfl/fantasy/json/Byes/'.$Season.'?key=f3f9384b1ef94f368ad04721c82b9573';

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url1);
			curl_setopt($ch, CURLOPT_ENCODING, '');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			$weekResponse = curl_exec($ch);
			$error_msg = '';
			if (curl_error($ch)) {
				$error_msg = curl_error($ch);
			}
			$weekResponse = json_decode($weekResponse, true);
			$flag=false;
			foreach($weekResponse as $key=>$value){
				$url2 = 'https://api.sportsdata.io/v3/nfl/projections/json/PlayerGameProjectionStatsByWeek/'.$value['Season'].'/'.$value['Week'].'?key=f6bdf480efc749a2bafec50f68e08976';
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url2);
				curl_setopt($ch, CURLOPT_ENCODING, '');
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				$playerResponse = curl_exec($ch);
				$error_msg = '';
				if (curl_error($ch)) {
					$error_msg = curl_error($ch);
				}
				$playerResponse = json_decode($playerResponse, true);

				/*$record	=	$this->Seasons->find()
				->where(['season'=>$value['Season'],'Week'=>$value['Week']])
				->first();
				if(empty($record)){*/
					$IsGameOver=!empty($playerResponse[0]['IsGameOver'])?$playerResponse[0]['IsGameOver']:'0';
					$record	=	$this->Seasons->find()
								->where(['season'=>$value['Season'],'week'=>$value['Week']])
								->first();
					if($record){
						$svalue	=	$this->Seasons->get($record->id);
					}else{
						$svalue	=	$this->Seasons->newEntity();
					}
					$svalue['season']=$value['Season'];
					$svalue['week']=$value['Week'];
					$svalue['game_over_status']=$IsGameOver;
					if ($this->Seasons->save($svalue)) {
						$flag=true;
					}
				//}
			}
			if($flag){
				$this->getPlayerData($Season,$week);
			}
		}
	}

	//player data acoording season and week
	public function getPlayerData($Season,$week) {
		//echo $Season;die;
		$record	=	$this->Seasons->find()
				->where(['season'=>$Season,'week'=>$week])
				->first();
		$nextWeek='';
		if($record->id){
			$idd=$record->id+1;
			$seasonData	=	$this->Seasons->get($idd);
			$nextWeek=!empty($seasonData['week'])?$seasonData['week']:'';
		}

		for($i=0;$i<2;$i++){
				if($i==1){
					$week=$nextWeek;
				}
				error_reporting(0);
				ini_set('max_execution_time', 1500);

				//$url = 'https://api.sportsdata.io/api/nfl/fantasy/json/PlayerGameStatsByWeek/2020REG/2?key=5689d1870dde484f9358f2b1d9ee3521';
				$url = 'https://api.sportsdata.io/v3/nfl/projections/json/PlayerGameProjectionStatsByWeek/'.$Season.'/'.$week.'?key=f6bdf480efc749a2bafec50f68e08976';
		
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

				$this->loadModel('ApiPlayers');

				if(!empty($response)){
					foreach($response AS $key => $item){

						$GameKey = (isset($item['GameKey'])) ? $item['GameKey'] : 0;
						$SeasonType = (isset($item['SeasonType'])) ? $item['SeasonType'] : 0;
						$Season = (isset($item['Season'])) ? $item['Season'] : 0;
						$GameDate = (isset($item['GameDate'])) ? $item['GameDate'] : '0000-00-00';
						$Week = (isset($item['Week'])) ? $item['Week'] : 0;
						$TeamID = (isset($item['TeamID'])) ? $item['TeamID'] : 0;
						$Team = (isset($item['Team'])) ? $item['Team'] : '';
						$OpponentID = (isset($item['OpponentID'])) ? $item['OpponentID'] : 0;
						$Opponent = (isset($item['Opponent'])) ? $item['Opponent'] : '';
						$HomeOrAway = (isset($item['HomeOrAway'])) ? $item['HomeOrAway'] : '';
						$PlayerID = (isset($item['PlayerID'])) ? $item['PlayerID'] : 0;
						$PlayerGameID = (isset($item['PlayerGameID'])) ? $item['PlayerGameID'] : 0;
						$Number = (isset($item['Number'])) ? $item['Number'] : 0;
						$Name = (isset($item['Name'])) ? $item['Name'] : '';

						$Position = (isset($item['Position'])) ? $item['Position'] : '';
						$FantasyPointsPPR = (isset($item['FantasyPointsPPR'])) ? $item['FantasyPointsPPR'] : 0;

						$FantasyPosition = (isset($item['FantasyPosition'])) ? $item['FantasyPosition'] : '';
						$FantasyPoints = (isset($item['FantasyPoints'])) ? $item['FantasyPoints'] : 0;
						
						$Played = (isset($item['Played'])) ? $item['Played'] : 0;
						$Started = (isset($item['Started'])) ? $item['Started'] : 0;

						$record	=	$this->ApiPlayers->find()
						->where(['PlayerID'=>$PlayerID,'Week'=>$Week])
						->first();
						if($record){
							$seriesData	=	$this->ApiPlayers->get($record->id); 
							$seriesData['modified']=	date('Y-m-d H:i:s'); 
						}  else{ 
							$seriesData	=	$this->ApiPlayers->newEntity();
							$seriesData['created']		=	date('Y-m-d H:i:s');
						}

						$seriesData['GameKey']=	$GameKey; 
						$seriesData['SeasonType']=	$SeasonType;
						$seriesData['Season']=	$Season;
						$seriesData['GameDate']=	$GameDate;
						$seriesData['Week']=	$Week;
						$seriesData['TeamID']=	$TeamID; 
						$seriesData['Team']=	$Team;
						$seriesData['OpponentID']=	$OpponentID;
						$seriesData['Opponent']=	$Opponent;
						$seriesData['HomeOrAway']=	$HomeOrAway;
						$seriesData['PlayerID']=	$PlayerID; 
						$seriesData['PlayerGameID']=	$PlayerGameID;
						$seriesData['Number']=	$Number;
						$seriesData['Name']=	$Name;
						$seriesData['Position']=	$Position;
						$seriesData['FantasyPointsPPR']=	$FantasyPointsPPR; 
						$seriesData['FantasyPosition']=	$FantasyPosition; 
						$seriesData['FantasyPoints']=	$FantasyPoints;
						$seriesData['Played']=	$Played;
						$seriesData['Started']=	$Started;

						if ($this->ApiPlayers->save($seriesData)) {
							//echo 'Series saved successfully.<br/>';
						}
					}
				}
			}
	}
	
}
