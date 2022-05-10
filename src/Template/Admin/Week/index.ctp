<?php use Cake\Core\Configure; 
	// echo SITE_URL;die;
?>

<div class="content-wrapper"> 
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1> Week List</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/">Home</a></li>
						<li class="breadcrumb-item active"> Admins List</li>
					</ol>
				</div>
			</div>
		</div>
	</section>
	<section class="content sub-admins_outer">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-body">

					<div class="row">
						
						<div class="col-md-12 text-right">
						<button class = 'btn btn-success site_btn_color' id="manageweek"><i class="fa fa-plus "></i></button>
						</div>
					</div>
						

						
						
					</div>
					
					<div class="card-body">
						<div class="table-responsive">
							<table  class="table  table-bordered responsive" >
								<thead>
									<tr>
										<!-- <th><input type="checkbox" class="selectall" /></th> -->
										<th>Game Week</th>
										<th><?php echo $this->Paginator->sort('Week.starting_at', __('Start Date')) ?></th>
										<th><?php echo $this->Paginator->sort('Week.ending_at', __('End Date')) ?></th>
										<!-- <th><?php echo __('Status');?></th> -->
										<th><?php echo __('Action');?></th>
									</tr>
								</thead>
								<tbody>
									<?php
									if(!empty($result->count()>0)) {
										$i	=	0;
										$start = 1;
										$pl=Configure::read('ADMIN_PAGE_LIMIT');
										foreach($result as $key => $value) {
											$i++; ?>
											<tr>
												
												<td>
												<?php 
												if(isset($this->request->params['?']['page'])){
                                                    $pc = $this->request->params['?']['page']-1;
                                                }else{
                                                    $pc = 0;
                                                }
                                                echo ($pl*$pc)+$start;
												?>
												</td>
												<td><?php echo h(date("M, d Y", strtotime($value->starting_at))); ?></td>
												<td><?php echo h(date("M, d Y", strtotime($value->ending_at))); ?></td>
												<!-- <td class="center">
													<?php
														if($value->status == 4) { ?> 
														<span class="label-block label">Block</span>    
														<?php
														} else {
															echo $this->Html->link(($value->status == 1) ? '<span class="btn btn-success">Active</span>' : '<span class="btn btn-danger">Inactive</span>', ['prefix' => 'admin', 'controller' => 'subAdmins', 'action' => 'status', $value->id], ['escape' => false]);
														}
													?>
												</td> -->
												<td class="center">												
													<?php 
													//echo $this->Html->link('Edit', ['controller'=>'subAdmins','action'=>'edit',$value->id],['escape'=>false,'id'=>'editButton','class'=>'btn btn-success',]); ?>
													<button class = 'btn btn-success editWeekButton' id='editWeek' value=<?= $value->id;?> >Edit</button>
													<?php echo $this->Html->link('Delete ', ['controller'=>'Week','action'=>'delete',$value->id],['escape'=>false,'class'=>'btn btn-danger btn-xs','title'=>'Delete Subadmin','onclick'=>"return confirm('Are you sure you want to delete sub admin user?')"]); ?>
												</td>
											</tr>
											<?php $start++;
										}
									} else { ?>
										<tr>
											<td colspan="9" class="text-center"><?php echo __('No Record Found') ?></td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
						<?php echo $this->element('Admin/pagination'); ?>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>


<!-- Add Modal -->
<div class="modal fade" id="WeekModel" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Manage Week</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<?php  
					echo $this->Form->create(null,['class' => 'form-inline search_form' , 'id'=> 'weekform']); 
					echo $this->Form->hidden('action',['value' => 'addWeek']); 
				?>
				
				<div class="card-body">
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-12">
                            <div class="form-group">
                                <?php
                                    echo $this->Form->input('starting_at', ['maxlength'=>'30','escape'=>false,'class' => 'form-control my_date_picker','label'=>'Start Date  <span class="required">*</span>', 'id'=>'teamid','placeholder' => __('Start Date '),'max'=>'20','required']);
                                ?>
                                <!-- <p id="teamNameCheck" style="color: red;">Plz Enter Team Name</p> -->
                            </div>
                        </div>

                        <div class="col-xs-6 col-sm-6 col-md-12">
                            <div class="form-group">
                                <?php
                                    echo $this->Form->input('ending_at', ['maxlength'=>'30','escape'=>false,'class' => 'form-control my_date_picker','label'=>'End Date <span class="required">*</span>', 'id'=>'displayname','placeholder' => __('End Date'),'max'=>'20','required']);
                                ?>
                                <!-- <p id="teamNameCheck" style="color: red;">Plz Enter Team Name</p> -->
                            </div>
                        </div>
                    </div>
		        </div>
                <div class="card-footer">
                    <button id="submitWeek" class="btn btn-primary ">Submit</button>
                    <?php echo($this->Html->link('<i class="fa fa-arrow-left"></i> Cancel', array('action' => 'index'), array('escape' => false, 'class' => 'btn btn-success'))); ?>
                </div>
				
				<?php echo $this->Form->end(); ?>

			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="weekedit" role="dialog">

</div>








