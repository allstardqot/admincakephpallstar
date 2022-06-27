<?php use Cake\Core\Configure; 
	// echo SITE_URL;die;
?>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Edit Week Details</h4>
            <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
        </div>
        <div class="modal-body">
			<?php echo $this->Form->create($weeks, ['type' => 'file','id'=>'editWeekForm']); 
			echo $this->Form->hidden('action',['value' => 'editsWeek']); 
			// pr($adminUser);die;
			// echo $this->Form->hidden()
			
			
			?>
			<input type="hidden" name="eid" value='<?php echo $weeks->id ?>'>
		<div class="card-body">
			<div class="row">
                <div class="col-xs-6 col-sm-6 col-md-12">
					<div class="form-group">
						<?php
							echo $this->Form->input('starting_at', ['type'=>'text','maxlength'=>'30','escape'=>false,'class' => 'form-control my_date_picker','label'=>'Start At  <span class="required">*</span>', 'id'=>'teamid','placeholder' => __('Start At  '),'max'=>'20']);
						?>
						<!-- <p id="teamNameCheck" style="color: red;">Plz Enter Team Name</p> -->
					</div>
				</div>

				<div class="col-xs-6 col-sm-6 col-md-12">
					<div class="form-group">
						
						<?php
							echo $this->Form->input('ending_at', ['type'=>'text','maxlength'=>'30','escape'=>false,'class' => 'form-control my_date_picker','label'=>'End At <span class="required">*</span>', 'id'=>'displayname','placeholder' => __('End At'),'max'=>'20']);
						?>
						<!-- <p id="teamNameCheck" style="color: red;">Plz Enter Team Name</p> -->
					</div>
				</div>
			</div>
		</div>
		<div class="card-footer">
			<button id="editWeekBtn" class="btn btn-primary ">Submit</button>
			<?php echo($this->Html->link('<i class="fa fa-arrow-left"></i> Cancel', array('action' => 'index'), array('escape' => false, 'class' => 'btn btn-success'))); ?>
		</div>
		<?php echo $this->Form->end(); ?>
		</div>
    </div>
</div>
					
