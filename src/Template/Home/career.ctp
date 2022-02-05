<div class="tm-top-a-box tm-full-width tm-box-bg-1 ">
    <div class="uk-container uk-container-center">
        <section id="tm-top-a" class="tm-top-a uk-grid uk-grid-collapse"
            data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin="">

            <div class="uk-width-1-1 uk-row-first">
                <div class="uk-panel">
                    <div class="uk-cover-background uk-position-relative head-wrap"
                        style="height: 290px; background-image: url('<?php echo SITE_URL; ?>assets/images/head-bg.jpg');">
                        <img class="uk-invisible" src="<?php echo SITE_URL; ?>assets/images/head-bg.jpg" alt=""
                            height="290" width="1920">
                        <div class="uk-position-cover uk-flex uk-flex-center head-title">
                            <h1>Career</h1>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<div class="uk-container uk-container-center alt">
    <ul class="uk-breadcrumb">
        <li><a href="index.html">Home</a></li>
        <li class="uk-active"><span>Career</span></li>
    </ul>
</div>

<div class="uk-container uk-container-center mb-3">
    <div id="tm-middle" class="tm-middle uk-grid" data-uk-grid-match="" data-uk-grid-margin="">
        <div class="tm-main uk-width-medium-4">
            <main id="tm-content" class="tm-content">
                <div class="match-list-wrap">

                    <?php 
                        if(!empty($jobs)){
                            foreach($jobs AS $job){
                    ?>
                                <div class="match-list-item contentwhite">
                                    <h3><?php echo $job->title; ?></h3>
                                    <?php echo $job->content; ?>
                                    <?php 
                                        echo $this->Html->link(
                                            'Apply',
                                            array('controller' => 'Home', 'action' => 'apply',$job->id),
                                            array('class' => 'btn btn-primary', 'escape' => false)
                                        );
                                    ?>
                                </div>
                    <?php
                            }
                        }
                    ?>
                </div>
            </main>
        </div>
    </div>
</div>