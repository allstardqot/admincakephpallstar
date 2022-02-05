

<?php 
    if(!isset($app) || !$app || $app==2) {
?>

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
                                <?php
                                    echo (isset($pagecontent->title)) ? $pagecontent->title : '';
                                ?>
                            </h1>
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
            <span>
                <?php
                    echo (isset($pagecontent->title)) ? $pagecontent->title : '';
                ?>
            </span>
        </li>
    </ul>
</div>
<?php
    }
?>

<div class="tm-bottom-a-box  ">
    <div class="uk-container uk-container-center">
        <section id="tm-bottom-a" class="tm-bottom-a uk-grid" data-uk-grid-match="{target:'> div > .uk-panel'}"
            data-uk-grid-margin="">

            <div class="uk-width-1-1 uk-row-first">
                <div class="uk-panel">
                    <div class="about-team-page-top-wrap">
                        <div class="uk-grid">
                            <div class="uk-width-large-10-10 uk-width-small-1-1" style="min-height:460px;">
                                
                                
                                <div class="top-title">
                                    <?php 
                                        if(!isset($app) || !$app || $app==2) {
                                    ?>
                                        <h2>
                                            <?php
                                                echo (isset($pagecontent->title)) ? $pagecontent->title : '';
                                            ?>
                                        </h2>
                                    <?php
                                        } else 
                                    ?>
                                </div>
                                
                                <?php echo $pagecontent->content; ?>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>