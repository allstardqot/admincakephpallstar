<div class="login-box">
	<div class="login-logo">
		<a href="<?php echo SITE_URL."admin" ?>"><img style="width:25%;" src="<?php echo SITE_URL.'webroot/dist/img/adminlogo.png' ?>"></a>
	</div>
	<!-- /.login-logo -->
	<div class="card">
		<div class="card-body login-card-body">
			<?php echo $this->Flash->render(); ?>
			<p class="login-box-msg">Sign in to start your session</p>
			<?php echo $this->Form->create($user,['']);?>
			<div class="form-group has-feedback">
				<?php echo $this->Form->input('email',['label'=>false,'placeholder'=>'Please enter email','class'=>'form-control','type'=>'email']); ?>
			</div>
			<div class="form-group has-feedback">
				<?php echo $this->Form->input('password',['label'=>false,'placeholder'=>'Please enter password','class'=>'form-control','type'=>'password']); ?>
			</div>
			<div class="row">
				<div class="col-8">
					<div class="checkbox icheck">
						<label>
							<?=$this->Form->input('remember',['label'=>' Remember Me','type'=>'checkbox'])?>
						</label>
					</div>
				</div>
				<!-- /.col -->
				<div class="col-4">
					<button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
				</div>
				<!-- /.col -->
			</div>
			<?php echo $this->Form->end();?>
			<p class="mb-1">
				<?php echo $this->Html->link('I forgot my password',['controller'=>'users','action'=>'forgotPassword'],['escape'=>false]); ?>
			</p>
		</div>
		<!-- /.login-card-body -->
	</div>
</div>