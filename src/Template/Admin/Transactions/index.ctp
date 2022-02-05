<?php ?>
<style type="text/css">
	.pagination {
		display: flex;
		padding-left: 144px;
	}
	div#example1_filter {
		margin-left: 280px;
	}
	#example1 thead th.last_td, #example1 tbody td.last_td {
		width: 150px !important;
		max-width: 150px !important;
		min-width: 150px !important;
	}
	select.form-control.form-control-sm {
		height: 35px !important;
	}
	.dataTables_empty {
		text-align: center;
	}
</style>
<?php use Cake\Core\Configure; ?>
<div class="content-wrapper">
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Transactions List</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?php echo SITE_URL; ?>admin/">Home</a></li>
						<li class="breadcrumb-item active">Transactions</li>
					</ol>
				</div>
			</div>
		</div>
	</section>
	<section class="content transactions-outer">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<?php
							echo $this->Html->link(__(' Export'), array('controller' => 'transactions', 'action' => 'export'), array('class' => 'btn btn-primary exportBtn', 'escape' => false));
						?>
                    </div>
                    <div class="card-body">
                    	<div id="example1_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
							<div class="row">
								<div class="col-md-9">
									<div class="row">
                    					<?php  echo $this->Form->create(null,['novalidate' => true, 'valueSources' => 'query', 'type' => 'get', 'class' => 'form-inline search_form']); ?>
                    						<div class="form-group col-sm-6 col-md-6 col-lg-4 col-xl-3">
												<?php
													echo $this->Form->input('phone',['class' => 'form-control', 'label' => false, 'placeholder' => __('User Mobile')]);
												?>
											</div>
											<div class="form-group col-sm-6 col-md-6 col-lg-4 col-xl-3">
												<?php
													echo $this->Form->input('email',['class' => 'form-control', 'label' => false, 'placeholder' => __('User Email')]);
												?>
											</div>
											<div class="form-group col-sm-6 col-md-6 col-lg-4 col-xl-3">
												<?php
												$transactionTypes= Configure::read('TRANSACTION_TYPE');
												echo $this->Form->select('added_type',$transactionTypes,['class' => 'form-control', 'label' => false, 'empty' => __('Select Transaction Type')]);
												?>
											</div>
											<div class="form-group col-sm-6 col-md-6 col-lg-4 col-xl-3">
												<?php
													echo $this->Form->button(__('Search'),['type' => 'submit', 'class' => 'btn btn-default site_btn_color']);
													
													echo $this->Html->link('<i class="fa fa-undo"></i>'.__(' Reset'), array('controller' => 'transactions', 'action' => 'index'), array('class' => 'btn btn-default', 'escape' => false));
												?>
											</div>
										<?php echo $this->Form->end(); ?>
									</div>
								</div>
								<div class="col-md-3">
									<form method="post" id="showRecords">
					                    <div class="dataTables_length" id="example1_length">
					                    	<label style="width: 100%;"><span style="float: left;padding:5px 0px;">Show</span> <select name="length" aria-controls="example1" class="form-control form-control-sm slctRow" style="float: left;width: 50%;margin: 0px 5px;">
					                    			<option value="10" <?php if(isset($limit) && ($limit=='10')){ echo 'selected'; } ?>>10</option>
					                    			<option value="25" <?php if(isset($limit) && ($limit=='25')){ echo 'selected'; } ?>>25</option>
					                    			<option value="50" <?php if(isset($limit) && ($limit=='50')){ echo 'selected'; } ?>>50</option>
					                    			<option value="100" <?php if(isset($limit) && ($limit=='100')){ echo 'selected'; } ?>>100</option>
					                    		</select> <span style="float: left;padding:5px 0px;">entries</span>
					                    	</label>
					                    </div>
				                	</form>
								</div>
							</div>
                    	</div>
	                	<div class="table-responsive">
							<table class="table  table-bordered responsive">
								<thead>
									<tr>
										<th>#</th>
										<th>Transaction Id</th>
										<th>Local Transaction Id</th>
										<th>User Mobile</th>
										<th>User Email</th>
										<th>Cr/Db</th>
										<th>Payment Mode</th>
										<th>Transaction Type</th>
										<th>Amount</th>
										<th>Date & Time</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$start = 1;
									$pl	=	$limit;
									if(!empty($result)) {
										foreach ($result as $key => $value) { ?>
											<tr>
												<td><?php 
												if(isset($this->request->params['?']['page'])){
                                                    $pc = $this->request->params['?']['page']-1;
                                                    
                                                }else{
                                                    $pc = 0;
                                                }
                                                echo ($pl*$pc)+$start;

												?></td>
												<td><?=$value->txn_id ? $value->txn_id : 'N/A';?></td>
												<td><?=$value->local_txn_id;?></td>
												<td><?=$value->user->phone; ?></td>
												<td><?php echo $this->Html->link($value->user->email,['controller'=>'Users','action'=>'detail',$value->user_id],['escape'=>false,'class'=>'']); ?></td>
												<td>
													<?php if($value->added_type == JOIN_CONTEST || $value->added_type == TRANSACTION_PENDING || $value->added_type == WITHDRAWAL || $value->added_type == ADMIN_DEDUCTED ){
														echo "Db";	
													} else if($value->added_type == TRANSACTION_CONFIRM){
														echo "Db";	
													}else{ 
														echo "Cr";
													} ?>
												</td>
												<?php if($value->gateway_name == 'WALLET'){
														$value->gateway_name = 'PAYTM';
												} ?>
												<td><?=$value->gateway_name ? $value->gateway_name : 'N/A';?></td>
												<td><?=Configure::read('TRANSACTION_TYPE.'.$value->added_type);?></td>
												<td><?=$value->txn_amount.' INR';?></td>
												<td><?=$value->txn_date?></td>
											</tr>
										<?php
										$start++;
										}
									} else { ?>
										<tr>
											<td colspan="6" class="text-center">No Record Found. </td>
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
<script src="<?= SITE_URL; ?>webroot/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?= SITE_URL; ?>webroot/plugins/datatables/dataTables.bootstrap4.js"></script>
<script>
	$(document).on('change', '.slctRow', function() {
		$('#showRecords').submit();
	});
</script>