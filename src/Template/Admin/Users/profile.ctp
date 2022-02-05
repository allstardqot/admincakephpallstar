<?php
	echo $this->Html->css(['admin/bootstrap-datepicker.min.css']);
	echo $this->Html->script(['admin/bootstrap-datepicker.min.js']);
	
	use Cake\Core\Configure; 
	use Cake\Routing\Router;
	$path = Router::url('/', true); 
?>
<div class="content-wrapper">
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Update Profile</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/">Home</a></li>
						<li class="breadcrumb-item active">Update Profile</li>
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
						<?php echo $this->Form->create($user, ['type' => 'file', 'novalidate' => true,'method'=>'post']); ?>
						<div class="card-body">
							<div class="row">
								<div class="col-xs-6 col-sm-6 col-md-6">
									<div class="form-group">
										<?php
											echo $this->Form->input('first_name', ['maxlength'=>'40','escape'=>false,'class' => 'form-control','label'=>'First Name <span class="required">*</span>', 'placeholder' => __('First Name')]);
										?>
									</div>
								</div>
								<div class="col-xs-6 col-sm-6 col-md-6">
									<div class="form-group">
										<?php
											echo $this->Form->input('last_name', ['maxlength'=>'40','escape'=>false,'class' => 'form-control','label'=>'Last Name <span class="required">*</span>','placeholder' => __('Last Name ')]);
										?>
									</div>
								</div>
								<div class="col-xs-6 col-sm-6 col-md-6">
									<div class="form-group">
										<?php
											echo $this->Form->input('team_name', ['escape'=>false,'class' => 'form-control','label'=>'Team Name <span class="required">*</span>','placeholder' => __('Team Name')]);
										?>
									</div>
								</div>
								<div class="col-xs-6 col-sm-6 col-md-6">
									<div class="form-group">
										<?php
											echo $this->Form->input('email', ['escape'=>false,'class' => 'form-control', 'placeholder' => __('E-Mail Addeess'), 'label' => __('Email <span class="required">*</span>',true),'readonly']); 
										?>
									</div>
								</div>
								<div class="col-xs-6 col-sm-6 col-md-6">
									<div class="form-group">
										<?php
											echo $this->Form->input('phone', ['escape'=>false,'class' => 'form-control','label'=>__('Phone Number <span class="required">*</span>'), 'placeholder' => __('Phone Number')]);
										?>
									</div>
								</div>
								<div class="col-xs-6 col-sm-6 col-md-6">
									<div class="form-group">
										<?php
											echo $this->Form->input('date_of_bith', ['escape'=>false,'class' => 'form-control DOB','label'=>__('Date of Birth'), 'placeholder' => __('Date of Birth',true),'readonly']);
										?>
									</div>
								</div>
								<div class="col-xs-6 col-sm-6 col-md-6">
									<div class="form-group">
										<?php
											echo $this->Form->input('gender', ['label'=>false,'class' => 'form-control', 'options' => Configure::read('GENDER_LIST'),'empty'=>'Select Gender','label'=>__('Gender',true)]);
										?>
									</div>
								</div>
								<div class="col-xs-6 col-sm-6 col-md-6">
									<div class="form-group">
										<?php
											echo $this->Form->input('country', ['type' => 'text', 'class' => 'form-control', 'placeholder' => __('Country'), 'label' => __('Country')]);
										?>
									</div>
								</div>
								<div class="col-xs-6 col-sm-6 col-md-6">
									<div class="form-group">
										<?php
											echo $this->Form->input('state', ['type' => 'text', 'class' => 'form-control', 'placeholder' => __('State'), 'label' => __('State')]);
										?>
									</div>
								</div>
								<div class="col-xs-6 col-sm-6 col-md-6">
									<div class="form-group">
										<?php
											echo $this->Form->input('address', ['type' => 'text', 'class' => 'form-control', 'placeholder' => __('Address'), 'label' => __('Address')]);
										?>
									</div>
								</div>
								<div class="col-xs-6 col-sm-6 col-md-6">
									<div class="form-group">
										<?php
											echo $this->Form->input('city', ['type' => 'text', 'class' => 'form-control', 'placeholder' => __('City'), 'label' => __('City')]);
										?>
									</div>
								</div>
								<div class="col-xs-6 col-sm-6 col-md-6">
									<div class="form-group">
										<?php
											echo $this->Form->input('postal_code', ['type' => 'text', 'class' => 'form-control', 'placeholder' => __('Pin Code'), 'label' => __('Pin Code')]);
										?>
									</div>
								</div>
							</div>
						</div>
						<div class="card-footer">
							<button type="submit" class="btn btn-primary">Submit</button>
						</div>
						<?php echo $this->Form->end(); ?>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script>
	var start = new Date();
	start.setFullYear(start.getFullYear() - 70);

	var end = new Date();
	end.setFullYear(end.getFullYear() - 18);
	$(function() {
		$('.DOB').datepicker({
			format:	'yyyy-mm-dd',
			startDate:start,
            endDate: end,
            autoclose: true
		});
	});
</script>