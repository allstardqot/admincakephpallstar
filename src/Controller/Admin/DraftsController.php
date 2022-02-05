<?php

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;


class DraftsController extends AppController {

	public function initialize() {
		parent::initialize();
		$this->loadComponent('Cookie');		
	}

	public function index() {
		if (!empty($this->request->query)) {
			$this->request->session()->write('sorting_query', $this->request->query);
		}
		$this->set('title_for_layout', __('List Drafts'));
		$this->request->session()->delete('sorting_query');
		if(!empty($this->request->query)) {
			$this->request->session()->write('sorting_query', $this->request->query);
		}
		$limit = Configure::read('ADMIN_PAGE_LIMIT');
		
		if ($this->request->is('Ajax')) {
			$this->viewBuilder()->layout(false);
		}
		
		$query		=	$this->Drafts->find('search', ['search' => $this->request->query])->contain(['PlayerTeams'])->where(['Drafts.delete_status !='=>1]);
		$category	=	$this->paginate($query, ['limit' => $limit, 'order' => ['Drafts.created DESC']]);
		$this->set(compact('category'));
	}
	
	public function draftnamecheck() {
		date_default_timezone_set('US/Eastern');
		$dname=$_POST['draftName'];
			$draft	=	$this->Drafts->find()->where(['name'=>$dname,'delete_status !='=>1])->first();
			if(!empty($draft)){
				echo "Draft name already Exits.";die;
			}
	}

	public function add() {
		$this->set('title_for_layout', __('Add Draft'));
		$draft	=	$this->Drafts->newEntity();
		if ($this->request->is(['patch', 'post', 'put'])) {

			$this->request->data['draft_data']	=	json_encode($this->request->getData('pchk'));
			$draft = $this->Drafts->patchEntity($draft, $this->request->getData(), ['validate' => 'AddEdit']);
			//pr($draft);die;
			if(empty($draft->errors())) {
				$draft->created	=	date('Y-m-d H:i:s');
				$draft->status	=	ACTIVE;
				if ($this->Drafts->save($draft)) {
					$this->Flash->success(__('Drafts has been added'));
					return $this->redirect(['action' => 'index']);
				}
				$this->Flash->error(__('Drafts could not be added, please try again.'));
			} else {
				$error_msg = [];
				foreach( $draft->errors() as $errors){
					if(is_array($errors)){
						foreach($errors as $error){
							$error_msg[]    =   $error;
						}
					}else{
						$error_msg[]    =   $errors;
					}
				}
				
				$message	=	__(implode("<br/>", $error_msg), true);
				$this->Flash->error(__('Please correct errors listed as below - <br/>'.$message),['escape'=>false]);
			}
		}

		$this->loadModel('ApiPlayers');
		$weeks = $this->ApiPlayers->find('list',['keyField'=>'Week','valueField'=>'Week'])
		->where(['Week > ' => 0])
		->group(['Week'])
		->toArray();
		//pr($weeks);die;

		$this->set(compact('draft','weeks'));
	}

	public function addnew() {
		date_default_timezone_set('US/Eastern');

		//$this->viewBuilder()->layout(false);
		$this->set('title_for_layout', __('Add Draft'));
		$draft	=	$this->Drafts->newEntity();
		$startTime=$endTime=$endDate=$startDate='';
		if ($this->request->is(['patch', 'post', 'put'])) {
			$dataGet=$this->request->getData();

			if(!empty($dataGet)){
				$date_teamid=[];
				if(!empty($dataGet['team_member'])){
					foreach($dataGet['team_member'] as $fkey=>$fvalue){
						foreach($fvalue as $key=>$value){
							$date_teamid[]=$value;
						}
					}
				}
				
				if(!empty($dataGet['week']) && !empty($date_teamid)){
					$this->loadModel('ApiPlayers');
					
					$condition=['ApiPlayers.Week'=> $dataGet['week'],'ApiPlayers.PlayerID IN'=> $date_teamid,'ApiPlayers.SeasonType'=> $dataGet['seasontype'],'ApiPlayers.Season'=> $dataGet['season']];
				
					$dateSort = $this->ApiPlayers->find()
					->select(['max_date'=>'MAX(GameDate)', 'min_date'=>'MIN(GameDate)'])
					->where($condition)->first();
					$endDate=strtotime($dateSort['max_date'])+60*60*5;
					$endDate= date('Y-m-d H:i:s', $endDate);
					$startDate=$dateSort['min_date'];
					$startDatear=explode(" ",$startDate);
					$endDatear=explode(" ",$endDate);
					$startTime=!empty($startDatear[1])?$startDatear[1]:'';
					$endTime=!empty($endDatear[1])?$endDatear[1]:'';	
					
				}

				//pr($endDate);die;
				$finaldata=$main_data=array();
				if(!empty($dataGet['filter_listing'])){
					$dataCount=count($dataGet['filter_listing']);
					for($i=0;$i<$dataCount;$i++){
						$newData=array();
						foreach($dataGet as $keyName=>$postvalue){
							if(is_array($postvalue)){
								foreach($postvalue as $key=>$dataValue){
									if($key==$i){
										$newData[$keyName]=$dataValue;
										array_push($main_data,$newData);
									}										
								}
							}/*else{
								$finaldata[$keyName]=$postvalue;
							}*/
						}
					}
					foreach($main_data as $key=>$value){
						if(count($main_data[$key])==3){
							$finaldata[]=$main_data[$key];
						}
					}
				}
			
				$this->request->data['draft_data']	=	json_encode($finaldata);
				$draft = $this->Drafts->patchEntity($draft, $this->request->getData(), ['validate' => 'AddEdit']);
				if(empty($draft->errors())) {
					$draft->created	=	date('Y-m-d H:i:s');
					$draft->startdate	=	$startDate;
					$draft->starttime	=	$startTime;
					$draft->enddate	=	$endDate;
					$draft->endtime	=	$endTime;
					$draft->status	=	ACTIVE;
					if ($this->Drafts->save($draft)) {
						$this->Flash->success(__('Drafts has been added'));
						return $this->redirect(['action' => 'index']);
					}
					$this->Flash->error(__('Drafts could not be added, please try again.'));
				} else {
					$error_msg = [];
					foreach( $draft->errors() as $errors){
						if(is_array($errors)){
							foreach($errors as $error){
								$error_msg[]    =   $error;
							}
						}else{
							$error_msg[]    =   $errors;
						}
					}
					
					$message	=	__(implode("<br/>", $error_msg), true);
					$this->Flash->error(__('Please correct errors listed as below - <br/>'.$message),['escape'=>false]);
				}
			}
		}

		$this->loadModel('ApiPlayers');
		$weeks = $this->ApiPlayers->find('list',['keyField'=>'Week','valueField'=>'Week'])
		->where(['Week > ' => 0])
		->group(['Week'])
		->toArray();

		$this->loadModel('ApiPlayers');
		$Season = $this->ApiPlayers->find('list',['keyField'=>'Season','valueField'=>'Season'])
		->where(['Season > ' => 0])
		->group(['Season'])
		->toArray();

		$Type = $this->ApiPlayers->find()
		->where(['SeasonType > ' => 0])
		->group(['SeasonType'])
		->toArray();
		$typeArray=array();
		foreach($Type as $key=>$tValue){
			$typeArray[$tValue['SeasonType']]=$tValue['Season'];
		}
		//pr($typeArray);die;

		$this->set(compact('draft','weeks','Season','typeArray'));
	}

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

	public function editdraft($eid=null) {
		$this->loadModel('PlayerTeams');

		$seriesData	=	$this->Drafts->get($eid); 
		$checkJoin_user	=	$this->PlayerTeams->find()
						->where(['draft_id'=>$eid])
						->first(); 
		if(!empty($checkJoin_user)){
			$this->Flash->error(__("Edit feature doesn't work because user already selected this draft."));
			return $this->redirect(['action' => 'index']);
		}

		if ($this->request->is(['patch', 'post', 'put'])) {
			$dataGet=$this->request->getData();
			//pr($dataGet);die;
			if(!empty($dataGet['week'])){
				$date_teamid=[];
				if(!empty($dataGet['team_member'])){
					foreach($dataGet['team_member'] as $fkey=>$fvalue){
						foreach($fvalue as $key=>$value){
							$date_teamid[]=$value;
						}
					}
				}

				$this->loadModel('ApiPlayers');
				$condition=array('ApiPlayers.Week'=> $dataGet['week'],'ApiPlayers.PlayerID IN'=> $date_teamid,'ApiPlayers.SeasonType'=> $dataGet['seasontype'],'ApiPlayers.Season'=> $dataGet['season']);
				$dateSort = $this->ApiPlayers->find()
				->select(['max_date'=>'MAX(GameDate)', 'min_date'=>'MIN(GameDate)'])
				->where($condition)
				->first();
				$endDate=$dateSort['max_date'];
				$startDate=$dateSort['min_date'];
				$startDatear=explode(" ",$startDate);
				$endDatear=explode(" ",$endDate);
				$startTime=!empty($startDatear[1])?$startDatear[1]:'';
				$endTime=!empty($endDatear[1])?$endDatear[1]:'';
			}
			$finaldata=$main_data=array();
			if(!empty($dataGet['filter_listing'])){
				$dataCount=count($dataGet['filter_listing']);
				for($i=0;$i<$dataCount;$i++){
					$newData=array();
					foreach($dataGet as $keyName=>$postvalue){
						if(is_array($postvalue)){
							foreach($postvalue as $key=>$dataValue){
								if($key==$i){
									$newData[$keyName]=$dataValue;
									array_push($main_data,$newData);
								}										
							}
						}/*else{
							$finaldata[$keyName]=$postvalue;
						}*/
					}
				}
				foreach($main_data as $key=>$value){
					if(count($main_data[$key])==3){
						$finaldata[]=$main_data[$key];
					}
				}
			}
			$this->request->data['draft_data']	=	json_encode($finaldata);
			$draft = $this->Drafts->patchEntity($seriesData, $this->request->getData(), ['validate' => 'AddEdit']);

			if(empty($draft->errors())) {
				$draft->modified	=	date('Y-m-d H:i:s');
				$draft->startdate	=	$startDate;
				$draft->starttime	=	$startTime;
				$draft->enddate	=	$endDate;
				$draft->endtime	=	$endTime;
				$draft->status	=	ACTIVE;
				if ($this->Drafts->save($draft)) {
					$this->Flash->success(__('Drafts has been added'));
					return $this->redirect(['action' => 'index']);
				}
				$this->Flash->error(__('Drafts could not be added, please try again.'));
			} else {
				$error_msg = [];
				foreach( $draft->errors() as $errors){
					if(is_array($errors)){
						foreach($errors as $error){
							$error_msg[]    =   $error;
						}
					}else{
						$error_msg[]    =   $errors;
					}
				}
				
				$message	=	__(implode("<br/>", $error_msg), true);
				$this->Flash->error(__('Please correct errors listed as below - <br/>'.$message),['escape'=>false]);
			}
		}

		$this->loadModel('ApiPlayers');
		$weeks = $this->ApiPlayers->find('list',['keyField'=>'Week','valueField'=>'Week'])
		->where(['Week > ' => 0])
		->group(['Week'])
		->toArray();

		$this->loadModel('ApiPlayers');
		$Season = $this->ApiPlayers->find('list',['keyField'=>'Season','valueField'=>'Season'])
		->where(['Season > ' => 0])
		->group(['Season'])
		->toArray();

		$Type = $this->ApiPlayers->find()
		->where(['SeasonType > ' => 0])
		->group(['SeasonType'])
		->toArray();
		$typeArray=array();
		foreach($Type as $key=>$tValue){
			$typeArray[$tValue['SeasonType']]=$tValue['Season'];
		}
		

		$this->set(compact('seriesData','weeks','Season','typeArray'));
	}

	public function edit($id = NULL) {
		$this->set('title_for_layout', __('Edit Drafts'));
		try {
			$category = $this->Drafts->get($id);
		} catch (\Throwable $e) {
			$this->Flash->error(__('Invaild attempt.'));
			return $this->redirect(['action' => 'index']);
		} catch (\Exception $e) {
			$this->Flash->error(__('Invaild attempt.'));
			return $this->redirect(['action' => 'index']);
		}
		// $oldImg	=	$category['image'];
		if($this->request->is(['patch', 'post', 'put'])) {
			$categoryImage		=	$category->image;
			$this->request->data['category_name']	=	trim($this->request->getData('category_name'));
			$this->request->data['description']		=	trim($this->request->getData('description'));
			$this->Drafts->patchEntity($category, $this->request->getData(), ['validate' => 'EditCategory']);
			$category['modified']	=	date('Y-m-d H:i:s');
			
			// pr($category->errors());die;
			if(empty($category->errors())) {			
				if(isset($this->request->data['image']) && !empty($this->request->data['image']['name'])){
					$file		=	$this->request->getData('image');
					$fileArr	=	explode('.',$file['name']);
					$ext		=	end($fileArr);
					$fileName	=	'background_'.time().'.'.$ext;
					$rootPath	=	WWW_ROOT .'uploads/category_image/';
					$filePath	=	$rootPath.$fileName;
					if(!empty($categoryImage) && file_exists($rootPath.$categoryImage)) {
						unlink($rootPath.$categoryImage);
					}
					if(move_uploaded_file($file['tmp_name'],$filePath)) {
						$category->image	=	$fileName;
					}
				} else {
					$category->image	=	$categoryImage;
				}
				if ($this->Drafts->save($category)) {
					$this->Flash->success(__('Drafts has been updated'));
					return $this->redirect(['action' => 'index']);
				}
				$this->Flash->error(__('Drafts could not be added, please try again.'));
			} else {
				$this->Flash->error(__('Please correct errors listed as below.'));
			}
		}
		$this->set(compact('category'));
	}

	public function status($id = NULL) {
		$category = $this->Drafts->get($id);
		$status = ($category->status == 0) ? 1 : 0;
		$category->status = $status;
		//pr($category);die;
		if ($this->Drafts->save($category)) {
			$this->Flash->success(__('Drafts status has been changed'));
			return $this->redirect($this->referer());
		}
		$this->Flash->error(__('Drafts status could not be changed, please try again.'));
	}

	public function detail($id = NULL) {
		$this->set('title_for_layout', __('Drafts Detail'));
		try {
			$category	=	$this->Drafts->get($id);
		} catch (\Throwable $e) {
			$this->Flash->error(__('Invaild attempt.'));
			return $this->redirect(['action' => 'index']);
		} catch (\Exception $e) {
			$this->Flash->error(__('Invaild attempt.'));
			return $this->redirect(['action' => 'index']);
		}
		$this->set(compact('category'));

	}

	public function delete($id = null) { 
		$this->loadModel('Drafts');
		if (isset($id) && !empty($id))
		{
			$entity = $this->Drafts->get($id); 
			$entity['delete_status']=1;
			if($this->Drafts->save($entity)) {
				$this->Flash->success(__('Drafts has been successfully deleted .'));
				return $this->redirect(['action' => 'index']);
			}else
			{
				$this->Flash->error(__('Unable to delete Drafts, please try again.'));
				return $this->redirect(['action' => 'index']);
			}
		}else
		{
			$this->Flash->error(__('Unable to delete category, please try again.'));
			return $this->redirect(['action' => 'index']);
		}
	}

	public function playerdata() {

		
		$week  	=	$this->request->data['week'];
		
		$this->viewBuilder()->layout(false);
		$this->loadModel('ApiPlayers');
		$weeks_data = $this->ApiPlayers->find()
		->where([ 'Week' => $week ])
		->order(['Position','TeamID','Name'])
		->toArray();
		
		$formated_week_data = [];
		if (!empty($weeks_data)) {
			foreach($weeks_data AS $key => $value){
				$Position = $value['Position'];
				$FantasyPointsPPR = $value['FantasyPointsPPR'];
				$TeamID = $value['TeamID'];
				$Team = $value['Team'];
				$OpponentID = $value['OpponentID'];
				$Opponent = $value['Opponent'];
				$PlayerID = $value['PlayerID'];
				$Name = $value['Name'];
				$formated_week_data[$Position][$Team][] = [
					'Position'=>$Position,
					'FantasyPointsPPR'=>$FantasyPointsPPR,
					'TeamID'=>$TeamID,
					'Team'=>$Team,
					'OpponentID'=>$OpponentID,
					'Opponent'=>$Opponent,
					'PlayerID'=>$PlayerID,
					'Name'=>$Name,
				];
			}
		}
		//pr($formated_week_data);die;

		$this->set(compact('formated_week_data'));
	}

	public function playerdatanew() { 

		
		$week  	=	$this->request->data['week'];
		$seasonList  	=	$this->request->data['seasonList'];
		$seasontypeList  	=	$this->request->data['seasontypeList'];
		$condition = [];
		if(!empty($this->request->data['stdate'])){
			//$condition[]=['GameDate >='=>$this->request->data['stdate']];
			$start_date = date('Y-m-d 00:00:00',strtotime($this->request->data['stdate']));
			//echo $start_date;
			$start_date_time = strtotime($start_date);
			$condition[]=['GameDate >='=> $start_date ];
		}
		if(!empty($this->request->data['enddate'])){
			//$condition[]=['GameDate <='=>$this->request->data['enddate']];
			$end_date = date('Y-m-d 23:59:59',strtotime($this->request->data['enddate']));
			$end_date_time = strtotime($end_date);
			$condition[]=['GameDate <='=> $end_date ];
		}
		if(!empty($week)){
			$condition[]=['Week'=>$week];
		}
		if(!empty($seasonList)){
			$condition[]=['Season'=>$seasonList];
		}
		if(!empty($week)){
			$condition[]=['SeasonType'=>$seasontypeList];
		}
		$draft_data=[];
		if(!empty($this->request->data['draft_data'])){
			$draft_data  	=	$this->request->data['draft_data'];
		}
		//pr($condition);
		//echo $condition;die;
		$this->viewBuilder()->layout(false);
		$this->loadModel('ApiPlayers');

		$dateSort = $this->ApiPlayers->find()
				->select(['max_date'=>'MAX(GameDate)', 'min_date'=>'MIN(GameDate)'])
				->where([$condition])->first();
		$endDate=$dateSort['max_date'];
		$startDate=$dateSort['min_date'];

		$weeks_data = $this->ApiPlayers->find()
		->where([ $condition ])
		->order(['Position','TeamID','Name'])
		->toArray();
		//pr($weeks_data);
		$formated_week_data = [];
		$formated_week_data['startdate']=$startDate;
		$formated_week_data['endDate']=$endDate;
		if (!empty($weeks_data)) {
			foreach($weeks_data AS $key => $value){
				$Position = $value['Position'];
				$FantasyPointsPPR = $value['FantasyPointsPPR'];
				$TeamID = $value['TeamID'];
				$Team = $value['Team'];
				$GameDate=$value['GameDate'];
				$OpponentID = $value['OpponentID'];
				$Opponent = $value['Opponent'];
				$PlayerID = $value['PlayerID'];
				$Name = $value['Name'];
				$formated_week_data[$Position][] = [
					'Position'=>$Position,
					'FantasyPointsPPR'=>$FantasyPointsPPR,
					'TeamID'=>$TeamID,
					'Team'=>$Team,
					'GameDate'=>$GameDate,
					'OpponentID'=>$OpponentID,
					'Opponent'=>$Opponent,
					'PlayerID'=>$PlayerID,
					'Name'=>$Name,
				];
				$formated_week_data['Flex'][] = [
					'Position'=>$Position,
					'FantasyPointsPPR'=>$FantasyPointsPPR,
					'TeamID'=>$TeamID,
					'Team'=>$Team,
					'GameDate'=>$GameDate,
					'OpponentID'=>$OpponentID,
					'Opponent'=>$Opponent,
					'PlayerID'=>$PlayerID,
					'Name'=>$Name,
				];
			}
		}
		//pr($formated_week_data);die;

		$this->set(compact('formated_week_data','draft_data'));
	}

	public function playerdataedit() {

		
		$week  	=	$this->request->data['week'];
		$draft_data  	=	$this->request->data['draft_data'];
		
		$this->viewBuilder()->layout(false);
		$this->loadModel('ApiPlayers');
		$weeks_data = $this->ApiPlayers->find()
		->where([ 'Week' => $week ])
		->order(['Position','TeamID','Name'])
		->toArray();
		
		$formated_week_data = [];
		if (!empty($weeks_data)) {
			foreach($weeks_data AS $key => $value){
				$Position = $value['Position'];
				$FantasyPointsPPR = $value['FantasyPointsPPR'];
				$TeamID = $value['TeamID'];
				$Team = $value['Team'];
				$OpponentID = $value['OpponentID'];
				$Opponent = $value['Opponent'];
				$PlayerID = $value['PlayerID'];
				$Name = $value['Name'];
				$formated_week_data[$Position][$Team][] = [
					'Position'=>$Position,
					'FantasyPointsPPR'=>$FantasyPointsPPR,
					'TeamID'=>$TeamID,
					'Team'=>$Team,
					'OpponentID'=>$OpponentID,
					'Opponent'=>$Opponent,
					'PlayerID'=>$PlayerID,
					'Name'=>$Name,
				];
			}
		}
		//pr($formated_week_data);die;

		$this->set(compact('formated_week_data'));
	}

	
	public function memberdata() {

		
		$selectteam  	=	$this->request->data['team'];
		$week  	=	$this->request->data['week'];
		if(!empty($this->request->data['stdate'])){
			$start_date = date('Y-m-d 00:00:00',strtotime($this->request->data['stdate']));

			$condition[]=['GameDate >='=>$start_date];
		}
		if(!empty($this->request->data['enddate'])){
			$end_date = date('Y-m-d 23:59:59',strtotime($this->request->data['enddate']));

			$condition[]=['GameDate <='=>$end_date];
		}
		if(!empty($this->request->data['seasonList'])){
			$condition[]=['Season'=>$this->request->data['seasonList']];
		}
		if(!empty($this->request->data['seasontypeList'])){
			$condition[]=['SeasonType'=>$this->request->data['seasontypeList']];
		}
		if(!empty($week)){
			$condition[]=['Week'=>$week];
		}
		if(!empty($selectteam) && $selectteam!='Flex'){
			$condition[]=['Position'=>$selectteam];
		}
		$this->viewBuilder()->layout(false);
		$this->loadModel('ApiPlayers');
		
		$position_data = $this->ApiPlayers->find()
		->where([$condition])
		->order(['FantasyPointsPPR'=>'Desc'])
		->toArray();

		//pr($position_data);die;
		if($selectteam=='Flex'){
			$defalutlisting=DefalutListing;
			
			foreach($defalutlisting as $key=>$value){
				//$whereCond[]=['FantasyPosition'=>$value];
				$whereCond[]=$value;
			}
			$position_data = $this->ApiPlayers->find()
							->where([$condition,'FantasyPosition IN'=>$whereCond])
							//->orWhere($whereCond)
							->order(['FantasyPointsPPR'=>'Desc'])
							->toArray();
		}
		$formated_position_data = [];
		if (!empty($position_data)) {
			foreach($position_data AS $key => $value){
				$Position = $value['Position'];
				$FantasyPointsPPR = $value['FantasyPointsPPR'];
				$TeamID = $value['TeamID'];
				$Week = $value['Week'];
				$Team = $value['Team'];
				$GameDate=$value['GameDate'];
				$OpponentID = $value['OpponentID'];
				$Opponent = $value['Opponent'];
				$Position = $value['Position'];
				$PlayerID = $value['PlayerID'];
				$Name = $value['Name'];
				//$formated_position_data[$Team][] = [
				$formated_position_data[] = [
					'Position'=>$Position,
					'FantasyPointsPPR'=>$FantasyPointsPPR,
					'TeamID'=>$TeamID,
					'Week'=>$Week,
					'GameDate'=>$GameDate,
					'Team'=>$Team,
					'Position'=>$Position,
					'OpponentID'=>$OpponentID,
					'Opponent'=>$Opponent,
					'PlayerID'=>$PlayerID,
					'Name'=>$Name,
				];
			}
		}
		//dd($formated_position_data);die;
		$newDataArray=array("selectname"=>$selectteam,"position_data"=>$formated_position_data);

		$this->set(compact('newDataArray'));
	}


	//
	public function schedule() {
		date_default_timezone_set('US/Eastern');
		
		$this->set('title_for_layout', __('Schedule Contest List'));
		$this->loadModel('Drafts');
		$this->loadModel('DraftContests');
		
		$currentDate	=	date('Y-m-d');
		$oneMonthDate	=	date('Y-m-d',strtotime('+7 Days'));
		$currentTime	=	date('H:i', strtotime(MATCH_DURATION));
		$completeDate	=	date('Y-m-d',strtotime('-10 week'));
		
		$upcoming	=	$this->Drafts->find()
							->where(['startdate >= '=>$currentDate,'Drafts.status'=>1])
							->select(['id','name','startdate','starttime','enddate','endtime'])
							->order(['startdate','starttime'])
							->toArray();
		
		$liveTime	=	date('H:i',strtotime(MATCH_DURATION));
		$currentTime=	date('H:i');
		$live		=	$this->Drafts->find()
							->where(['startdate <=' => $currentDate,'Drafts.status'=>1,'enddate >='=> $currentDate])
							->select(['id','name','startdate','starttime','enddate','endtime'])
							->order(['startdate','starttime'])
							->toArray();
		

		$complete	=	$this->Drafts->find()
							->where(['enddate <=' => $currentDate,'Drafts.status'=>1])
							//->where(['OR'=>[['match_status'=>MATCH_FINISH],['match_status'=>MATCH_CANCELLED]]])
							->select(['id','name','startdate','starttime','enddate','endtime'])
							->order(['startdate','starttime'])
							->toArray();
		
		//pr($complete);die;
		$query = $this->DraftContests->find();
			$query=$query->select([
				'draft_id' => 'draft_id',
				'total' => $query->func()->count('draft_id')
			])
			->group('draft_id')
			->having(['draft_id >=' => 1])->toArray();
		$contest_ids_ary=array();
		//pr($query);

		if(!empty($query)){
			foreach($query as $key=>$value){
				if(!empty($value['draft_id']) && !empty($value['draft_id'])){
					$contest_ids_ary[$value['draft_id']]=$value['total'];
				}
			}
		}

		//pr($upcoming);pr($live);pr($complete);die;

		$this->set(compact('upcoming','live','complete','contest_ids_ary'));
	}

	public function addcontest($id = NULL) {
		$this->set('title_for_layout', __('Add Contest For a match'));
		$this->loadModel('Contest');
		$this->loadModel('DraftContests');

		if( $this->request->is(['post', 'put']) ) {
			// echo 'hii';die;
			$action	=	$this->request->getData();
			$string	=	'0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			
			if( isset($this->request->data['contest_id']) && !empty($this->request->data['contest_id']) ) {
				
				$strShuffled	=	str_shuffle($string);
				$contestStr		=	substr($strShuffled,0,5);
				$contest_id		=	$this->request->data['contest_id'];
				$set['draft_id']=	$this->request->data['draft_id'];
				$query			=	$this->DraftContests->query();
				//pr($contest_id);die;
				$contest_ids_ary	=	$this->Contest->find('list', ['keyField'=>'id','valueField'=>'id'])->where(['status'=>ACTIVE,'category_id != '=>0,'is_auto_create'=>1])->toArray();
				
				if ($set['draft_id'] && !empty($contest_ids_ary)){
					$query->delete()
					->where(['draft_id' => $set['draft_id'], 'contest_id IN '=>$contest_ids_ary])
					->execute();
				}

				$fcm=false;				
				foreach($contest_id as $key => $value) {
					$set['contest_id']	=	$value;
					$set['invite_code']	=	'1Q'.$contestStr.substr(str_shuffle($strShuffled),0,5);
					$setArr	=	$this->DraftContests->newEntity($set);
					if($this->DraftContests->save($setArr)){
						$fcm=true;				
					}
				}
				if($fcm){
					$message="A new contest lfgdraft has been added, play and earn.";
					$title="Contest Added";
					$this->sendNotificationFCMNew("all",$message,$title);
				}
				return $this->redirect(['action' => 'schedule']);
			}else{
				$this->Flash->error(__('Please select contest'));
			}
		}

		$arr	=	array();
		$list	=	$this->DraftContests->find('all',['conditions'=>['draft_id'=>$id]])->toArray();
		foreach($list as $key => $vl) {
			$arr[]	=	$vl->contest_id;
		}

		$record	=	$this->Contest->find('all')->where(['status'=>ACTIVE,'category_id != '=>0,'is_auto_create'=>1])->toArray();

		$newRecords = array();
		foreach($record as $k=>$v){
			$newRecords[$v->category_id][] = $v;
		}
		$this->set(compact('id','record','arr','newRecords'));
	}

	public function contest($id = NULL) {
		$this->set('title_for_layout', __('Add Contest For a match'));
		$this->loadModel('Contest');
		$this->loadModel('DraftContest');

		if($this->request->is(['post', 'put']) && !isset($this->request->data['winning_amount'])) {
			$action	=	$this->request->getData();
			$string	=	'0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			
			if(isset($this->request->data['contest_id']) && !empty($this->request->data['contest_id'])) {
				
				$strShuffled	=	str_shuffle($string);
				$contestStr		=	substr($strShuffled,0,5);
				$contest_id		=	$this->request->data['contest_id'];
				$set['match_id']=	$this->request->data['match_id'];
				$query			=	$this->DraftContest->query();

				$contest_ids_ary	=	$this->Contest->find('list', ['keyField'=>'id','valueField'=>'id'])->where(['status'=>ACTIVE,'category_id != '=>0,'is_auto_create'=>1])->toArray();
				
				if ($set['match_id'] && !empty($contest_ids_ary)){
					$query->delete()
					->where(['match_id' => $set['match_id'], 'contest_id IN '=>$contest_ids_ary])
					->execute();
				}
				
				foreach($contest_id as $key => $value) {
					$set['contest_id']	=	$value;
					$set['invite_code']	=	'1Q'.$contestStr.substr(str_shuffle($strShuffled),0,5);
					$setArr	=	$this->DraftContest->newEntity($set);
					$this->DraftContest->save($setArr);
				}
				//Cache::delete('home_page_matches','custom'); 
				//Cache::delete('home_page_draft_contests_count','custom');
				return $this->redirect(['action' => 'index']);
			}else{
				$this->Flash->error(__('Please select contest'));
			}
		}

		$arr	=	array();
		$list	=	$this->DraftContest->find('all',['conditions'=>['match_id'=>$id]])->toArray();
		foreach($list as $key => $vl) {
			$arr[]	=	$vl->contest_id;
		}

		if(isset($this->request->data['winning_amount'])){
			$winning_amount = $this->request->data['winning_amount'];
			$record	=	$this->Contest->find('all')->where(['winning_amount'=>$winning_amount,'status'=>ACTIVE,'category_id != '=>0,'is_auto_create'=>1])->toArray();
		} else {
			$record	=	$this->Contest->find('all')->where(['status'=>ACTIVE,'category_id != '=>0,'is_auto_create'=>1])->toArray();
		}

		$newRecords = array();
		foreach($record as $k=>$v){
			$newRecords[$v->category_id][] = $v;
		}
		// pr($newRecords);die;
		$this->set(compact('id','record','arr','newRecords'));
	}


	public function contestDetails($contestId = NULL,$id = NULL){
		$this->set('title_for_layout', __('Contest Details'));
		$this->loadModel('Contest');
		$this->loadModel('Drafts');
		$this->loadModel('DraftContests');
		$this->loadModel('PlayerTeams');
		//$this->loadModel('PlayerTeamContests');
		$contest=	$this->Contest->find('all',['conditions'=>['id'=>$contestId]])->first();
		$match	=	$this->Drafts->find('all',['conditions'=>['id'=>$id]])->first();
		#############
		if(!empty($match)){
			$match_id = $match->id;
			
			$totalTeamsJoined	=	$this->PlayerTeams->find()
							->where(['draft_id'=>$match_id,'contest_id'=>$contestId])
							//->contain(['PlayerTeams'=>['PlayerDayTeams'],'Users'=>['fields'=>['team_name','id','image']]])
							->order(['user_id'])->count();
			//echo "<pre>";print_r($totalTeamsJoined);echo "</pre>";die;
			$totalTeamsList	=	$this->PlayerTeams->find()
							->where(['draft_id'=>$match_id,'contest_id'=>$contestId])
							->contain(['Users'=>['fields'=>['id','profile_image','full_name']]])
							->order(['Users.rank'=>'ASC'])->toArray();
			//echo "<pre>";print_r($totalTeamsList);echo "</pre>";die;
			
			$details	=	$this->DraftContests->find()
							->where(['draft_id'=>$match_id,'contest_id'=>$contestId])
							//->contain(['DraftContests'=>['fields'=>['id']]])
							->first();
			//echo "<pre>";print_r($details);echo "</pre>";die;
			
			$isCanceled	=	$details['is_cancelled'];
			$invite_code=	$details['invite_code'];
		}
		
		
		$this->set(compact('contest','match','totalTeamsJoined','totalTeamsList','contestId','id','invite_code','match_id','isCanceled','boatlist','anglerslist','boat_day_points','anglers_day_points'));
	}
	public function categoryContest() 
	{
		$id	=	$this->request->data['id'];
		$category_id   =	$this->request->data['category_id'];
		
		$this->viewBuilder()->layout(false);
		$this->loadModel('Contest');
		$this->loadModel('DraftContest');
		$arr	=	array();
		$list	=	$this->DraftContest->find('all',['conditions'=>['match_id'=>$id]])->toArray();
		foreach($list as $key => $vl) {
			$arr[]	=	$vl->contest_id;
		}
		$record	=	$this->Contest->find('all')->where(['status'=>ACTIVE,'category_id'=>$category_id])->toArray();
		$newRecords = array();
		foreach($record as $k=>$v)
		{
			$newRecords[$category_id][] = $v;
		}
		// pr($newRecords);die;
		$this->set(compact('id','record','arr','newRecords','category_id'));
	}
	
	public function addedContest($id = NULL) {
		$this->set('title_for_layout', __('Details For Added Contest'));
		$this->loadModel('Contest');
		$this->loadModel('Drafts');
		if(isset($this->request->data['winning_amount']))
		{
			$winning_amount = $this->request->data['winning_amount'];
			$query	=	$this->Contest->find()
			->select(['Contest.id','Contest.category_id','Contest.winning_amount','Contest.contest_size','Contest.contest_type','Contest.entry_fee','draft_contests.is_cancelled'])
			->join([
				'draft_contests' => [
					'table' => 'draft_contests',
					'type' => 'LEFT',
					'conditions' => '(Contest.id = draft_contests.contest_id)'
				]
			])->WHERE(['Contest.status'=>'1','Contest.winning_amount'=>$winning_amount,'draft_contests.draft_id'=>$id]);
		}
		else
		{
			$query	=	$this->Contest->find()
			->select(['Contest.id','Contest.category_id','Contest.winning_amount','Contest.contest_size','Contest.contest_type','Contest.entry_fee','draft_contests.is_cancelled'])
			->join([
				'draft_contests' => [
					'table' => 'draft_contests',
					'type' => 'LEFT',
					'conditions' => '(Contest.id = draft_contests.contest_id)'
				]
			])->WHERE(['Contest.status'=>'1','draft_contests.draft_id'=>$id]);
		}
		
		$record	=	$query->toArray();

		$matchDtl = $this->Drafts->find()->where(['id'=>$id])->first();
		
		$draft_id = $id;
		$newRecords = array();
		foreach($record as $k=>$v)
		{
			$newRecords[$v->category_id][] = $v;
		}
		
		 //pr($newRecords);die;
		$this->set(compact('record','id','draft_id','newRecords'));
	}
	public function addedCategoryContest() 
	{
		$id	=	$this->request->data['id'];
		$category_id   =	$this->request->data['category_id'];
		$this->viewBuilder()->layout(false);
		$this->loadModel('Contest');
		$this->loadModel('Drafts');
		$query	=	$this->Contest->find()
					->select(['Contest.id','Contest.category_id','Contest.winning_amount','Contest.contest_size','Contest.contest_type','Contest.entry_fee','draft_contests.is_cancelled'])
					->join([
						'draft_contests' => [
							'table' => 'draft_contests',
							'type' => 'LEFT',
							'conditions' => '(Contest.id = draft_contests.contest_id)'
						]
					])->WHERE(['Contest.status'=>'1','draft_contests.draft_id'=>$id,'category_id'=>$category_id]);
		$record	=	$query->toArray();

		$matchDtl = $this->Drafts->find()->where(['id'=>$id])->first();
		$match_id = $matchDtl->id;
		$newRecords = array();
		foreach($record as $k=>$v)
		{
			$newRecords[$category_id][] = $v;
		}
		// pr($newRecords);die;
		$this->set(compact('record','id','match_id','newRecords'));
	}

	public function editweek(){
		$this->viewBuilder()->layout(false);

		$seasontype  	=	$this->request->data['seasontype'];
		$season  	=	$this->request->data['season'];

		$this->loadModel('ApiPlayers');
		$weeks = $this->ApiPlayers->find('list',['keyField'=>'Week','valueField'=>'Week'])
		->where(['Season' => $season,'SeasonType'=>$seasontype])
		->group(['Week'])
		->toArray();
		//pr($weeks);
		$data='<option value="">Select Season</option>';
		foreach($weeks as $key=>$weekValue){
				$data.="<option value=".$key."> Week".$key."</option>";
		}
		echo $data;die;
	}

}
