<?php use Cake\Core\Configure; 
	// echo SITE_URL;die;
?>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Edit Points </h4>
            <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
        </div>
        <div class="modal-body">
			<?php echo $this->Form->create($userPoint, ['id'=>'editpointsForm']); 
			echo $this->Form->hidden('action',['value' => 'editpoint']); 
			// pr($adminUser);die;
			// echo $this->Form->hidden()
			
			?>
			<input type="hidden" name="eid" value='<?php echo $userPoint->id ?>'>
		<div class="card-body">
			<div class="row">
				<div class="col-xs-6 col-sm-6 col-md-12">
					<div class="form-group">
						<?php
							echo $this->Form->input('point', ['maxlength'=>'30','escape'=>false,'class' => 'form-control','label'=>'Enter Point <span class="required">*</span>', 'id'=>'point','placeholder' => __('Point'),'max'=>'20']);
						?>
						<p id="pointCheck" style="color: red;">Plz Enter Point</p>
					</div>
				</div>
			</div>
		</div>
		<div class="card-footer">
			<button type="button" id="editpointBtn" class="btn btn-primary ">Submit</button>
			<?php echo($this->Html->link('<i class="fa fa-arrow-left"></i> Cancel', array('action' => 'index','?'=>$this->request->session()->read('sorting_query')), array('escape' => false, 'class' => 'btn btn-success '))); ?>
		</div>
		<?php echo $this->Form->end(); ?>
		</div>
    </div>
</div>
					
