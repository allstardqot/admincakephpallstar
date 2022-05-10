<?php use Cake\Core\Configure; 
	// echo SITE_URL;die;
?>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Edit Player Details</h4>
            <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
        </div>
        <div class="modal-body">
			<?php echo $this->Form->create($players, ['type' => 'file','id'=>'editPlayerForm']); 
			echo $this->Form->hidden('action',['value' => 'editsPlayer']); 
			// pr($adminUser);die;
			// echo $this->Form->hidden()
			
			?>
			<input type="hidden" name="eid" value='<?php echo $players->id ?>'>
		<div class="card-body">
			<div class="row">
                <div class="col-xs-6 col-sm-6 col-md-6">
					<div class="form-group">
						<?php
							echo $this->Form->input('team_id', ['type'=>'select','options'=>$team,'maxlength'=>'30','escape'=>false,'class' => 'form-control','label'=>'Team  <span class="required">*</span>', 'id'=>'teamid','placeholder' => __('Team '),'max'=>'20']);
						?>
						<!-- <p id="teamNameCheck" style="color: red;">Plz Enter Team Name</p> -->
					</div>
				</div>

				<div class="col-xs-6 col-sm-6 col-md-6">
					<div class="form-group">
						<?php
							echo $this->Form->input('display_name', ['maxlength'=>'30','escape'=>false,'class' => 'form-control','label'=>'Display Name <span class="required">*</span>', 'id'=>'displayname','placeholder' => __('Display Name'),'max'=>'20']);
						?>
						<!-- <p id="teamNameCheck" style="color: red;">Plz Enter Team Name</p> -->
					</div>
				</div>

                <div class="col-xs-6 col-sm-6 col-md-6">
					<div class="form-group">
						<?php
							echo $this->Form->input('fullname', ['maxlength'=>'30','escape'=>false,'class' => 'form-control','label'=>'Player Name <span class="required">*</span>', 'id'=>'fullname','placeholder' => __('Player Name'),'max'=>'20']);
						?>
						<!-- <p id="teamNameCheck" style="color: red;">Plz Enter Team Name</p> -->
					</div>
				</div>
				<div class="col-xs-6 col-sm-6 col-md-6">
					<div class="form-group">
						<?php
							echo $this->Form->input('birthdate', ['type'=>'text','maxlength'=>'30','escape'=>false,'class' => 'form-control','id'=>'dob','label'=>'DOB <span class="required">*</span>','placeholder' => __('DOB'),'max'=>'20']);
						?>
					</div>
				</div>

                <div class="col-xs-6 col-sm-6 col-md-12">
					<div class="form-group">
						<?php
							echo $this->Form->input('position_id', ['options'=>$position,'maxlength'=>'30','escape'=>false,'class' => 'form-control','id'=>'position_id','label'=>'Primary Position in Team <span class="required">*</span>','placeholder' => __('Primary Position in Team'),'max'=>'20']);
						?>
					</div>
				</div>

				

                

                <div class="col-xs-6 col-sm-6 col-md-6">
					<div class="form-group">
						<?php
							echo $this->Form->input('height', ['maxlength'=>'50','escape'=>false,'class' => 'form-control', 'id'=>'founded','placeholder' => __('Player Height'), 'label' => __('Player Height *')]); 
						?>
						<!-- <p id="foundedCheck" style="color: red;">Plz Enter Player Weight</p> -->
					</div>
				</div>

                <div class="col-xs-6 col-sm-6 col-md-6">
					<div class="form-group">
						<?php
							echo $this->Form->input('weight', ['maxlength'=>'50','escape'=>false,'class' => 'form-control', 'id'=>'founded','placeholder' => __('Player Weight'), 'label' => __('Player Weight *')]); 
						?>
						<!-- <p id="foundedCheck" style="color: red;">Plz Enter Player Weight</p> -->
					</div>
				</div>

                <div class="col-xs-6 col-sm-6 col-md-6">
					<div class="form-group">
						<?php
							echo $this->Form->input('birthcountry', ['maxlength'=>'50','escape'=>false,'class' => 'form-control', 'id'=>'countryName','placeholder' => __('Birth Country'), 'label' => __('Birth Country *')]); 
						?>
						<!-- <p id="CountryCheck" style="color: red;">Plz Enter Birth Country</p> -->
					</div>
				</div>

               
                <div class="col-xs-6 col-sm-6 col-md-6">
					<div class="form-group">
						<?php
							echo $this->Form->input('birthplace', ['maxlength'=>'50','escape'=>false,'class' => 'form-control', 'id'=>'birthplace','placeholder' => __('Birth Place'), 'label' => __('Birth Place *')]); 
						?>
						<!-- <p id="shortNameCheck" style="color: red;">Plz Enter Birth Place</p> -->
					</div>
				</div>

                <div class="col-xs-6 col-sm-6 col-md-6">
					<div class="form-group">
						<?php
							echo $this->Form->input('nationality', ['maxlength'=>'50','escape'=>false,'class' => 'form-control', 'id'=>'nationality','placeholder' => __('Nationality'), 'label' => __('Nationality *')]); 
						?>
						<!-- <p id="shortNameCheck" style="color: red;">Plz Enter Birth Place</p> -->
					</div>
				</div>

				<div class="col-xs-6 col-sm-6 col-md-6">
					<div class="form-group">
						<?php
							echo $this->Form->input('sell_price', ['maxlength'=>'50','escape'=>false,'class' => 'form-control', 'id'=>'sell_price','placeholder' => __('Selling Price'), 'label' => __('Selling Price *')]); 
						?>
						<!-- <p id="shortNameCheck" style="color: red;">Plz Enter Birth Place</p> -->
					</div>
				</div>

                <div class="col-xs-6 col-sm-6 col-md-12">
					<div class="form-group">
						<?php
							echo $this->Form->input('description', ['maxlength'=>'50','escape'=>false,'class' => 'form-control', 'id'=>'descriptionP','placeholder' => __('About Player'), 'label' => __('About Player *')]); 
						?>
						<!-- <p id="shortNameCheck" style="color: red;">Plz Enter Birth Place</p> -->
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
					
