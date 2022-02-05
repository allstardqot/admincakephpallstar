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


class CronController extends AppController {
	
	public function initialize() {
		$this->loadModel('Seasons');
		$this->loadModel('SeasonNew');
		$this->loadModel('ApiPlayers');
		$this->loadModel('PlayerTeams');
		$this->loadModel('CustomBreakup');
		$this->loadModel('Users');
		$this->loadModel('Drafts');
		$this->loadModel('Settings');
		$this->loadModel('Notifications');
		parent::initialize();
		$this->Auth->allow();
	}

	public function beforeFilter(Event $event) {
		parent::beforeFilter($event);
		$this->loadComponent('General');
    }

	public function getWeekData() {
		$this->loadModel('ApiPlayers');
		$this->loadModel('Seasons');
		
		$this->autoRender = false;
		error_reporting(0);
		ini_set('max_execution_time', 1500);

		$url = 'https://www.michaelb.dev/nfl/meta-data';
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
		
		$ApiSeason=!empty($response['AvailableSeasons'])?$response['AvailableSeasons']:'';
		
		$currentSeason='';
		foreach($ApiSeason as $key=>$svalue){
			if($svalue['IsCurrent']){
				$currentSeason=$svalue['Season'];
			}

		}

		$record	=	$this->Seasons->find()->where(['season'=>$currentSeason])->toArray();
		if(!empty($record)){
			foreach($record as $skey=>$svalue){
				$url = 'https://www.michaelb.dev/nfl/projections/'.$svalue['season'].'/'.$svalue['week'].'/'.$svalue['season_type'].'/';

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
				$index = 0;
				if(!empty($response['Data'])){
					foreach($response['Data'] AS $key => $item){
						$GameDate=(isset($item['GameDate'])) ? str_replace(".000Z","",$item['GameDate']): '0000-00-00';
						
						$HomeOrAway = (isset($item['TeamIsHome'])) ? $item['TeamIsHome'] : '';
						if($HomeOrAway){
							$HomeOrAway='HOME';
						}else{
							$HomeOrAway='AWAY';
						}
						$GameKey = (isset($item['GameKey'])) ? $item['GameKey'] : 0;
						$SeasonType = (isset($svalue['season_type'])) ? $svalue['season_type'] : 0;
						$Season = (isset($item['Season'])) ? $item['Season'] : 0;
						$Week = (isset($item['Week'])) ? $item['Week'] : 0;
						$TeamID = (isset($item['TeamID'])) ? $item['TeamID'] : 0;
						$Team = (isset($item['Team'])) ? $item['Team'] : '';
						$OpponentID = (isset($item['OpponentID'])) ? $item['OpponentID'] : 0;
						$Opponent = (isset($item['Opponent'])) ? $item['Opponent'] : '';
						//$HomeOrAway = (isset($item['TeamIsHome'])) ? $item['TeamIsHome'] : '';
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
						->where(['PlayerID'=>$PlayerID,'Week'=>$Week,'Season'=>$Season,'SeasonType'=>$SeasonType])
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
							//echo 'Series saved successfully.<br/>';die;
						}

						$index++;

					}
				}
			}
		}
		$this->updateimage();
		echo 'Done';die;
	}

	//current season
	public function getseason() {	

		$this->autoRender = false;
		$flag=false;
		error_reporting(0);
		ini_set('max_execution_time', 1500);

		$url = 'https://www.michaelb.dev/nfl/meta-data';
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
		$ApiSeason=!empty($response['AvailableSeasons'])?$response['AvailableSeasons']:'';
		
		foreach($ApiSeason as $key=>$svalue){
			if($svalue['IsCurrent']){
				$currentSeason=$svalue['Season'];
			}

		}
		$Season=!empty($response['AvailableWeeks'])?$response['AvailableWeeks']:'';
		//pr($Season);die;

		foreach($Season as $key=>$data){
				if($data['Season']==$currentSeason){
					$checkGame	=	$this->Seasons->find()
											->where(['season'=>$data['Season'],'week'=>$data['Week'],'season_type'=>$data['SeasonType']])
											->first();
					if($checkGame['game_over_status']==1){
						continue;
					}
					//echo $checkGame['game_over_status'].'---'.$data['Week'];die;
					$url2 = 'https://www.michaelb.dev/nfl/projections/'.$data['Season'].'/'.$data['Week'].'/'.$data['SeasonType']."/";
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
						$IsGameOver=!empty($playerResponse['Data'][0]['IsGameOver'])?$playerResponse['Data'][0]['IsGameOver']:'0';

						$record	=	$this->Seasons->find()
											->where(['season'=>$data['Season'],'week'=>$data['Week'],'season_type'=>$data['SeasonType']])
											->first();
						if($record){
							$svalue	=	$this->Seasons->get($record->id);
						}else{
							$svalue	=	$this->Seasons->newEntity();
						}
						$svalue['season']=$data['Season'];
						$svalue['season_type']=$data['SeasonType'];
						$svalue['week']=$data['Week'];
						$svalue['game_over_status']=$IsGameOver;
						$flag=true;
						$this->Seasons->save($svalue);
					
				}
			
		}
		echo "done";die;
	}

	//new setpoints
	public function setpoints() {	
		$this->autoRender=false;
		$playerteam=$this->PlayerTeams->find()
					->where(['is_croned'=>'0'])
					->toArray();
		ini_set('max_execution_time', 1500);
		
		$flag=false;
		foreach($playerteam as $key =>$value){
			//echo $value['draft_id'];die;
			$draft_data = $this->Drafts->find()
			->select(['week','draft_data','seasontype','season'])
			->where(['id'=>$value['draft_id']])
			->first();
			
			$draft_arr=json_decode($draft_data['draft_data'],true);
			$draft_week=isset($draft_data['week'])?$draft_data['week']:'';

			$seasontype=isset($draft_data['seasontype'])?$draft_data['seasontype']:'';
			$season=isset($draft_data['season'])?$draft_data['season']:'';
			//pr($draft_arr);//pr($draft_week);pr($seasontype);

			$final_points=$this->getplayerrank($draft_arr,$draft_week,$seasontype,$season);
			//pr($final_points);
			
			$playersData=json_decode($value['player_data'],true);
			$totalCount=count($playersData);
			//pr($playersData);
			
			$userPoints=0;
			foreach($playersData as $pkey => $pvalue){
				$gameOver=true;
				$upoints=!empty($final_points[$pvalue['team']][$pvalue['player_id']])?$final_points[$pvalue['team']][$pvalue['player_id']]:'';
				if(empty($upoints)){
					$upoints=!empty($final_points['Flex'][$pvalue['player_id']])?$final_points['Flex'][$pvalue['player_id']]:'tt';
					// if(){
					// 	pr($final_points);die;
					// }
				}
				//pr($upoints);
				$playerteams=$this->ApiPlayers->find()
					->select(['FantasyPoints','Season','Week'])
					->where(['id'=>!empty($pvalue['apitable_id'])?$pvalue['apitable_id']:''])
					->first();
				
					$record	=	$this->Seasons->find()
					->select(['game_over_Status'])
					->where(['season'=>$playerteams->Season,'week'=>$playerteams->Week])
					->first();
					
					if($record->game_over_Status==0){
						$gameOver=false;
					}
					if($upoints==1){
						$userPoints+=$upoints;
					}
			}
			//echo $userPoints."ttttt";die;
			
			$perctg=$userPoints/$totalCount*100;
			
			$admin_setting=$this->Settings->find()
							->first();
			$bonusPoint='';
			if($perctg>=90){
				$bonusPoint=$admin_setting['bonus_full'];
			}elseif($perctg>80 && $perctg <= 90){
				$bonusPoint=$admin_setting['bonus_second_last'];
			}elseif($perctg>70 && $perctg<=80){
				$bonusPoint=$admin_setting['bonus_last'];
			}
			
			$playerTeamData	=	$this->PlayerTeams->get($value['id']); 
			if($gameOver){
				$playerTeamData['is_croned']=1;
			}
			$playerTeamData['points']=$userPoints;
			$playerTeamData['bonus']=$bonusPoint;
			if($this->PlayerTeams->save($playerTeamData)){
				$flag=true;
			}
			
		}
		if($flag){
			$this->rankUpdate();
		}
	}

	public function getplayerrank($draft_arr,$draft_week,$seasontype,$season){
		$this->loadModel('ApiPlayers');
		$this->loadModel('Seasons');
		//pr($draft_arr);
		
		$display_name=$player_rank=[];
			foreach($draft_arr as $dkey=>$dValue){
				$players_points=[];
				$display_name[$dValue['filter_listing']]=$dValue['display_name'];

				foreach($dValue['team_member'] as $key=>$value){
					$players_inf = $this->ApiPlayers->find()
							->select(['id','FantasyPoints','Position'])
							->where(['PlayerID'=> $value,'Week'=>$draft_week,'Season'=>$season,'SeasonType'=>$seasontype] )
							->first();
					$players_points[$value]=$players_inf['FantasyPoints'];
				}
				arsort($players_points);
				$player_rank[$dValue['display_name']]=$players_points;
			}
			//pr($display_name);
			$final_points=[];
			foreach($player_rank as $tkey=>$tValue){
				$tmpPoint=$ranks=0;
				$pointsArr=[];
				foreach($tValue as $key=>$value){
					$pointOfTeam=$value;
						
						if($tmpPoint == $pointOfTeam) {
							$rank	=	$ranks;
						} else {
								$ranks++;
							$rank	=	$ranks;
						}
						$tmpPoint	=	$pointOfTeam;
						$pointsArr[$key]=$rank;	
				}
				$final_points[$tkey]=$pointsArr;
			}
		return $final_points;
	}

	// user rank update
	public function rankUpdate(){
		
		$contest	=	$this->PlayerTeams->find()
								->select(['contest_id','draft_id'])
								//->where(['is_croned'=>'0'])
								->group(['contest_id','draft_id'])->toArray();
								//pr($contest);die;
		$flag=false;
		if(!empty($contest)){
			foreach($contest as $key => $value) {
				$teamContest=	$this->PlayerTeams->find()
										->where(['contest_id'=>$value->contest_id,'draft_id'=>$value->draft_id])
										->order(['points'=>'DESC'])
										->toArray();
		
				if(!empty($teamContest)){
					$ranks=0;
					$tmpPoint	=	0;
					
					$counterRank = $counter	=	1;
					foreach($teamContest as $tkey=>$tvalue){
						if($tvalue['points']==0){
							continue;
						}
						$pointOfTeam=$tvalue['points'];
						
						if($tmpPoint == $pointOfTeam) {
							$counter++;
							$rank	=	$ranks;
						} else {
							if($counter > 1) {
								$ranks	+=	$counter;
							} else {
								$ranks++;
							}
							$rank	=	$ranks;
							$counter	=	1;
						}
						$tmpPoint	=	$pointOfTeam;

						$tvalue['previous_rank']	=	$tvalue['rank'];
						$tvalue['rank']	=	$rank;
						$tvalue['counter']	=	$counterRank;
						$this->PlayerTeams->id = $tvalue['id'];
						
						if($this->PlayerTeams->save($tvalue)){
							$flag=true;
						}
						$counterRank++;
					}
				}
			}
		}
		if($flag){
			$this->coinUpdate();
		}
	}
	// user winning coin update and add to wallet
	public function coinUpdate(){
		$this->autoRender=false;
		
		$contest	=	$this->PlayerTeams->find()
								->select(['id','contest_id','draft_id','rank','user_id','counter'])
								->where(['is_croned'=>'1','winning_distribute'=>'0'])
								->group(['contest_id','draft_id','rank'])
								->toArray();
		if(!empty($contest)){
			foreach($contest as $key => $value){
				$contests	=	$this->PlayerTeams->find()
									->select(['id','contest_id','draft_id','rank','user_id','counter','bonus'])
									->where(['contest_id'=>$value['contest_id'],'draft_id'=>$value['draft_id'],'rank'=>$value['rank']])
									->toArray();
				
				$totalUser=count($contests);
				$totalWinPrice=$defrank=0;
				foreach($contests as $ckey=>$cValue){
					$getRank=$cValue['rank'];
					if($getRank==$defrank){
						$getRank=$cValue['counter'];
					}

					$customBreakup=$this->CustomBreakup->find()
								->select(['start','end','price'])
								->where(['contest_id'=>$cValue['contest_id'],'start <='=>$getRank,'end >='=>$getRank])
								->first();
					if(!empty($customBreakup)){
						$totalWinPrice=$customBreakup['price'];
						$defrank=$cValue['rank'];

						$distributePrice=$totalWinPrice/$totalUser;
						
						$wValue	=	$this->PlayerTeams->get($cValue['id']); 
						$wValue['winning_coins']	=	$distributePrice+$wValue['bonus']; 
						if($this->PlayerTeams->save($wValue)){
							$uValue	=	$this->Users->get($cValue['user_id']); 
							$uValue['winning_wallet']+=$distributePrice+$wValue['bonus'];
							if($this->Users->save($uValue)){
								if($distributePrice > 0){
									$message="You have won ".$distributePrice." lfg coins in lfg draft";
									$this->loadModel('Transactions');
									$transData	=	$this->Transactions->newEntity();
									$transData['created']		=	date('Y-m-d H:i:s');
									$transData['txn_date']		=	date('Y-m-d H:i:s');
									$transData['txn_amount']		=	$distributePrice;
									$transData['user_id']		=	$cValue['user_id'];
									$transData['local_txn_id']		=	date('dmY').time();
									$transData['added_type']		=	WON_CONTEST;
									$transData['amount_status']		=	Add;

									if($result = $this->Transactions->save($transData) && $wValue['bonus'] > 0){
										if($wValue['bonus']!=0){
											$message.=" and Bonus added ".$wValue['bonus']." points";
											$transData	=	$this->Transactions->newEntity();
											$transData['created']		=	date('Y-m-d H:i:s');
											$transData['txn_date']		=	date('Y-m-d H:i:s');
											$transData['txn_amount']		=	$wValue['bonus'];
											$transData['user_id']		=	$cValue['user_id'];
											$transData['local_txn_id']		=	date('dmY').time();
											$transData['added_type']		=	Add_BONUS;
											$transData['amount_status']		=	Add;

											$result = $this->Transactions->save($transData);
										}
									}
									$this->PlayerTeams->find()
										->update()
										->set(['winning_distribute' => 1])
										->where(['id' =>$cValue['id']])
										->execute();
									
									$title="You have won";
									$this->sendNotificationFCMNew($cValue['user_id'],$message,$title);
									$this->userWinnigRank();
								}
							}
						}
					}

				}
			}
		}
		echo "done";die;
	}

	public function fantasypoint(){
		
		ini_set('max_execution_time', 1500);
		$url = 'https://www.michaelb.dev/nfl/meta-data';
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
		//pr($response);die;
		$ApiSeason=!empty($response['AvailableWeeks'])?$response['AvailableWeeks']:'';
		//pr($ApiSeason);die;
		$seasonType=$currentWeek=$currentSeason='';
		foreach($ApiSeason as $key=>$svalue){
			if($svalue['IsCurrent']){
				$currentSeason=$svalue['Season'];
				$currentWeek=$svalue['Week'];
				$seasonType=$svalue['SeasonType'];
			}

		}
		//echo $currentWeek.'-------'.$currentSeason;die;
		if(!empty($currentWeek) && !empty($currentSeason)){
	
			
				error_reporting(0);
				ini_set('max_execution_time', 1500);

				//$url = 'https://api.sportsdata.io/api/nfl/fantasy/json/PlayerGameStatsByWeek/2020REG/2?key=5689d1870dde484f9358f2b1d9ee3521';
				$url = 'https://www.michaelb.dev/nfl/stats/'.$currentSeason.'/'.$currentWeek.'/'.$seasonType.'/';
		
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
				// /echo "tyyyy";pr($response);die;
				if(!empty($response['Data'])){
					foreach($response['Data'] AS $key => $item){

						$Season = (isset($item['Season'])) ? $item['Season'] : 0;
						$GameDate=(isset($item['GameDate'])) ? str_replace(".000Z","",$item['GameDate']): '0000-00-00';
						$Week = (isset($item['Week'])) ? $item['Week'] : 0;
						
						$Team = (isset($item['Team'])) ? $item['Team'] : '';
						$HomeOrAway = (isset($item['TeamIsHome'])) ? $item['TeamIsHome'] : '';
						if($HomeOrAway){
							$HomeOrAway='HOME';
						}else{
							$HomeOrAway='AWAY';
						}
						$Opponent = (isset($item['Opponent'])) ? $item['Opponent'] : '';
						$seasonType = (isset($seasonType)) ? $seasonType : '';
						
						$PlayerID = (isset($item['PlayerID'])) ? $item['PlayerID'] : 0;
						$Name = (isset($item['Name'])) ? $item['Name'] : '';

						$Position = (isset($item['Position'])) ? $item['Position'] : '';
						$FantasyPointsPPR = (isset($item['FantasyPointsPPR'])) ? $item['FantasyPointsPPR'] : 0;

						$FantasyPosition = (isset($item['FantasyPosition'])) ? $item['FantasyPosition'] : '';
						$FantasyPoints = (isset($item['FantasyPoints'])) ? $item['FantasyPoints'] : 0;
						
						$Played = (isset($item['Played'])) ? $item['Played'] : 0;
						$Started = (isset($item['Started'])) ? $item['Started'] : 0;

						$record	=	$this->ApiPlayers->find()
						->where(['PlayerID'=>$PlayerID,'Week'=>$Week,'Season'=>$Season,'SeasonType'=>$seasonType])
						->first();
						if($record){
							$seriesData	=	$this->ApiPlayers->get($record->id); 
							$seriesData['modified']=	date('Y-m-d H:i:s'); 
						}  else{ 
							$seriesData	=	$this->ApiPlayers->newEntity();
							$seriesData['created']		=	date('Y-m-d H:i:s');
						}
						
							
							$seriesData['Season']=	$Season;
							$seriesData['SeasonType']=	$seasonType;
							$seriesData['GameDate']=	$GameDate;
							$seriesData['Week']=	$Week;
							$seriesData['Team']=	$Team;
							$seriesData['Opponent']=	$Opponent;
							$seriesData['HomeOrAway']=	$HomeOrAway;
							$seriesData['PlayerID']=	$PlayerID; 
							
							$seriesData['Name']=	$Name;
							$seriesData['Position']=	$Position;
							$seriesData['FantasyPointsPPR']=	$FantasyPointsPPR; 
							$seriesData['FantasyPosition']=	$FantasyPosition; 
							$seriesData['FantasyPoints']=	$FantasyPoints;
							$seriesData['Played']=	$Played;
							$seriesData['Started']=	$Started;
							
							if ($this->ApiPlayers->save($seriesData)) {
								//echo $seriesData['modified']."<br>";echo 'Series saved successfully.<br/>';
							}
						
					}
				}
		}
		$this->sendnotification();
	}

	//user match start notification
	public function sendnotification(){
		date_default_timezone_set("Asia/Bangkok");

		$player_team=$this->PlayerTeams->find()
					->where(["notifi_cron"=>'0'])
					->limit("100")
					->toArray();

		$curent_date=strtotime(date('Y-m-d H:i:s'));
		
		foreach($player_team as $key=>$value){
			$draft=$this->Drafts->find()
					->where(["id"=>$value['draft_id']])
					->first();
			$matchDate=strtotime($draft['startdate'].' '.$draft['starttime']);
			if($curent_date>=$matchDate){
				$message=$draft['name'].' contest is now live please check the game update.';
				$title=	$draft['name'].' is live';
			//echo $draft['startdate'].' '.$draft['starttime']."<br>";
				$notification	=	$this->Notifications->newEntity();
				$notification['status']=1;
				$notification['user_id']=$value['user_id'];
				$notification['created']=date('Y-m-d H:i:s');
				$notification['title']=$title;
				$notification['description']=$message;
				$this->Notifications->save($notification);

				$seriesData	=	$this->PlayerTeams->get($value['id']); 
				$seriesData['notifi_cron']=1;
				if($this->PlayerTeams->save($seriesData)){
					$this->sendNotificationFCMNew($value['user_id'],$message,$title);
				}
			}
		}
		echo "fine";die;
	}

	public function setImagePlayer() {
		$this->loadModel('ApiPlayers');
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

		$index = 0;
		if(!empty($response)){
			foreach($response AS $key => $item){
				if(!empty($item['PhotoUrl']) && !empty($item['PlayerID'])){
					$this->ApiPlayers->find()
					->update()
					->set(['Image' => $item['PhotoUrl']])
					->where(['PlayerID' => $item['PlayerID'] ])
					->execute();
				}
			}
		}
	}

	public function userWinnigRank(){
        		
		$status		=	false;
				
		$this->loadModel('Users');
		$role_id=	Configure::read('ROLES.User');
		$Users = $this->Users->find()
				->where(['role_id'=> $role_id] )
				->order(['winning_wallet'=>'DESC'])->toArray();

				$tmpPoint=$ranks=0;
				foreach($Users as $key => $value){
					if(!empty($value)){
						if($tmpPoint == $value->winning_wallet) {
							$rank	=	$ranks;
						} else {
								$ranks++;
							$rank	=	$ranks;
						}
						$this->Users->find()
						->update()
						->set(['rank' => $rank])
						->where(['id' => $value->id])
						->execute();
						$tmpPoint	=	$value->winning_wallet;						
					}
				}
			$status		=	true;

		$response_data	=	array('status'=>$status);
		
		echo json_encode(array('response' => $response_data));
		die;
	}

	public function updateimage(){
		$record	=	$this->ApiPlayers->find()
				->where(['image'=>''])
				->toArray();
		foreach($record as $key=>$value){
			if($value){
				$seriesData	=	$this->ApiPlayers->find()->where(['Image !='=>'','PlayerID'=>$value['PlayerID']])->first(); 
				if(!empty($seriesData)){
					$this->ApiPlayers->find()
						->update()
						->set(['Image' => $seriesData['Image']])
						->where(['PlayerID' => $value['PlayerID']])
						->execute();
				}
				
			}
		}
		echo "done";die;
	}


	
}
