<?php
	use Cake\Core\Configure;	
	?>
<div class="content-wrapper">
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Edit Category</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/">Dashboard</a></li>
						<li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/category">Category</a></li>
						<li class="breadcrumb-item active">Edit Category</li>
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
						<?php echo $this->Form->create($category, ['type' => 'file', 'novalidate' => true,'id'=>'categoryForm']); ?>
						<div class="card-body">
							<div class="row">
								<div class="col-xs-6 col-sm-6 col-md-6">
									<div class="form-group">
										<?php
											echo $this->Form->input('category_name', ['maxlength'=>'25','escape'=>false,'class' => 'form-control','label'=>'Category Name <span class="required">*</span>', 'placeholder' => __('Category Name')]);
										?>
									</div>
								</div>
								<div class="col-xs-6 col-sm-6 col-md-6">
									<div class="form-group">
										<?php
											echo $this->Form->input('description', ['maxlength'=>'150','escape'=>false,'class' => 'form-control', 'placeholder' => __('Description'), 'label' => __('Description <span class="required">*</span>')]); 
										?>
									</div>
								</div>
								<div class="col-xs-6 col-sm-6 col-md-6">
									<div class="form-group">
										<?php
											echo $this->Form->input('sequence', ['type'=>'text','maxlength'=>'5','escape'=>false,'class' => 'form-control','label'=>'Sequence Number <span class="required">*</span>', 'placeholder' => __('Sequence')]);
										?>
									</div>
								</div>
								<div class="col-xs-3 col-sm-3 col-md-3">
									<div class="form-group">
										<?php
											echo $this->Form->input('image', ['type'=>'file','escape'=>false,'class' => 'form-control', 'placeholder' => __('Upload Image'), 'label' => __('Upload Image <span class="required"></span>')]); 
										?>
									</div>
								</div>


								<div class="col-xs-3 col-sm-3 col-md-3">
									<div class="form-group">
										<?php
											if(!empty($category->image)) {
												echo '<img src="'.$this->request->webroot.'uploads/category_image/'.$category->image.'" hight="120px" width="120px" alt="">';
											} else {
												echo 'No Image';
											} 
										?>
									</div>
								</div>


								

							</div>
						</div>
						<div class="card-footer">
							<button type="submit" class="btn btn-primary submit">Submit</button>
							<?php echo($this->Html->link('<i class="fa fa-arrow-left"></i> Back', array('controller' => 'category','action' => 'index'), array('escape' => false, 'class' => 'btn btn-primary '))); ?>
						</div>
						<?= $this->Form->end(); ?>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script>
	$(document).ready(function() {
		$(document).on('click', '.submit', function() {
			$(this).attr('disabled',true);
			$('#categoryForm').submit();
		});
	});
</script>