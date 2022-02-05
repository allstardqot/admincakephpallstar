<div class="content-wrapper">
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Edit Player Group</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/drafts">Drafts</a></li>
						<li class="breadcrumb-item active">Add Player Group</li>
					</ol>
				</div>
			</div>
		</div>
	</section>

	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<?php echo $this->Form->create('Users', ['id'=>'scheduleFormNew']); ?>
						<div class="card-body pt-10 mt-10">
							<div class="row">
								<div class="col-xs-3 col-sm-3 col-md-3">
									<div class="form-group">
										<select name="season" id="seasonList" class="form-control">
											<?php 
												if(!empty($Season)) { 
													
											?>
													<option value="">Select Season</option>
											<?php
													foreach ($Season AS $key => $value) {
														$select="";
														if($seriesData->season==$key){
															$select="selected";
														}
											?>			
													<option value="<?php echo $key?>" <?= $select; ?>> Season <?php echo $value; ?> </option>
											<?php
													}
												} else { 
											?>
													<option value="">No Category Found</option>
											<?php
												} 
											?>
										</select>
									</div>
								</div>
								<div class="col-xs-3 col-sm-3 col-md-3">
									<div class="form-group">
										<select name="seasontype" id="seasontypeList" class="form-control">
											<?php 
												if(!empty($typeArray)) { 
													
											?>
													<option value="">Select Season Type</option>
											<?php
													foreach ($typeArray AS $key => $value) {
														$showtest=($key==1)?"Reg":'';
														if(empty($showtest)){ $showtest=($key==2)?"Pre":''; }
														if(empty($showtest)){ $showtest=($key==3)?"Post":''; };

														$select="";
														if($seriesData->seasontype==$key){
															$select="selected";
														}
											?>
													<option value="<?php echo $key?>" <?= $select; ?>> Type <?php echo $showtest; ?> </option>
											<?php
													}
												} else { 
											?>
													<option value="">No Category Found</option>
											<?php
												} 
											?>
										</select>
									</div>
								</div>

								<div class="col-xs-3 col-sm-3 col-md-3">
									<div class="form-group">
										<select name="week" id="weekList" class="form-control">
											<?php 
												if(!empty($weeks)) { 
													
											?>
													<option value="">Select Week</option>
											<?php
													foreach ($weeks AS $key => $value) {
														$select="";
														if($seriesData->week==$key){
															$select="selected";
														}
											?>
													<option value="<?php echo $key?>" <?= $select; ?>> Week <?php echo $value; ?> </option>
											<?php
													}
												} else { 
											?>
													<option value="">No Category Found</option>
											<?php
												} 
											?>
										</select>
									</div>
								</div>
								
								<div class="col-xs-6 col-sm-3 col-md-3">
									<div class="form-group">
										<input type="text" name="name" id="name" class="form-control" placeholder="Draft Name" value="<?= $seriesData->name; ?>">
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-12">
			
									<table id="displyData" class="table table-bordered responsive" >
										<tbody>
											<tr>
												<td colspan="4" style="text-align: center;">No data found</td>
											</tr>
										</tbody>
									</table>
									<div class="col-sm-5">
										<?php echo ($this->Form->button(__('Save Contest'), array('class' => 'btn btn-primary actionBtnNew', 'div' => FALSE))); ?>
									</div>
									
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>

	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-body">
						<div class="col-sm-12" style="margin-bottom: 40px;">
							<?php echo($this->Html->link('<i class="fa fa-arrow-left"></i> Back', array('controller' => 'drafts', 'action' => 'index'), array('escape' => false, 'class' => 'btn btn-primary'))); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

</div>

<script>

	$( "#scheduleFormNew" ).submit(function(event) {
		var arr=[];
		$( "select[name='team_member[]']" ).each(function( index ) {
			$(this).attr("name","team_member["+index+"][]");
		});
		
		$( "input[name='display_name[]']" ).each(function( index ) {
			var value=$( this ).val();
			if(arr.indexOf(value)==-1){
				arr.push(value);
			}else{
				alert("Display name should be unique, Please change duplicate name.");
				event.stopPropagation();
				event.preventDefault();
				return false;
			}
		});
	});
	
	$(document).ready(function(){
		var draft_data=<?php echo $seriesData->draft_data; ?>;
		var week = $("#weekList").val();		
		var seasonList = $("#seasonList").val();
		var seasontypeList = $("#seasontypeList").val();
		
		$.ajax({
				url	:	'<?php echo $this->Url->build(['controller'=>'drafts','action'=>'playerdatanew']); ?>',
				type:	'POST',
				data:	{draft_data: draft_data,week:week,seasonList:seasonList,seasontypeList:seasontypeList},
				success:function(data) {
					$('#displyData').html(data);
				}
			});
	});

	$('#weekList').on('change',function(){
		week = $(this).val();
		if(week.length) {
			$.ajax({
				url	:	'<?php echo $this->Url->build(['controller'=>'drafts','action'=>'playerdatanew']); ?>',
				type:	'POST',
				data:	{week: week},
				success:function(data) {
					$('#displyData').html(data);
				}
			});
		}
	});

	function checkAll(ele) {
     	var checkboxes = document.getElementsByClassName('checkbox');
     	if (ele.checked) {
         	for (var i = 0; i < checkboxes.length; i++) {
             	if (checkboxes[i].type == 'checkbox') {
                 	checkboxes[i].checked = true;
             	}
         	}
     	} else {
         	for (var i = 0; i < checkboxes.length; i++) {
             	console.log(i)
             	if (checkboxes[i].type == 'checkbox') {
                 	checkboxes[i].checked = false;
             	}
         	}
     	}
  	}

</script>