<?php use Cake\Core\Configure; 
	// echo SITE_URL;die;
?>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Edit User Details</h4>
            <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
        </div>
        <div class="modal-body">
			<?php echo $this->Form->create($adminUser, ['type' => 'file','id'=>'editUserForm']); 
			echo $this->Form->hidden('action',['value' => 'edituser']); 
			// pr($adminUser);die;
			// echo $this->Form->hidden()
			
			?>
			<input type="hidden" name="eid" value='<?php echo $adminUser->id ?>'>
		<div class="card-body">
			<div class="row">
				<div class="col-xs-6 col-sm-6 col-md-12">
					<div class="form-group">
						<?php
							echo $this->Form->input('user_name', ['maxlength'=>'30','escape'=>false,'class' => 'form-control','label'=>'First Name <span class="required">*</span>', 'id'=>'firstname','placeholder' => __('First Name'),'max'=>'20']);
						?>
						<!-- <p id="NameCheck" style="color: red;">Plz Enter First Name</p> -->
					</div>
				</div>
				<div class="col-xs-6 col-sm-6 col-md-12">
					<div class="form-group">
						<?php
							echo $this->Form->input('email', ['maxlength'=>'30','escape'=>false,'class' => 'form-control','id'=>'lastname','label'=>'Last Name <span class="required">*</span>','placeholder' => __('Email'),'max'=>'20']);
						?>
						<!-- <p id="lastNCheck" style="color: red;">Plz Enter Last Name</p> -->
					</div>
				</div>
				<div class="col-xs-6 col-sm-6 col-md-12">
					<div class="form-group">
						<?php
							echo $this->Form->input('phone', ['maxlength'=>'50','escape'=>false,'class' => 'form-control', 'id'=>'emailV','placeholder' => __('Phone'), 'label' => __('Phone *')]); 
						?>
						<!-- <p id="emailChk" style="color: red;">Plz Enter Email</p> -->
					</div>
				</div>

				<div class="col-xs-6 col-sm-6 col-md-12">
					<div class="form-group">
						<?php
							echo $this->Form->input('address', ['maxlength'=>'50','escape'=>false,'class' => 'form-control', 'id'=>'emailV','placeholder' => __(' Address'), 'label' => __('Address *')]); 
						?>
						<!-- <p id="emailChk" style="color: red;">Plz Enter Email</p> -->
					</div>
				</div>

				<div class="col-xs-6 col-sm-6 col-md-12">
					<div class="form-group">
						<input type="hidden" name="hidden_wallet" value='<?php echo $adminUser->wallet?>'>
						<?php
							echo $this->Form->input('wallet', ['escape'=>false,'class' => 'form-control', 'id'=>'wallet','placeholder' => __(' Wallet'), 'label' => __('Wallet *')]); 
						?>
						<!-- <p id="emailChk" style="color: red;">Plz Enter Email</p> -->
					</div>
				</div>
			</div>
		</div>
		<div class="card-footer">
			<button type="button" id="edituserBtn" class="btn btn-primary ">Submit</button>
			<?php echo($this->Html->link('<i class="fa fa-arrow-left"></i> Cancel', array('action' => 'index','?'=>$this->request->session()->read('sorting_query')), array('escape' => false, 'class' => 'btn btn-success '))); ?>
		</div>
		<?php echo $this->Form->end(); ?>
		</div>
    </div>
</div>
					
