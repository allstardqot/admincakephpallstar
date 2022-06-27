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
										<th><?php echo $this->Paginator->sort('Country.name', __('Name')) ?></th>
										<th><?php echo $this->Paginator->sort('Country.culture_code', __('Culture Code')) ?></th>
										<th><?php echo $this->Paginator->sort('Country.iso_code', __('ISO Code')) ?></th>
										 <th><?php echo $this->Paginator->sort('Country.currency_name', __('Currency Name ')) ?></th> 
										<th><?php echo __('Status');?></th>
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
											$i++; 
											$extra_params   =   json_decode($value->extra,true);
                                            // pr($extra_params );die;
                                            ?>
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
												<td><?php echo h($value->name); ?></td>
												<td>en-<?php echo h(isset($extra_params['iso2'])? $extra_params['iso2']:'' ); ?></td>
												<td><?php echo h(isset($extra_params['iso']) ? $extra_params['iso'] : ''); ?></td>
												
												<td>USD-$<?php  ?></td>
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
													<button class = 'btn btn-success editCountry'  value=<?= $value->id;?> >Edit</button>
													<?php echo $this->Html->link('Delete ', ['controller'=>'subAdmins','action'=>'delete',$value->id],['escape'=>false,'class'=>'btn btn-danger btn-xs','title'=>'Delete Subadmin','onclick'=>"return confirm('Are you sure you want to delete sub admin user?')"]); ?>
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





<div class="modal fade" id="country_edit" role="dialog">

</div>








