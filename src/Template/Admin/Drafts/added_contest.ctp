<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Details For Added Contest</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/drafts">Schedule Contest</a></li>
                        <li class="breadcrumb-item active">Added Contest</li>
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
						<div class="card-body pt-10 mt-10">
							<div class="col-xs-6 col-sm-6 col-md-6">
								<div class="form-group">
									<select name="catList" id="catList" class="form-control">
										<?php 
										if(!empty($newRecords))
										{
											?>
												<option value="">Select Category</option>
											<?php
											foreach ($newRecords as $kC => $vC) 
											{
												$catName = $this->Custom->getCategoryName($kC);
												if(!empty($catName))
												{
												?>
													<option value="<?php echo $kC?>"><?php echo $this->Custom->getCategoryName($kC)?></option>
												<?php
												}
											} ?>
											<option value="resetFilter">Reset Category Filter </option>
											<?php
										}
										else
										{
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
									
								</div>
							</div>	
						</div>		
					</div>
					
                    <div class="card-body pt-0 mt-0">
                        <div class="row"><div class="col-md-12">
                            <div class="card-body pt-0 mt-0">
                                <table id="displyData" class="table table-bordered responsive" >
									<tbody>
										<?php 
										if(!empty($newRecords))
										{
											//echo "<pre>";print_r($newRecords);echo "</pre>";die;
											foreach ($newRecords as $k => $v) 
											{ 
											?>
												<tr class="cat_heading">
													<td class="border-0">
													<div class="cat_thumb_outer">
													<?php 
														$image = $this->Custom->getCategoryImage($k);
														if($image!=''){
															echo '<img src="'.$this->request->webroot.'uploads/category_image/'.$image.'" hight="80px" width="80px" alt="">';
														}else{
															 echo '<img src="'.$this->request->webroot.'img/front_home/no_image.png" hight="80px" width="80px" alt="">';
														}
													?>
													</div>
													</td>
													<td class="border-0">
													<h4><?=$this->Custom->getCategoryName($k) ?></h4>
													</td>
													
													<td colspan="3" class="text-right border-0">
													<?php
													if(count($v) > 5)
													{
													?>
														<a data-attr="<?php echo $k?>" href="javascript:void(0);" class="btn btn-primary btn-sm viewAll"> View All</a>
													<?php
													}
													?>
													</td>
													
												</tr>
												<tr>
													<!--<th>Category Name</th>-->
													<th>Winning Amount</th>
													<th>Contest Size</th>
													<th>Entry Fee</th>
													<th>Contest Status</th>
													<th>View</th>
												</tr>
												<?php
												$i = 0;
												foreach($v as $key=>$val)
												{
													$data	=	$this->Custom->getCategoryDetails($val->category_id);
													$joined	=	$this->Custom->getContestLeftMatch($val->id,$draft_id); 
													
													$left 	= 	($val->contest_size-$joined);
													if($val->draft_contests['is_cancelled']==0){
														$mtchStatus = 'Active';
													}else{
														$mtchStatus = 'Cancelled';
													}
													?>
													<tr>
														
														<!--<td><span style="font-weight: bold;"> </span><?=$this->Custom->getCategoryName($val->category_id) ?>
														</td>-->
														<td>
															<span style="font-weight: bold;"> </span><?=$val->winning_amount ?>
														</td>
														<td>
															<span style="font-weight: bold;"> </span><?php echo $joined ?>/<?php echo $val->contest_size?><br>
															
														</td>
														<td>
															<span style="font-weight: bold;"> </span><?=$val->entry_fee ?><br>
														</td>
														<td>
															<?=$mtchStatus;?>
														</td>
														<td width="5%">
															<?php echo $this->Html->link('View',['action'=>'contestDetails',$val->id,$id],['class'=>'btn btn-primary btn-sm viewBTN']); ?>
														</td>
													</tr>
													<?php 
													$i++;
													if($i == 5)
													{
														break;
													}
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
                            </div>
                        </div></div>
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
							<?php echo($this->Html->link('<i class="fa fa-arrow-left"></i> Back', array('action' => 'index'), array('escape' => false, 'class' => 'btn btn-primary'))); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section> -->
</div>
<script>
	$('#catList').on('change',function(){
		category_id = $(this).val();
		if(category_id=='resetFilter'){
			location.reload();
		}else{
			if(category_id.length)
			{
				id='<?php echo $id?>';
				$.ajax({
					url	:	'<?php echo $this->Url->build(['controller'=>'drafts','action'=>'addedCategoryContest']); ?>',
					type:	'POST',
					data:	{id :id,category_id: category_id},
					success:function(data) {
						$('#displyData').html(data);
					}
				});
			}
		}
	});
	$('.viewAll').on('click',function(){
		category_id = $(this).attr('data-attr');
		// console.log(category_id.length);
		if(category_id.length) {
			id='<?php echo $id?>';
			$.ajax({
				url	:	'<?php echo $this->Url->build(['controller'=>'drafts','action'=>'addedCategoryContest']); ?>',
				type:	'POST',
				data:	{id :id,category_id: category_id},
				success:function(data) {
					console.log(data);
					$('#displyData').html(data);
				}
			});
		}
	});
</script>