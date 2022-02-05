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
					<h1>Edit Email Template</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/email-templates">Email Template List</a></li>
						<li class="breadcrumb-item active">Edit Email Template</li>
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
						<?php echo $this->Form->create($email, ['type' => 'file', 'novalidate' => true,'id'=>'emailForm']); ?>
						<div class="row">
							<div class="col-md-12">
								<div class="card-body">
									<div class="form-group">
										<?php
											echo $this->Form->input('email_name',['escape'=>false,'class' => 'form-control','label'=>'Email Subject <span class="required">*</span>', 'placeholder' => __('Email Subject')]);
										?>
									</div>
									<div class="form-group">
										<?php
											echo $this->Form->input('subject',['escape'=>false,'class' => 'form-control','label'=>'Key <span class="required">*</span>', 'placeholder' => __('Key'),'readonly']);
										?>
									</div>
									<div class="form-group">
										<label>Template <span class="required">*</span></label>
										<?php
											echo $this->Form->textarea('template',['class'=>'form-control','label'=>false]);
											if(!empty($errorTemplate))
											{
											?>
												<div class="error-message"><?php echo $errorTemplate?></div>
											<?php
											}
											?>
									</div>
								</div>
							</div>
						</div>
						<div class="card-footer">
							<button type="submit" class="btn btn-primary submit">Submit</button>
							<?php echo($this->Html->link('<i class="fa fa-arrow-left"></i> Back', array('action' => 'index'), array('escape' => false, 'class' => 'btn btn-primary '))); ?>
						</div>
						<?php echo $this->Form->end(); ?>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script src="https://cdn.ckeditor.com/4.11.1/standard-all/ckeditor.js"></script>
<script>
	$(document).on('click', '.submit', function() {
		$(this).attr('disabled',true);
		$('#emailForm').submit();
	});
	
	CKEDITOR.replace('template', {
		allowedContent: true,
		height: 320
	});
</script>