<style>
    .error-message{
        color: crimson;
        font-size: 12px;
    }
</style>
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
                            <h1>
                                Apply a Job
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
        <li><a href="index.html">Home</a></li>
        <li class="uk-active"><span>Apply a Job</span></li>
    </ul>
</div>

<div class="uk-container uk-container-center">
    <div id="tm-middle" class="tm-middle uk-grid" data-uk-grid-match="" data-uk-grid-margin="">
        <div class="tm-main uk-width-medium-1-1 uk-row-first">
            <main id="tm-content" class="tm-content">
                <div id="system-message-container"></div>

                <div class="jshop productfull" id="comjshop">

                    <div id="list_product_demofiles"></div>
                    <div class="jcomments_comment">
                        <div id="jc">
                            <div id="comments"></div>
                            <h3 class="title-bottom">Apply to join the super cool <span>Lions11 Family</span></h3>
                            <a id="addcomments" href="#addcomments"></a>
                            <!-- <form id="comments-form" name="comments-form"> -->
                            <?php echo $this->Form->create($jobfrm, ['type' => 'file', 'novalidate' => true,"id"=>"comments-form", "name"=>"comments-form"]); ?>
                                <input type="hidden" name="job_id" value="<?php echo $job_id; ?>"/>
                                <div class="uk-grid">
                                    <div class="uk-width-1-1 uk-width-large-1-2 uk-panel ">

                                        <p>
                                            <span>
                                                <!-- <label for="">Enter your Name*</label>
                                                <input id="comments-form-name" placeholder="Enter your Name" name="name" value=""  tabindex="1" type="text"> -->
                                                <?php
                                                    echo $this->Form->input('name', ['templates' => [
                                                        'inputContainer' => '{{content}}'
                                                    ],'tabindex'=>1,'id'=>'comments-form-name','escape'=>false,'div'=>false,'class' => 'form-control','label'=>'Enter your Name <span class="required">*</span>', 'placeholder' => __('Enter your Name')]);
                                                ?>
                                            </span>
                                        </p>
                                        
                                        <p>
                                            <span>
                                                <!-- <label for="">Phone*</label>
                                                <input id="comments-form-phone" placeholder="Phone" name="phone" value="" tabindex="3" type="text"> -->
                                                <?php
                                                    echo $this->Form->input('phone', ['templates' => [
                                                        'inputContainer' => '{{content}}'
                                                    ],'type'=>'text','tabindex'=>3,'id'=>'comments-form-phone','escape'=>false,'div'=>false,'class' => 'form-control','label'=>'Phone <span class="required">*</span>', 'placeholder' => __('Phone')]);
                                                ?>
                                            </span>
                                        </p>

                                        <p>
                                            <span>
                                                <?php
                                                    $marital_status = ['Single'=>'Single' ,'Married'=>'Married'];
                                                    echo $this->Form->label('Marital Status');
                                                    echo $this->Form->select('marital_status',$marital_status,['class' => 'w-100', 'label' => 'Marital Status <span class="required">*</span>', 'tabindex'=>5, 'empty' => __('Select')]);
                                                ?>
                                            </span>
                                        </p>

                                        <p>
                                            <span>
                                                <!-- <label for="">Current Designation *</label>
                                                <input id="comments-form-designation" placeholder="Current Designation" name="current_designation" value="" tabindex="7" type="text"> -->
                                                <?php
                                                    echo $this->Form->input('current_designation', ['templates' => [
                                                        'inputContainer' => '{{content}}'
                                                    ],'type'=>'text','tabindex'=>7,'id'=>'comments-form-designation','escape'=>false,'div'=>false,'class' => 'form-control','label'=>'Current Designation <span class="required">*</span>', 'placeholder' => __('Current Designation')]);
                                                ?>
                                            </span>
                                        </p>

                                        <p>
                                            <span>
                                                <?php
                                                    $job_opportunities = [
                                                            'Digital Marketing Manager- Paid Ads'=>'Digital Marketing Manager- Paid Ads',
                                                            'Operations Analyst'=>'Operations Analyst',
                                                            'Community Manager'=>'Community Manager',
                                                            'Customer Insights'=>'Customer Insights',
                                                    ];
                                                    echo $this->Form->label('Job Opportunities*');
                                                    echo $this->Form->select('job_opportunities',$job_opportunities,['class' => 'w-100', 'label' => 'Job Opportunities*', 'tabindex'=>9, 'empty' => __('Select')]);
                                                ?>
                                            </span>
                                        </p>

                                        <p>
                                            <span>
                                                <?php
                                                    echo $this->Form->input('reference1', ['templates' => [
                                                        'inputContainer' => '{{content}}'
                                                    ],'type'=>'text','tabindex'=>11,'id'=>'comments-form-file','escape'=>false,'div'=>false,'class' => 'w-100','label'=>'Reference Name 1', 'placeholder' => __('Reference Name 1')]);
                                                ?>
                                            </span>
                                        </p>

                                        <p>
                                            <span>
                                                <?php
                                                    echo $this->Form->input('phone1', ['templates' => [
                                                        'inputContainer' => '{{content}}'
                                                    ],'type'=>'text','tabindex'=>13,'id'=>'comments-form-file','escape'=>false,'div'=>false,'class' => 'w-100','label'=>'Phone Number', 'placeholder' => __('Phone Number')]);
                                                ?>
                                            </span>
                                        </p>

                                    </div>

                                    <div class="uk-width-1-1 uk-width-large-1-2 uk-panel uk-flex uk-flex-column">
                                        <p>
                                            <span>
                                                <?php
                                                    echo $this->Form->input('email', ['templates' => [
                                                        'inputContainer' => '{{content}}'
                                                    ],'type'=>'text','tabindex'=>2,'id'=>'comments-form-email','escape'=>false,'div'=>false,'class' => 'w-100','label'=>'Email <span class="required">*</span>', 'placeholder' => __('Email')]);
                                                ?>
                                            </span>
                                        </p>

                                        <p>
                                            <span>
                                                <?php
                                                    $gender = [
                                                        'Male'=>'Male',
                                                        'Female'=>'Female',
                                                    ];
                                                    echo $this->Form->label('Gender*');
                                                    echo $this->Form->select('gender',$gender,['class' => 'w-100', 'label' => 'Gender*', 'tabindex'=>4, 'empty' => __('Select')]);
                                                ?>
                                            </span>
                                        </p>

                                        <p>
                                            <span>
                                                <?php
                                                    $qualifications = [
                                                        'All'=>'All',
                                                        'Below Matric'=>'Below Matric',
                                                        '10th'=>'10th',
                                                        '10+2'=>'10+2',
                                                        'AMIE'=>'AMIE',
                                                        'B.Com'=>'B.Com',
                                                        'B.Tech (TEXTILE) (IIT DELHI)'=>'B.Tech (TEXTILE) (IIT DELHI)',
                                                        'B.Tech (UDCT, MUMBAI)'=>'B.Tech (UDCT, MUMBAI)',
                                                        'B.Text'=>'B.Text',
                                                        'B.Text Chem (other than UDCT)'=>'B.Text Chem (other than UDCT)',
                                                        'BA'=>'BA',
                                                        'BBA'=>'BBA',
                                                        'BE (Civil)'=>'BE (Civil)',
                                                        'BE (Comp)'=>'BE (Comp)',
                                                        'BE (Electrical)'=>'BE (Electrical)',
                                                        'BSc'=>'BSc',
                                                        'BSc.(Textile Chem)'=>'BSc.(Textile Chem)',
                                                        'MSc. Chem'=>'MSc. Chem',
                                                        'PhD'=>'PhD',
                                                        'Others'=>'Others',
                                                    ];
                                                    echo $this->Form->label('Qualifications*');
                                                    echo $this->Form->select('qualifications',$qualifications,['class' => 'w-100', 'label' => 'Qualifications*', 'tabindex'=>6, 'empty' => __('Select')]);
                                                ?>
                                            </span>
                                        </p>

                                        <p>
                                            <span>
                                                <?php
                                                    $experience = [
                                                        '1'=>'1',
                                                        '2'=>'2',
                                                        '3'=>'3',
                                                        '4'=>'4',
                                                        '5'=>'5',
                                                        '6'=>'6',
                                                        '7'=>'7',
                                                        '8'=>'8',
                                                        '9'=>'9',
                                                        '10'=>'10',
                                                        'morethan'=>'More than 10',
                                                    ];
                                                    echo $this->Form->label('Year of Experience*');
                                                    echo $this->Form->select('experience',$experience,['class' => 'w-100', 'label' => 'Year of Experience*', 'tabindex'=>8, 'empty' => __('Select')]);
                                                ?>
                                            </span>
                                        </p>

                                        <p>
                                            <span>
                                                <label for="">Upload your CV *</label>
                                                <input class="w-100" id="comments-form-file" placeholder="File" name="cv" value="" tabindex="10" type="file" accept="application/pdf,application/msword,
  application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                                                <span style="color:red;font-size:12px;">Only word file and pdf is allowed.</span>
                                            </span>
                                        </p>

                                        <p>
                                            <span>
                                                <?php
                                                    echo $this->Form->input('reference2', ['templates' => [
                                                        'inputContainer' => '{{content}}'
                                                    ],'type'=>'text','tabindex'=>12,'id'=>'comments-form-file','escape'=>false,'div'=>false,'class' => 'w-100','label'=>'Reference Name 2', 'placeholder' => __('Reference Name 2')]);
                                                ?>
                                            </span>
                                        </p>

                                        <p>
                                            <span>
                                                <?php
                                                    echo $this->Form->input('phone2', ['templates' => [
                                                        'inputContainer' => '{{content}}'
                                                    ],'type'=>'text','tabindex'=>14,'id'=>'comments-form-file','escape'=>false,'div'=>false,'class' => 'w-100','label'=>'Phone Number', 'placeholder' => __('Phone Number')]);
                                                ?>
                                            </span>
                                        </p>

                                        <div class="btnbar">
                                            <button class="btn btn-primary">Submit</button>
                                        </div>

                                    </div>
                                </div>
                                <div>
                                    <input name="object_id" value="6" type="hidden">
                                    <input name="object_group" value="com_jshopping" type="hidden">
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>