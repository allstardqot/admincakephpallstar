<?php use Cake\Core\Configure; ?>

<div class="content-wrapper">
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Sub Admins List</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/">Home</a></li>
						<li class="breadcrumb-item active">Sub Admins List</li>
					</ol>
				</div>
			</div>
		</div>
	</section>
	<section class="content sub-admins_outer">
		<div class="r o w">
			<div class="col-md-12">
				<div class="card">
					<div class="card-body">
						<?php echo $this->Form->create(null,['novalidate' => true, 'valueSources' => 'query', 'type' => 'get', 'class' => 'form-inline search_form']); ?>
						<div class="row">
							<div class="form-group col-sm-6 col-md-3">
								<?php
									echo $this->Form->input('name',['class' => 'form-control', 'label' => false, 'placeholder' => __('Enter Name')]);
								?>
							</div>
							<div class="form-group col-sm-6 col-md-3">
								<?php
									echo $this->Form->input('email',['class' => 'form-control', 'label' => false, 'placeholder' => __('Email Address')]);
								?>
							</div>
							<div class="form-group col-sm-6 col-md-3">
								<?php 
									echo $this->Form->input('phone',['class' => 'form-control', 'label' => false, 'placeholder' => __('Phone Number')]);
								?>
							</div>
							<div class="form-group col-sm-6 col-md-3">
								<?php           
									echo $this->Form->input('start_date',["type" => "text",'readonly' => 'readonly','class' => 'form-control datepicker-input start_date', 'label' => false, 'placeholder' => __('Enter Registered From'),'required'=>false]);
								?>
							</div>
						   
						<!-- </div>
						<div class="row"> -->
							<div class="form-group col-sm-6 col-md-3">
								<?php 
									echo $this->Form->input('end_date',["type" => "text",'readonly' => 'readonly','class' => 'form-control datepicker-input end_date', 'label' => false, 'placeholder' => __('To'),'required'=>false]);
								?>
							</div>
							<div class="form-group col-sm-6 col-md-3 ">
								<?php
									echo $this->Form->button(__('Search'),['type' => 'submit', 'class' => 'btn btn-default site_btn_color']);
									
									echo $this->Html->link('<i class="fa fa-undo"></i>'.__(' Reset'), array('controller' => 'subAdmins', 'action' => 'index'), array('class' => 'btn btn-default', 'escape' => false)); ?>
							</div>
						</div>
						<?php  echo $this->Form->end(); ?>
					</div>
					<div class="card-header">
					  <h3 class="card-title">Sub admin Details</h3>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table  class="table  table-bordered responsive" >
								<thead>
									<tr>
										<!-- <th><input type="checkbox" class="selectall" /></th> -->
										<th>#</th>
										<th><?php echo $this->Paginator->sort('Users.first_name', __('Name')) ?></th>
										<th><?php echo $this->Paginator->sort('Users.email', __('Email')) ?></th>
										<th><?php echo $this->Paginator->sort('Users.phone', __('Phone Number')) ?></th>
										<th><?php echo $this->Paginator->sort('Users.created', __('Registration Date')) ?></th>
										<th><?php echo __('Status');?></th>
										<th><?php echo __('Action');?></th>
									</tr>
								</thead>
								<tbody>
									<?php
									if(!empty($users->count()>0)) {
										$i	=	0;
										$start = 1;
										$pl=Configure::read('ADMIN_PAGE_LIMIT');
										foreach($users as $key => $value) {
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
												<td><?php echo h($value->first_name.' '.$value->last_name); ?></td>
												<td><?php echo h($value->email); ?></td>
												<td><?php echo h($value->phone); ?></td>
												<td><?php echo h(date("Y-m-d", strtotime($value->created))); ?></td>
												<td class="center">
													<?php
														if($value->status == 4) { ?> 
														<span class="label-block label">Block</span>    
														<?php
														} else {
															echo $this->Html->link(($value->status == 1) ? '<span class="btn btn-success">Active</span>' : '<span class="btn btn-danger">Inactive</span>', ['prefix' => 'admin', 'controller' => 'subAdmins', 'action' => 'status', $value->id], ['escape' => false]);
														}
													?>
												</td>
												<td class="center">												
													<?php echo $this->Html->link('Edit', ['controller'=>'subAdmins','action'=>'edit',$value->id],['escape'=>false,'class'=>'btn btn-success',]); ?>
													<?php echo $this->Html->link('Delete User', ['controller'=>'subAdmins','action'=>'delete',$value->id],['escape'=>false,'class'=>'btn btn-danger btn-xs','title'=>'Delete Subadmin','onclick'=>"return confirm('Are you sure you want to delete sub admin user?')"]); ?>
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
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script type="text/javascript">
	$(function () {		
		$(".start_date").datepicker({
			format	:	"yyyy-mm-dd",
			autoclose: true,
			endDate: '+0d',
		}).on('changeDate', function (selected) {
			$('.end_date').val('');
			var minDate	=	new Date(selected.date.valueOf());
			$('.end_date').datepicker('setStartDate', minDate);
		});
		
		$(".end_date").datepicker( {
			format	:	"yyyy-mm-dd",
			endDate: '+0d',
			autoclose: true,
		}).on('changeDate', function (selected) {
			// $('.start_date').val('');
			var maxDate = new Date(selected.date.valueOf());
			$('.start_date').datepicker('setEndDate', maxDate);
		});
	});
</script>




