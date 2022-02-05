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


class CronpointsController extends AppController {
	
	public function initialize() {
		$this->loadModel('ApiPlayers');
		$this->loadModel('PlayerTeams');
		$this->loadModel('CustomBreakup');
		$this->loadModel('Notifications');
		$this->loadModel('Users');
		$this->loadModel('Drafts');
		parent::initialize();
		$this->Auth->allow();
	}

	public function beforeFilter(Event $event) {
		parent::beforeFilter($event);
		$this->loadComponent('General');
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
			//echo $draft['startdate'].' '.$draft['starttime']."<br>";
				$notification	=	$this->Notifications->newEntity();
				$notification['status']=1;
				$notification['user_id']=$value['user_id'];
				$notification['created']=date('Y-m-d H:i:s');
				$notification['title']="Start Contest";
				$notification['description']="Your contest live please check match update.";
				$this->Notifications->save($notification);

				$seriesData	=	$this->PlayerTeams->get($value['id']); 
				$seriesData['notifi_cron']=1;
				$this->PlayerTeams->save($seriesData);
			}
		}
		echo "fine";die;
	}
	
	// user set points
	public function setpoints() {	
		$this->autoRender=false;
		$playerteam=$this->PlayerTeams->find()
					->where(['is_croned'=>'0'])
					->toArray();
					//pr($playerteam);die;
		$flag=false;
		foreach($playerteam as $key =>$value){
			$playersData=json_decode($value['player_data'],true);
			$userPoints=0;
			foreach($playersData as $pkey => $pvalue){
				
				$playerteams=$this->ApiPlayers->find()
					->select(['FantasyPoints'])
					->where(['id'=>!empty($pvalue['apitable_id'])?$pvalue['apitable_id']:''])
					->first();
				$userPoints+=$playerteams->FantasyPoints;
			}
			
			$playerTeamData	=	$this->PlayerTeams->get($value['id']); 
			$playerTeamData['points']=$userPoints;
			$playerTeamData['is_croned']=1;
			if($this->PlayerTeams->save($playerTeamData)){
				$flag=true;
			}
			
		}
		if($flag){
			$this->rankUpdate();
		}
	}
	// user rank update
	public function rankUpdate(){
		
		$contest	=	$this->PlayerTeams->find()
								->select(['contest_id','draft_id'])
								->group(['contest_id','draft_id'])->toArray();
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
								->group(['contest_id','draft_id','rank'])
								->toArray();
		foreach($contest as $key => $value){
			$contests	=	$this->PlayerTeams->find()
								->select(['id','contest_id','draft_id','rank','user_id','counter'])
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
				    $wValue	=	$this->Users->get($cValue['id']); 

					$wValue['winning_coins']	=	$distributePrice;
					if($this->PlayerTeams->save($wValue)){
						$uValue	=	$this->Users->get($cValue['user_id']); 
						$uValue['winning_wallet']+=$distributePrice;
						$this->Users->save($uValue);
					}
				}

			}
		}
		echo "done";die;
	}

	
}
