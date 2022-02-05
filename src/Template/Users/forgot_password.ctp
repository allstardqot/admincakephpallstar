<style>
	.va-latest-middle{
		height: 265px !important;
	}

	.error-message{
		font-size: 11px;
    	color: #ef5217;
	}
</style>

<div class="tm-top-a-box tm-full-width tm-box-bg-1 ">
    <div class="uk-container uk-container-center">
        <section id="tm-top-a" class="tm-top-a uk-grid uk-grid-collapse"
            data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin="">
            <div class="uk-width-1-1 uk-row-first">
                <div class="uk-panel">
                    <div class="uk-cover-background uk-position-relative head-wrap"
                        style="height: 290px; background-image: url('<?php echo SITE_URL; ?>assets/images/head-bg.jpg')">
                        <!-- <img class="uk-invisible" src="<?php echo SITE_URL; ?>assets/images/head-bg.jpg" alt="" height="290" width="1920"> -->
                        <div class="slides">
                            <ul> <!-- slides -->
                                <li><img src="<?php echo SITE_URL; ?>assets/images/grheadbg.jpg" alt="image01" />
                                   
                                </li>
                                <li><img src="<?php echo SITE_URL; ?>assets/images/crheadbg.jpg" alt="image02" />
                                    
                                </li>
                                <li><img src="<?php echo SITE_URL; ?>assets/images/head-bg1.jpg" alt="image03" />
                                   
                                </li>
                               
                            </ul>
                        </div>
                        <div class="uk-position-cover uk-flex uk-flex-center head-title">
                            <h1>
                                Reset Password
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<div class="tm-top-b-box tm-full-width  ">
            <div class="uk-container uk-container-center">
                <section id="tm-top-b" class="tm-top-b uk-grid" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin="">

                    <div class="uk-width-1-1">
                        <div class="uk-panel">

                            <div class="va-latest-wrap">
                                <div class="uk-container uk-container-center ">
                                    <div class="va-latest-top">
                                        <h3>Reset <span>Password</span></h3>
                                        <div class="tournament">
                                            <address><!-- Vivamus hendrerit, tortor sed luctus maximus, nunc urna hendrerit nibh, sit amet efficitur libero lorem quis mauris. Nunc a pulvinar lectus. Pellentesque aliquam justo ut rhoncus lobortis.  --></address> </div>
                                        
                                    </div>
                                </div>
                                <div class="va-latest-middle uk-flex uk-flex-middle text-center">
                                    <div class="uk-container uk-container-center">
                                        <div class="uk-grid uk-flex uk-flex-middle text-center">
											<?php echo $this->Form->create($user); ?>
                                                <div class="form-group ">
													<?php
														echo $this->Form->input('password',["type" => "password", 'escape'=>false,'class' => 'form-control','label'=>false, 'placeholder' => __('New password')]);
													?>
                                                </div>
												<small>Password must be of minimum 6 characters with 1 number.</small>
												<div class="form-group"  style="margin-top:20px;">
													<?php
														echo $this->Form->input('confirm_password',["type" => "password", 'escape'=>false,'class' => 'form-control','label'=>false, 'placeholder' => __('Confirm password')]);
													?>
												</div>
												<div style="margin-top:20px;">
													<button type="submit" class="btn send_btn">Submit</button>
												</div>
											<?php echo $this->Form->end(); ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="uk-container uk-container-center">
                                    <div class="va-latest-bottom">
                                     

                                        <div class="uk-grid">
                                            <div class="uk-width-1-1">
                                               <!--  <div class="btn-wrap uk-container-center">
                                                    <a class="read-more" href="results.html">More Info</a>
                                                </div> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </section>
            </div>
        </div>