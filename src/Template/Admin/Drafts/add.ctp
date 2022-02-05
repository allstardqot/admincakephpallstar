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
					<?php echo $this->Form->create('Users', ['id'=>'scheduleForm']); ?>
						<div class="card-body pt-10 mt-10">
							<div class="row">
								<div class="col-xs-6 col-sm-6 col-md-6">
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
								<div class="col-xs-6 col-sm-6 col-md-6">
									<div class="form-group">
										<input type="text" name="name" id="name" class="form-control" placeholder="Draft Name">
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
										<?php echo ($this->Form->button(__('Save Contest'), array('class' => 'btn btn-primary actionBtn', 'div' => FALSE))); ?>
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

	$(document).on('click', '.actionBtn', function() {
		$(this).attr('disabled',true);
		$('#scheduleForm').submit();
	});

	$('#weekList').on('change',function(){
		week = $(this).val();
		//alert(week);
		if(week.length) {
			$.ajax({
				url	:	'<?php echo $this->Url->build(['controller'=>'drafts','action'=>'playerdata']); ?>',
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