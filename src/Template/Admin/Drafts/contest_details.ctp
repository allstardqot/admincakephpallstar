<?php ?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Contest Details</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/">Dashboard</a></li>
                        <li class="breadcrumb-item active">Contest Details</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card-body"><div class="card-deck">
                                    <div class="card"><div class="card-body">
                                        <?php
                                        $data = $this->Custom->getCategoryDetails($contest['category_id']);
                                        $totalWins = $this->Custom->getTotalWinners($contest['id']);
                                        ?>
                                        <div class="row">
                                            <div class="col-sm-6">Category:</div>
                                            <div class="col-sm-6"><strong><?=$data['category_name'];?></strong></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">Winning Prize:</div>
                                            <div class="col-sm-6"><strong><?=$contest['winning_amount'];?> INR</strong></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">Max Team Size:</div>
                                            <div class="col-sm-6"><strong><?=$contest['contest_size'];?></strong></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">Winner Team Size:</div>
                                            <div class="col-sm-6"><strong><?=$totalWins;?></strong></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">Entry Fee:</div>
                                            <div class="col-sm-6"><strong><?=$contest['entry_fee'];?> INR</strong></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">Contest Type:</div>
                                            <div class="col-sm-6"><strong><?=$contest['contest_type'];?></strong></div>
                                        </div>
                                    </div></div>
                                    <div class="card"><div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-6">Starts At:</div>
                                            <div class="col-sm-6"><strong><?php echo date('Y-m-d',strtotime($match['date'])).', '.$match['time'];?></strong></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">Entries Left:</div>
                                            <div class="col-sm-6"><strong><?=($contest['contest_size']-$totalTeamsJoined);?></strong></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">Teams Joined:</div>
                                            <div class="col-sm-6"><strong><?= $totalTeamsJoined?></strong></div>
                                        </div>
                                        <?php
                                        if($invite_code==''){
                                            $invite_code = 'Contest invite code not ceated.';
                                        }
                                        ?>
                                        <div class="row">
                                            <div class="col-sm-6">Contest Invite Code:</div>
                                            <div class="col-sm-6"><strong><?= $invite_code?></strong></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">&nbsp;</div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">Contest Status: <b><?php echo ($isCanceled) ? "Cancelled" : "Running"; ?></b></div>
                                            <div class="col-sm-6">
                                                <!-- <a style="margin-right: 45px;" href="<?=SITE_URL."admin/schedule/cancelUnconfirmedContest/".$match_id.'/'.$contestId; ?>" class="btn btn-primary btn-sm" onclick="return confirm('Are you sure, You want to cancel this contest?')">Cancel Contest</a> -->
                                            </div>
                                        </div>
                                    </div></div>
                                </div></div>
                            </div>
                        </div>

                        <!-- <div class="row">
                            <div class="col-md-12">
                                <div class="card-body">
                                    <div class="card-deck">
                                        <div class="card">
                                            <div class="card-body">
                                                <span>Download PDF</span>
                                                <a style="float: right; margin-right: 45px;" href="<?=SITE_URL."admin/schedule/leaderboard/".$contestId.'/'.$id?>" class="btn btn-primary btn-sm">Download</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->

                        <div class="row"><div class="col-md-12">
                            <div class="card-body">
                                <div class="card-deck">
                                <div class="card">
                                    <div class="card-body">
                                    <div class="card-header">
                                        <strong><i class="icon-info pr-1"></i>Joined Team</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Id</th>
                                                        <th>Image</th>
                                                        <th>Team Name</th>
                                                        <th>Created By </th>
                                                        <th>Points</th>
                                                        <th>Rank</th>
                                                        <th>Amount</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    
                                                    <?php
                                                    if(!empty($totalTeamsList)){ $i=1;
                                                        foreach($totalTeamsList as $team){ 
                                                        if($team->winning_coins==''){
                                                            $amnt = 0;
                                                        }else{
                                                            $amnt = $team->winning_coins;
                                                        }
                                                        //pr($team->player_team->player_team_details);
                                                        $teamDtl = $this->Custom->getPlayersDetails($team->player_data);
                                                        //$teamDtl = json_decode($team->player_data); 
                                                        //echo "<pre>";print_r($teamDtl);echo "</pre>";
                                                        //echo "fffffff".$teamDtl."tttt";die;
                                                        //echo '<pre>';
                                                        //print_r($team);die;
                                                        ?>
                                                        <tr>
                                                            <td><?= $i?> </td>
                                                            <td>
                                                                <?php
                                                                if($team->user->profile_image!='') {
                                                                    echo '<img src="'.$this->request->webroot.'uploads/users/'.$team->user->profile_image.'" hight="100px" width="40px" alt="">';
                                                                 } else {
                                                                     echo 'No Image';
                                                                } ?>
                                                            </td>
                                                            <!--<td><?= $team->user->team_name;?> </td>-->
                                                            <td><?= $team->user->full_name;?></td>
                                                            <td></td>
                                                            <td>
    														    <div class="container">

                                                                    <div class="modal fade" id="myModal<?=$i?>" role="dialog">
                                                                        <div class="modal-dialog"><div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h4 class="modal-title">Team List</h4>
                                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <div class="table-responsive">
                                                                                <table class="table table-bordered responsive">
                                                                                    <tr>
                                                                                        <th>Image</th>
                                                                                        <th>Day</th>
                                                                                        <th>Name</th>
                                                                                        <th>Playing Role</th>
                                                                                        <th>Projected Points</th>
                                                                                        <th>Fantasy Points</th>
                                                                                    </tr>
                                                                                    <?php 
                                                                                    $teamPoints = 0;
                                                                                    foreach($teamDtl as $row) {

                                                                                        //$teamPoints = $teamPoints+$playerrcd['point'];
                                                                                        $img    =   $this->Html->image($row->Image,['alt'=>'player image','style'=>'hight:40px;width:40px;']);
                                                                                        $tounament_id       =   $row->GameKey;
                                                                                        //$selected_boats     =   json_decode($row->selected_boats);
                                                                                        //$selected_anglers   =   json_decode($row->selected_anglers);

                                                                                        //pr($selected_boats);

                                                                                        /*if( !empty( $selected_boats ) ){
                                                                                            foreach( $selected_boats AS $key => $boat_id ){
                                                                                                $mindex = date('Y-m-d',strtotime($row->day));*/
                                                                                        ?>
                                                                                                <tr>
                                                                                                    <td><?php echo $img; ?></td>
                                                                                                    <td><?php echo date('d F, Y',strtotime($row->GameDate)); ?></td>
                                                                                                    <td><?php echo $row->Name; ?></td>
                                                                                                    <td><?php echo $row->Position; ?></td>FantasyPoints
                                                                                                    <td><?php echo $row->FantasyPointsPPR; ?></td>
                                                                                                    <td><?php echo $row->FantasyPoints; ?></td>
                                                                                                </tr>
                                                                                        <?php   
                                                                                            /*}
                                                                                        }*/
                                                                                    ?>
                                                                                      
                                                                                    <?php } ?>
                                                                                </table>
                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                            </div>
                                                                        </div></div>
                                                                    </div>
                                                                    
                                                                </div>
    														
                                                                <?php
                                                                    echo $team->points;
                                                                ?> 
                                                            </td>
                                                            <td><?= $team->rank;?> </td>
                                                            <td><?= $amnt;?> INR</td>
                                                            <td class="last_td"><button type="button" class="btn btn-success" data-toggle="modal" data-target="#myModal<?=$i?>">View List</button>
                                                            </td>
                                                        </tr>
                                                        <?php $i++; 
                                                        }
                                                    }else{
                                                    ?>   
                                                    <tr>
                                                        <td colspan="9">
                                                            <div class="card"><div class="card-body">
                                                                <center><b>No Data Found</b></center>
                                                            </div></div>
                                                        </td>
                                                    </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="col-sm-12" style="margin-bottom: 40px;">
                            <?php echo($this->Html->link('<i class="fa fa-arrow-left"></i> Back', array('controller' => 'Schedule', 'action' => 'index'), array('escape' => false, 'class' => 'btn btn-primary'))); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> -->
</div>
