<!doctype html>
<html lang="en">
	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<?php
			echo $this->Html->css([
				'../assets/css/bootstrap.min.css',
				'pointstyle.css',
			]); 
		?>
		<link href="https://fonts.googleapis.com/css?family=Montserrat:100,300,400,500,600,700" rel="stylesheet">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
		<title>Points System</title>
	</head>
	<body>
		<main>
			<section class="real_11_outer top_outer">
				<div class="container">
					<div class="row" >
						<div class="col-md-12 real_outer">
							<div class="inner_tabing">
								<!-- <div class="top_header real11">
									<div class="logo_side">
										<a href="#"><img src="https://d13ir53smqqeyp.cloudfront.net/d11-static-pages/images/home-icon.png" alt=""></a>
									</div>
									<div class="page_heading text-center">
										<h4>Points System</h4>
									</div>
								</div> -->
								<div class="list-group real11" id="myList" role="tablist">
									<a class="list-group-item list-group-item-action active show" data-toggle="list" href="#home" role="tab" aria-selected="true">T20</a>
									<a class="list-group-item list-group-item-action" data-toggle="list" href="#profile" role="tab" aria-selected="false">ODI</a>
									<a class="list-group-item list-group-item-action" data-toggle="list" href="#messages" role="tab" aria-selected="false">TEST</a>
									<a class="list-group-item list-group-item-action" data-toggle="list" href="#messages_5" role="tab" aria-selected="false">T10</a>
								</div>
							</div>
						</div>
						</div>
				</div>
			</section>
			<section class="real_11_outer">
				<div class="container">
					<div class="row">
						<div class="col-md-12 real_outer">
							<div class="inner_tabing">
								<div class="tab-content">
									<div class="tab-pane active show" id="home" role="tabpanel">
										<div class="culture_tab">
											<div class="row">
												<div class="col-xs-12 col-md-12">
													<div class="top_head">
														<p>Here’s how your team earns points</p>
													</div>
													<div class="full help_center_collaps points" id="accordion">
														<div class="card">
															<div class="card-header">
																<a class="card-link" data-toggle="collapse" href="#collapseOne">
																<span class="headingS"><img src="img/batting-icon_1.png" alt=""></span>Batting
																</a>
															</div>
															<div id="collapseOne" class="collapse show" data-parent="#accordion">
																<div class="card-body">
																	<ul class="batting_list">
																		<li>
																			<a href="javascript:void(0)">
																			Run <span>+<?php echo !empty($t20data->battingRun) ? $t20data->battingRun : '0';?></span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																			Boundary Bonus <span>+<?php echo !empty($t20data->battingBoundary) ? $t20data->battingBoundary : '0';?></span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																			Six Bonus <span>+<?php echo !empty($t20data->battingSix) ? $t20data->battingSix : '0';?></span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																			Half-Century Bonus <span>+<?php echo !empty($t20data->battingHalfCentury) ? $t20data->battingHalfCentury : '0';?></span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																			Century Bonus <span>+<?php echo !empty($t20data->battingCentury) ? $t20data->battingCentury : '0';?></span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																			Dismissal for a duck 
																			<small>Batsman, wicket-keeper & All-Rounder</small> 
																			<span class="orange"><?php echo !empty($t20data->battingDuck) ? $t20data->battingDuck : '0';?></span>
																			</a>
																		</li>
																	</ul>
																</div>
															</div>
														</div>
														<div class="card">
															<div class="card-header">
																<a class="collapsed card-link" data-toggle="collapse" href="#collapseTwo">
																	<span class="headingS">
																		<img src="img/batting-icon_2.png" alt="">
																	</span>Bowling
																</a>
															</div>
															<div id="collapseTwo" class="collapse" data-parent="#accordion">
																<div class="card-body">
																	<ul class="batting_list">
																		<li>
																			<a href="javascript:void(0)">
																			Wicket <small> Excluding Run Out </small> 
																			<span>+<?php echo !empty($t20data->bowlingWicket) ? $t20data->bowlingWicket : '0';?></span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																			4 wicket haul Bonus 
																			<span>+<?php echo !empty($t20data->bowling4Wicket) ? $t20data->bowling4Wicket : '0';?></span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																			5 wicket haul Bonus 
																			<span>+<?php echo !empty($t20data->bowling5Wicket) ? $t20data->bowling5Wicket : '0';?></span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																			Maiden over 
																			<span>+<?php echo !empty($t20data->bowlingMaiden) ? $t20data->bowlingMaiden : '0';?></span>
																			</a>
																		</li>
																	</ul>
																</div>
															</div>
														</div>
														<div class="card">
															<div class="card-header">
																<a class="collapsed card-link" data-toggle="collapse" href="#collapseThree">
																<span class="headingS"><img src="img/batting-icon_3.png" alt=""></span>Fielding
																</a>
															</div>
															<div id="collapseThree" class="collapse" data-parent="#accordion">
																<div class="card-body">
																	<ul class="batting_list">
																		<li>
																			<a href="javascript:void(0)">
																			Catch <span>+<?php echo !empty($t20data->fieldingCatch) ? $t20data->fieldingCatch : '0';?></span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																			Stumping/Run-out 
																			<small>Direct</small>
																			<span>+<?php echo !empty($t20data->fieldingStumpRunOut) ? $t20data->fieldingStumpRunOut : '0';?></span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																			Run-out thrower
																			<span class="one">
																				+<?php echo !empty($t20data->fieldingRunOutThrower) ? $t20data->fieldingRunOutThrower : '0';?>
																			</span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																			Run-out catcher
																			<span class="one">
																				+<?php echo !empty($t20data->fieldingRunOutCatcher) ? $t20data->fieldingRunOutCatcher : '0';?>
																			</span>
																			</a>
																		</li>
																	</ul>
																</div>
															</div>
														</div>
														<div class="card">
															<div class="card-header">
																<a class="collapsed card-link" data-toggle="collapse" href="#collapseFour">
																<span class="headingS"><img src="img/batting-icon_4.png" alt=""></span>Others
																</a>
															</div>
															<div id="collapseFour" class="collapse" data-parent="#accordion">
																<div class="card-body">
																	<ul class="batting_list">
																		<li>
																			<a href="javascript:void(0)">
																			Captain <span><?php echo !empty($t20data->othersCaptain) ? $t20data->othersCaptain : '0';?>X</span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																			Vice-Captain <span><?php echo !empty($t20data->othersViceCaptain) ? $t20data->othersViceCaptain : '0';?>X</span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																			In starting 11 <span>+<?php echo !empty($t20data->othersStarting11) ? $t20data->othersStarting11 : '0';?></span>
																			</a>
																		</li>
																	</ul>
																</div>
															</div>
														</div>
														<div class="card">
															<div class="card-header">
																<a class="collapsed card-link" data-toggle="collapse" href="#collapseFive">
																<span class="headingS"><img src="img/batting-icon_5.png" alt=""></span>Economy Rate
																<span class="sub_head">Min 2 Overs To Be Bowled</span>
																</a>
															</div>
															<div id="collapseFive" class="collapse" data-parent="#accordion">
																<div class="card-body">
																	<ul class="batting_list">
																		<li>
																			<a href="javascript:void(0)">
																				Below 4 runs per over <span>+<?php echo !empty($t20data->t20EconomyLt4Runs) ? $t20data->t20EconomyLt4Runs : '0';?></span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																				Between 4-4.99 runs per over <span>+<?php echo !empty($t20data->t20EconomyGt4Runs) ? $t20data->t20EconomyGt4Runs : '0';?></span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																				Between 5-8 runs per over <span>+<?php echo !empty($t20data->t20EconomyGt5Runs) ? $t20data->t20EconomyGt5Runs : '0';?></span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																				Between 9-10 runs per over <span class="orange"><?php echo !empty($t20data->t20EconomyGt9Runs) ? $t20data->t20EconomyGt9Runs : '0';?></span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																				Between 10.1-11 runs per over <span class="orange"><?php echo !empty($t20data->t20EconomyGt10Runs) ? $t20data->t20EconomyGt10Runs : '0';?></span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																				Above 11 runs per over <span class="orange"><?php echo !empty($t20data->t20EconomyGt11Runs) ? $t20data->t20EconomyGt11Runs : '0';?></span>
																			</a>
																		</li>
																	</ul>
																</div>
															</div>
														</div>
														<div class="card">
															<div class="card-header">
																<a class="collapsed card-link" data-toggle="collapse" href="#collapseSix">
																<span class="headingS"><img src="img/batting-icon_6.png" alt=""></span>Strike rate(Except Bowler)
																<span class="sub_head">Min 10 balls to be played</span>
																</a>
															</div>
															<div id="collapseSix" class="collapse" data-parent="#accordion">
																<div class="card-body">
																	<ul class="batting_list">
																		<li>
																			<a href="javascript:void(0)">
																				Between 60-70 runs per 100 balls <span class="orange"><?php echo !empty($t20data->t20StrikeGt60Runs) ? $t20data->t20StrikeGt60Runs : '0';?></span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																				Between 50-59.9 runs per 100 balls <span class="orange"><?php echo !empty($t20data->t20StrikeGt50Runs) ? $t20data->t20StrikeGt50Runs : '0';?></span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																				Below 50 runs per 100 balls <span class="orange"><?php echo !empty($t20data->t20StrikeLt50Runs) ? $t20data->t20StrikeLt50Runs : '0';?></span>
																			</a>
																		</li>
																	</ul>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="col-md-12">
													<?php //echo $content->content; ?>
													<?php /* <ul class="culture_list">
														<li>
															The cricketer you choose to be your Fantasy Cricket Team’s Captain will receive 2 times the points
														</li>
														<li>
															The Vice-Captain will receive 1.5 times the points for his performance
														</li>
														<li>
															Starting points are assigned to any player on the basis of announcement of his/her inclusion in the team. However, in case the player is unable to start the match after being included in the team sheet, he/she will not score any points. Points shall however, be applicable (including starting points) to any player who plays as a replacement of such player to whom starting points were initially assigned.
														</li>
														<li>
															Strike rate scoring is applicable only for strike rate below 70 runs per 100 balls
														</li>
														<li>
															None of the events occurring during a Super Over, if any, will be considered for Points to be assigned/applied to a player.
														</li>
														<li>
															In case of run-outs involving 3 players from the fielding side, all players involved will be allotted 2 points each.
														</li>
														<li>
															Any player scoring a century will only get points for the century and no points will be awarded for half-century. Any score over and above century will be eligible for bonus points only for the century.
														</li>
														<li>
															Substitutes on the field will not be awarded points for any contribution they make. However, 'Concussion Substitutes' (as are permitted in the applicable rules and regulations for certain domestic matches in Australia, New Zealand and England) will be awarded points for any contributions they make in a match where they make an appearance as a 'Concussion Substitute'. It is clarified that Concussion Substitutes will be awarded only one (1) point for making an appearance (other than the points awarded for their sporting contributions) in a match as opposed to the two (2) points which are awarded to a player who is named in the starting eleven of a particular match.
														</li>
														<li>
															In case a player is transferred/reassigned to a different team between two scheduled updates, for any reason whatsoever, such transfer/reassignment (by whatever name called) shall not be reflected in the roster of players until next scheduled update. It is clarified that during the intervening period of two scheduled updates, while such player will be available for selection in the team to which the player originally belong, no points will be attributable to such player during the course of such contest.
														</li>
														<li>
															Data is provided by reliable sources and once the points have been marked as completed i.e. winners have been declared, no further adjustments will be made. Points awarded live in-game are subject to change as long as the status is 'In progress' or 'Waiting for review'.
														</li>
													</ul> */ ?>
												</div>
											</div>
										</div>
									</div>
									<div class="tab-pane" id="profile" role="tabpanel">
										<div class="culture_tab">
											<div class="row">
												<div class="col-xs-12 col-md-12">
													<div class="top_head">
														<p>Here’s how your team earns points</p>
													</div>
													<div class="full help_center_collaps points" id="accordion2">
														<div class="card">
															<div class="card-header">
																<a class="card-link" data-toggle="collapse" href="#collapsebating">
																<span class="headingS"><img src="img/batting-icon_1.png" alt=""></span>Batting
																</a>
															</div>
															<div id="collapsebating" class="collapse show" data-parent="#accordion2">
																<div class="card-body">
																	<ul class="batting_list">
																		<li>
																			<a href="javascript:void(0)">
																				Run <span>+<?php echo !empty($odidata->battingRun) ? $odidata->battingRun : '0';?></span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																				Boundary Bonus <span>+<?php echo !empty($odidata->battingBoundary) ? $odidata->battingBoundary : '0';?></span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																				Six Bonus <span>+<?php echo !empty($odidata->battingSix) ? $odidata->battingSix : '0';?></span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																				Half-Century Bonus <span>+<?php echo !empty($odidata->battingHalfCentury) ? $odidata->battingHalfCentury : '0';?></span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																				Century Bonus <span>+<?php echo !empty($odidata->battingCentury) ? $odidata->battingCentury : '0';?></span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																				Dismissal for a duck 
																				<small>Batsman, wicket-keeper & All-Rounder</small> 
																				<span class="orange"><?php echo !empty($odidata->battingDuck) ? $odidata->battingDuck : '0';?></span>
																			</a>
																		</li>
																	</ul>
																</div>
															</div>
														</div>
														<div class="card">
															<div class="card-header">
																<a class="collapsed card-link" data-toggle="collapse" href="#collapsebatingTwo">
																<span class="headingS"><img src="img/batting-icon_2.png" alt=""></span>Bowling
																</a>
															</div>
															<div id="collapsebatingTwo" class="collapse" data-parent="#accordion2">
																<div class="card-body">
																	<ul class="batting_list">
																		<li>
																			<a href="javascript:void(0)">
																				Wicket <small> Excluding Run Out </small> 
																				<span>+<?php echo !empty($odidata->bowlingWicket) ? $odidata->bowlingWicket : '0';?></span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																				4 wicket haul Bonus 
																				<span>+<?php echo !empty($odidata->bowling4Wicket) ? $odidata->bowling4Wicket : '0';?></span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																				5 wicket haul Bonus 
																				<span>+<?php echo !empty($odidata->bowling5Wicket) ? $odidata->bowling5Wicket : '0';?></span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																				Maiden over 
																				<span>+<?php echo !empty($odidata->bowlingMaiden) ? $odidata->bowlingMaiden : '0';?></span>
																			</a>
																		</li>
																	</ul>
																</div>
															</div>
														</div>
														<div class="card">
															<div class="card-header">
																<a class="collapsed card-link" data-toggle="collapse" href="#collapsebatingThree">
																<span class="headingS"><img src="img/batting-icon_3.png" alt=""></span>Fielding
																</a>
															</div>
															<div id="collapsebatingThree" class="collapse" data-parent="#accordion2">
																<div class="card-body">
																	<ul class="batting_list">
																		<li>
																			<a href="javascript:void(0)">
																			Catch <span>+<?php echo !empty($odidata->fieldingCatch) ? $odidata->fieldingCatch : '0';?></span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																			Stumping/Run-out  
																			<small>Direct</small>
																			<span>+<?php echo !empty($odidata->fieldingStumpRunOut) ? $odidata->fieldingStumpRunOut : '0';?></span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																			Run-out thrower
																			<span class="one">
																				+<?php echo !empty($odidata->fieldingRunOutThrower) ? $odidata->fieldingRunOutThrower : '0';?>
																			</span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																			Run-out catcher
																			<span class="one">
																				+<?php echo !empty($odidata->fieldingRunOutCatcher) ? $odidata->fieldingRunOutCatcher : '0';?>
																			</span>
																			</a>
																		</li>
																		


																	</ul>
																</div>
															</div>
														</div>
														<div class="card">
															<div class="card-header">
																<a class="collapsed card-link" data-toggle="collapse" href="#collapsebatingFour">
																<span class="headingS"><img src="img/batting-icon_4.png" alt=""></span>Others
																</a>
															</div>
															<div id="collapsebatingFour" class="collapse" data-parent="#accordion2">
																<div class="card-body">
																	<ul class="batting_list">
																		<li>
																			<a href="javascript:void(0)">
																			Captain <span><?php echo !empty($odidata->othersCaptain) ? $odidata->othersCaptain : '0';?>X</span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																			Vice-Captain <span><?php echo !empty($odidata->othersViceCaptain) ? $odidata->othersViceCaptain : '0';?>X</span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																			In starting 11 <span>+<?php echo !empty($odidata->othersStarting11) ? $odidata->othersStarting11 : '0';?></span>
																			</a>
																		</li>
																	</ul>
																</div>
															</div>
														</div>
														<div class="card">
															<div class="card-header">
																<a class="collapsed card-link" data-toggle="collapse" href="#collapsebatingFive">
																<span class="headingS"><img src="img/batting-icon_5.png" alt=""></span>Economy Rate
																<span class="sub_head">Min 5 Overs To Be Bowled</span>
																</a>
															</div>
															<div id="collapsebatingFive" class="collapse" data-parent="#accordion2">
																<div class="card-body">
																	<ul class="batting_list">
																		<li>
																			<a href="javascript:void(0)">
																				Below 2.5 runs per over <span>+<?php echo !empty($odidata->odiEconomyLt2_5Runs) ? $odidata->odiEconomyLt2_5Runs : '0';?></span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																				Between 2.5 - 3.49 runs per over <span>+<?php echo !empty($odidata->odiEconomyGt2_5Runs) ? $odidata->odiEconomyGt2_5Runs : '0';?></span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																				Between 3.5 - 4.5 runs per over <span>+<?php echo !empty($odidata->odiEconomyGt3_5Runs) ? $odidata->odiEconomyGt3_5Runs : '0';?></span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																				Between 7 - 8 runs per over <span class="orange"><?php echo !empty($odidata->odiEconomyGt5Runs) ? $odidata->odiEconomyGt5Runs : '0';?></span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																				Between 8.1 - 9 runs per over <span class="orange"><?php echo !empty($odidata->odiEconomyGt8Runs) ? $odidata->odiEconomyGt8Runs : '0';?></span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																				Above 9 runs per over <span class="orange"><?php echo !empty($odidata->odiEconomyGt9Runs) ? $odidata->odiEconomyGt9Runs : '0';?></span>
																			</a>
																		</li>
																	</ul>
																</div>
															</div>
														</div>
														<div class="card">
															<div class="card-header">
																<a class="collapsed card-link" data-toggle="collapse" href="#collapsebatingSix">
																<span class="headingS"><img src="img/batting-icon_6.png" alt=""></span>Strike rate(Except Bowler)
																<span class="sub_head">Min 10 balls to be played</span>
																</a>
															</div>
															<div id="collapsebatingSix" class="collapse" data-parent="#accordion2">
																<div class="card-body">
																	<ul class="batting_list">
																		<li>
																			<a href="javascript:void(0)">
																				Between 50 - 60 runs per 100 balls <span class="orange"><?php echo !empty($odidata->odiStrikeGt50Runs) ? $odidata->odiStrikeGt50Runs : '0';?></span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																				Between 40 - 49.99 runs per 100 balls <span class="orange"><?php echo !empty($odidata->odiStrikeGt40Runs) ? $odidata->odiStrikeGt40Runs : '0';?></span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																				Below 40 runs per 100 balls <span class="orange"><?php echo !empty($odidata->odiStrikeLt40Runs) ? $odidata->odiStrikeLt40Runs : '0';?></span>
																			</a>
																		</li>
																	</ul>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="col-md-12">
													<?php //echo $content->content; ?>
												</div>
											</div>
										</div>
									</div>
									<div class="tab-pane" id="messages" role="tabpanel">
										<div class="culture_tab">
											<div class="row">
												<div class="col-xs-12 col-md-12">
													<div class="top_head">
														<p>Here’s how your team earns points</p>
													</div>
													<div class="full help_center_collaps points" id="accordion3">
														<div class="card">
															<div class="card-header">
																<a class="card-link" data-toggle="collapse" href="#collapseOneball">
																<span class="headingS"><img src="img/batting-icon_1.png" alt=""></span>Batting
																</a>
															</div>
															<div id="collapseOneball" class="collapse show" data-parent="#accordion3">
																<div class="card-body">
																	<ul class="batting_list">
																		<li>
																			<a href="javascript:void(0)">
																			Run <span>+<?php echo !empty($testdata->battingRun) ? $testdata->battingRun : '0';?></span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																			Boundary Bonus <span>+<?php echo !empty($testdata->battingBoundary) ? $testdata->battingBoundary : '0';?></span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																			Six Bonus <span>+<?php echo !empty($testdata->battingSix) ? $testdata->battingSix : '0';?></span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																			Half-Century Bonus <span>+<?php echo !empty($testdata->battingHalfCentury) ? $testdata->battingHalfCentury : '0';?></span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																			Century Bonus <span>+<?php echo !empty($testdata->battingCentury) ? $testdata->battingCentury : '0';?></span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																			Dismissal for a duck 
																			<small>Batsman, wicket-keeper & All-Rounder</small> 
																			<span class="orange"><?php echo !empty($testdata->battingDuck) ? $testdata->battingDuck : '0';?></span>
																			</a>
																		</li>
																	</ul>
																</div>
															</div>
														</div>
														<div class="card">
															<div class="card-header">
																<a class="collapsed card-link" data-toggle="collapse" href="#collapseTwoball">
																<span class="headingS"><img src="img/batting-icon_2.png" alt=""></span>Bowling
																</a>
															</div>
															<div id="collapseTwoball" class="collapse" data-parent="#accordion3">
																<div class="card-body">
																	<ul class="batting_list">
																		<li>
																			<a href="javascript:void(0)">
																			Wicket <small> Excluding Run Out </small> 
																			<span>+<?php echo !empty($testdata->bowlingWicket) ? $testdata->bowlingWicket : '0';?></span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																			4 wicket haul Bonus 
																			<span>+<?php echo !empty($testdata->bowling4Wicket) ? $testdata->bowling4Wicket : '0';?></span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																			5 wicket haul Bonus 
																			<span>+<?php echo !empty($testdata->bowling5Wicket) ? $testdata->bowling5Wicket : '0';?></span>
																			</a>
																		</li>
																		<?php /* <li>
																			<a href="javascript:void(0)">
																			Maiden over 
																			<span>+<?php echo !empty($testdata->bowlingMaiden) ? $testdata->bowlingMaiden : '0';?></span>
																			</a>
																		</li> */ ?>
																	</ul>
																</div>
															</div>
														</div>
														<div class="card">
															<div class="card-header">
																<a class="collapsed card-link" data-toggle="collapse" href="#collapseThreeball">
																<span class="headingS"><img src="img/batting-icon_3.png" alt=""></span>Fielding
																</a>
															</div>
															<div id="collapseThreeball" class="collapse" data-parent="#accordion3">
																<div class="card-body">
																	<ul class="batting_list">
																		<li>
																			<a href="javascript:void(0)">
																			Catch <span>+<?php echo !empty($testdata->fieldingCatch) ? $testdata->fieldingCatch : '0';?></span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																			Stumping/Run-out 
																			<small>Direct</small>
																			<span>+<?php echo !empty($testdata->fieldingStumpRunOut) ? $testdata->fieldingStumpRunOut : '0';?></span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																			Run-out thrower
																			<span class="one">
																				+<?php echo !empty($testdata->fieldingRunOutThrower) ? $testdata->fieldingRunOutThrower : '0';?>
																			</span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																			Run-out catcher
																			<span class="one">
																				+<?php echo !empty($testdata->fieldingRunOutCatcher) ? $testdata->fieldingRunOutCatcher : '0';?>
																			</span>
																			</a>
																		</li>

																	</ul>
																</div>
															</div>
														</div>
														<div class="card">
															<div class="card-header">
																<a class="collapsed card-link" data-toggle="collapse" href="#collapseFourball">
																<span class="headingS"><img src="img/batting-icon_4.png" alt=""></span>Others
																</a>
															</div>
															<div id="collapseFourball" class="collapse" data-parent="#accordion3">
																<div class="card-body">
																	<ul class="batting_list">
																		<li>
																			<a href="javascript:void(0)">
																			Captain <span><?php echo !empty($testdata->othersCaptain) ? $testdata->othersCaptain : '0';?>X</span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																			Vice-Captain <span><?php echo !empty($testdata->othersViceCaptain) ? $testdata->othersViceCaptain : '0';?>X</span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																			In starting 11 <span>+<?php echo !empty($testdata->othersStarting11) ? $testdata->othersStarting11 : '0';?></span>
																			</a>
																		</li>
																	</ul>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="col-md-12">
													<?php //echo $content->content; ?>
												</div>
											</div>
										</div>
									</div>
									<div class="tab-pane" id="messages_5" role="tabpanel">
										<div class="culture_tab">
											<div class="row">
												<div class="col-xs-12 col-md-12">
													<div class="top_head">
														<p>Here’s how your team earns points</p>
													</div>
													<div class="full help_center_collaps points" id="accordion4">
														<div class="card">
															<div class="card-header">
																<a class="card-link" data-toggle="collapse" href="#collapseOnebeat">
																<span class="headingS"><img src="img/batting-icon_1.png" alt=""></span>Batting
																</a>
															</div>
															<div id="collapseOnebeat" class="collapse show" data-parent="#accordion4">
																<div class="card-body">
																	<ul class="batting_list">
																		<li>
																			<a href="javascript:void(0)">
																			Run <span>+<?php echo !empty($t10data->battingRun) ? $t10data->battingRun : '0';?></span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																			Boundary Bonus <span>+<?php echo !empty($t10data->battingBoundary) ? $t10data->battingBoundary : '0';?></span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																			Six Bonus <span>+<?php echo !empty($t10data->battingSix) ? $t10data->battingSix : '0';?></span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																			30 run Bonus <span>+<?php echo !empty($t10data->t10Bonus30Runs) ? $t10data->t10Bonus30Runs : '0';?></span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																			50 run Bonus <span>+<?php echo !empty($t10data->t10Bonus50Runs) ? $t10data->t10Bonus50Runs : '0';?></span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																			Dismissal for a duck 
																			<small>Batsman, wicket-keeper & All-Rounder</small> 
																			<span class="orange"><?php echo !empty($t10data->battingDuck) ? $t10data->battingDuck : '0';?></span>
																			</a>
																		</li>
																	</ul>
																</div>
															</div>
														</div>
														<div class="card">
															<div class="card-header">
																<a class="collapsed card-link" data-toggle="collapse" href="#collapseTwobeat">
																<span class="headingS"><img src="img/batting-icon_2.png" alt=""></span>Bowling
																</a>
															</div>
															<div id="collapseTwobeat" class="collapse" data-parent="#accordion4">
																<div class="card-body">
																	<ul class="batting_list">
																		<li>
																			<a href="javascript:void(0)">
																				Wicket <small> Excluding Run Out </small> 
																				<span>+<?php echo !empty($t10data->bowlingWicket) ? $t10data->bowlingWicket : '0';?></span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																				2 wicket haul Bonus 
																				<span>+<?php echo !empty($t10data->t10bowling2Wicket) ? $t10data->t10bowling2Wicket : '0';?></span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																				3 wicket haul Bonus 
																				<span>+<?php echo !empty($t10data->t10bowling3Wicket) ? $t10data->t10bowling3Wicket : '0';?></span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																				Maiden over 
																				<span>+<?php echo !empty($t10data->bowlingMaiden) ? $t10data->bowlingMaiden : '0';?></span>
																			</a>
																		</li>
																	</ul>
																</div>
															</div>
														</div>
														<div class="card">
															<div class="card-header">
																<a class="collapsed card-link" data-toggle="collapse" href="#collapseThreebeat">
																<span class="headingS"><img src="img/batting-icon_3.png" alt=""></span>Fielding
																</a>
															</div>
															<div id="collapseThreebeat" class="collapse" data-parent="#accordion4">
																<div class="card-body">
																	<ul class="batting_list">
																		<li>
																			<a href="javascript:void(0)">
																			Catch <span>+<?php echo !empty($t10data->fieldingCatch) ? $t10data->fieldingCatch : '0';?></span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																			Stumping/Run-out  
																			<small>Direct</small>
																			<span>+<?php echo !empty($t10data->fieldingStumpRunOut) ? $t10data->fieldingStumpRunOut : '0';?></span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																			Run-out thrower
																			<span class="one">+<?php echo !empty($t10data->fieldingRunOutThrower) ? $t10data->fieldingRunOutThrower : '0';?></span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																			Run-out catcher
																			<span class="one">
																				+<?php echo !empty($t10data->fieldingRunOutCatcher) ? $t10data->fieldingRunOutCatcher : '0';?>
																			</span>
																			</a>
																		</li>
																	</ul>
																</div>
															</div>
														</div>
														<div class="card">
															<div class="card-header">
																<a class="collapsed card-link" data-toggle="collapse" href="#collapseFourbeat">
																<span class="headingS"><img src="img/batting-icon_4.png" alt=""></span>Others
																</a>
															</div>
															<div id="collapseFourbeat" class="collapse" data-parent="#accordion4">
																<div class="card-body">
																	<ul class="batting_list">
																		<li>
																			<a href="javascript:void(0)">
																			Captain <span><?php echo !empty($t10data->othersCaptain) ? $t10data->othersCaptain : '0';?>X</span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																			Vice-Captain <span><?php echo !empty($t10data->othersViceCaptain) ? $t10data->othersViceCaptain : '0';?>X</span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																			Starting <span>+<?php echo !empty($t10data->othersStarting11) ? $t10data->othersStarting11 : '0';?></span>
																			</a>
																		</li>
																	</ul>
																</div>
															</div>
														</div>
														<div class="card">
															<div class="card-header">
																<a class="collapsed card-link" data-toggle="collapse" href="#collapseFivebeat">
																<span class="headingS"><img src="img/batting-icon_5.png" alt=""></span>Economy Rate
																<span class="sub_head">Min 1 Over</span>
																</a>
															</div>
															<div id="collapseFivebeat" class="collapse" data-parent="#accordion4">
																<div class="card-body">
																	<ul class="batting_list">
																		<li>
																			<a href="javascript:void(0)">
																			Below 6 runs per over <span>+<?php echo !empty($t10data->t10EconomyLt6Runs) ? $t10data->t10EconomyLt6Runs : '0';?></span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																			Between 6 - 6.99 runs per over <span>+<?php echo !empty($t10data->t10EconomyGt6Runs) ? $t10data->t10EconomyGt6Runs : '0';?></span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																			Between 7 - 8 runs per over <span>+<?php echo !empty($t10data->t10EconomyBt7_8Runs) ? $t10data->t10EconomyBt7_8Runs : '0';?></span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																			Between 11 - 12 runs per over <span class="orange"><?php echo !empty($t10data->t10EconomyBt11_12Runs) ? $t10data->t10EconomyBt11_12Runs : '0';?></span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																			Between 12.01 - 13 runs per over <span class="orange"><?php echo !empty($t10data->t10EconomyBt12_13Runs) ? $t10data->t10EconomyBt12_13Runs : '0';?></span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																			Above 13 runs per over <span class="orange"><?php echo !empty($t10data->t10EconomyGt13Runs) ? $t10data->t10EconomyGt13Runs : '0';?></span>
																			</a>
																		</li>
																	</ul>
																</div>
															</div>
														</div>
														<div class="card">
															<div class="card-header">
																<a class="collapsed card-link" data-toggle="collapse" href="#collapseSixbeat">
																<span class="headingS"><img src="img/batting-icon_6.png" alt=""></span>Strike rate(Except Bowler)
																<span class="sub_head">Min 5 Balls</span>
																</a>
															</div>
															<div id="collapseSixbeat" class="collapse" data-parent="#accordion4">
																<div class="card-body">
																	<ul class="batting_list">
																		<li>
																			<a href="javascript:void(0)">
																				Between 90 - 99.99 runs per 100 balls <span class="orange"><?php echo !empty($t10data->t10StrikeGt90Runs) ? $t10data->t10StrikeGt90Runs : '0';?></span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																				Between 80 - 89.99 runs per 100 balls <span class="orange"><?php echo !empty($t10data->t10StrikeBt80_90Runs) ? $t10data->t10StrikeBt80_90Runs : '0';?></span>
																			</a>
																		</li>
																		<li>
																			<a href="javascript:void(0)">
																				Below 80 runs per 100 balls <span class="orange"><?php echo !empty($t10data->t10StrikeLt80Runs) ? $t10data->t10StrikeLt80Runs : '0';?></span>
																			</a>
																		</li>
																	</ul>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="col-md-12">
													<?php //echo $content->content; ?>
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
		</main>
		<?php echo $this->Html->script([
			'../assets/js/jquery.js',
			'../assets/js/popper.min.js',
			'admin/bootstrap.min.js',
		]); ?>
		<script type="text/javascript">
			$(document).ready(function(){
				$('.custom_menu .navbar-nav li').click(function(){
					$('.navbar-nav li').removeClass("active");
					$(this).addClass("active");
				});
			});
		</script>
	</body>
</html>