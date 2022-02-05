<?php use Cake\Core\Configure; ?>
<div class="content-wrapper">
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Add Player Group</h1>
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
											?>
													<option value="<?php echo $key?>"> Season <?php echo $value; ?> </option>
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
											?>
													<option value="<?php echo $key?>"> Type <?php echo $showtest; ?> </option>
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
												?>
														<option value="<?php echo $key?>"> Week <?php echo $value; ?> </option>
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
								
								<div class="form-group col-sm-6 col-md-3">
									<?php           
										echo $this->Form->input('created',["type" => "text",'readonly' => 'readonly','id'=>'startfildate','class' => 'form-control datepicker-input start_date', 'label' => false, 'placeholder' => __('Enter Registered From'),'required'=>false]);
									?>
								</div>
								<div class="form-group col-sm-6 col-md-3">
									<?php 
										echo $this->Form->input('modified',["type" => "text",'readonly' => 'readonly','id'=>'endfildate','class' => 'form-control datepicker-input end_date', 'label' => false, 'placeholder' => __('To'),'required'=>false]);
									?>
								</div>
								<div class="col-xs-6 col-sm-3 col-md-3">
									<div class="form-group">
										<input type="text" name="name" id="name" class="form-control" placeholder="Draft Name">
										<div id="draft_er" style="color:red;"></div>
									</div>
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
<?= $this->Html->css(['admin/bootstrap-datepicker.min']) ?>
<?= $this->Html->script(['admin/bootstrap-datepicker.min']) ?>
<script>

	$("#name").focusout(function(){
		var draftName = $( this ).val();
		if(draftName.length) {
			$.ajax({
				url	:	'<?php echo $this->Url->build(['controller'=>'drafts','action'=>'draftnamecheck']); ?>',
				type:	'POST',
				data:	{draftName: draftName},
				success:function(data) {
					$('#draft_er').html(data);
				}
			});
		}
	});

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
	/*$(document).on('click', '.actionBtnNew', function() {
		//$(this).attr('disabled',true);
		$('#scheduleFormNew').submit();
	});*/

	$('#weekList').on('change',function(){
		
		if(!$("#seasontypeList").val()){
			alert("Please select season type.");
			$(this).val('');
		}
		// week = $(this).val();
		// stdate = $("#startfildate").val();
		// enddate = $("#endfildate").val();

		// if(week.length) {
		// 	$.ajax({
		// 		url	:	'<?php echo $this->Url->build(['controller'=>'drafts','action'=>'playerdatanew']); ?>',
		// 		type:	'POST',
		// 		data:	{week: week,stdate:stdate,enddate:enddate},
		// 		success:function(data) {
		// 			//$('#displyData').html(data);
		// 		}
		// 	});
		// }
	});

	$('#seasontypeList').on('change',function(){
		if(!$("#seasonList").val()){
			alert("Please select season.");
			$(this).val('');
		}else{
			seasontype = $(this).val();
			season=$("#seasonList").val()

			if(seasontype.length) {
				$.ajax({
					url	:	'<?php echo $this->Url->build(['controller'=>'drafts','action'=>'editweek']); ?>',
					type:	'POST',
					data:	{seasontype: seasontype,season:season},
					success:function(data) {
						$('#weekList').html(data);

					}
				});
			}
		}
	});

	$('#startfildate').on('change',function(){
		stdate = $(this).val();
		enddate = $("#endfildate").val();
		seasonList = $("#seasonList").val();
		seasontypeList = $("#seasontypeList").val();
		seasonList = $("#seasonList").val();
		week = $("#weekList").val();
		if(week.length) {
			$.ajax({
				url	:	'<?php echo $this->Url->build(['controller'=>'drafts','action'=>'playerdatanew']); ?>',
				type:	'POST',
				data:	{week:week,stdate: stdate,enddate:enddate,seasonList:seasonList,seasontypeList:seasontypeList},
				success:function(data) {
					$('#displyData').html(data);
				}
			});
		}
	});

	$('#endfildate').on('change',function(){
		enddate = $(this).val();
		stdate = $("#startfildate").val();
		seasonList = $("#seasonList").val();
		seasontypeList = $("#seasontypeList").val();
		week = $("#weekList").val();
		if(week.length) {
			$.ajax({
				url	:	'<?php echo $this->Url->build(['controller'=>'drafts','action'=>'playerdatanew']); ?>',
				type:	'POST',
				data:	{week:week,stdate: stdate,enddate:enddate,seasonList:seasonList,seasontypeList:seasontypeList},
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
	
	$( function() {
		$(".start_date").datepicker({
			format	:	"yyyy-mm-dd",
			autoclose: true,
			//endDate: '+0d',
		}).on('changeDate', function (selected) {
			$('.end_date').val('');
			var minDate	=	new Date(selected.date.valueOf());
			$('.end_date').datepicker('setStartDate', minDate);
		});
		
		$(".end_date").datepicker( {
			format	:	"yyyy-mm-dd",
			//endDate: '+0d',
			autoclose: true,
		}).on('changeDate', function (selected) {
			// $('.start_date').val('');
			var maxDate = new Date(selected.date.valueOf());
			$('.start_date').datepicker('setEndDate', maxDate);
		});
	});

</script>