<?php
	use Cake\Core\Configure;
?>
<div class="content-wrapper">
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Settings</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/">Home</a></li>
						<li class="breadcrumb-item active">Settings</li>
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
						<?php echo $this->Form->create($setting, ['type' => 'file', 'novalidate' => true,'id'=>'settingForm']); ?>
						<div class="row">
							<div class="col-md-12">
								<div class="card-body">
									<div class="form-group">
										<?php
											echo $this->Form->input('admin_email',['escape'=>false,'class' => 'form-control','label'=>'Admin Email <span class="required">*</span>', 'placeholder' => __('Admin Email')]);
										?>
									</div>

									<div class="form-group">
										<?php
											echo $this->Form->input('phone',['escape'=>false,'class' => 'form-control','label'=>'Admin Phone <span class="required">*</span>', 'placeholder' => __('Admin Phone')]);
										?>
									</div>

									<div class="form-group">
										<?php
											echo $this->Form->input('address',['escape'=>false,'class' => 'form-control','label'=>'Admin Address <span class="required">*</span>', 'placeholder' => __('Admin Address')]);
										?>
									</div>

									<div class="form-group">
										<?php
											echo $this->Form->input('facbook_url',['type'=>'text','escape'=>false,'class' => 'form-control','label'=>'Admin Facebook <span class="required">*</span>', 'placeholder' => __('Admin Facebook')]);
										?>
									</div>

									<div class="form-group">
										<?php
											echo $this->Form->input('youtube_link',['type'=>'text','escape'=>false,'class' => 'form-control','label'=>'Admin Youtube <span class="required">*</span>', 'placeholder' => __('Admin Youtube')]);
										?>
									</div>

									<div class="form-group">
										<?php
											echo $this->Form->input('instagram_url',['type'=>'text','escape'=>false,'class' => 'form-control','label'=>'Admin Address <span class="required">*</span>', 'placeholder' => __('Admin Address')]);
										?>
									</div>

									<div class="form-group">
										<?php
											echo $this->Form->input('linkdin_url',['type'=>'text','escape'=>false,'class' => 'form-control','label'=>'Lindkin<span class="required">*</span>', 'placeholder' => __('Admin Linkdin')]);
										?>
									</div>

									

									


									<!-- <div class="form-group">
										<?php
											echo $this->Form->input('admin_percentage',['escape'=>false,'class' => 'form-control','label'=>'Usable Bonus Percentage', 'placeholder' => __('Usable Bonus Percentage'),'oninput'=>"this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"]);
										?>
									</div>
									<div class="form-group">
										<?php
											echo $this->Form->input('referral_bouns_amount',['maxlength'=>'8','escape'=>false,'class' => 'form-control','label'=>'Signup Bouns Amount (INR)', 'placeholder' => __('Signup Bouns Amount'),'oninput'=>"this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"]);
										?>
									</div>
									<div class="form-group">
										<?php
											echo $this->Form->input('referral_bouns_amount_referral',['maxlength'=>'8','escape'=>false,'class' => 'form-control','label'=>'Referral Bouns Amount (INR)', 'placeholder' => __('Referral Bouns Amount'),'oninput'=>"this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"]);
										?>
									</div>
									<div class="form-group">
										<?php
											echo $this->Form->input('min_deposit_for_referral',['maxlength'=>'8','escape'=>false,'class' => 'form-control','label'=>'Minimum Deposit For Referral (INR)', 'placeholder' => __('Minimum Deposit For Referral (INR)'),'oninput'=>"this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"]);
										?>
									</div>
									<<div class="form-group">
										<?php
											/* echo $this->Form->input('contest_commission',['maxlength'=>'5','type'=>'text','escape'=>false,'class' => 'form-control','label'=>'Contest Commission (In percentage)', 'placeholder' => __('Contest Commission'),'oninput'=>"this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"]); */
										?>
									</div> 
									<div class="form-group">
										<?php
											echo $this->Form->input('min_withdraw_amount',['maxlength'=>'7','type'=>'text','escape'=>false,'class' => 'form-control','label'=>'Minimum Withdraw Amount (INR)', 'placeholder' => __('Minimum Withdraw Amount'),'oninput'=>"this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"]);
										?>
									</div>
									<div class="form-group">
										<?php
											echo $this->Form->input('tds',['maxlength'=>'5','type'=>'text','escape'=>false,'class' => 'form-control','label'=>'TDS (On minimum 10000 INR)', 'placeholder' => __('TDS'),'oninput'=>"this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"]);
										?>
									</div>

									<div class="form-group">
										<?php
											echo $this->Form->input('youtube_link',['type'=>'textarea','escape'=>false,'class' => 'form-control','label'=>'Youtube video link', 'placeholder' => __('Youtube video link')]);
										?>
									</div>

									<div class="form-group">
										<?php
											echo $this->Form->input('downloads_count',['maxlength'=>'5','type'=>'text','escape'=>false,'class' => 'form-control','label'=>'Downloads count', 'placeholder' => __('Downloads count'),'oninput'=>"this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"]);
										?>
									</div>

									<div class="form-group">
										<?php
											echo $this->Form->input('bonus_full',['maxlength'=>'5','type'=>'text','escape'=>false,'class' => 'form-control','label'=>'Bonus 100 %', 'placeholder' => __('Enter Points'),'oninput'=>"this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"]);
										?>
									</div>

									<div class="form-group">
										<?php
											echo $this->Form->input('bonus_second_last',['maxlength'=>'5','type'=>'text','escape'=>false,'class' => 'form-control','label'=>'Bonus 90 %', 'placeholder' => __('Enter Points'),'oninput'=>"this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"]);
										?>
									</div>

									<div class="form-group">
										<?php
											echo $this->Form->input('bonus_last',['maxlength'=>'5','type'=>'text','escape'=>false,'class' => 'form-control','label'=>'Bonus 80 %', 'placeholder' => __('Enter Points'),'oninput'=>"this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"]);
										?>
									</div> -->

									<?php /*
									<div class="form-group">
										<div class="row">
											<div class="col-md-8">
												<?php
													echo $this->Form->label('Admin Background');
													echo $this->Form->input('admin_background',['type'=>'file','class' => '','label'=>false]);
												?>
											</div>
											<div class="col-md-3">
												<?php
													$filePath	=	WWW_ROOT .'uploads/settings/';
													if(!empty($setting->site_logo) && file_exists($filePath.$setting->admin_background)) {
														echo $this->Html->image('../uploads/settings/'.$setting->admin_background,['alt'=>'site logo','style'=>'width:100px']);
													}
												?>
											</div>
										</div>
									</div>
									
									<?php /* <div class="form-group">
										<?php
											echo $this->Form->input('meta_title',['escape'=>false,'class' => 'form-control','label'=>'Meta Title', 'placeholder' => __('Meta Title')]);
										?>
									</div>
									
									<div class="form-group">
										<?php
											echo $this->Form->input('site_meta_description',['escape'=>false,'class' => 'form-control','label'=>'Meta Description', 'placeholder' => __('Meta Description')]);
										?>
									</div>
									
									<h4 class="text-info">
										<?php echo $this->Html->image('paytm.png',['alt'=>'paytm','style'=>'width:55px;']);?>
										Configuration
									</h4> */ ?>
									<?php /* <div class="form-group">
										<?php
											echo $this->Form->label('Paytm Environment (Live / Test)');
											echo $this->Form->input('paytm_environment',['type'=>'checkbox','class' => '','label'=>false]);
										?>
									</div>
									<div class="form-group">
										<?php
											echo $this->Form->input('paytm_merchant_key',['escape'=>false,'class' => 'form-control','label'=>'Paytm Merchant Key', 'placeholder' => __('Paytm Merchant Key')]);
										?>
									</div>
									<div class="form-group">
										<?php
											echo $this->Form->input('paytm_merchant_mid',['escape'=>false,'class' => 'form-control','label'=>'Paytm Merchant MID', 'placeholder' => __('Paytm Merchant MID')]);
										?>
									</div> */ ?>
									<!-- <div class="form-group">
										<?php
											//echo $this->Form->input('paytm_merchant_website',['escape'=>false,'class' => 'form-control','label'=>'Paytm Merchant Website', 'placeholder' => __('Paytm Merchant Website')]);
										?>
									</div>
									<h4 class="text-info">
										<?php //echo $this->Html->image('facbook.png',['alt'=>'facebook','style'=>'width:75px;']);?>
										Facebook Configuration 
									</h4>
									<div class="form-group">
										<?php
											//echo $this->Form->input('facebook_app_id',['type'=>'text','class' => 'form-control','label'=>'App Id', 'placeholder' => __('App Id')]);
										?>
									</div>
									<div class="form-group">
										<?php
											//echo $this->Form->input('facebook_app_secret',['escape'=>false,'class' => 'form-control','label'=>'App Secret', 'placeholder' => __('App Secret')]);
										?>
									</div>
									<h4 class="text-info">
										<?php //echo $this->Html->image('google.jpg',['alt'=>'google','style'=>'width:45px;']);?>
										Google Configuration
									</h4>
									<div class="form-group">
										<?php
											//echo $this->Form->input('google_app_id',['type'=>'text','class' => 'form-control','label'=>'App Id', 'placeholder' => __('App Id')]);
										?>
									</div>
									<div class="form-group">
										<?php
											//echo $this->Form->input('google_app_secret',['escape'=>false,'class' => 'form-control','label'=>'App Secret', 'placeholder' => __('App Secret')]);
										?>
									</div>
									<div class="form-group">
										<?php
											//echo $this->Form->input('coutn_down_date',['escape'=>false,'class' => 'form-control','label'=>'Count Down Date', 'placeholder' => __('Count Down Date')]);
										?>
									</div>
									<div class="form-group">
										<?php
											//echo $this->Form->input('referral_code', ['type' => 'text', 'class' => 'form-control', 'placeholder' => __('Referral Code'), 'label' => __('Referral Code')]);
										?>
									</div> -->
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
<script>
	$(document).on('click', '.submit', function() {
		$(this).attr('disabled',true);
		$('#settingForm').submit();
	});
</script>
<style>
	.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}
/*
input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}*/

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>