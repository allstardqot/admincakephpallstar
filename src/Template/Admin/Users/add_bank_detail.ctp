<?php
	//$panDetail	=	$this->Custom->getPanAadhrDetails($userId);
	//$bankDetail	=	$this->Custom->getBankDetails($userId);
?>
<?php use Cake\Core\Configure; ?>
<div class="modal-dialog">
	<div class="modal-content">
		
		<?php if($type == 'bank_detail') { ?>
			<div class="modal-header">
				<h4 class="modal-title">Bank Account</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<?php echo $this->Form->create($bank, ['type' => 'file', 'novalidate' => true,'id'=>'','type' => 'file']); 
				echo $this->Form->hidden('uset_id',[ 'value'=>$userId ]);
				echo $this->Form->hidden('type',[ 'value'=>$type ]);
				echo $this->Form->hidden('page',[ 'value'=>$page ]);
				if ( $action == 'editBankDetail' ){
					echo $this->Form->hidden('action',[ 'value'=>'updateBankDetail' ]);
				} else {
					echo $this->Form->hidden('action',[ 'value'=>'saveBankDetail' ]);
				}
			?>
			<div class="modal-body">
				<table class="table table-bordered responsive">
					<tr>
						<th>Account Number</th>
						<td>
							<?php 
								//echo !empty($bankDetail->bank_detail) ? $bankDetail->bank_detail->account_number : ''; 
								echo $this->Form->input('account_number', ['escape'=>false,'class' => 'form-control','label'=>false, 'placeholder' => __('Account Number'),
									'templates' => [
									'inputContainer' => '<div class="col-sm-12 col-md-12"><div class="form-group">{{content}}</div></div>',
									'inputContainerError' => '<div class="col-sm-12 col-md-12"><div class="form-group">{{content}}{{error}}</div></div>'
									]
								]);
							?>
						</td>
					</tr>
					<tr>
						<th>IFSC Code</th>
						<td>
							<?php 
								//echo !empty($bankDetail->bank_detail) ? $bankDetail->bank_detail->ifsc_code : ''; 
								echo $this->Form->input('ifsc_code', ['escape'=>false,'class' => 'form-control','label'=>false, 'placeholder' => __('IFSC Code'),
									'templates' => [
									'inputContainer' => '<div class="col-sm-12 col-md-12"><div class="form-group">{{content}}</div></div>',
									'inputContainerError' => '<div class="col-sm-12 col-md-12"><div class="form-group">{{content}}{{error}}</div></div>'
									]
								]);
							?>
						</td>
					</tr>
					<tr>
						<th>Branch Name</th>
						<td>
							<?php 
								//echo !empty($bankDetail->bank_detail) ? $bankDetail->bank_detail->branch : ''; 
								echo $this->Form->input('branch', ['escape'=>false,'class' => 'form-control','label'=>false, 'placeholder' => __('Branch Name'),
									'templates' => [
									'inputContainer' => '<div class="col-sm-12 col-md-12"><div class="form-group">{{content}}</div></div>',
									'inputContainerError' => '<div class="col-sm-12 col-md-12"><div class="form-group">{{content}}{{error}}</div></div>'
									]
								]);		
							?>
						</td>
					</tr>
					<tr>
						<th>Bank Name</th>
						<td>
							<?php 
								//echo !empty($bankDetail->bank_detail) ? $bankDetail->bank_detail->bank_name : ''; 
								echo $this->Form->input('bank_name', ['escape'=>false,'class' => 'form-control','label'=>false, 'placeholder' => __('Bank Name'),
									'templates' => [
									'inputContainer' => '<div class="col-sm-12 col-md-12"><div class="form-group">{{content}}</div></div>',
									'inputContainerError' => '<div class="col-sm-12 col-md-12"><div class="form-group">{{content}}{{error}}</div></div>'
									]
								]);	
							?>
						</td>
					</tr>
					<tr>
						<th>Bank Image</th>
						<td>
							<?php
								$rootPath	=	WWW_ROOT.'uploads'.DS.'bank_proof'.DS;
								$image		=	!empty($bank->bank_image) ? $bank->bank_image : '';
								if(!empty($image) && file_exists($rootPath.$image)) {
									echo $this->Html->image('../uploads/bank_proof/'.$image,['class'=>'img','alt'=>'Bank Image','style'=>'width:100px;height:60px']);
								} else {
									echo $this->Html->image('no_image.png',['class'=>'img','alt'=>'Bank Image','style'=>'width:100px;height:60px']);
								}
							?>
							<input type="file" name="bank_image_upload">
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