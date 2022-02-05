<?php
	use Cake\Core\Configure; 
	use Cake\Routing\Router;
	$path	=	Router::url('/', true);
?>
<div class="content-wrapper">
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Add Payment Offer</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?php echo SITE_URL; ?>admin/">Home</a></li>
						<li class="breadcrumb-item active">Add Payment Offer</li>
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
						<?php echo $this->Form->create($offers, ['type' => 'file', 'novalidate' => true,'id'=>'offerForm']); ?>
						<div class="row">
							<div class="col-md-12">
								<div class="card-body">
									<div class="form-group">
										<?php
											echo $this->Form->input('coupon_code',['escape'=>false,'class' => 'form-control','label'=>'Coupon Code <span class="required">*</span>', 'placeholder' => __('Coupon Code'),'maxlength'=>'15']);
										?>
										<small>Provide 15-Digit coupon code.</small>
									</div>
									<div class="form-group">
										<?php
											echo $this->Form->input('min_amount',['escape'=>false,'class' => 'form-control','label'=>'Minimum Amount (INR) <span class="required">*</span>', 'placeholder' => __('Minimum Amount',true),'oninput'=>"this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');",'maxlength'=>'10']);
										?>
										<small>Minimum amount to be available for the offer.</small>
									</div>
									<div class="form-group">
										<?php
											echo $this->Form->input('max_cashback_amount',['escape'=>false,'class' => 'form-control','label'=>'Maximum Cashback Amount (INR) <span class="required">*</span>', 'placeholder' => __('Maximum Cashback Amount'),'oninput'=>"this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');",'maxlength'=>'10']);
										?>
									</div>
									<div class="form-group">
										<?php
											echo $this->Form->input('max_cashback_percent',['escape'=>false,'class' => 'form-control','label'=>'Cashback Percentage (%) <span class="required">*</span>', 'placeholder' => __('Cashback Percentage'),'oninput'=>"this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');",'maxlength'=>'5']);
										?>
									</div>
									<div class="form-group">
										<?php
											echo $this->Form->input('usage_limit',['escape'=>false,'class' => 'form-control usage_limit','label'=>'Usage Limit <span class="required">*</span>', 'placeholder' => __('Usage Limit'),'type'=>'text','value'=>'Unlimited','readonly','oninput'=>"this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');",'maxlength'=>'10']);
										?>
										<div class="position-relative form-check">
											<input name="check" id="Check_use_limit" type="checkbox" class="form-check-input" checked>
											<label for="Check_use_limit" class="form-check-label">Unlimited</label>
										</div>
									</div>
									<div class="form-group">
										<?php
											echo $this->Form->input('per_user_limit',['escape'=>false,'class' => 'form-control','label'=>'Limit per User <span class="required">*</span>', 'placeholder' => __('Limit per User'),'type'=>'text','oninput'=>"this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');",'maxlength'=>'10']);
										?>
									</div>
									<div class="form-group">
										<?php
											echo $this->Form->input('expiry_date',['escape'=>false,'class' => 'form-control expiry_date','label'=>'Expiry Date <span class="required">*</span>', 'placeholder' => __('Expiry Date'),'type'=>'text','readonly']);
										?>
									</div>
									<div class="form-group">
										<?php
											$option	=	Configure::read('status');
											echo $this->Form->input('status',['type'=>'select','options'=>$option,'escape'=>false,'class' => 'form-control','label'=>'Status <span class="required">*</span>', 'empty' => __('Select Status')]);
										?>
									</div>
								</div>
							</div>
						</div>
						<div class="card-footer">
							<button type="submit" class="btn btn-primary submit">Submit</button>
						</div>
						<?php echo $this->Form->end(); ?>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<?php
	// echo $this->Html->css(['jquery.datetimepicker.min.css']);
	// echo $this->Html->script(['jquery.datetimepicker.full.min.js']);
	echo $this->Html->css(['bootstrap-datetimepicker.min']);
	echo $this->Html->script(['bootstrap-datetimepicker.min'])
?>
<script>
	$(document).on('click', '.submit', function() {
		$(this).attr('disabled',true);
		$('#offerForm').submit();
	});
	
	$(document).ready(function() {
		$('#Check_use_limit').change(function() {
			var isChecked	=	$(this).prop('checked');
			if(isChecked == true) {
				$('.usage_limit').val('Unlimited');
				$('.usage_limit').attr('readonly',true);
			} else {
				$('.usage_limit').val('');
				$('.usage_limit').attr('readonly',false);
			}
		});
		var date = new Date()
		toDay = date.setDate(date.getDate()-1);
		
		$('#expiry-date').datetimepicker({
			format: 'yyyy-mm-dd hh:ii:ss',
			autoclose: true,
			startDate: '<?php echo date("Y-m-d H:i:s"); ?>',
		});
	});
</script>