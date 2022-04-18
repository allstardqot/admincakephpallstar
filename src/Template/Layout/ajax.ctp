<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 * @var \App\View\AppView $this
 */

echo $this->fetch('content');
?>
<script src="<?= SITE_URL; ?>/webroot/js/admin/validation.js"></script>
<script>
    $(document).ready(function () {

        $( ".my_date_picker" ).datepicker();
      // Validate firstName
        $('#NameCheck').hide();   
        let firstNameError = true;
        $('#firstname').keyup(function () {
            // alert('sdlkfj');
            validateFirstname();
        });
        // First Name 
        function validateFirstname() {
            // alert('dajfh');
            let firstnameValue = $('#firstname').val();
            // alert(firstnameValue);
            if (firstnameValue.length == '') {
            $('#NameCheck').show();
                firstNameError = false;
                return false;
            }
            else if((firstnameValue.length < 3)||
                    (firstnameValue.length > 10)) {
                $('#NameCheck').show();
                $('#NameCheck').html("length of first name must be between 3 and 10");
                firstNameError = false;
                // return false;
            }
            else {
                $('#NameCheck').hide();
                firstNameError = true;
            }
        }

        //  Last Name 
        $('#lastNCheck').hide();   
        let lastNameError = true;
        $('#lastname').keyup(function () {
            validateLastname();
        });
        // Last Name
        function validateLastname() {
            let lastnameValue = $('#lastname').val();
            if (lastnameValue.length == '') {
            $('#lastNCheck').show();
                lastNameError = false;
                return false;
            }
            else if((lastnameValue.length < 3)||
                    (lastnameValue.length > 10)) {
                $('#lastNCheck').show();
                $('#lastNCheck').html("length of last name must be between 3 and 10");
                lastNameError = false;
                return false;
            }
            else {
                $('#lastNCheck').hide();
                lastNameError = true;
            }
        }


        // Validate Password
        $('#passCheck').hide();
        let passwordError = true;
        $('#password').keyup(function () {
            validatePassword();
        });
         // Password
        function validatePassword() {
            let passwordValue =
                $('#password').val();
                // alert(passwordValue);
            if (passwordValue.length == '') {
                $('#passCheck').show();
                passwordError = false;
                // return false;
            }
            if ((passwordValue.length < 6)||
                (passwordValue.length > 10)) {
                $('#passCheck').show();
                $('#passCheck').html
                ("length of your Password must be between 6 and 10");
                $('#passCheck').css("color", "red");
                passwordError = false;
                return false;
            } else {
                $('#passCheck').hide();
                passwordError = true;
            }
        }

        
            
        // Validate Confirm Password
        $('#conpasscheck').hide();
        let confirmPasswordError = true;
        $('#confirm_password').keyup(function () {
            validateConfirmPassword();
        });
        function validateConfirmPassword() {
            let confirmPasswordValue =
                $('#confirm_password').val();
            let passwordValue =
                $('#password').val();
            if (confirmPasswordValue.length == '') {
                $('#conpasscheck').show();
                confirmPasswordError = false;
                    return false;
            }
            if (passwordValue != confirmPasswordValue) {
                $('#conpasscheck').show();
                $('#conpasscheck').html(
                    "Password Number didn't Match");
                $('#conpasscheck').css(
                    "color", "red");
                confirmPasswordError = false;
                return false;
            } else {
                $('#conpasscheck').hide();
                confirmPasswordError = true;
            }
        }
        
        // Validate Confirm Password
        $('#emailChk').hide();
        let emailError = true;
        $('#emailV').keyup(function () {
            validateEmail();
        });
        function validateEmail() {
            let emailval = $('#emailV').val();
            
            if (emailval.length == '') {
                $('#emailChk').show();
                emailError = false;
                    return false;
            }
            if (emailval) {
                var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                if(!regex.test(emailval)) {
                    $('#emailChk').show();
                    $('#emailChk').html(
                        "Plz Enter Valid Email!");
                    emailError = false;
                    return false;
                }else{
                    $('#emailChk').hide();
                    emailError = true;
                    return true;
                }
            } else {
                $('#emailChk').hide();
                emailError = true;
            }
        }

        $('#editadminBtn').click(function () {
            // alert('hsd');
            validateFirstname();
            validateLastname();
            // validatePassword();
            // validateConfirmPassword();
            // alert(res); && ( passwordError == true) && ( confirmPasswordError  == true)
            if ((firstNameError == true) && ( lastNameError == true) && (emailError == true) ) {
                $( "#editAdminForm" ).submit();
                return true;        
            } else {
                return false;
            }
        });

        $('#editWeekBtn').click(function () {
            // alert('hsd');
            
            $( "#editAdminForm" ).submit();
               
        });


        // Points update
        $('#pointCheck').hide();   
        let pointError = true;
        $('#point').keyup(function () {
            // alert('sdlkfj');
            validatePoint();
        });
        // First Name 
        function validatePoint() {
            // alert('dajfh');
            let pointValue = $('#point').val();
            // alert(firstnameValue);
            if (pointValue.length == '') {
            $('#pointCheck').show();
                pointError = false;
                return false;
            }else {
                $('#pointCheck').hide();
                pointError = true;
            }
        }



        $('#editpointBtn').click(function () {
            // alert('hsd');
            validatePoint();
            
            if ((pointError == true)  ) {
                $( "#editpointsForm" ).submit();
                return true;        
            } else {
                return false;
            }
        });



        $('#editteamBtn').click(function () {
            // alert('hsd');
            $( "#editTeamForm" ).submit();
            return true;        
           
        });
    });
</script>
