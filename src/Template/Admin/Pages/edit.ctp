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
					<h1>Edit Content</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/contents">Content List</a></li>
						<li class="breadcrumb-item active">Edit Content</li>
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
						<?php echo $this->Form->create($page, ['type' => 'file', 'novalidate' => true]); ?>
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
											echo $this->Form->input('content',['type'=>'textarea','class'=>'form-control','label'=>false,'required'=>true]);
										?>
									</div>
								</div>
							</div>
						</div>
						<div class="card-footer">
							<button type="submit" class="btn btn-primary">Submit</button>
							<?php echo($this->Html->link('<i class="fa fa-arrow-left"></i> Back', array('action' => 'index','?'=>$this->request->session()->read('sorting_query')), array('escape' => false, 'class' => 'btn btn-primary '))); ?>
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
	CKEDITOR.replace('content', {
		allowedContent: true,
		height: 320
	});
</script>