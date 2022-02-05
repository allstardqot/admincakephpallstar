<?php
	//$panDetail	=	$this->Custom->getPanAadhrDetails($userId);
	//$bankDetail	=	$this->Custom->getBankDetails($userId);
?>
<?php use Cake\Core\Configure; ?>
<div class="modal-dialog">
	<div class="modal-content">
		<?php if($type == 'pan_card') { ?>
			<div class="modal-header">
				<h4 class="modal-title">Pan Card</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<?php echo $this->Form->create($pan, ['type' => 'file', 'novalidate' => true,'id'=>'','type' => 'file']); 
				echo $this->Form->hidden('uset_id',[ 'value'=>$userId ]);
				echo $this->Form->hidden('type',[ 'value'=>$type ]);
				echo $this->Form->hidden('page',[ 'value'=>$page ]);
				if ( $action == 'editPanDetail' ){
					echo $this->Form->hidden('action',[ 'value'=>'updatePandDetail' ]);
				} else {
					echo $this->Form->hidden('action',[ 'value'=>'savePandDetail' ]);
				}
			?>
			<div class="modal-body">
				<table class="table table-bordered responsive">
					
					<tr>
						<th>Pan Number <span class="required">*</span></th>
						<td>
							<?php 
								//echo !empty($panDetail->pen_aadhar_card) ? $panDetail->pen_aadhar_card->pan_card : ''; 
								echo $this->Form->input('pan_card', ['escape'=>false,'class' => 'form-control','label'=>false, 'placeholder' => __('Pan no.'),
									'templates' => [
									'inputContainer' => '<div class="col-sm-12 col-md-12"><div class="form-group">{{content}}</div></div>',
									'inputContainerError' => '<div class="col-sm-12 col-md-12"><div class="form-group">{{content}}{{error}}</div></div>'
									]
								]);
							?>
						</td>
					</tr>
					<tr>
						<th>Pan Image</th>
						<td>
							<?php
								$rootPath	=	WWW_ROOT.'uploads'.DS.'pan_image'.DS;
								$panImage	=	!empty($pan->pan_image) ? $pan->pan_image : '';
								if(!empty($panImage) && file_exists($rootPath.$panImage)) { ?>
									<a href="<?php echo SITE_URL.'uploads/pan_image/'.$panImage; ?>" download>
										<?php echo $this->Html->image('../uploads/pan_image/'.$panImage,['class'=>'img','alt'=>'Pan Image','style'=>'width:100px;height:60px']); ?>
									</a>
								<?php } else {
									echo $this->Html->image('no_image.png',['class'=>'img','alt'=>'Pan Image','style'=>'width:100px;height:60px']);
								}
							?>
							<input type="file" name="pan_image_upload">
						</td>
					</tr>
					<tr>
						<th>Name On Pan <span class="required">*</span></th>
						<td>
							<?php 
								//echo !empty($panDetail->pen_aadhar_card) ? $panDetail->pen_aadhar_card->pan_name : ''; 
								echo $this->Form->input('pan_name', ['escape'=>false,'class' => 'form-control','label'=>false, 'placeholder' => __('Name on Pan'),
									'templates' => [
										'inputContainer' => '<div class="col-sm-12 col-md-12"><div class="form-group">{{content}}</div></div>',
										'inputContainerError' => '<div class="col-sm-12 col-md-12"><div class="form-group">{{content}}{{error}}</div></div>'
									]
								]);
							?>
						</td>
					</tr>
					<tr>
						<th>Date of Birth <span class="required">*</span><br/>(Y-m-d Format)</th>
						<td>
							<?php 
								//echo !empty($panDetail->pen_aadhar_card) ? date('Y-m-d',strtotime($panDetail->pen_aadhar_card->date_of_birth)) : ''; 
								echo $this->Form->input('date_of_birth', ['escape'=>false,'type'=>'text','class' => 'form-control','label'=>false, 'placeholder' => __('DOB (Y-m-d Format)'),
									'templates' => [
										'inputContainer' => '<div class="col-sm-12 col-md-12"><div class="form-group">{{content}}</div></div>',
										'inputContainerError' => '<div class="col-sm-12 col-md-12"><div class="form-group">{{content}}{{error}}</div></div>'
									]
								]);
							?>
						</td>
					</tr>
					<tr>
						<th>State <span class="required">*</span></th>
						<td>
							<?php 
								//echo !empty($panDetail->pen_aadhar_card) ? $panDetail->pen_aadhar_card->state : '';
								echo $this->Form->input('state', ['escape'=>false,'class' => 'form-control','label'=>false, 'placeholder' => __('State'),
									'templates' => [
										'inputContainer' => '<div class="col-sm-12 col-md-12"><div class="form-group">{{content}}</div></div>',
										'inputContainerError' => '<div class="col-sm-12 col-md-12"><div class="form-group">{{content}}{{error}}</div></div>'
									]
								]);		
							?>
						</td>
					</tr>
				</table>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-primary submit">Submit</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
			<?php echo $this->Form->end(); ?>
		<?php } ?>

		
		


	</div>
</div>
<style>
	.label-warning {
		padding: 2px 5px;
		border-radius: 4px;
	}
</style>