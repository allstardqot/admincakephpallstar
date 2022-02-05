<?php
	use Cake\Core\Configure; 
	use Cake\Routing\Router;
	// $path	=	Router::url('/', true);
?>
<div class="content-wrapper">
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>View Payment Offer</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/payment-offers">Payment Offers</a></li>
						<li class="breadcrumb-item active">View Payment Offer</li>
					</ol>
				</div>
			</div>
		</div>
	</section>
	<section class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="card card-primary">
						<table class="table table-bordered">
							<tr>
								<td width="30%">
									<label>Coupon Code</label>
								</td>
								<td width="70%">
									<?php echo !empty($result->coupon_code) ? $result->coupon_code : ''; ?>
								</td>
							</tr>
							<tr>
								<td width="30%">
									<label>Minimum Amount (INR) </label>
								</td>
								<td width="70%">
									<?php echo !empty($result->min_amount) ? number_format($result->min_amount,2) : '0.00'; ?>
								</td>
							</tr>
							<tr>
								<td width="30%">
									<label>Maximum Cashback Amount (INR) </label>
								</td>
								<td width="70%">
									<?php echo !empty($result->max_cashback_amount) ? number_format($result->max_cashback_amount,2) : '0.00'; ?>
								</td>
							</tr>
							<tr>
								<td width="30%">
									<label>Cashback Percentage</label>
								</td>
								<td width="70%">
									<?php echo !empty($result->max_cashback_percent) ? number_format($result->max_cashback_percent,2) : '0.00'; ?>
								</td>
							</tr>
							<tr>
								<td width="30%">
									<label>Usage Limit</label>
								</td>
								<td width="70%">
									<?php echo !empty($result->usage_limit == 0) ? 'Unlimited' : number_format($result->usage_limit,2); ?>
								</td>
							</tr>
							<tr>
								<td width="30%">
									<label>Limit per User</label>
								</td>
								<td width="70%">
									<?php echo !empty($result->per_user_limit) ? $result->per_user_limit : '0'; ?>
								</td>
							</tr>
							<tr>
								<td width="30%">
									<label>Expiry Date</label>
								</td>
								<td width="70%">
									<?php echo !empty($result->expiry_date) ? date('Y-m-d h:i:s',strtotime($result->expiry_date)) : ''; ?>
								</td>
							</tr>
							<tr>
								<td width="30%">
									<label>Status</label>
								</td>
								<td width="70%">
									<?php
										echo ($result->status == 1) ? '<span class="label-success label label-default">Active</span>' : '<span class="label-default label label-danger">Inactive</span>';
									?>
								</td>
							</tr>
						</table>
						<h2>
							User Detail
						</h2>
						<table class="table table-bordered">
							<tr>
								<th>User Name</th>
								<th>Applied On</th>
							</tr>
							<?php if(!empty($result->user_coupon_codes)) {
								foreach($result->user_coupon_codes as $userCoupons) {
									if($userCoupons->status == ACTIVE) { ?>
										<tr>
											<td>
												<?php echo !empty($userCoupons->user) ? substr($userCoupons->user->full_name,0,50) : ''; ?>
											</td>
											<td>
												<?php echo !empty($userCoupons->applied_on) ? date('Y-m-d',strtotime($userCoupons->applied_on)) : ''; ?>
											</td>
										</tr>
									<?php 
									}
								}
							} else { ?>
								<tr>
									<td class="text-center" colspan="2">No Record Found</td>
								</tr>
							<?php
							} ?>
						</table>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>