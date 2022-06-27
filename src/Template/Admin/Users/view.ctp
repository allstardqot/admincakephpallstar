<?php use Cake\Core\Configure; ?>
<style type="text/css">
	.exportBtn {float: right;}
</style>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>List User Pools</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/users/">List Users</a></li>
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
										<th>Action</th>
										
										
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
										foreach ($query as $key => $value) {
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
												<td><span id="" title = 'View Team' class="showTeam" onClick="showTeam(<?php echo $value->id?>)"><i class="fa fa-eye"></i></span></td>

												
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
						<?php //cho $this->element('Admin/pagination'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<div class="modal fade" id="myModal" role="dialog"></div>
<div class="modal fade" id="user_edit" role="dialog">

</div>

<style>
	.player-data .table td, .table th {
    
    border-bottom: 1px solid #dee2e6;
	border-top: 0px;
}
.player-data  .user-detail-card {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    flex-grow: 1;
}

.player-data  .user-detail-card .contents {
    padding-left: 15px;
}
.player-data .user-detail-card small {
    font-size: 16px;
}
.player-data .user-detail-card h5 {
    font-size: 1.20rem;
    font-weight: 500;
    /* margin: 0; */
	color: #ff5000;
    text-transform: capitalize;
}

</style>

<!-- Modal Popup -->
<div id="MyPopup" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					&times;</button>
					

			</div>
			<div class="modal-body" id="modalBody">
				
			</div>
			<div class="modal-footer">
				<input type="button" id="btnClosePopup" value="Close" class="btn btn-danger close" data-dismiss="modal" />
			</div>
		</div>
	</div>
</div>




<?= $this->Html->css(['admin/bootstrap-datepicker.min']) ?>
<?= $this->Html->script(['admin/bootstrap-datepicker.min']) ?>
<?= $this->Html->script(['admin/jquery-3.2.1']) ?>
</div>





