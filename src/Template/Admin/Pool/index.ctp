<?php use Cake\Core\Configure; ?>
<style type="text/css">
	.exportBtn {float: right;}
</style>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>List Pool</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/">Home</a></li>
                        <!-- <li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/pool/">List Pool</a></li> -->
                        <li class="breadcrumb-item active">User Pools</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content admin_users">
        <div class="r o w">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
						<div class="row">
							<div class="col-md-8">
								<?php  echo $this->Form->create(null,['novalidate' => true, 'valueSources' => 'query', 'type' => 'get', 'class' => 'form-inline search_form']); ?>
								<div class="row ">
									<div class="form-group col-sm-6 col-md-3">
									<?php 
										//echo $this->Form->input('user_name',['class' => 'form-control', 'label' => false, 'placeholder' => __('Enter name')]);
									?>
									</div>
									<div class="form-group col-sm-6 col-md-3 ">
										<?php
										//	echo $this->Form->button(__('Search'),['type' => 'submit', 'class' => 'btn btn-default site_btn_color']);
											
										//	echo $this->Html->link('<i class="fa fa-undo"></i>'.__(' Reset'), array('controller' => 'users', 'action' => 'index'), array('class' => 'btn btn-default', 'escape' => false));
										?>
									</div>
								<?php echo $this->Form->end(); ?>
							</div>
						</div>
						<div class="col-md-4 text-right">
							<!-- <button class = 'btn btn-success site_btn_color' id="manageUser"><i class="fa fa-plus "></i></button> -->
						</div>
					</div>
                    </div>
                    <div class="card-header">
                      <h3 class="card-title" style="display: inline-block;">Pool Details</h3>
                      <?php
						//echo $this->Html->link(__(' Export'), array('controller' => 'users', 'action' => 'export'), array('class' => 'btn btn-primary exportBtn', 'escape' => false));
                      ?>
                    </div>
                    <div class="card-body">
						<div class="table-responsive">
							<table  class="table  table-bordered responsive" >
								<thead>
									<tr>
										<!-- <th><input type="checkbox" class="selectall" /></th> -->
										<th>#</th>
										<th><?php echo $this->Paginator->sort('User_Pool.user_id', __('User')) ?></th>
										<th class="userEmailField"><?php echo $this->Paginator->sort('User_Pool.pool_name', __('Pool Name')) ?></th>
										<th><?php echo $this->Paginator->sort('User_Pool.pool_type', __('Pool Type')) ?></th>
										<th><?php echo $this->Paginator->sort('User_Pool.entry_fees', __('Entry Fees')) ?></th>
										<th><?php echo $this->Paginator->sort('User_Pool.max_participants', __('Max Participate')) ?></th>
										
										
										<!-- <th><?php echo __('Active Status');?></th> -->
										<!-- <th><?php echo __('Action');?></th> -->
									</tr>
								</thead>
								<tbody>
									<?php
									// if (!empty($query->count() > 0)) {
										$i	=	0;
										$start = 1;
										$pl=Configure::read('ADMIN_PAGE_LIMIT');
										foreach ($result as $key => $value) {
											$i++; ?>
											<tr>
												<!-- <td><?php echo $this->Form->checkbox('published', ['hiddenField' => false,'value' => $value->id, 'class'=>'individual']); ?></td> -->
												<td>
												<?php 
													if(isset($this->request->params['?']['page'])){
														$pc = $this->request->params['?']['page']-1;
														
													} else {
														$pc = 0;
													}
													echo ($pl*$pc)+$start;
												?>
												</td>
												<td><?php echo h($value->user->user_name); ?></td>
												<td class="userEmailField"><?php echo h($value->pool_name); ?></td>
												<td><?php echo h(($value->pool_type == 0 ? 'Public': 'Private')); ?></td>
												<td><?php echo h($value->entry_fees); ?></td>
                                                <td><?php echo h($value->max_participants);?></td>

												
											</tr>
										<?php $start++;
										}
									// } else { ?>
										<!-- <tr>
											<td colspan="9" style="text-align: center;"><?php echo __('No Record Found') ?></td>
										</tr> -->
									<!-- <?php //} ?> -->
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
<div class="modal fade" id="myModal" role="dialog"></div>
<div class="modal fade" id="user_edit" role="dialog">

</div>


<?= $this->Html->css(['admin/bootstrap-datepicker.min']) ?>
<?= $this->Html->script(['admin/bootstrap-datepicker.min']) ?>
<?= $this->Html->script(['admin/jquery-3.2.1']) ?>
</div>





