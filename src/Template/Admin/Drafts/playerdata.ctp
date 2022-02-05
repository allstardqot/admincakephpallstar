<tbody>
	<tr>
		<td colspan="5">
			<div class="chkAlign">
				<!-- <input type="checkbox" class="checkbox"  onchange="checkAll(this)" name="chk[]" /> Select All -->
			</div>
		</td>
	</tr>
	<tr>
		<td colspan="5">
			<?php 
				echo '<div class="accordion" id="accordionExample">';
					$index = 1;
					$tindex = 1;
					$pndex = 1;
					//<input type="checkbox" class="checkbox" name="gchk[]" value="'.$key.'" />
					foreach($formated_week_data AS $key => $value) {
							$team_data = $value;
							echo '
								<div class="card">
									<div class="card-header" id="heading'.$index.'">
										<h2 class="mb-0">
										<button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapse'.$index.'" aria-expanded="true" aria-controls="collapse'.$index.'">
										 <span style="color:green;font-weight:bold;">Positions:&nbsp; '.$key.'</span>
										</button>
										</h2>
									</div>
								
									<div id="collapse'.$index.'" class="collapse" aria-labelledby="heading'.$index.'" data-parent="#accordionExample">
										<div class="card-body">';
											

											if( !empty($team_data) ) {
												echo '<div class="accordion" id="accordionExample2">';
												//<input type="checkbox" class="checkbox" name="tchk[]"  value="'.$tkey.'"/>
												foreach ( $team_data AS $tkey => $tvalue ) {
													echo '<div class="card">
														<div class="card-header" id="t_heading'.$tindex.'">
															<h2 class="mb-0">
															<button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#t_collapse'.$tindex.'" aria-expanded="true" aria-controls="t_collapse'.$tindex.'">
															 <span style="color:orange;font-weight:bold;">Team : &nbsp; '.$tkey.'</span>
															</button>
															</h2>
														</div>
													
														<div id="t_collapse'.$tindex.'" class="collapse" aria-labelledby="t_heading'.$tindex.'" data-parent="#accordionExample2">
															<div class="card-body">';
																

																if( !empty($tvalue) ) {
																	echo '<table class="table table-bordered responsive">';
																	echo '<tr>
																			<td><b>#</b></td>
																			<td><b>Player Name</b></td>
																			<td><b>Fantasy Points</b></td>
																		</tr>';
																	foreach ( $tvalue AS $pkey => $pvalue ) {
																		echo '<tr>
																			<td><input type="checkbox" class="checkbox" name="pchk['.$key.'][]" value="'.$pvalue['PlayerID'].'"/></td>
																			<td>'.$pvalue['Name'].'</td>
																			<td>'.$pvalue['FantasyPointsPPR'].'</td>
																		</tr>';
																	}
																	echo '</table>';
																}


															echo '</div>
														</div>
													</div>';
													$tindex++;
												}
												echo '</div>';
											}
											


										echo '</div>
									</div>
								</div>';
						
						$index++;
					}
				echo '</div>';
			?>
		</td>
	</tr>
</tbody>

<script>
	$('.accordion input[type="checkbox"]').click(function(e) {
		e.stopPropagation();
	});
</script>