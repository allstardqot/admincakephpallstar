<div class="login-box">
	<div class="login-logo">
		<a href="/admin">
			<?php
			echo $this->Html->image('../dist/img/adminlogo.png',['escape'=>false, 'class'=>'img-fluid']);
		?>
		</a>
		
	</div>
	<!-- /.login-logo -->
	<div class="card">
		<div class="card-body login-card-body">
			<?php echo $this->Flash->render(); ?>
			<p class="login-box-msg">Forgot Password</p>
			<?php echo $this->Form->create($user,['id'=>"adminLogin"]); ?>
			<div class="form-group has-feedback">
				<?php echo $this->Form->input('email',['label'=>false,'placeholder'=>'Email','class'=>'form-control','type'=>'email']); ?>
			</div>
			<div class="row">
				<div class="col-4">
					<button id="sndMail" type="submit" class="btn btn-primary btn-block btn-flat">Send Email</button>
				</div>
				<div class="col-4">
					<?php echo $this->Html->link('Sign In',['action'=>'login'],['class'=>'btn btn-primary','escape'=>false]); ?>
				</div>
			</div>
			<?php echo $this->Form->end();?>
			<p class="mb-1">
				
			</p>
		</div>
		<!-- /.login-card-body -->
	</div>
</div>
<script>
	$(document).ready(function(){
		$('#adminLogin').on('submit',function(){
			$('#sndMail').attr('disabled',true);
		});
	});
</script>