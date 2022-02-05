<?php
	//$panDetail	=	$this->Custom->getPanAadhrDetails($userId);
	//$bankDetail	=	$this->Custom->getBankDetails($userId);
?>
<?php use Cake\Core\Configure; ?>
<div class="modal-dialog">
	<div class="modal-content">
		<?php if($type == 'aadhar_detail') { ?>
			<div class="modal-header">
				<h4 class="modal-title">Address Proof</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<?php echo $this->Form->create($bank, ['type' => 'file', 'novalidate' => true,'id'=>'','type' => 'file']); 
				echo $this->Form->hidden('uset_id',[ 'value'=>$userId ]);
				echo $this->Form->hidden('type',[ 'value'=>$type ]);
				echo $this->Form->hidden('page',[ 'value'=>$page ]);

				if ( $action == 'editAadharDetail' ){
					echo $this->Form->hidden('action',[ 'value'=>'updateAadharDetail' ]);
				} else {
					echo $this->Form->hidden('action',[ 'value'=>'saveAadharDetail' ]);
				}
			?>
			<div class="modal-body">
				<table class="table table-bordered responsive">
					
					

					

					<tr>
						<th>House no. <span class="required">*</span></th>
						<td>
							<?php 
								echo $this->Form->input('house_no', ['escape'=>false,'class' => 'form-control','label'=>false, 'placeholder' => __('House no.'),
									'templates' => [
										'inputContainer' => '<div class="col-sm-12 col-md-12"><div class="form-group">{{content}}</div></div>',
										'inputContainerError' => '<div class="col-sm-12 col-md-12"><div class="form-group">{{content}}{{error}}</div></div>'
									]
								]);
							?>
						</td>
					</tr>

					<tr>
						<th>Line 1 <span class="required">*</span></th>
						<td>
							<?php 
								echo $this->Form->input('line1', ['escape'=>false,'class' => 'form-control','label'=>false, 'placeholder' => __('Line 1'),
									'templates' => [
										'inputContainer' => '<div class="col-sm-12 col-md-12"><div class="form-group">{{content}}</div></div>',
										'inputContainerError' => '<div class="col-sm-12 col-md-12"><div class="form-group">{{content}}{{error}}</div></div>'
									]
								]);
							?>
						</td>
					</tr>

					<tr>
						<th>Line 2 <span class="required">*</span></th>
						<td>
							<?php 
								echo $this->Form->input('line2', ['escape'=>false,'class' => 'form-control','label'=>false, 'placeholder' => __('Line 2'),
									'templates' => [
										'inputContainer' => '<div class="col-sm-12 col-md-12"><div class="form-group">{{content}}</div></div>',
										'inputContainerError' => '<div class="col-sm-12 col-md-12"><div class="form-group">{{content}}{{error}}</div></div>'
									]
								]);
							?>
						</td>
					</tr>

					<tr>
						<th>City <span class="required">*</span></th>
						<td>
							<?php 
								echo $this->Form->input('city', ['escape'=>false,'class' => 'form-control','label'=>false, 'placeholder' => __('City'),
									'templates' => [
										'inputContainer' => '<div class="col-sm-12 col-md-12"><div class="form-group">{{content}}</div></div>',
										'inputContainerError' => '<div class="col-sm-12 col-md-12"><div class="form-group">{{content}}{{error}}</div></div>'
									]
								]);
							?>
						</td>
					</tr>

					<tr>
						<th>Pincode <span class="required">*</span></th>
						<td>
							<?php 
								echo $this->Form->input('pincode', ['escape'=>false,'class' => 'form-control','label'=>false, 'placeholder' => __('Pincode'),
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
								echo $this->Form->input('state', ['escape'=>false,'class' => 'form-control','label'=>false, 'placeholder' => __('State'),
									'templates' => [
										'inputContainer' => '<div class="col-sm-12 col-md-12"><div class="form-group">{{content}}</div></div>',
										'inputContainerError' => '<div class="col-sm-12 col-md-12"><div class="form-group">{{content}}{{error}}</div></div>'
									]
								]);		
							?>
						</td>
					</tr>

					<tr>
						<th>Address Proof Image</th>
						<td>
							<?php
								$rootPath	=	WWW_ROOT.'uploads'.DS.'pan_image'.DS;
								$panImage	=	!empty($bank->image) ? $bank->image : '';
								if(!empty($panImage) && file_exists($rootPath.$panImage)) { ?>
									<a href="<?php echo SITE_URL.'uploads/pan_image/'.$panImage; ?>" download>
										<?php echo $this->Html->image('../uploads/pan_image/'.$panImage,['class'=>'img','alt'=>'Address Proof','style'=>'width:100px;height:60px']); ?>
									</a>
								<?php } else {
									echo $this->Html->image('no_image.png',['class'=>'img','alt'=>'Address Proof','style'=>'width:100px;height:60px']);
								}
							?>
							<input type="file" name="aadhar_image_upload">
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