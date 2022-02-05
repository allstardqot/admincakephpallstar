<?php
namespace App\View\Helper;
 
use Cake\View\Helper;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;


class CustomHelper extends Helper {

    public function getCategoryName($id = NULL) {
        $Categories	=	TableRegistry::get('Category');
        $list		=	$Categories->find('all',array('conditions'=>array('Category.id'=>$id)))->first();
        return $list['category_name'];
    }

    public function getCategoryImage($id = NULL) {
        $Categories	=	TableRegistry::get('Category');
        $list		=	$Categories->find('all',array('conditions'=>array('Category.id'=>$id)))->first();
        return $list['image'];
    }

    public function getCategoryDetails($id = NULL) {
        $Categories	=	TableRegistry::get('Category');
        $list		=	$Categories->find('all',array('conditions'=>array('Category.id'=>$id)))->first();
        return $list;
    }

    public function getNoOfContest($id = NULL) {
    	$count = '0';
        $MatchContest	=	TableRegistry::get('MatchContest');
        $count			=	$MatchContest->find('all', ['conditions' => ['match_id' => $id]])->count();
        return $count;
    }
	
    public function getNoOfAutoContest($id = NULL) {
    	$count = '0';
        $MatchContest	=	TableRegistry::get('MatchContest');
        $count			=	$MatchContest->find('all', ['conditions' => ['match_id' => $id,'Contest.is_auto_create'=>2]])->contain(['Contest'=>['fields'=>['id','is_auto_create']]])->count();
        return $count;
    }
	
    public function totalUser($id = NULL) {
    	$count	=	'0';
        $role_id=   Configure::read('ROLES.User');
        $Users	=	TableRegistry::get('Users');
        $count	=	$Users->find('all', ['conditions' => ['Users.role_id' => $role_id]])->count();
        return $count;
    }

    public function totalActiveUser() {
        $date    =   date('Y-m-d');
        $count   =   '0';
        $Users   =   TableRegistry::get('PlayerTeamContests');
        $count   =   $Users->find('all', ['conditions' => ['DATE(created)' => $date]])->group(['user_id'])->count();
        return $count;
    }

    public function totalDeactiveUser() {
        $count   =   '0';
        $Users   =   TableRegistry::get('Users');
        $count   =   $Users->find('all', ['conditions' => ['role_id' => '2','status' => '0']])->count();
        return $count;
    }

    public function totalUnverifiedUsers() {
        $count   =   '0';
        $Users   =   TableRegistry::get('Users');
        $count   =   $Users->find()->where(['role_id' => '2','OR'=>['PenAadharCard.is_verified'=>INACTIVE,'BankDetails.is_verified'=>INACTIVE]])->group(['Users.id'])->contain(['PenAadharCard','BankDetails'])->count();
        return $count;
    }

    public function totalNewUser() {
        $date    =   date('Y-m-d');
        $count   =   '0';
        $Users   =   TableRegistry::get('Users');
        $count   =   $Users->find('all', ['conditions' => ['role_id' => '2','DATE(created)' => $date]])->count();
        return $count;
    }

    public function totalWithdrawalReq() {
        $WithdrawRequests=   TableRegistry::get('WithdrawRequests');
        $result =   $WithdrawRequests->find('all')
                    ->where(['amount !='=>'','WithdrawRequests.request_status'=>'0'])
                    ->count();
        
        return $result;
    }

    public function todayjoinedContest() {
        $date    =   date('Y-m-d');
        $count   =   '0';
        $Users   =   TableRegistry::get('Contest');
        $count   =   $Users->find('all')->where(['is_auto_create'=>1,'DATE(created)' => $date])->count();
        return $count;
    }

    public function TodayTotalDeposit() {
        $date    =   date('Y-m-d');
        $amount  =   0;
        $Users   =   TableRegistry::get('Transactions');
        $cashArr =   $Users->find('all', ['conditions' => ['added_type' => CASH_DEPOSIT,'DATE(txn_date)'=>$date]])->toArray();
        if(!empty($cashArr)){
            foreach ($cashArr as $value) {
                $amount += $value->txn_amount;
            }
        }
        return $amount;
    }

    public function currentMnthTotalDeposit() {
        $amount    =   0;
        $startDate = date('Y-m-01');
        $endDate   = date('Y-m-t');
        $Users     =   TableRegistry::get('Transactions');
        $cashArr   =   $Users->find('all', ['conditions' => ['added_type' => CASH_DEPOSIT,'DATE(txn_date) >='=>$startDate,'DATE(txn_date) <='=>$endDate]])->toArray();
        if(!empty($cashArr)){
            foreach ($cashArr as $value) {
                $amount += $value->txn_amount;
            }
        }
        return $amount;
    }

    public function currentMnthTotalWithdrawal() {
        $startDate = date('Y-m-01');
        $endDate   = date('Y-m-t');
        $totalEarning = 0;        
        $type = 1;
        $conn = ConnectionManager::get('default');
        $stmt = $conn->execute("SELECT sum(refund_amount) as ttl FROM withdraw_requests where request_status = $type AND date_format(modified,'%Y-%m-%d') >= '".$startDate."' AND date_format(modified,'%Y-%m-%d') <= '".$endDate."'");
        
        $results = $stmt ->fetchAll('assoc');
        $totalEarning += $results[0]['ttl'];

        return $totalEarning;
    }

    public function totalContest($id = NULL) {
        $count	=	'0';
        $Contest=	TableRegistry::get('contest');
        $count	=	$Contest->find('all')->where(['is_auto_create'=>1])->count();
        return $count;
    }

    public function totalActiveContest($id = NULL) {
        $count	=	'0';
        $Contest=	TableRegistry::get('contest');
        $count	=	$Contest->find('all')->where(['is_auto_create'=>1,'status'=>1])->count();
        return $count;
    }

    public function totalInactiveContest($id = NULL) {
    	$count	=	'0';
        $Contest=	TableRegistry::get('contest');
        $count  =   $Contest->find('all')->where(['is_auto_create'=>1,'status'=>0])->count();
        return $count;
    }

    public function getPanAadhrDetails($id = NULL) {
        $table	=	TableRegistry::get('Users');
        $list	=	$table->find('all')->where(['Users.id'=>$id])
					->select(['Users.id','first_name','last_name','PenAadharCard.pan_card','PenAadharCard.pan_image','PenAadharCard.pan_name','PenAadharCard.date_of_birth','PenAadharCard.state','PenAadharCard.is_verified','PenAadharCard.aadhar_card'])
					->contain(['PenAadharCard'])->first();
		return $list;
    }

    public function getBankDetails($id = NULL) {
        $table	=	TableRegistry::get('Users');
        $list	=	$table->find('all',array('conditions'=>array('Users.id'=>$id)))
					->select(['Users.id','first_name','last_name','BankDetails.account_number','BankDetails.ifsc_code','BankDetails.bank_name','BankDetails.branch','BankDetails.bank_image','BankDetails.is_verified'])
					->contain(['BankDetails'])->first();
        return $list;
    }

    public function getAadharDetails($id = NULL) {
        $table	=	TableRegistry::get('Users');
        $list	=	$table->find('all',array('conditions'=>array('Users.id'=>$id)))
					->select(['Users.id','first_name','last_name','AadharCard.house_no','AadharCard.line1','AadharCard.line2','AadharCard.city','AadharCard.state','AadharCard.pincode','AadharCard.image','AadharCard.is_verified'])
					->contain(['AadharCard'])->first();
        return $list;
    }

    public function getSeriesAllMathes($series_id = NULL) {
        $currentDate=	date("Y-m-d");
		$curentTime	=	date('H:i');
        $Categories	=	TableRegistry::get('series_squad');
        // $list		=	$Categories->find('all',array('conditions'=>array('date >='=>$currentDate,'series_id'=>$series_id,'time > '=>$curentTime)))->toArray();
		$list		=	$Categories->find()->where(['OR'=>[['date'=>$currentDate/* ,'time >= '=>$curentTime */],['date > '=>$currentDate]],'series_id'=>$series_id])->order(['date'=>'ASC'])->toArray();
        return $list;
    }

    public function getTotalWinners($contest_id = NULL) {
        $totalWins	=	'0';
        $Categories	=	TableRegistry::get('custom_breakup');
        $list		=	$Categories->find('all',array('conditions'=>array('contest_id'=>$contest_id)))->order(['id' => 'DESC'])->first();
        if($list['end']=='0'){
            $totalWins	=	$list['start'];
        } else {
            $totalWins	=	$list['end'];
        }
        return $totalWins;
    }
	
	public function getUpcomingContest($categoryId = null) {
		$contestTable	=	TableRegistry::get('Contest');
		$matchContest	=	TableRegistry::get('MatchContest');
		
		$currentDate	=	date('Y-m-d');
		$oneMonthDaate	=	date('Y-m-d',strtotime('+7 Days'));
		$currentTime	=	date('H:i', strtotime('+60 min'));
		$result			=	$contestTable->find()->where(['category_id'=>$categoryId,'status'=>ACTIVE])->toArray();
		$matchCount		=	0;
		if(!empty($result)) {
			foreach($result as $record) {
				$matchContestData	=	$matchContest->find()->where(['contest_id'=>$record->id,'OR'=>[['date'=>$currentDate,'time >= '=>$currentTime],['date > '=>$currentDate,'date <= '=>$oneMonthDaate]]])->contain(['SeriesSquad'])->count();
				$matchCount	+=	$matchContestData;
			}
		}
		return $matchCount;
	}
	
	public function getLiveContest($categoryId = null) {
		$contestTable	=	TableRegistry::get('Contest');
		$matchContest	=	TableRegistry::get('MatchContest');
		
		$currentDate	=	date('Y-m-d');
		$liveTime		=	date('H:i',strtotime('+60 min'));
		$currentTime	=	date('H:i');
		$result			=	$contestTable->find()->where(['category_id'=>$categoryId,'status'=>ACTIVE])->toArray();
		$matchCount		=	0;
		if(!empty($result)) {
			foreach($result as $record) {
				$matchContestData	=	$matchContest->find()->where(['contest_id'=>$record->id,'date' => $currentDate,'OR'=>[['time >='=>$currentTime,'time <='=>$liveTime],'match_status'=>MATCH_INPROGRESS]])->contain(['SeriesSquad'])->count();
				$matchCount	+=	$matchContestData;
			}
		}
		return $matchCount;
	}
    
	public function getResultContest($categoryId = null) {
		$contestTable	=	TableRegistry::get('Contest');
		$matchContest	=	TableRegistry::get('MatchContest');
		
		$currentDate	=	date('Y-m-d');
		$completeDate	=	date('Y-m-d',strtotime('-1 week'));
		$result			=	$contestTable->find()->where(['category_id'=>$categoryId,'status'=>ACTIVE])->toArray();
		$matchCount		=	0;
		if(!empty($result)) {
			foreach($result as $record) {
				$matchContestData	=	$matchContest->find()->where(['contest_id'=>$record->id,'date >=' => $completeDate,'date <=' => $currentDate,'match_status'=>MATCH_FINISH])->contain(['SeriesSquad'])->count();
				$matchCount	+=	$matchContestData;
			}
		}
		return $matchCount;
	}

    public function getTeamFlag($id = NULL) {
        $Categories =   TableRegistry::get('mst_teams');
        $list       =   $Categories->find('all',array('conditions'=>array('mst_teams.team_id'=>$id)))->first();
        return $list['flag'];
    }

    public function getSeriesName($id = NULL) {
        $Categories =   TableRegistry::get('series');
        $list       =   $Categories->find('all',array('conditions'=>array('series.id_api'=>$id)))->first();
        return $list['name'];
    }
	
	public function dateFormat($date) {
		return date('Y-m-d',strtotime($date));
	}
	
	public function getsetiesMatch($sereisId = null, $matchId = null) {
		$table	=	TableRegistry::get('SeriesSquad');
		$result	=	$table->find()->where(['series_id'=>$sereisId,'match_id'=>$matchId])->first();
		return $result;
	}
	
	public function getOffers($offerId = null) {
		$table	=	TableRegistry::get('CouponCodes');
		$result	=	$table->find()->where(['id'=>$offerId])->first();
		return $result;
	}

    public function getPlayerRecordContest($series_id,$match_id,$player_id,$captian = '',$vice_captain = '') { //pr($series_id);die;
		$mType='';
		ini_set('max_execution_time', 1500);
        $points=0;
        $data['image']=$data['name']=$data['role']=$data['point']='';
        $playerRecordTable  =   TableRegistry::get('PlayerRecord');
        $liveScoreTable     =   TableRegistry::get('LiveScore');
        $pointSystem     =   TableRegistry::get('PointSystem');
        $liveScore  =   $liveScoreTable->find()->where(['seriesId'=>$series_id,'matchId'=>$match_id,'playerId'=>$player_id])->toArray();
        if(!empty($liveScore)){
            //$points = $liveScore->point;
			foreach ($liveScore as $row) {
                $points += $row->point;
                $mType = $row->matchType;
            }
        }
        if(isset($mType) && $mType != ''){
            //$mType = $liveScore->matchType;
            if(($mType=='Test') || ($mType=='First-class')){
                $rePnt = $pointSystem->find('all',array('conditions'=>array('matchType'=>'3')))->first();
            }elseif ($mType=='ODI') {
                $rePnt = $pointSystem->find('all',array('conditions'=>array('matchType'=>'2')))->first();
            }elseif ($mType=='T20') {
                $rePnt = $pointSystem->find('all',array('conditions'=>array('matchType'=>'1')))->first();
            }
            if(!empty($rePnt)){
                $captainPoint = $rePnt->othersCaptain;
                $viceCaptainPoint = $rePnt->othersViceCaptain;
                if($captian == $player_id){
                    $points = ($points*$captainPoint);
                }
                if($vice_captain == $player_id){
                    $points = ($points*$viceCaptainPoint);
                }
            }
        }
        $plyrRcrd   =   $playerRecordTable->find()->where(['player_id'=>$player_id])->first();
        if(!empty($plyrRcrd)) {
            $data['image'] = $plyrRcrd->image;
            $data['name']  = $plyrRcrd->player_name;
            $data['role']  = $plyrRcrd->playing_role;
            $data['point'] = $points;
        }
		
		/* $PlayerTeams  	=   TableRegistry::get('PlayerTeams');
		$SeriesPlayers  =   TableRegistry::get('SeriesPlayers');
		$LiveScore  	=   TableRegistry::get('LiveScore');
		$PointSystem  	=   TableRegistry::get('PointSystem');
		$playerRecordTable  =   TableRegistry::get('PlayerRecord');
		$query	=	$PlayerTeams->find()
					->where(['PlayerTeams.series_id'=>$series_id,'PlayerTeams.match_id'=>$match_id])
					->contain(['PlayerTeamDetails'=>['fields'=>['player_id','player_team_id']]]);
		$query	=	$query->toArray();
		$pointOfPlayer = 0;
		$mType = '';
		if(!empty($query)){
			foreach ($query as $vl) {
				$players = $vl->player_team_details;
				
				$playerPointArr	=	[];
				foreach($players as $playerLIst) {
					
					$checkPlyer	=	$SeriesPlayers->find()
									->where(['series_id'=>$vl->series_id,'player_id'=>$player_id])
									->select(['series_id','player_id'])
									->first();
					if(!empty($checkPlyer)){
						$pointOfPlayer	=	0;
						$recordNew	=	$LiveScore->find('all',array('conditions'=>array('seriesId'=>$vl->series_id,'matchId'=>$vl->match_id,'playerId'=>$player_id)))->select(['point','matchType'])->first();
						
						if(!empty($recordNew)){
							$pointOfPlayer = $recordNew->point;
							$mType = $recordNew->matchType;
						}
						
						if(($mType=='Test') || ($mType=='First-class')){
							$rePnt	=	$PointSystem->find('all',array('conditions'=>array('matchType'=>'3')))->select(['othersCaptain','othersViceCaptain'])->first();
						}elseif ($mType=='ODI') {
							$rePnt	=	$PointSystem->find('all',array('conditions'=>array('matchType'=>'2')))->select(['othersCaptain','othersViceCaptain'])->first();
						}elseif ($mType=='T20') {
							$rePnt	=	$PointSystem->find('all',array('conditions'=>array('matchType'=>'1')))->select(['othersCaptain','othersViceCaptain'])->first();
						}
						$captain	=	$rePnt->othersCaptain;
						$viceCaptain=	$rePnt->othersViceCaptain;
						if($vl->captain == $player_id){
							$pointOfPlayer = ($pointOfPlayer * $captain);
						}
						if($vl->vice_captain == $player_id){
							$pointOfPlayer = ($pointOfPlayer * $viceCaptain);
						}
						
					}
				}
				
			}
		} */
				
					
        return $data;
    }


    public function getkPlayerRecordContest($series_id,$match_id,$player_id,$captian = '',$vice_captain = '') { //pr($series_id);die;
		$mType='';
		ini_set('max_execution_time', 1500);
        $points=0;
        $data['image']=$data['name']=$data['role']=$data['point']='';
        $playerRecordTable  =   TableRegistry::get('KplayerRecord');
        $liveScoreTable     =   TableRegistry::get('KliveScore');
        $pointSystem     =   TableRegistry::get('KabPointSystem');
        $liveScore  =   $liveScoreTable->find()->where(['seriesId'=>$series_id,'matchId'=>$match_id,'playerId'=>$player_id])->toArray();
        if(!empty($liveScore)){
            //$points = $liveScore->point;
			foreach ($liveScore as $row) {
                $points += $row->point;
                $mType = $row->matchType;
            }
        }
        if(isset($mType) && $mType != ''){
            //$mType = $liveScore->matchType;
            $rePnt = $pointSystem->find('all',array('conditions'=>array('matchType'=>'1')))->first();
            if(!empty($rePnt)){
                $captainPoint = $rePnt->captain_points_multiplied_by;
                $viceCaptainPoint = $rePnt->vice_captain_points_multiplied_by;
                if($captian == $player_id){
                    $points = ($points*$captainPoint);
                }
                if($vice_captain == $player_id){
                    $points = ($points*$viceCaptainPoint);
                }
            }
        }
        $plyrRcrd   =   $playerRecordTable->find()->where(['player_id'=>$player_id])->first();
        if(!empty($plyrRcrd)) {
            $data['image'] = $plyrRcrd->image;
            $data['name']  = $plyrRcrd->player_name;
            $data['role']  = $plyrRcrd->playing_role;
            $data['point'] = $points;
        }			
        return $data;
    }

    public function getMatchName($match_id){
        $seriesSquadTable = TableRegistry::get('SeriesSquad');
        $seriesDetails  =   $seriesSquadTable->find()->where(['match_id'=>$match_id])->first();
        if(!empty($seriesDetails)){
            return $seriesDetails->localteam.' vs '.$seriesDetails->visitorteam;
        }
        return '';
    }
    
    public function getuserDetails($id = NULL) {
        $users    =   TableRegistry::get('users');
        $details  =   $users->find('all',array('conditions'=>array('id'=>$id)))->first();
        if(!empty($details)){
            return $details->first_name.' '.$details->last_name;
        }
        return '';
    }

    public function isContestJoined($contest_id) {
        $PlayerTeamContests    =   TableRegistry::get('PlayerTeamContests');

        $isJoined   =   $PlayerTeamContests->find()
                            ->where(['contest_id'=>$contest_id])->first();
        if(!empty($isJoined)){
            return $isJoined;
        }else{
            return '';
        }
    }

    public function getContestLeftMatch($contest_id,$draft_id)
    {
        $PlayerTeams    =   TableRegistry::get('PlayerTeams');
        $totalTeamsJoined   =   $PlayerTeams->find()
                            ->where(['draft_id'=>$draft_id,'contest_id'=>$contest_id])
                            ->order(['user_id'])->count();
        return $totalTeamsJoined;

    }

    public function getPlayersDetails($json){
        $jsArr=json_decode($json,true);
        $apiPlayerTable    =   TableRegistry::get('ApiPlayers');
        $resposeData=array();
            foreach($jsArr as $key=>$value){
                $resposeData[]=$apiPlayerTable->find()
                        ->where(['id'=>$value['apitable_id']])
                        ->first();
            }
            //pr($resposeData);
        return $resposeData;
    }

    public function getTotalTeamParticipants($contest_id,$match_id) {
        $count   =   '0';
        $Users   =   TableRegistry::get('JoinContestDetails');
        $count   =   $Users->find('all', ['conditions' => ['contest_id' => $contest_id, 'match_id' => $match_id]])->count();
        return $count;
    }

    public function getTotalAmountOfaContest($contest_id,$totalTeam) {
        $Users   =   TableRegistry::get('Contest');
        $details =   $Users->find('all', ['conditions' => ['id' => $contest_id]])->first();
        if(!empty($details)){
            $entryFee= $details->entry_fee;
            $finAmunt= $entryFee * $totalTeam;
            return $finAmunt;
        }
        return 0;
    }

    public function getTotalTeamBonus($contest_id,$match_id) {
        $bonus   =   '0';
        $Users   =   TableRegistry::get('JoinContestDetails');
        $record  =   $Users->find('all', ['conditions' => ['contest_id' => $contest_id, 'match_id' => $match_id]])->toArray();
        if(!empty($record)){
            foreach ($record as $value) {
                $bonus = $value->bonus_amount;
            }
            return $bonus;
        }
        return $bonus;
    }

    public function getAdminPercentageVal($contest_id,$amount) {
        error_reporting(0);
        $comission   =   '0';
        $Users   =   TableRegistry::get('Contest');
        $record  =   $Users->find('all', ['conditions' => ['id' => $contest_id]])->first();
        if(!empty($record)){
            $adminComsn = $record->admin_comission;
            $comission = (($adminComsn/100) * $amount);
            $comission = round($comission,2);
            return $comission;
        }
        return $comission;
    }

    public function getTotalEarning()
    {
        $finalAmnt = 0;
        $Users   =   TableRegistry::get('JoinContestDetails');
        $record  =   $Users->find('all')->group(['contest_id','match_id'])->toArray();
        if(!empty($record)){          

            foreach ($record as $value) {
                $totalTeam =    $this->getTotalTeamParticipants($value->contest_id,$value->match_id);
                $contAmnt  =    $this->getTotalAmountOfaContest($value->contest_id,$totalTeam);
                $bonusAmnt  =   $this->getTotalTeamBonus($value->contest_id,$value->match_id);
                $adminCmsn  =   $this->getAdminPercentageVal($value->contest_id,$contAmnt);

                $finalRslt = $adminCmsn-$bonusAmnt;
                $finalAmnt += $finalRslt;
            }
        }

        return $finalAmnt;
    }

    public function unverifiedUsersList() {
        $lsit    =   array();
        $Users   =   TableRegistry::get('Users');
        $list    =   $Users->find()->where(['role_id' => '2','OR'=>['PenAadharCard.is_verified'=>INACTIVE,'BankDetails.is_verified'=>INACTIVE]])->contain(['PenAadharCard','BankDetails'])->toArray();
        return $list;
    }
	
	public function deactiveMatchCount($series_id=null) {
		$table	=	TableRegistry::get('SeriesSquad');
		$currentDate=	date("Y-m-d");
		$curentTime	=	date('H:i');
		$list		=	$table->find()->where(['OR'=>[['date'=>$currentDate,'time >= '=>$curentTime],['date > '=>$currentDate]],'series_id'=>$series_id,'SeriesSquad.status'=>INACTIVE])->count();
		return $list;
    }
    
    
	
	########## 13March19 ###########
    public function getTotalTeamContest($series_id, $match_id) {
        $count  =   0;
        $seriesSquad  =   TableRegistry::get('seriesSquad');
        $matchContest =   TableRegistry::get('matchContest');
        $mId = $seriesSquad->find()->where(['series_id'=>$series_id,'match_id' => $match_id])->select(['id'])->first();
        $count = $matchContest->find()->where(['match_id'=>$mId['id'],'isCanceled'=>0])->count();        
        return $count;
    }

    public function getTeamParticipantsContest($series_id,$match_id) {
        $count   =   '0';
        $Users   =   TableRegistry::get('playerTeamContests');
        $count   =   $Users->find('all', ['conditions' => ['series_id' => $series_id, 'match_id' => $match_id]])->count();
        return $count;
    }

    public function getTotalEarningContest($series_id,$match_id,$totalTeam) {
        $totalEarning = 0;        
        
        $conn = ConnectionManager::get('default');
        $stmt = $conn->execute("SELECT sum(total_amount) as ttl FROM join_contest_details where series_id = $series_id AND match_id = $match_id");
        $results = $stmt ->fetchAll('assoc');
        $totalEarning += $results[0]['ttl'];

        return $totalEarning;
    }

    public function getTotalBonusUsed($series_id,$match_id) {
        $totalEarning = 0;        
        
        $conn = ConnectionManager::get('default');
        $stmt = $conn->execute("SELECT sum(bonus_amount) as ttl FROM join_contest_details where series_id = $series_id AND match_id = $match_id");
        $results = $stmt ->fetchAll('assoc');
        $totalEarning += $results[0]['ttl'];


        return $totalEarning;
    }

    public function getTotalAdminComision($series_id,$match_id) {
        $comission = 0;
        $joinContestDetails =   TableRegistry::get('joinContestDetails');
        $records      = $joinContestDetails->find()->where(['series_id'=>$series_id,'match_id' => $match_id])->select(['admin_comission','id'])->toArray();
        if(!empty($records)){
            foreach ($records as $value) {
                $comission += $value->admin_comission;
            }
        }
        return $comission;
    }
	########## 13March19 ###########
	
	public function getuserInformation($id = NULL) {
        $users    =   TableRegistry::get('users');
        $details  =   $users->find('all',array('conditions'=>array('id'=>$id)))->first();
        if(!empty($details)){
            return $details;
        }
        return '';
    }

    public function getMatchData($match_id){
        $seriesSquadTable = TableRegistry::get('SeriesSquad');
        $seriesDetails  =   $seriesSquadTable->find()->where(['match_id'=>$match_id])->first();
        if(!empty($seriesDetails)){
            return $seriesDetails->date;
        }
        return '';
    }

    ########### 15 March 19 ############ 
    public function getTotalUserWin($user_id) {
        $totalEarning = 0;        
        
        $conn = ConnectionManager::get('default');
        $stmt = $conn->execute("SELECT sum(winning_amount) as ttl FROM player_team_contests where user_id = $user_id");
        $results = $stmt ->fetchAll('assoc');
        $totalEarning = $results[0]['ttl'];

        return $totalEarning;
    }

    public function getTotalDeposits() {
        $totalEarning = 0;        
        $type = CASH_DEPOSIT;
        $conn = ConnectionManager::get('default');
        $stmt = $conn->execute("SELECT sum(txn_amount) as ttl FROM transactions where added_type = $type");
        $results = $stmt ->fetchAll('assoc');
        $totalEarning += $results[0]['ttl'];


        return $totalEarning;
    }
	
	public function totalCash() {
        $totalEarning = 0;        
        $type = CASH_DEPOSIT;
        $conn = ConnectionManager::get('default');
        $stmt = $conn->execute("SELECT sum(cash_balance) as ttl FROM users where role_id = 2 AND status=1");
        $results = $stmt ->fetchAll('assoc');
        $totalEarning += $results[0]['ttl'];

        return number_format($totalEarning,2);
    }
    public function totalWinning() {
        $totalEarning = 0;        
        $type = CASH_DEPOSIT;
        $conn = ConnectionManager::get('default');
        $stmt = $conn->execute("SELECT sum(winning_balance) as ttl FROM users where role_id = 2 AND status=1");
        $results = $stmt ->fetchAll('assoc');
        $totalEarning += $results[0]['ttl'];

        return number_format($totalEarning,2);
    }
    public function totalBonus() {
        $totalEarning = 0;        
        $type = CASH_DEPOSIT;
        $conn = ConnectionManager::get('default');
        $stmt = $conn->execute("SELECT sum(bonus_amount) as ttl FROM users where role_id = 2 AND status=1");
        $results = $stmt ->fetchAll('assoc');
        $totalEarning += $results[0]['ttl'];

        return number_format($totalEarning,2);
    }
    ########### 15 March 19 ############ 
	
	########### 18 March 19 ############ 
    
    public function getTotalWinningAmnt($series_id,$match_id) {
        $totalEarning = 0;        
        $type = CASH_DEPOSIT;
        $conn = ConnectionManager::get('default');
        $stmt = $conn->execute("SELECT sum(winning_amount) as ttl FROM player_team_contests where series_id = $series_id AND match_id=$match_id");
        $results = $stmt ->fetchAll('assoc');
        $totalEarning += $results[0]['ttl'];

        return round($totalEarning,2);
    }
	
	
    // Kabdaddi Matches start here
    public function kab_deactiveMatchCount($series_id=null) {
		$table	=	TableRegistry::get('KseriesSquad');
		$currentDate=	date("Y-m-d");
		$curentTime	=	date('H:i');
		$list		=	$table->find()->where(['OR'=>[['date'=>$currentDate,'time >= '=>$curentTime],['date > '=>$currentDate]],'series_id'=>$series_id,'KseriesSquad.status'=>INACTIVE])->count();
		return $list;
    }
    
    public function kab_getSeriesAllMathes($series_id = NULL) {
        $currentDate=	date("Y-m-d");
		$curentTime	=	date('H:i');
        $Categories	=	TableRegistry::get('kab_series_squad');
        // $list		=	$Categories->find('all',array('conditions'=>array('date >='=>$currentDate,'series_id'=>$series_id,'time > '=>$curentTime)))->toArray();
		$list		=	$Categories->find()->where(['OR'=>[['date'=>$currentDate,'time >= '=>$curentTime],['date > '=>$currentDate]],'series_id'=>$series_id])->order(['date'=>'ASC'])->toArray();
        return $list;
    }

    public function kab_getNoOfContest($id = NULL) {
    	$count = '0';
        $MatchContest	=	TableRegistry::get('KmatchContest');
        $count			=	$MatchContest->find('all', ['conditions' => ['match_id' => $id]])->count();
        return $count;
    }

    public function kab_getNoOfAutoContest($id = NULL) {
    	$count = '0';
        $MatchContest	=	TableRegistry::get('KmatchContest');
        $count			=	$MatchContest->find('all', ['conditions' => ['match_id' => $id,'Contest.is_auto_create'=>2]])->contain(['Contest'=>['fields'=>['id','is_auto_create']]])->count();
        return $count;
    }

    public function kab_getTeamFlag($id = NULL) {
        $Categories =   TableRegistry::get('kab_teams');
        $list       =   $Categories->find('all',array('conditions'=>array('kab_teams.team_id'=>$id)))->first();
        return $list['flag'];
    }

    public function kab_getSeriesName($id = NULL) {
        $Categories =   TableRegistry::get('kab_series');
        $list       =   $Categories->find('all',array('conditions'=>array('kab_series.id_api'=>$id)))->first();
        return $list['name'];
    }

    public function kab_getContestLeftMatch($contest_id,$match_id){
        $PlayerTeamContests    =   TableRegistry::get('KplayerTeamContests');
        $totalTeamsJoined   =   $PlayerTeamContests->find()
                            ->where(['KplayerTeamContests.match_id'=>$match_id,'KplayerTeamContests.contest_id'=>$contest_id])
                            ->contain(['Users'=>['fields'=>['team_name','id','image']]])
                            ->order(['KplayerTeamContests.user_id'])->count();
        return $totalTeamsJoined;

    }

    public function soc_getContestLeftMatch($contest_id,$match_id){
        $PlayerTeamContests    =   TableRegistry::get('SplayerTeamContests');
        $totalTeamsJoined   =   $PlayerTeamContests->find()
                            ->where(['SplayerTeamContests.match_id'=>$match_id,'SplayerTeamContests.contest_id'=>$contest_id])
                            ->contain(['Users'=>['fields'=>['team_name','id','image']]])
                            ->order(['SplayerTeamContests.user_id'])->count();
        return $totalTeamsJoined;

    }

    // BasketBall Matches start here
    public function bas_deactiveMatchCount($series_id=null) {
        $table  =   TableRegistry::get('SseriesSquad');
        $currentDate=   date("Y-m-d");
        $curentTime =   date('H:i');
        $list       =   $table->find()->where(['OR'=>[['date'=>$currentDate,'time >= '=>$curentTime],['date > '=>$currentDate]],'series_id'=>$series_id,'SseriesSquad.status'=>INACTIVE])->count();
        return $list;
    }
    
    public function bas_getSeriesAllMathes($series_id = NULL) {
        $currentDate=   date("Y-m-d");
        $curentTime =   date('H:i');
        $Categories =   TableRegistry::get('soc_series_squad');
        // $list        =   $Categories->find('all',array('conditions'=>array('date >='=>$currentDate,'series_id'=>$series_id,'time > '=>$curentTime)))->toArray();
        $list       =   $Categories->find()->where(['OR'=>[['date'=>$currentDate,'time >= '=>$curentTime],['date > '=>$currentDate]],'series_id'=>$series_id])->order(['date'=>'ASC'])->toArray();
        return $list;
    }

    public function bas_getNoOfContest($id = NULL) {
        $count = '0';
        $MatchContest   =   TableRegistry::get('SmatchContest');
        $count          =   $MatchContest->find('all', ['conditions' => ['match_id' => $id]])->count();
        return $count;
    }

    public function bas_getNoOfAutoContest($id = NULL) {
        $count = '0';
        $MatchContest   =   TableRegistry::get('SmatchContest');
        $count          =   $MatchContest->find('all', ['conditions' => ['match_id' => $id,'Contest.is_auto_create'=>2]])->contain(['Contest'=>['fields'=>['id','is_auto_create']]])->count();
        return $count;
    }

    public function bas_getTeamFlag($id = NULL) {
        $Categories =   TableRegistry::get('soc_teams');
        $list       =   $Categories->find('all',array('conditions'=>array('soc_teams.team_id'=>$id)))->first();
        return $list['flag'];
    }

    public function bas_getSeriesName($id = NULL) {
        $Categories =   TableRegistry::get('soc_series');
        $list       =   $Categories->find('all',array('conditions'=>array('soc_series.id_api'=>$id)))->first();
        return $list['name'];
    }

    public function bas_getContestLeftMatch($contest_id,$match_id){
        $PlayerTeamContests    =   TableRegistry::get('SplayerTeamContests');
        $totalTeamsJoined   =   $PlayerTeamContests->find()
                            ->where(['SplayerTeamContests.match_id'=>$match_id,'SplayerTeamContests.contest_id'=>$contest_id])
                            ->contain(['Users'=>['fields'=>['team_name','id','image']]])
                            ->order(['SplayerTeamContests.user_id'])->count();
        return $totalTeamsJoined;

    }


    // Hockey Matches start here
    public function hoc_deactiveMatchCount($series_id=null) {
        $table          =   TableRegistry::get('HseriesSquad');
        $currentDate    =   date("Y-m-d");
        $curentTime     =   date('H:i');
        $list           =   $table->find()->where(['OR'=>[['date'=>$currentDate,'time >= '=>$curentTime],['date > '=>$currentDate]],'series_id'=>$series_id,'HseriesSquad.status'=>INACTIVE])->count();
        return $list;
    }
    
    public function hoc_getSeriesAllMathes($series_id = NULL) {
        $currentDate=   date("Y-m-d");
        $curentTime =   date('H:i');
        $Categories =   TableRegistry::get('hoc_series_squad');
        // $list        =   $Categories->find('all',array('conditions'=>array('date >='=>$currentDate,'series_id'=>$series_id,'time > '=>$curentTime)))->toArray();
        $list       =   $Categories->find()->where(['OR'=>[['date'=>$currentDate,'time >= '=>$curentTime],['date > '=>$currentDate]],'series_id'=>$series_id])->order(['date'=>'ASC'])->toArray();
        return $list;
    }

    public function hoc_getNoOfContest($id = NULL) {
        $count = '0';
        $MatchContest   =   TableRegistry::get('HmatchContest');
        $count          =   $MatchContest->find('all', ['conditions' => ['match_id' => $id]])->count();
        return $count;
    }

    public function hoc_getNoOfAutoContest($id = NULL) {
        $count = '0';
        $MatchContest   =   TableRegistry::get('HmatchContest');
        $count          =   $MatchContest->find('all', ['conditions' => ['match_id' => $id,'Contest.is_auto_create'=>2]])->contain(['Contest'=>['fields'=>['id','is_auto_create']]])->count();
        return $count;
    }

    public function hoc_getTeamFlag($id = NULL) {
        $Categories =   TableRegistry::get('hoc_teams');
        $list       =   $Categories->find('all',array('conditions'=>array('hoc_teams.team_id'=>$id)))->first();
        return $list['flag'];
    }

    public function hoc_getSeriesName($id = NULL) {
        $Categories =   TableRegistry::get('hoc_series');
        $list       =   $Categories->find('all',array('conditions'=>array('hoc_series.id_api'=>$id)))->first();
        return $list['name'];
    }

    public function hoc_getContestLeftMatch($contest_id,$match_id){
        $PlayerTeamContests    =   TableRegistry::get('HplayerTeamContests');
        $totalTeamsJoined   =   $PlayerTeamContests->find()
                            ->where(['HplayerTeamContests.match_id'=>$match_id,'HplayerTeamContests.contest_id'=>$contest_id])
                            ->contain(['Users'=>['fields'=>['team_name','id','image']]])
                            ->order(['HplayerTeamContests.user_id'])->count();
        return $totalTeamsJoined;

    }


}