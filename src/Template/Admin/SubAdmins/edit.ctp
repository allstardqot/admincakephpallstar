<?php use Cake\Core\Configure; 
	// echo SITE_URL;die;
?>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Edit Admin Details</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
			<?php echo $this->Form->create($adminUser, ['type' => 'file','id'=>'editAdminForm']); 
			echo $this->Form->hidden('action',['value' => 'editsubadmin']); 
			// pr($adminUser);die;
			// echo $this->Form->hidden()
			
			?>
			<input type="hidden" name="eid" value='<?php echo $adminUser->id ?>'>
		<div class="card-body">
			<div class="row">
				<div class="col-xs-6 col-sm-6 col-md-12">
					<div class="form-group">
						<?php
							echo $this->Form->input('first_name', ['maxlength'=>'30','escape'=>false,'class' => 'form-control','label'=>'First Name <span class="required">*</span>', 'id'=>'firstname','placeholder' => __('First Name'),'max'=>'20']);
						?>
						<p id="NameCheck" style="color: red;">Plz Enter First Name</p>
					</div>
				</div>
				<div class="col-xs-6 col-sm-6 col-md-12">
					<div class="form-group">
						<?php
							echo $this->Form->input('last_name', ['maxlength'=>'30','escape'=>false,'class' => 'form-control','id'=>'lastname','label'=>'Last Name <span class="required">*</span>','placeholder' => __('Last Name'),'max'=>'20']);
						?>
						<p id="lastNCheck" style="color: red;">Plz Enter Last Name</p>
					</div>
				</div>
				<div class="col-xs-6 col-sm-6 col-md-12">
					<div class="form-group">
						<?php
							echo $this->Form->input('email', ['maxlength'=>'50','escape'=>false,'class' => 'form-control', 'id'=>'emailV','placeholder' => __('E-Mail Address'), 'label' => __('Email *')]); 
						?>
						<p id="emailChk" style="color: red;">Plz Enter Email</p>
					</div>
				</div>
			</div>
		</div>
		<div class="card-footer">
			<button type="button" id="editadminBtn" class="btn btn-primary ">Submit</button>
			<?php echo($this->Html->link('<i class="fa fa-arrow-left"></i> Back', array('action' => 'index','?'=>$this->request->session()->read('sorting_query')), array('escape' => false, 'class' => 'btn btn-primary '))); ?>
		</div>
		<?php echo $this->Form->end(); ?>
		</div>
    </div>
</div>
					
