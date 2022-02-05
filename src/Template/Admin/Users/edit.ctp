<?php
	use Cake\Core\Configure;	
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit User</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/users">User</a></li>
                        <li class="breadcrumb-item active">Edit User</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

	<section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                    	<?php
						//echo $user['gender'];
						echo $this->Form->create($user, ['type' => 'file', 'novalidate' => true,'id'=>'userForm']); ?>
                    	<div class="row">
							<?php
							echo $this->Form->input('full_name', ['maxlength'=>'30','escape'=>false,'class' => 'form-control','label'=>'Full Name <span class="required">*</span>', 'placeholder' => __('Full Name'),
							    'templates' => [
								'inputContainer' => '<div class="col-xs-12 col-sm-6 col-md-6"><div class="form-group">{{content}}</div></div>',
								'inputContainerError' => '<div class="col-xs-12 col-sm-6 col-md-6"><div class="form-group">{{content}}{{error}}</div></div>'
							    ]
							]);
							echo $this->Form->input('country', ['maxlength'=>'30','escape'=>false,'class' => 'form-control','label'=>'Country Name <span class="required">*</span>', 'placeholder' => __('Country Name'),
							    'templates' => [
								'inputContainer' => '<div class="col-xs-12 col-sm-6 col-md-6"><div class="form-group">{{content}}</div></div>',
								'inputContainerError' => '<div class="col-xs-12 col-sm-6 col-md-6"><div class="form-group">{{content}}{{error}}</div></div>'
							    ]
							]);
							?>
							
					    </div>
					    <div class="row">
							<?php
							echo $this->Form->input('email', ['escape'=>false,'class' => 'form-control', 'placeholder' => __('E-Mail Address'), 'label' => __('Email <span class="required">*</span>'),
							    'templates' => [
								'inputContainer' => '<div class="col-xs-12 col-sm-6 col-md-6"><div class="form-group">{{content}}</div></div>',
								'inputContainerError' => '<div class="col-xs-12 col-sm-6 col-md-6"><div class="form-group">{{content}}{{error}}</div></div>'
							    ]
							]);
							echo $this->Form->input('phone', ['escape'=>false,'class' => 'form-control','label'=>__('Phone Number <span class="required">*</span>'), 'placeholder' => __('Phone Number'),
							    
							    'templates' => [
								'inputContainer' => '<div class="col-xs-12 col-sm-6 col-md-6"><div class="form-group">{{content}}</div></div>',
								'inputContainerError' => '<div class="col-xs-12 col-sm-6 col-md-6"><div class="form-group">{{content}}{{error}}</div></div>'
							    ]
							]);
							?>
					    </div>
						<div class="row">
							<?php 
							//pr(Configure::read('GENDER_LIST'));
							echo $this->Form->input('gender', ['label'=>false,'class' => 'form-control', 'options' => Configure::read('GENDER_LIST2'),'empty'=>'Select Gender',
								'templates' => [
								'inputContainer' => '<div class="col-xs-12 col-sm-6 col-md-6"><div class="form-group"><label for="gender">Gender</label>  {{content}}</div></div>',
								'inputContainerError' => '<div class="col-xs-12 col-sm-6 col-md-6"><div class="form-group"><label for="gender">Gender</label> <span class="required">*</span>{{content}}{{error}}</div></div>'
								]
							]);

							echo $this->Form->input('address', ['type' => 'text', 'class' => 'form-control', 'placeholder' => __('Address'), 'label' => __('Address'),
								'templates' => [
								'inputContainer' => '<div class="col-xs-12 col-sm-6 col-md-6"><div class="form-group">{{content}}</div></div>',
								'inputContainerError' => '<div class="col-xs-12 col-sm-6 col-md-6"><div class="form-group">{{content}}{{error}}</div></div>'
								]
							]);
							?>
					    </div>
						<div class="row">
							<?php
							echo $this->Form->input('city', ['maxlength'=>'30','escape'=>false,'class' => 'form-control','label'=>'City Name <span class="required">*</span>', 'placeholder' => __('City Name'),
							    'templates' => [
								'inputContainer' => '<div class="col-xs-12 col-sm-6 col-md-6"><div class="form-group">{{content}}</div></div>',
								'inputContainerError' => '<div class="col-xs-12 col-sm-6 col-md-6"><div class="form-group">{{content}}{{error}}</div></div>'
							    ]
							]);
							echo $this->Form->input('state', ['maxlength'=>'30','escape'=>false,'class' => 'form-control','label'=>'State Name <span class="required">*</span>', 'placeholder' => __('State Name'),
							    'templates' => [
								'inputContainer' => '<div class="col-xs-12 col-sm-6 col-md-6"><div class="form-group">{{content}}</div></div>',
								'inputContainerError' => '<div class="col-xs-12 col-sm-6 col-md-6"><div class="form-group">{{content}}{{error}}</div></div>'
							    ]
							]);
							?>
							
					    </div>
						<div class="row">
							<?php
							echo $this->Form->input('country_code', ['maxlength'=>'30','escape'=>false,'class' => 'form-control','label'=>'Country Code <span class="required">*</span>', 'placeholder' => __('Country code'),
							    'templates' => [
								'inputContainer' => '<div class="col-xs-12 col-sm-6 col-md-6"><div class="form-group">{{content}}</div></div>',
								'inputContainerError' => '<div class="col-xs-12 col-sm-6 col-md-6"><div class="form-group">{{content}}{{error}}</div></div>'
							    ]
							]);
							// echo $this->Form->input('winning_wallet', ['maxlength'=>'30','escape'=>false,'class' => 'form-control','label'=>'Winning Points <span class="required">*</span>', 'placeholder' => __('Winning Points'),
							//     'templates' => [
							// 	'inputContainer' => '<div class="col-xs-12 col-sm-6 col-md-6"><div class="form-group">{{content}}</div></div>',
							// 	'inputContainerError' => '<div class="col-xs-12 col-sm-6 col-md-6"><div class="form-group">{{content}}{{error}}</div></div>'
							//     ]
							// ]);
							?>							
					    </div>
						<div class="row">
							<?php
								echo $this->Form->input('ch_password', ['type' => 'text', 'class' => 'form-control', 'id' =>'password' , 'placeholder' => __('Change Password'), 'label' => __('Change Password'),
								'templates' => [
								'inputContainer' => '<div class="col-xs-12 col-sm-6 col-md-6"><div class="form-group">{{content}}</div></div>',
								'inputContainerError' => '<div class="col-xs-12 col-sm-6 col-md-6"><div class="form-group">{{content}}{{error}}</div></div>'
								]
							]);
							echo $this->Form->input('cn_password', ['type' => 'text', 'class' => 'form-control', 'id'=>'confirm_password' , 'placeholder' => __('Confirm Change Password'), 'label' => __('Confirm Change Password'),
								'templates' => [
								'inputContainer' => '<div class="col-xs-12 col-sm-6 col-md-6"><div class="form-group">{{content}}</div></div>',
								'inputContainerError' => '<div class="col-xs-12 col-sm-6 col-md-6"><div class="form-group">{{content}}{{error}}</div></div>'
								]
							]);
							?>
							<span id="message"></span>
						</div>
						<button type="submit" class="btn btn-primary submit">Submit</button>
						<?php echo($this->Html->link('<i class="fa fa-arrow-left"></i> Back', array('controller' => 'users','action' => 'index','?'=>$this->request->session()->read('sorting_query')), array('escape' => false, 'class' => 'btn btn-primary '))); ?>	

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
	$(document).on('click', '.submit', function() {
		//$(this).attr('disabled',true);
		//$('#userForm').submit();
		$("#userForm").on("submit",function(event){
			var pas=$('#password').val();
			if(pas.length<8 && pas.length!=0){
				$('#message').html('Password entered must be between 8 to 15 Charcters').css('color', 'red');
			}
			if ($('#password').val() == $('#confirm_password').val() && (pas.length>=8 || pas.length==0)) {
				$(this).attr('disabled',true);
				$('#userForm').submit();
			} else {
				event.preventDefault();
			};
		});
	});
	
	$('#password, #confirm_password').on('keyup', function () {
		var pas=$('#password').val();
		
		if ($('#password').val() == $('#confirm_password').val()) {
			$("#password").attr('name', 'password');
			$('#message').html('Matching').css('color', 'green');
		} else {
			$('#message').html('Not Matching').css('color', 'red');
		}
	});
</script>
