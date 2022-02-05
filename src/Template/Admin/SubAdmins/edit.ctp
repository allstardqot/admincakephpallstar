<?php
	use Cake\Core\Configure;	
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Sub Admin</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/sub-admins">Sub Admin</a></li>
                        <li class="breadcrumb-item active">Edit Sub Admin</li>
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
						<?php echo $this->Form->create($adminUser, ['type' => 'file', 'novalidate' => true,'id'=>'subAdminForm']); ?>
						<div class="card-body">
							<div class="row">
								<div class="col-xs-6 col-sm-6 col-md-6">
									<div class="form-group">
										<?php
											echo $this->Form->input('first_name', ['maxlength'=>'30','escape'=>false,'class' => 'form-control','label'=>'First Name <span class="required">*</span>', 'placeholder' => __('First Name'),'max'=>'20']);
										?>
									</div>
								</div>
								<div class="col-xs-6 col-sm-6 col-md-6">
									<div class="form-group">
										<?php
											echo $this->Form->input('last_name', ['maxlength'=>'30','escape'=>false,'class' => 'form-control','label'=>'Last Name <span class="required">*</span>','placeholder' => __('Last Name'),'max'=>'20']);
										?>
									</div>
								</div>
								<div class="col-xs-6 col-sm-6 col-md-6">
									<div class="form-group">
										<?php
											echo $this->Form->input('email', ['maxlength'=>'50','escape'=>false,'class' => 'form-control', 'placeholder' => __('E-Mail Address'), 'label' => __('Email <span class="required">*</span>')]); 
										?>
									</div>
								</div>
								<?php /* <div class="col-xs-6 col-sm-6 col-md-6">
									<div class="form-group">
										<?php
											echo $this->Form->input('current_password', ['escape'=>false,'type' => 'text', 'class' => 'form-control', 'placeholder' => __('Current Password'), 'label' => __('Current Password <span class="required">*</span>'),'readonly'=>'readonly']);
										?>
									</div>
								</div> */ ?>
								<div class="col-xs-6 col-sm-6 col-md-6">
									<div class="form-group">
										<?php
											echo $this->Form->input('password', ['escape'=>false,'type' => 'password', 'class' => 'form-control', 'placeholder' => __('New Password'), 'label' => __('New Password <span class="required"></span>'),'value'=>'']);
										?>
									</div>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-6">
									<div class="form-group">
										<?php
											echo $this->Form->input('phone', ['escape'=>false,'class' => 'form-control','label'=>__('Phone Number <span class="required">*</span>'), 'placeholder' => __('Phone Number'),'oninput'=>"this.value = this.value.replace(/[^0-9]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"]);
										?>
									</div>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-6">
									<div class="form-group">
										<?php
											echo $this->Form->input('gender', ['label'=>'Gender','class' => 'form-control', 'options' => Configure::read('GENDER_LIST'),'empty'=>'Select Gender']);
										?>
									</div>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-6">
									<div class="form-group">
										<?php
											echo $this->Form->input('date_of_bith', ['type' => 'text', 'class' => 'form-control DOB', 'placeholder' => __('Date of Birth'), 'label' => __('Date of Birth'),'readonly']);
										?>
									</div>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-6">
									<div class="form-group">
										<span style="padding:10px; margin-bottom:10px;"><img src="<?php echo SITE_URL; ?>/uploads/users/<?php echo $adminUser->image; ?>" width="100" height="100"/></span><br>
										<?php
											echo $this->Form->label('Profile Picture');
											echo $this->Form->input('image',[ "onchange"=>"loadGroupFile(event)", "accept"=>"image/*",'type'=>'file','class' => '','label'=>false]);
										?>
									</div>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-6">
									<div class="form-group">
										<?php
											echo $this->Form->label('Module Access');
											$options	=	Configure::read('MODULE_ACCESS');
											echo $this->Form->select('modules', $options,['label' => false,'style'=>'width:100%;','id'=>'module_access','multiple','value'=>explode(',',$adminUser->module_access)]);
											echo $this->Form->hidden('module_access',['class'=>'module_accesss']);
										?>
									</div>
								</div>
							</div>
						</div>
						<div class="card-footer">
							<button type="submit" id="submitBtn" class="btn btn-primary submit">Submit</button>
							<?php echo($this->Html->link('<i class="fa fa-arrow-left"></i> Back', array('action' => 'index','?'=>$this->request->session()->read('sorting_query')), array('escape' => false, 'class' => 'btn btn-primary '))); ?>
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

	$('#submitBtn').click(function(){
		setTimeout( function(){
	   		$('#submitBtn').prop('disabled', true);
	   	}  , 100 );
	});
	
</script>
