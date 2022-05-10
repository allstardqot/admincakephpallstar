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
					<h1>Add FAQ </h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/">Home</a></li>
						<li class="breadcrumb-item active">Add FAQ </li>
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
						<div class="card-header">
							<h3 class="card-title">Fill Form</h3>
						</div>
						<?php echo $this->Form->create($content, ['type' => 'file', 'novalidate' => true,'id'=>'emailForm']); ?>
						<div class="row">
							<div class="col-md-12">
								<div class="card-body">
									<div class="form-group">
										<?php
											echo $this->Form->input('title',['escape'=>false,'class' => 'form-control','label'=>'Title <span class="required">*</span>', 'placeholder' => __('Title')]);
										?>
									</div>
									
									<div class="form-group">
										<label>Content <span class="required">*</span></label>
										<?php
											echo $this->Form->input('description',['type'=>'textarea','class'=>'form-control','label'=>false,'required'=>true]);
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
<script src="https://cdn.ckeditor.com/4.11.1/standard-all/ckeditor.js"></script>
<script>
	$(document).on('click', '.submit', function() {
		$(this).attr('disabled',true);
		$('#emailForm').submit();
	});
	
	CKEDITOR.replace('content', {
		allowedContent: true,
		height: 320
	});
</script>