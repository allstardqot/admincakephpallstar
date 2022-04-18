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
						<?php echo $this->Form->create($user, ['type' => 'file', 'novalidate' => true,'method'=>'post']); 
							// pr($user);die;
						?>

						<div class="card-body">
							<div class=" text-center">
								<img src="<?php echo SITE_URL ?>/dist/img/noimg.png" class="img-fluid rounded ">
							</div>
							<!-- <div class="row"> -->
								<div class="form-row justify-content-center ">
									<div class="form-group col-md-3">
										<?php
											echo $this->Form->input('first_name', ['maxlength'=>'40','escape'=>false,'class' => 'form-control','label'=>'First Name <span class="required">*</span>', 'placeholder' => __('First Name')]);
										?>
									</div>
								</div>
								<div class="form-row justify-content-center ">
									<div class="form-group col-md-3">
										<?php
											echo $this->Form->input('last_name', ['maxlength'=>'40','escape'=>false,'class' => 'form-control','label'=>'Last Name <span class="required">*</span>','placeholder' => __('Last Name ')]);
										?>
									</div>
								</div>
								<div class="form-row justify-content-center ">
									<div class="form-group col-md-3">
										<?php
											echo $this->Form->input('email', ['escape'=>false,'class' => 'form-control', 'placeholder' => __('E-Mail Addeess'), 'label' => __('Email <span class="required">*</span>',true),'readonly']); 
										?>
									</div>
								</div>
								<div class="form-row justify-content-center  ">
									<div class="form-group col-md-3">
										<?php
											echo $this->Form->input('phone', ['escape'=>false,'class' => 'form-control','label'=>__('Phone Number <span class="required">*</span>'), 'placeholder' => __('Phone Number')]);
										?>
									</div>
								</div>
								
								
							<!-- </div> -->
						</div>
						<div class="text-center">
							<div class="card-footer ">
								<button type="submit" class="btn btn-primary">Submit</button>
							</div>
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