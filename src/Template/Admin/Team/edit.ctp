<?php use Cake\Core\Configure; 
	// echo SITE_URL;die;
?>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Edit Team Details</h4>
            <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
        </div>
        <div class="modal-body">
			<?php echo $this->Form->create($team, ['type' => 'file','id'=>'editTeamForm']); 
			echo $this->Form->hidden('action',['value' => 'editsTeam']); 
			// pr($adminUser);die;
			// echo $this->Form->hidden()
			
			?>
			<input type="hidden" name="eid" value='<?php echo $team->id ?>'>
		<div class="card-body">
			<div class="row">
				<div class="col-xs-6 col-sm-6 col-md-12">
					<div class="form-group">
						<?php
							echo $this->Form->input('name', ['maxlength'=>'30','escape'=>false,'class' => 'form-control','label'=>'Team Name <span class="required">*</span>', 'id'=>'teamname','placeholder' => __('Team Name'),'max'=>'20']);
						?>
						<!-- <p id="teamNameCheck" style="color: red;">Plz Enter Team Name</p> -->
					</div>
				</div>
				<div class="col-xs-6 col-sm-6 col-md-12">
					<div class="form-group">
						<?php
							echo $this->Form->input('description', ['maxlength'=>'30','escape'=>false,'class' => 'form-control','id'=>'teamdescription','label'=>'Description <span class="required">*</span>','placeholder' => __('Description'),'max'=>'20']);
						?>
					</div>
				</div>

				<div class="col-xs-6 col-sm-6 col-md-12">
					<div class="form-group">
						<?php
							echo $this->Form->input('national_team', ['type'=>'text','maxlength'=>'50','escape'=>false,'class' => 'form-control', 'id'=>'teamManger','placeholder' => __('Team Manager'), 'label' => __('Team Manager *')]); 
						?>
						<!-- <p id="teamMangerCheck" style="color: red;">Plz Enter Team Manager</p> -->
					</div>
				</div>
                <div class="col-xs-6 col-sm-6 col-md-12">
					<div class="form-group">
						<?php
							echo $this->Form->input('short_code', ['maxlength'=>'50','escape'=>false,'class' => 'form-control', 'id'=>'shortName','placeholder' => __('Short Name'), 'label' => __('Short Name *')]); 
						?>
						<!-- <p id="shortNameCheck" style="color: red;">Plz Enter Short Name</p> -->
					</div>
				</div>

                <div class="col-xs-6 col-sm-6 col-md-12">
					<div class="form-group">
						<?php
							echo $this->Form->input('founded', ['maxlength'=>'50','escape'=>false,'class' => 'form-control', 'id'=>'founded','placeholder' => __('Foundation Year'), 'label' => __('Foundation Year *')]); 
						?>
						<!-- <p id="foundedCheck" style="color: red;">Plz Enter Foundation Year</p> -->
					</div>
				</div>

                <div class="col-xs-6 col-sm-6 col-md-12">
					<div class="form-group">
						<?php
							echo $this->Form->input('country_id', ['type'=>'select','options'=>$countryoption,'maxlength'=>'50','escape'=>false,'class' => 'form-control', 'id'=>'countryName','placeholder' => __('Country Name'), 'label' => __('Country Name *')]); 
						?>
						<!-- <p id="CountryCheck" style="color: red;">Plz Enter Country Name</p> -->
					</div>
				</div>

			</div>
		</div>
		<div class="card-footer">
			<button id="editteamBtn" class="btn btn-primary ">Submit</button>
			<?php echo($this->Html->link('<i class="fa fa-arrow-left"></i> Cancel', array('action' => 'index'), array('escape' => false, 'class' => 'btn btn-success '))); ?>
		</div>
		<?php echo $this->Form->end(); ?>
		</div>
    </div>
</div>
					
