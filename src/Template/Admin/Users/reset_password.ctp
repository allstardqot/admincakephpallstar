<div class="login-box">
	<div class="login-logo">
		<?php
			echo $this->Html->link($this->Html->image('../dist/img/adminlogo.png'),['controller'=>'users','action'=>'login'],['escape'=>false]);
		?>
	</div>
	<div class="card">
		<div class="card-body login-card-body">
			<?php echo $this->Flash->render(); ?>
			<p class="login-box-msg">Reset Password</p>
			<?php echo $this->Form->create($userData,['id'=>'adminLogin']); ?>
			<div class="form-group has-feedback">
				<?php echo $this->Form->input('password',['label'=>false,'placeholder'=>'New Password','class'=>'form-control','type'=>'password']); ?>
			</div>
			<div class="row">
				<div class="col-4">
					<button type="submit" class="btn btn-primary btn-flat" id="sndMail">Reset Password</button>
				</div>
			</div>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$('#adminLogin').on('submit',function(){
			$('#sndMail').attr('disabled',true);
		});
	});
</script>