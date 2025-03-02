$(document).ready(function () {
    // Validate email dynamically on typing
    $('#email').on('input', function () {
        var email = $(this).val();
        var emailRegex = /^[a-zA-Z0-9][a-zA-Z0-9._%+-]*@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,10}$/;

        if (emailRegex.test(email)) {
            // Email is valid
            $(this).css('outline', '1px solid green');
            $(this).next('.error-message').remove();
        } else {
            // Email is invalid
            $(this).css('outline', '1px solid red');
            if ($(this).next('.error-message').length === 0) {
                $(this).after(
                    '<div class="error-message" style="color:red; font-size:0.8em; align-self:flex-start;">Invalid email format.</div>'
                );
            }
        }
    });

    // Validate password dynamically on typing
    $('#password').on('input', function () {
        var password = $(this).val();
        var passwordRegex = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/;

        if (passwordRegex.test(password)) {
            // Password is valid
            $(this).css('outline', '1px solid green');
            $(this).next('.error-message').remove();
        } else {
            // Password is invalid
            $(this).css('outline', '1px solid red');
            if ($(this).next('.error-message').length === 0) {
                $(this).after(
                    '<div class="error-message" style="color:red; font-size:0.8em; align-self: flex-start;">Use A-Z, a-z, 0-9, !@#$%^&* in password</div>'
                );
            }
        }
    });

    // Prevent form submission if there are validation errors
    $('form').on('submit', function (event) {
        var isValid = true;

        var email = $('#email').val();
        var password = $('#password').val();
        var emailRegex = /^[a-zA-Z0-9][a-zA-Z0-9._%+-]*@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,10}$/;
        var passwordRegex = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/;

        // Validate email
        if (!emailRegex.test(email)) {
            $('#email').css('outline', '1px solid black');
            if ($('#email').next('.error-message').length === 0) {
                $('#email').after(
                    '<div class="error-message" style="color:red; font-size:0.8em; align-self: flex-start;">Invalid email format.</div>'
                );
            }
            isValid = false;
        }

        // Validate password
        if (!passwordRegex.test(password)) {
            $('#password').css('outline', '1px solid red'); 
            if ($('#password').next('.error-message').length === 0) {
                $('#password').after(
                    '<div class="error-message" style="color:red; font-size:0.8em; align-self: flex-start;">Use A-Z, a-z, 0-9, !@#$%^&* in password</div>'
                );
            }
            isValid = false;
        }

        // Prevent form submission if validation fails
        if (!isValid) {
            event.preventDefault();
        }
    });
});
