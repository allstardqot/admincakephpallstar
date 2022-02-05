<div class="content-wrapper">
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Add Contest For a Draft</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/drafts/schedule">Schedule Contest</a></li>
						<li class="breadcrumb-item active">Add Contest</li>
					</ol>
				</div>
			</div>
		</div>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="row">
						<div class="col-sm-6">
							<div class="py-2 pl-3">
								<div class="search_filter_outer">
								</div>
							</div>
						</div>
						<div class="col-sm-6">
						</div>
					</div>
					<div class="card-body pt-0 mt-0">
						<div class="row">
						<div class="col-md-12">
							<?php echo $this->Form->create('Users', ['id'=>'scheduleForm']); ?>
								<input type="hidden" name="draft_id" value="<?=$id?>">
								<table id="displyData" class="table table-bordered responsive" >
									<tbody>
										<tr>
											<td colspan="5">
												<div class="chkAlign">
													<INPUT class="checkbox" type="checkbox" onchange="checkAll(this)" name="chk[]" /> Select All
												</div>
											</td>
										</tr>
										<?php 
										if(!empty($newRecords)) {
											// pr($newRecords);die;
											foreach ($newRecords as $k => $v)  {  ?>
												<tr class="cat_heading">
													<td class="border-0">
														<div class="cat_thumb_outer">
														<?php 
															$image = $this->Custom->getCategoryImage($k);
															if($image!=''){
																echo '<img src="'.$this->request->webroot.'uploads/category_image/'.$image.'" hight="80px" width="80px" alt="">';
															}else{
																 echo '<img src="'.$this->request->webroot.'img/no_image.png" hight="80px" width="80px" alt="">';
															}
														?>
														</div>
													</td>
													<td class="border-0">
														<h4><?=$this->Custom->getCategoryName($k) ?></h4>
													</td>
													
													<td colspan="3" class="text-right border-0">
														
													</td>
												</tr>
												<tr>
													<th class="text-center"></th>
													<th>Winning Points</th>
													<th>Contest Size</th>
													<th>Entry Points</th>
												</tr>
												<?php
												$i = 0;
												foreach($v as $key=>$val)
												{
													?>
													<tr>
														<td class="text-center">
														<?php 
															if (in_array($val->id, $arr))
															{  
																?>
																	<input id="selectGroup" class="selectGroup" checked onclick="return false;" type="checkbox" name="contest_id[]" value="<?=$val->id ?>">
																<?php
															}
															else
															{
															?>
																<input id="selectGroup" class="selectGroup checkbox" type="checkbox" name="contest_id[]" value="<?=$val->id ?>">
															<?php
															}	
															?>
														</td>
														<td>
															<span style="font-weight: bold;"> </span><?=$val->winning_amount ?>
														</td>
														<td>
															<span style="font-weight: bold;"> </span><?=$val->contest_size ?><br>
															
														</td>
														<td>
															<span style="font-weight: bold;"> </span><?=$val->entry_fee ?><br>
														</td>
													</tr>
												<?php 
														$i++;
													}
												} 
											} 
											else 
											{ ?>
												<tr>
													<td colspan="4" style="text-align: center;">No data found</td>
												</tr>
									<?php 	} ?>
									</tbody>
								</table>
								<div class="col-sm-5">
									<?php echo ($this->Form->button(__('Save Contest'), array('class' => 'btn btn-primary actionBtn', 'div' => FALSE))); ?>
								</div>
							</div>
						</div>
					</div>
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
							<?php echo($this->Html->link('<i class="fa fa-arrow-left"></i> Back', array('controller' => 'Drafts', 'action' => 'schedule'), array('escape' => false, 'class' => 'btn btn-primary'))); ?>
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
	$('#catList').on('change',function(){
		category_id = $(this).val();
		if(category_id.length)
		{
			id='<?php echo $id?>';
			$.ajax({
				url	:	'<?php echo $this->Url->build(['controller'=>'schedule','action'=>'categoryContest']); ?>',
				type:	'POST',
				data:	{id :id,category_id: category_id},
				success:function(data) {
					$('#displyData').html(data);
				}
			});
		}
	});
	$('.viewAll').on('click',function(){
		var category_id	=	$(this).attr('data-attr');
		var thisClass	=	$(this);
		if(category_id.length) {
			id='<?php echo $id?>';
			$.ajax({
				url	:	'<?php echo $this->Url->build(['controller'=>'schedule','action'=>'categoryContest']); ?>',
				type:	'POST',
				data:	{id :id,category_id: category_id},
				success:function(data) {
					// var divClass	=	thisClass.parent().parent().attr('class');
					// $('.'+divClass).html(data);
					$('#displyData').html(data);
				}
			});
		}
	});
</script>
<script>
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