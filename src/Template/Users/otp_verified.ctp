<section class="body_outer">
    <div class="">
      <div class="app-container appContainer_6475d">
        <div class="appBgImage_fd065"></div>
        <div class="home home_fed50">
          <div class="app-toolbar appToolbar_1ea26">
            <div>
              <div class="homeToolbar_7b795">
                <div class="align-center">
                  <div class="headerElement_af700 left_a1d14"></div>
                  <a href="#" class="lgn_heading">OTP</a>
                  <div class="headerElement_af700 right_c98d9"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="homeListContainer_55b59">
            <div class="login_body">
            	<div class="form-field login_form">
            		<?php echo $this->Form->create($user, array('url' => array('controller' => 'users', 'action' => 'otp-verified'))); ?>
            			<div class="form-group">
            				<label>OTP</label>
            				<span class="input_outer">
                				<?php echo $this->Form->input("otp", array("type" => "text", "div" => false, "label" => false, 'class' => 'form-control', 'placeholder' => __(''))); ?>
                			</span>
            			</div>
            			<div class="submit_login">
            				<button>VERIFY OTP</button>
                    <p>Did not receive the OTP? <?php echo $this->Html->link(
				     'Resend', ['controller' => 'users', 'action' => 'Resend'], ['escape' => false]
			         );?></p>
            			</div>
            		<?php echo $this->Form->end(); ?>
            	</div>
            </div>
          </div>
          <div class="tab_menu">
            <ul class="menulist">
              <li class="active">
                <a href="#"><i class="fas fa-home"></i> <span>Home</span></a>
              </li>
              <li>
                <a href="#"><i class="fas fa-trophy"></i> <span>Contest</span></a>
              </li>
              <li>
                <a href="#"><i class="fas fa-user"></i> <span>Profile</span></a>
              </li>
              <li>
                <a href="#"><i class="fas fa-ellipsis-h"></i> <span>More</span></a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>
<script>
	$(document).ready(function(){
		$('body').addClass('inner_bodyPage Login');
	});
</script>