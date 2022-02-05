<div class="tm-top-a-box tm-full-width tm-box-bg-1 ">
            <div class="uk-container uk-container-center">
                <section id="tm-top-a" class="tm-top-a uk-grid uk-grid-collapse" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin="">

                    <div class="uk-width-1-1 uk-row-first">
                        <div class="uk-panel">
                            <div class="uk-cover-background uk-position-relative head-wrap" style="height: 290px; background-image: url('<?php echo SITE_URL; ?>assets/images/head-bg.jpg');">
                                <img class="uk-invisible" src="<?php echo SITE_URL; ?>assets/images/head-bg.jpg" alt="" height="290" width="1920">
                                <div class="uk-position-cover uk-flex uk-flex-center head-title">
                                    <h1>Contact</h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>

        <div class="uk-container uk-container-center alt">
            <ul class="uk-breadcrumb">
                <li>
                    <?php 
                        echo $this->Html->link(
                            'HOME',
                            array('controller' => 'Home', 'action' => 'index'),
                            array('class' => '', 'escape' => false)
                        );
                    ?>
                </li>
                <li class="uk-active">
                    <span>Contact</span>
                </li>
            </ul>
        </div>

        <div class="tm-bottom-a-box  ">
            <div class="uk-container uk-container-center">
                <section id="tm-bottom-a" class="tm-bottom-a uk-grid" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin="">
                    <div class="uk-width-1-1 uk-row-first">
                        <div class="uk-panel">
                            <div class="contact-page">
                                <div class="uk-grid">
                                    <div class="uk-width-1-1">
                                        <div class="contact-title">
                                            <h2>Contact Us</h2>
                                        </div>
                                        <!-- <div class="contact-text">Aenean aliquam, dolor eu lacinia pellentesque, dui arcu condimentum nisl, quis sollicitudin mi lorem quis leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quis sapien a ante rutrum pulvinar quis ac tellus. Proin facilisis dui at mollis tincidunt. Sed dignissim orci non arcu luctus pretium. Donec at ex aliquet, porttitor lacus eget, ullamcorper quam. Integer pellentesque egestas arcu, nec molestie leo sollicitudin et</div> -->
                                    </div>
                                    <div class="uk-width-1-1">
                                        <div class="contact-socials-wrap">
                                            <ul class="contact-socials">
                                                <li><a href="<?php echo FB_SOCIAL_PAGE; ?>" target="<?php echo FB_SOCIAL_PAGE; ?>"><i class="uk-icon-facebook"></i></a></li>
                                                <li><a href="<?php echo TW_SOCIAL_PAGE; ?>" target="<?php echo TW_SOCIAL_PAGE; ?>"><i class="uk-icon-twitter"></i></a></li>
                                                <li><a href="<?php echo YT_SOCIAL_PAGE; ?>" target="<?php echo YT_SOCIAL_PAGE; ?>"><i class="uk-icon-youtube"></i></a></li>
                                                <li><a href="<?php echo IG_SOCIAL_PAGE; ?>" target="<?php echo IG_SOCIAL_PAGE; ?>"><i class="uk-icon-instagram"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>

        <?php echo $this->element('contact_form'); ?>