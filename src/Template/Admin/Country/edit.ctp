<?php use Cake\Core\Configure; 
	// echo SITE_URL;die;
?>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Edit Country Details</h4>
            <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
        </div>
        <div class="modal-body">
			<?php echo $this->Form->create($country, ['type' => 'file','id'=>'editPlayerForm']); 
			    echo $this->Form->hidden('action',['value' => 'editsCountry']);
                $extra = json_decode($country->extra) ;
				$iso2 = isset($extra->iso2)?$extra->iso2:'';
				$iso =  isset($extra->iso)?$extra->iso:'';
			// pr($extra);die;
			// echo $this->Form->hidden()
			
			?>
			<input type="hidden" name="eid" value='<?php echo $country->id ?>'>
		<div class="card-body">
			<div class="row">
                <div class="col-xs-6 col-sm-6 col-md-12">
					<div class="form-group">
						<?php
							echo $this->Form->input('name', ['maxlength'=>'30','escape'=>false,'class' => 'form-control','label'=>'Team  <span class="required">*</span>', 'id'=>'teamid','placeholder' => __('Team '),'max'=>'20']);
						?>
						<!-- <p id="teamNameCheck" style="color: red;">Plz Enter Team Name</p> -->
					</div>
				</div>

				<div class="col-xs-6 col-sm-6 col-md-12">
					<div class="form-group">
						<?php
							echo $this->Form->input('iso2', ['value'=>$iso2,'maxlength'=>'30','escape'=>false,'class' => 'form-control','label'=>'Display Name <span class="required">*</span>', 'id'=>'displayname','placeholder' => __('Display Name'),'max'=>'20']);
						?>
						<!-- <p id="teamNameCheck" style="color: red;">Plz Enter Team Name</p> -->
					</div>
				</div>

                <div class="col-xs-6 col-sm-6 col-md-12">
					<div class="form-group">
						<?php
							echo $this->Form->input('iso', ['value'=>$iso,'maxlength'=>'30','escape'=>false,'class' => 'form-control','label'=>'Player Name <span class="required">*</span>', 'id'=>'fullname','placeholder' => __('Player Name'),'max'=>'20']);
						?>
						<!-- <p id="teamNameCheck" style="color: red;">Plz Enter Team Name</p> -->
					</div>
				</div>
				<div class="col-xs-6 col-sm-6 col-md-6">
					<div class="form-group">
						<?php
							//echo $this->Form->input('birthdate', ['type'=>'text','maxlength'=>'30','escape'=>false,'class' => 'form-control','id'=>'dob','label'=>'DOB <span class="required">*</span>','placeholder' => __('DOB'),'max'=>'20']);
						?>
					</div>
				</div>

                <div class="col-xs-6 col-sm-6 col-md-12">
					<div class="form-group">
						<?php
							//echo $this->Form->input('position_id', ['options'=>$position,'maxlength'=>'30','escape'=>false,'class' => 'form-control','id'=>'position_id','label'=>'Primary Position in Team <span class="required">*</span>','placeholder' => __('Primary Position in Team'),'max'=>'20']);
						?>
					</div>
				</div>

				

                

                

			</div>
		</div>
		<div class="card-footer">
			<button id="editPlayerBtn" class="btn btn-primary ">Submit</button>
			<?php echo($this->Html->link('<i class="fa fa-arrow-left"></i> Cancel', array('action' => 'index'), array('escape' => false, 'class' => 'btn btn-success'))); ?>
		</div>
		<?php echo $this->Form->end(); ?>
		</div>
    </div>
</div>
					
