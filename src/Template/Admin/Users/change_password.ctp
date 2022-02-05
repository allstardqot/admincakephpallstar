<?php
	use Cake\Core\Configure; 
	use Cake\Routing\Router;
?>
<div class="content-wrapper">
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Change Password</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?php echo SITE_URL; ?>admin/">Home</a></li>
						<li class="breadcrumb-item active">Change Password</li>
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
						<?php echo $this->Form->create($user, ['novalidate' => true,'method'=>'post','id'=>'changePassForm']); ?>
						<div class="card-body">
							<div class="row">
								<div class="col-xs-6 col-sm-6 col-md-6">
									<div class="form-group">
										<?php
											echo $this->Form->input('old_password', ['escape'=>false,'type' => 'password', 'class' => 'form-control', 'placeholder' => __('Old Password'), 'label' => __('Old Password <span class="required">*</span>')]);
										?>
									</div>
								</div>
								<div class="col-xs-6 col-sm-6 col-md-6">
									<div class="form-group">
										<?php
											$password	=	isset($this->request->data['password']) ? $this->request->data['password'] : '';
											echo $this->Form->input('password', ['escape'=>false,'type' => 'password', 'class' => 'form-control', 'placeholder' => __('New Password'), 'label' => __('New Password <span class="required">*</span>'),'value'=>$password]);
										?>
									</div>
								</div>
								<div class="col-xs-6 col-sm-6 col-md-6">
									<div class="form-group">
										<?php
											echo $this->Form->input('confirm_password', ['escape'=>false,'type' => 'password', 'class' => 'form-control', 'placeholder' => __('Confirm Password'), 'label' => __('Confirm Password <span class="required">*</span>')]);
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
<script>
	$(document).on('click', '.submit', function() {
		$(this).attr('disabled',true);
		$('#changePassForm').submit();
	});
</script>