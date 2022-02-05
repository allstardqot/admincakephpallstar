<?php use Cake\Core\Configure; ?>
<div class="content-wrapper">
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Payment Offers List</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?php echo SITE_URL; ?>admin/">Home</a></li>
						<li class="breadcrumb-item active">Payment Offers List</li>
					</ol>
				</div>
			</div>
		</div>
	</section>
	<section class="content payment-offers-outer">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-body">
						<div class="table-responsive">
							<table id="example1" class="table table-bordered table-striped">
								<thead>
									<tr>
										<th>#</th>
										<th>Coupon Code</th> 
										<th>Status</th> 
										<th>Expired Status</th> 
										<th class="">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$i	=	1;
									$start = 1;
									$pl=Configure::read('ADMIN_PAGE_LIMIT');
									if(!empty($result)) {
										foreach ($result as $key => $value) { ?>
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
												<td><?php echo !empty($value->coupon_code) ? $value->coupon_code : ''; ?></td>
												<td>
													<?php
														echo $this->Html->link(($value->status == 1) ? '<span class="label-success label label-default">Active</span>' : '<span class="label-default label label-danger">Inactive</span>', ['prefix' => 'admin', 'controller' => 'paymentOffers', 'action' => 'status', $value->id], ['escape' => false]);
													?>
												</td>
												<td>
													<?php
														$expireyDate	=	strtotime($value->expiry_date);
														// $expireyDate	=	date('Y-m-d H:i:s',strtotime($value->expiry_date));
														$currentDate	=	strtotime(date('Y-m-d H:i:s'));
														if($expireyDate < $currentDate) {
															$exClass	=	'danger';
															$exStatus	=	'Expired';
														} else {
															$exClass	=	'success';
															$exStatus	=	'Not Expired';
														}
													?>
													<span class="label label-<?php echo $exClass; ?>"><?php echo $exStatus; ?></span>
												</td>
												<td class="">
													<?php echo $this->Html->link('Edit', ['controller'=>'paymentOffers','action'=>'edit',$value->id],['escape'=>false,'class'=>'btn btn-success',]); ?>
													<?php echo $this->Html->link('View', ['controller'=>'paymentOffers','action'=>'view',$value->id],['escape'=>false,'class'=>'btn btn-info']); ?>
													<?php echo $this->Html->link('Delete', ['controller'=>'paymentOffers','action'=>'delete',$value->id],['escape'=>false,'class'=>'btn btn-danger','onclick'=>"return confirm('Are you sure you want to delete offer?')"]); ?>
												</td>
											</tr>
										<?php $start++;
										$i++;
										}
									} else { ?>
										<tr>
											<td colspan="5" class="text-center">No Record Found. </td>
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
