<?php use Cake\Core\Configure; 
	// echo SITE_URL;die;
?>

<div class="content-wrapper"> 
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1> Admins List</h1>
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
						<div class="col-md-8">
							<?php echo $this->Form->create(null,['novalidate' => true, 'valueSources' => 'query', 'type' => 'get', 'class' => 'form-inline search_form']); ?>
							<div class="row ">
								<div class="form-group col-sm-6 col-md-3">
									<?php
										echo $this->Form->input('user_name',['class' => 'form-control', 'label' => false, 'placeholder' => __('Enter Name')]);
									?>
								</div>
								<div class="form-group col-sm-6 col-md-3 ">
									<?php
										echo $this->Form->button(__('Search'),['type' => 'submit', 'class' => 'btn btn-default site_btn_color']);
										
										echo $this->Html->link('<i class="fa fa-undo"></i>'.__(' Reset'), array('controller' => 'subAdmins', 'action' => 'index'), array('class' => 'btn btn-default', 'escape' => false)); ?>
								</div>
								<?php  echo $this->Form->end(); ?>
							</div>
						</div>
						<div class="col-md-4 text-right">
						<button class = 'btn btn-success site_btn_color' id="manageAdmin"><i class="fa fa-plus "></i></button>
						</div>
					</div>
						

						
						
					</div>
					<div class="card-header">
					  <h3 class="card-title"> Admin Details</h3>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table  class="table  table-bordered responsive" >
								<thead>
									<tr>
										<!-- <th><input type="checkbox" class="selectall" /></th> -->
										<th>#</th>
										<th><?php echo $this->Paginator->sort('Users.first_name', __('First Name')) ?></th>
										<th><?php echo $this->Paginator->sort('Users.last_name', __('Last Name')) ?></th>
										<th><?php echo $this->Paginator->sort('Users.email', __('Email')) ?></th>
										<!-- <th><?php echo $this->Paginator->sort('Users.password', __('Password ')) ?></th> -->
										<th><?php echo $this->Paginator->sort('Users.last_login', __('Last Login')) ?></th>
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
												<td><?php echo h($value->first_name); ?></td>
												<td><?php echo h($value->last_name); ?></td>
												<td><?php echo h($value->email); ?></td>
												<!-- <td><?php echo h($value->password); ?></td> -->
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
													<?php 
													//echo $this->Html->link('Edit', ['controller'=>'subAdmins','action'=>'edit',$value->id],['escape'=>false,'id'=>'editButton','class'=>'btn btn-success',]); ?>
													<button class = 'btn btn-success editButton' id='editButton' value=<?= $value->id;?> >Edit</button>
													<?php echo $this->Html->link('Delete ', ['controller'=>'subAdmins','action'=>'delete',$value->id],['escape'=>false,'class'=>'btn btn-danger btn-xs','title'=>'Delete Subadmin','onclick'=>"return confirm('Are you sure you want to delete admin ?')"]); ?>
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
<div class="modal fade" id="myModal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Manage Admin</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<?php  
					echo $this->Form->create(null,['class' => 'form-inline search_form' , 'id'=> 'admin_form']); 
					echo $this->Form->hidden('action',['value' => 'addsubadmin']); 
				?>
				
				<table class="table table-bordered responsive">
					
					<tr>
						<th>First Name</th>
						<td>
							
							<?php
								echo $this->Form->input('first_name',['class' => 'form-control ', 'id'=>'first_name', 'label' => false, 'placeholder' => __('Enter First Name')]);
							?>
							<p id="firstNameCheck" style="color: red;">Plz Enter First Name</p>
							
							
						</td>
					</tr>
					
					<tr>
						<th>Last Name </th>
						<td>
							<?php
								echo $this->Form->input('last_name',['class' => 'form-control ','id'=>'last_name', 'label' => false, 'placeholder' => __('Enter Last Name')]);
								
							?>
							<p id="lastNameCheck" style="color: red;">Plz Enter Last Name</p>
						</td>
					</tr>
					
					
					<tr>
						<th>Email</th>
						<td>
							<?php
								
								echo $this->Form->input('email',["type"=>"email",'class' => 'form-control ','id'=>'email','label' => false, 'placeholder' => __('Enter Admin Email')]);
							?>
							<p id="emailCheck" style="color: red;">Plz Enter Email!</p>
						</td>
					</tr>

					<tr>
						<th> Password</th>
						<td>
							<?php
								
								echo $this->Form->input('pass',["type"=>"password",'class' => 'form-control ','id'=>'password','label' => false, 'placeholder' => __('Enter Password')]);
							?>
							<p id="passCheck" style="color: red;">Plz Enter Password </p>
						</td>

					</tr>
					<tr>
						<th>Confirm Password</th>
						<td>
							<?php
								
								echo $this->Form->input('confirm_password',["type"=>"password",'class' => 'form-control ','id'=>'confirm_password','label' => false, 'placeholder' => __('Enter Confirm Password')]);
							?>
							<p id="conpasscheck" style="color: red;">Plz Enter Confirm Password </p>
						</td>
					</tr>

					<tr>
						<th>Manage Permission</th>
						<td>
							<?php
								$options =Configure::read('global_config.MODULE_ACCESS');
								$module	=	explode(',',$loggedInUser['module_access']);
								
								echo $this->Form->select('module[]',$options,['label' => false,'style'=>'width:100%;font-family: inherit;','id'=>'subadmin_roles','multiple']);
								echo $this->Form->hidden('module_access',['class'=>'module_accesss']);
							?>
						</td>
					</tr>

					
				</table>
				<?php	
					echo $this->Form->button(__('Confirm'),['type' => 'button','id'=>'submitAdmin', 'class' => 'btn btn-success btn-sm']);
				?>
				<?php echo $this->Form->end(); ?>

			</div>
			
		</div>
	</div>
</div>


<div class="modal fade" id="myModal_edit" role="dialog">

</div>








