<div class="tm-bottom-b-box tm-full-width  ">
    <div class="uk-container uk-container-center">
        <section id="tm-bottom-b" class="tm-bottom-b uk-grid uk-grid-collapse"
            data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin="">
            <div class="uk-width-1-1">
                <div class="uk-panel">
                    <div class="map-wrap">
                        <div class="contact-form-wrap uk-flex">
                            <div class="uk-container uk-container-center uk-flex-item-1">
                                <div class="uk-grid  uk-grid-collapse uk-flex-item-1 uk-height-1-1 uk-nbfc">
                                    <div class="uk-width-5-10 contact-left uk-vertical-align contact-left-wrap">
                                        <div class="contact-info-lines uk-vertical-align-middle">
                                            <!-- <div class="item phone">
                                                <span class="icon"><i class="uk-icon-phone"></i></span>
                                                <a href="tel:917011728180">+91 70117 28180</a>
                                            </div> -->
                                            <div class="item mail">
                                                <span class="icon"><i class="uk-icon-envelope"></i></span>
                                                <a href="mailto:contact@lions11.com">contact@lions11.com</a>
                                            </div>
                                            <div class="item location">
                                                <span class="icon"><i class="uk-icon-map-marker"></i></span>
                                                3rd floor Goodearth Business Bay Gurgaon Haryana
                                            </div>
                                        </div>
                                    </div>
                                    <div class="uk-width-medium-5-10 uk-width-small-1-1 contact-right-wrap">
                                        <div class="contact-form uk-height-1-1">
                                            <div class="uk-position-cover uk-flex uk-flex-column uk-flex-center">
                                                <div class="contact-form-title">
                                                    <h2>Get in touch</h2>
                                                </div>
                                                <div id="aiContactSafe_form_1">
                                                    <div class="aiContactSafe" id="aiContactSafe_mainbody_1">
                                                        <div class="contentpaneopen">
                                                            <div id="aiContactSafeForm">
                                                                <?php echo $this->Form->create('', ['id' => '', 'name' => 'adminForm_1', 'type' => 'file', 'novalidate' => true]); ?>
                                                                    <div id="displayAiContactSafeForm_1">
                                                                        <div class="aiContactSafe"
                                                                            id="aiContactSafe_contact_form">
                                                                            <div class="aiContactSafe"
                                                                                id="aiContactSafe_info"></div>

                                                                            <div class="aiContactSafe_row" id="aiContactSafe_row_aics_name">
                                                                                <div
                                                                                    class="aiContactSafe_contact_form_field_label_left">
                                                                                    <span class="aiContactSafe_label" id="aiContactSafe_label_aics_name">
                                                                                        <label for="aics_name">Name</label>
                                                                                    </span>&nbsp;
                                                                                    <label class="required_field">*</label>
                                                                                </div>
                                                                                <div class="aiContactSafe_contact_form_field_right">
                                                                                    <?php
                                                                                        echo $this->Form->input('name',['escape'=>false,'class' => 'textbox','label'=>false, 'placeholder' => __('Name')]);
                                                                                    ?>
                                                                                </div>
                                                                            </div>

                                                                            <div class="aiContactSafe_row" id="aiContactSafe_row_aics_email">
                                                                                <div class="aiContactSafe_contact_form_field_label_left">
                                                                                    <span class="aiContactSafe_label" id="aiContactSafe_label_aics_email">
                                                                                        <label for="aics_email">E-mail</label>
                                                                                    </span>&nbsp;
                                                                                    <label class="required_field">*</label>
                                                                                </div>
                                                                                <div class="aiContactSafe_contact_form_field_right">
                                                                                        <?php
                                                                                            echo $this->Form->input('email',['type'=>'text','escape'=>false,'class' => 'email','label'=>false, 'placeholder' => __('Email')]);
                                                                                        ?>
                                                                                </div>
                                                                            </div>

                                                                            
                                                                            <div class="aiContactSafe_row" id="aiContactSafe_row_aics_message">
                                                                                <div class="aiContactSafe_contact_form_field_label_left">
                                                                                    <span class="aiContactSafe_label" id="aiContactSafe_label_aics_message">
                                                                                    <label for="aics_message">Message</label></span>&nbsp;
                                                                                    <label class="required_field">*</label>
                                                                                </div>
                                                                                <div
                                                                                    class="aiContactSafe_contact_form_field_right">
                                                                                        <?php
                                                                                            echo $this->Form->input('message',['type'=>'textarea','escape'=>false,'class' => 'editbox','label'=>false, 'placeholder' => __('Message')]);
                                                                                        ?>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <br>
                                                                    <br>
                                                                    <div id="aiContactSafeBtns">
                                                                        <div id="aiContactSafeButtons_center"
                                                                            style="clear:both; display:block; text-align:center;">
                                                                            <table
                                                                                style="margin-left:auto; margin-right:auto;">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <div
                                                                                                id="aiContactSafeSend_loading_1">
                                                                                                &nbsp;</div>
                                                                                        </td>
                                                                                        <td id="td_aiContactSafeSendButton">
                                                                                            <input
                                                                                                id="aiContactSafeSendButton"
                                                                                                value="Send"
                                                                                                type="submit">
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                    <br>
                                                                    <?php echo $this->Form->end(); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                        <script>
                        window.map = false;



                        function initialize() {
                            var myLatlng = new google.maps.LatLng(28.094080, 76.991920);

                            var mapOptions = {
                                zoom: 16,
                                center: myLatlng,
                                mapTypeId: google.maps.MapTypeId.ROADMAP,
                                scrollwheel: false,

                                streetViewControl: false,
                                overviewMapControl: false,
                                mapTypeControl: false

                            };

                            window.map = new google.maps.Map(document.getElementById('map'), mapOptions);

                        }

                        //google.maps.event.addDomListener(window, 'load', initialize);
                        </script>
                        <div id="map" style="background:#4a4a48"></div>

                    </div>
                </div>
            </div>
        </section>
    </div>
</div>