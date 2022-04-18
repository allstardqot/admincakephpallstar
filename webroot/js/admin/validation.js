$(document).ready(function () {

        // Validate firstName
        $('#firstNameCheck').hide();   
        let firstNameError = true;
        $('#first_name').keyup(function () {
            validateFirstname();
        });
        // First Name 
        function validateFirstname() {
            let firstnameValue = $('#first_name').val();
            if (firstnameValue.length == '') {
            $('#firstNameCheck').show();
                firstNameError = false;
                return false;
            }
            else if((firstnameValue.length < 3)||
                    (firstnameValue.length > 10)) {
                $('#firstNameCheck').show();
                $('#firstNameCheck').html("length of first name must be between 3 and 10");
                firstNameError = false;
                // return false;
            }
            else {
                $('#firstNameCheck').hide();
                firstNameError = true;
            }
        }

        //  Last Name 
        $('#lastNameCheck').hide();   
        let lastNameError = true;
        $('#last_name').keyup(function () {
            validateLastname();
        });
        // Last Name
        function validateLastname() {
            let lastnameValue = $('#last_name').val();
            if (lastnameValue.length == '') {
            $('#lastNameCheck').show();
                lastNameError = false;
                return false;
            }
            else if((lastnameValue.length < 3)||
                    (lastnameValue.length > 10)) {
                $('#lastNameCheck').show();
                $('#lastNameCheck').html("length of last name must be between 3 and 10");
                lastNameError = false;
                return false;
            }
            else {
                $('#lastNameCheck').hide();
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
        $('#emailCheck').hide();
        let emailError = true;
        $('#email').keyup(function () {
            validateEmail();
        });
        function validateEmail() {
            let emailval = $('#email').val();
            
            if (emailval.length == '') {
                $('#emailCheck').show();
                emailError = false;
                    return false;
            }
            if (emailval) {
                var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                if(!regex.test(emailval)) {
                    $('#emailCheck').show();
                    $('#emailCheck').html(
                        "Plz Enter Valid Email!");
                    emailError = false;
                    return false;
                }else{
                    $('#emailCheck').hide();
                    emailError = true;
                    return true;
                }
            } else {
                $('#emailCheck').hide();
                emailError = true;
            }
        }
        
       
       
        
        // Submit button
        $('#submitAdmin').click(function () {
            validateFirstname();
            validateLastname();
            validatePassword();
            validateConfirmPassword();
            validateEmail();
            // alert(res);
            if ((firstNameError == true) && ( lastNameError == true) && ( passwordError == true) && ( confirmPasswordError  == true) && (emailError == true ) ) {
                $( "#admin_form" ).submit();
                return true;        
            } else {
                return false;
            }
        });

        
    });



    

    
   
    

    
