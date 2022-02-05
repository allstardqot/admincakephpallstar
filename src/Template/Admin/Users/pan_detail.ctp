<?php
	$panDetail		=	$this->Custom->getPanAadhrDetails($userId);
	$bankDetail		=	$this->Custom->getBankDetails($userId);
	$aadharDetail	=	$this->Custom->getAadharDetails($userId);
?>
<?php use Cake\Core\Configure; ?>
<div class="modal-dialog">
	<div class="modal-content">

		<?php if($type == 'pan_card') { ?>
			<div class="modal-header">
				<h4 class="modal-title">Pan Card</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<table class="table table-bordered responsive">
					<tr>
						<th>Full Name</th>
						<td><?php echo h($panDetail->first_name.' '.$panDetail->last_name); ?></td>
					</tr>
					<tr>
						<th>Pan Number</th>
						<td><?php echo !empty($panDetail->pen_aadhar_card) ? $panDetail->pen_aadhar_card->pan_card : ''; ?></td>
					</tr>
					<tr>
						<th>Pan Image</th>
						<td>
							<?php
								$rootPath	=	WWW_ROOT.'uploads'.DS.'pan_image'.DS;
								$panImage	=	!empty($panDetail->pen_aadhar_card) ? $panDetail->pen_aadhar_card->pan_image : '';
								if(!empty($panImage) && file_exists($rootPath.$panImage)) { ?>
									<a href="<?php echo SITE_URL.'uploads/pan_image/'.$panImage; ?>" download>
										<?php echo $this->Html->image('../uploads/pan_image/'.$panImage,['class'=>'img','alt'=>'Pan Image','style'=>'width:100px;height:60px']); ?>
									</a>
								<?php } else {
									echo $this->Html->image('no_image.png',['class'=>'img','alt'=>'Pan Image','style'=>'width:100px;height:60px']);
								}
							?>
						</td>
					</tr>
					<tr>
						<th>Name On Pan</th>
						<td><?php echo !empty($panDetail->pen_aadhar_card) ? $panDetail->pen_aadhar_card->pan_name : ''; ?></td>
					</tr>
					<tr>
						<th>Date of Birth</th>
						<td>
							<?php echo !empty($panDetail->pen_aadhar_card) ? date('Y-m-d',strtotime($panDetail->pen_aadhar_card->date_of_birth)) : ''; ?>
						</td>
					</tr>
					<tr>
						<th>State</th>
						<td><?php echo !empty($panDetail->pen_aadhar_card) ? $panDetail->pen_aadhar_card->state : ''; ?></td>
					</tr>
					<tr>
						<th>Status</th>
						<td>
							<?php
								$class	=	Configure::read('verified_status_class.'.$panDetail->pen_aadhar_card->is_verified);
								echo !empty($panDetail->pen_aadhar_card) ? '<span class="label label-'.$class.'">'.Configure::read('verified_status.'.$panDetail->pen_aadhar_card->is_verified).'<span>' : '';
							?>
						</td>
					</tr>
				</table>
			</div>
			<div class="modal-footer">
				<?php
					if(!empty($panDetail->pen_aadhar_card) && $panDetail->pen_aadhar_card->is_verified == 0) {
						// echo $this->Html->link('Verify', ['controller'=>'users','action'=>'verifyPan',$panDetail->id,$page],['class'=>'btn btn-success']);
						// echo $this->Html->link('Cancel Request', ['controller'=>'users','action'=>'cancelPan',$panDetail->id,$page],['class'=>'btn btn-danger']);
						echo $this->Html->link('Verify', 'javascript:void(0)',['data-id'=>$panDetail->id,'class'=>'btn btn-success verify_pan']);
						echo $this->Html->link('Cancel Request', 'javascript:void(0)',['data-id'=>$panDetail->id,'class'=>'btn btn-danger cancel_pan']);
					}
					echo $this->Html->link('Edit', 'javascript:void(0)',['data-id'=>$panDetail->id,'class'=>'btn btn-primary edit_pan']);
				?>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		<?php } ?>

					

		<?php 
		//echo $type; die;
		if($type == 'aadhar_detail') { ?>
			<div class="modal-header">
				<h4 class="modal-title">Address Proof</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<table class="table table-bordered responsive">
					<tr>
						<th>House no.</th>
						<td><?php echo !empty($aadharDetail->aadhar_card) ? $aadharDetail->aadhar_card->house_no : ''; ?></td>
					</tr>
					<tr>
						<th>Line 1</th>
						<td><?php echo !empty($aadharDetail->aadhar_card) ? $aadharDetail->aadhar_card->line1 : ''; ?></td>
					</tr>
					
					<tr>
						<th>Line 2</th>
						<td><?php echo !empty($aadharDetail->aadhar_card) ? $aadharDetail->aadhar_card->line2 : ''; ?></td>
					</tr>
					<tr>
						<th>City</th>
						<td><?php echo !empty($aadharDetail->aadhar_card) ? $aadharDetail->aadhar_card->city : ''; ?></td>
					</tr>
					<tr>
						<th>Pincode</th>
						<td><?php echo !empty($aadharDetail->aadhar_card) ? $aadharDetail->aadhar_card->pincode : ''; ?></td>
					</tr>
					<tr>
						<th>State</th>
						<td><?php echo !empty($aadharDetail->aadhar_card) ? $aadharDetail->aadhar_card->state : ''; ?></td>
					</tr>
					<tr>
						<th>Address Image</th>
						<td>
							<?php
								$rootPath	=	WWW_ROOT.'uploads'.DS.'pan_image'.DS;
								$panImage	=	!empty($aadharDetail->aadhar_card) ? $aadharDetail->aadhar_card->image : '';
								if(!empty($panImage) && file_exists($rootPath.$panImage)) { ?>
									<a href="<?php echo SITE_URL.'uploads/pan_image/'.$panImage; ?>" download>
										<?php echo $this->Html->image('../uploads/pan_image/'.$panImage,['class'=>'img','alt'=>'Address Image','style'=>'width:100px;height:60px']); ?>
									</a>
								<?php } else {
									echo $this->Html->image('no_image.png',['class'=>'img','alt'=>'Address Image','style'=>'width:100px;height:60px']);
								}
							?>
						</td>
					</tr>
					<tr>
						<th>Status</th>
						<td>
							<?php
								$class	=	Configure::read('verified_status_class.'.$aadharDetail->aadhar_card->is_verified);
								echo !empty($aadharDetail->aadhar_card) ? '<span class="label label-'.$class.'">'.Configure::read('verified_status.'.$aadharDetail->aadhar_card->is_verified).'<span>' : '';
							?>
						</td>
					</tr>
				</table>
			</div>

			<div class="modal-footer">
				<?php
					if(!empty($aadharDetail->aadhar_card) && $aadharDetail->aadhar_card->is_verified == 0) {
						// echo $this->Html->link('Verify', ['controller'=>'users','action'=>'verifyPan',$aadharDetail->id,$page],['class'=>'btn btn-success']);
						// echo $this->Html->link('Cancel Request', ['controller'=>'users','action'=>'cancelPan',$aadharDetail->id,$page],['class'=>'btn btn-danger']);
						echo $this->Html->link('Verify', 'javascript:void(0)',['data-id'=>$aadharDetail->id,'class'=>'btn btn-success verify_aadhar']);
						echo $this->Html->link('Cancel Request', 'javascript:void(0)',['data-id'=>$aadharDetail->id,'class'=>'btn btn-danger cancel_aadhar']);
					}
					echo $this->Html->link('Edit', 'javascript:void(0)',['data-id'=>$aadharDetail->id,'class'=>'btn btn-primary edit_aadhar']);
				?>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		<?php } ?>



		<?php if($type == 'bank_detail') { ?>
			<div class="modal-header">
				<h4 class="modal-title">Bank Account</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<table class="table table-bordered responsive">
					<tr>
						<th>Full Name</th>
						<td><?php echo h($bankDetail->first_name.' '.$bankDetail->last_name); ?></td>
					</tr>
					<tr>
						<th>Account Number</th>
						<td>
							<?php echo !empty($bankDetail->bank_detail) ? $bankDetail->bank_detail->account_number : ''; ?>
						</td>
					</tr>
					<tr>
						<th>IFSC Code</th>
						<td>
							<?php echo !empty($bankDetail->bank_detail) ? $bankDetail->bank_detail->ifsc_code : ''; ?>
						</td>
					</tr>
					<tr>
						<th>Branch Name</th>
						<td>
							<?php echo !empty($bankDetail->bank_detail) ? $bankDetail->bank_detail->branch : ''; ?>
						</td>
					</tr>
					<tr>
						<th>Bank Name</th>
						<td>
							<?php echo !empty($bankDetail->bank_detail) ? $bankDetail->bank_detail->bank_name : ''; ?>
						</td>
					</tr>
					<tr>
						<th>Bank Image</th>
						<td>
							<?php
								$rootPath	=	WWW_ROOT.'uploads'.DS.'bank_proof'.DS;
								$image		=	!empty($bankDetail->bank_detail) ? $bankDetail->bank_detail->bank_image : '';
								if(!empty($image) && file_exists($rootPath.$image)) {
									echo $this->Html->image('../uploads/bank_proof/'.$image,['class'=>'img','alt'=>'Bank Image','style'=>'width:100px;height:60px']);
								} else {
									echo $this->Html->image('no_image.png',['class'=>'img','alt'=>'Bank Image','style'=>'width:100px;height:60px']);
								}
							?>
						</td>
					</tr>
					<tr>
						<th>Status</th>
						<td>
							<?php
								$class	=	Configure::read('verified_status_class.'.$bankDetail->bank_detail->is_verified);
								echo !empty($bankDetail->bank_detail) ? '<span class="label label-'.$class.'">'.Configure::read('verified_status.'.$bankDetail->bank_detail->is_verified).'<span>' : '';
							?>
						</td>
					</tr>
				</table>
			</div>
			<div class="modal-footer">
				<?php
					if(!empty($bankDetail->bank_detail) && $bankDetail->bank_detail->is_verified == 0) {
						// echo $this->Html->link('Verify', ['controller'=>'users','action'=>'verifyBank',$bankDetail->id,$page],['class'=>'btn btn-success']);
						// echo $this->Html->link('Cancel Request', ['controller'=>'users','action'=>'cancelBank',$bankDetail->id,$page],['class'=>'btn btn-danger']);
						echo $this->Html->link('Verify', 'javascript:void(0)',['data-id'=>$bankDetail->id,'class'=>'btn btn-success verify_bank']);
						echo $this->Html->link('Cancel Request', 'javascript:void(0)',['data-id'=>$bankDetail->id,'class'=>'btn btn-danger cancel_bank']);
					}
					echo $this->Html->link('Edit', 'javascript:void(0)',['data-id'=>$bankDetail->id,'class'=>'btn btn-primary edit_bank']);
				?>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		<?php } ?>


		<?php if($type == 'aadhar') { ?>
			<div class="modal-header">
				<h4 class="modal-title">Aadhar Card</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<table class="table table-bordered responsive">
				<tr>
					<th>Full Name</th>
					<td><?php echo h($panDetail->first_name.' '.$panDetail->last_name); ?>
				</tr>
				<tr>
					<th>Aadhar Number</th>
					</td><td><?php echo !empty($panDetail->pen_aadhar_card) ? h($panDetail->pen_aadhar_card->aadhar_card) : ''; ?></td>
				</tr>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		<?php } ?>
	</div>
</div>
<style>
	.label-warning {
		padding: 2px 5px;
		border-radius: 4px;
	}
</style>