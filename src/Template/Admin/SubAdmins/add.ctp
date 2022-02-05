<?php echo $this->Html->css(['admin/bootstrap-datepicker.min.css']); ?>
<?php echo $this->Html->script(['admin/bootstrap-datepicker.min.js']); ?>
<?php
	use Cake\Core\Configure; 
	use Cake\Routing\Router;
?>
<div class="content-wrapper">
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Add Sub Admin</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/">Home</a></li>
						<li class="breadcrumb-item active">Add Sub Admin</li>
					</ol>
				</div>
			</div>
		</div>
	</section>
	<section class="content admins_add">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="card card-primary">
						<div class="card-header">
							<h3 class="card-title">Fill Form</h3>
						</div>
						<?php echo $this->Form->create($adminUser, ['type' => 'file', 'novalidate' => true,'id'=>'subAdminForm']); ?>
						<div class="card-body">
							<div class="row">
								<div class="col-sm-6 col-md-6">
									<div class="form-group">
										<?php
											echo $this->Form->input('first_name', ['maxlength'=>'30','escape'=>false,'class' => 'form-control','label'=>'First Name <span class="required">*</span>', 'placeholder' => __('First Name'),'max'=>'20']);
										?>
									</div>
								</div>
								<div class="col-sm-6 col-md-6">
									<div class="form-group">
										<?php
											echo $this->Form->input('last_name', ['maxlength'=>'30','escape'=>false,'class' => 'form-control','label'=>'Last Name <span class="required">*</span>','placeholder' => __('Last Name '),'max'=>'20']);
										?>
									</div>
								</div>
								<div class="col-sm-6 col-md-6">
									<div class="form-group">
										<?php
											echo $this->Form->input('email', ['maxlength'=>'50','escape'=>false,'class' => 'form-control', 'placeholder' => __('E-Mail Address'), 'label' => __('Email <span class="required">*</span>')]); 
										?>
									</div>
								</div>
								<div class="col-sm-6 col-md-6">
									<div class="form-group">
										<?php
											echo $this->Form->input('phone', ['maxlength'=>'15','escape'=>false,'class' => 'form-control','label'=>__('Phone Number <span class="required">*</span>'), 'placeholder' => __('Phone Number'),'oninput'=>"this.value = this.value.replace(/[^0-9]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"]);
										?>
									</div>
								</div>
								<div class="col-sm-6 col-md-6">
									<div class="form-group">
										<?php
											$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
											$randomPassword	=	substr( str_shuffle( $alphabet ), 0, 8 );
											echo $this->Form->input('password', ['escape'=>false,'type' => 'password', 'class' => 'form-control', 'placeholder' => __('Password'), 'label' => __('Password <span class="required">*</span>'),'value'=>$randomPassword]);
										?>
									</div>
								</div>
								<div class="col-sm-6 col-md-6">
									<div class="form-group">
										<?php
											echo $this->Form->input('date_of_bith', ['type' => 'text', 'class' => 'form-control DOB', 'placeholder' => __('Date of Birth'), 'label' => __('Date of Birth'),'readonly']);
										?>
									</div>
								</div>
								<div class="col-sm-6 col-md-6">
									<div class="form-group">
										<?php
											echo $this->Form->input('gender', ['label'=>'Gender','class' => 'form-control', 'options' => Configure::read('GENDER_LIST'),'empty'=>'Select Gender']);
										?>
									</div>
								</div>
								<div class="col-sm-6 col-md-6">
									<div class="form-group">
										<?php
											echo $this->Form->label('Profile Picture');
											echo $this->Form->input('image',[ "onchange"=>"loadGroupFile(event)", "accept"=>"image/*",'type'=>'file','class' => '','label'=>false]);
										?>
									</div>
								</div>
								<div class="col-sm-6 col-md-6">
									<div class="form-group">
										<?php
											echo $this->Form->label('Module Access');
											$options	=	Configure::read('MODULE_ACCESS');
											$this->request->data['modules']	=	explode(',',$adminUser->module_access);
											echo $this->Form->select('modules', $options,['label' => false,'style'=>'width:100%;font-family: inherit;','id'=>'module_access','multiple']);
											echo $this->Form->hidden('module_access',['class'=>'module_accesss']);
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
<?php echo $this->Html->css('admin/select2.css'); ?>
<?php echo $this->Html->script('admin/select2.js'); ?>
<script>
	$(document).on('click', '.submit', function() {
		$(this).attr('disabled',true);
		$('#subAdminForm').submit();
	});

	var start = new Date();
	start.setFullYear(start.getFullYear() - 70);

	var end = new Date();
	end.setFullYear(end.getFullYear() - 18);
	$(function() {
		$('.DOB').datepicker({
			format:	'yyyy-mm-dd',
			startDate:start,
            endDate: end,
		});
		
	});
	$(document).ready(function() {
		$('#module_access').select2({
			placeholder:	"Select Module Access"
		});
		
		$('#module_access').change(function() {
			var value	=	$(this).val();
			$('.module_accesss').val(value.toString())
		});
	});
	
	var loadGroupFile = function (event) {
	
		var files = $("#image")[0].files;
		var mimeType = files[0].type;
		var arrMime = mimeType.split('/');
		if(arrMime[0] != 'image')
		{
			alert('Only image files are allowed.');
			$("#image").val('');
			return false;
		}
	};
</script>