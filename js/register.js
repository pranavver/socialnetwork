$(document).ready(function () {
  // Validate profile picture
   $('#profile-pic-input').on('change', function () {
        const file = this.files[0];
        const validImageTypes = ['image/jpeg', 'image/png', 'image/jpg'];

        // If there's an existing error message, remove it first
        const $error = $(this).siblings('.error-message');
        $error.remove();

        if (file && validImageTypes.includes(file.type)) {
            $('.profile-pic').attr('src', URL.createObjectURL(file)); // Update profile picture preview
        } else {
            $(this).parent().append('<div class="error-message" style="color:red;font-size:0.8em; margin-top: 5px;">Please upload a valid image (JPEG/PNG/JPG).</div>');
        }
    });

  // Validate full name
  $('#full-name').on('input', function () {
      const fullName = $(this).val().trim();
      const nameRegex = /^[a-zA-Z\s]+$/;
      const $error = $(this).next('.error-message');

      if (!$error.length) {
          $(this).after('<div class="error-message" style="color:red;font-size:0.8em;align-self: flex-start;"></div>');
      }

      if (nameRegex.test(fullName) && fullName.length > 1) {
          $(this).css('outline', '1px solid green');
          $(this).next('.error-message').text('');
      } else {
          $(this).css('outline', '1px solid red');
          $(this).next('.error-message').text('Full name should contain only letters and spaces.');
      }
  });

  // Validate date of birth
  $('#dob').on('change', function () {
      const dob = new Date($(this).val());
      const today = new Date();
      const minAge = new Date();
      minAge.setFullYear(today.getFullYear() - 9);
      const $error = $(this).next('.error-message');

      if (!$error.length) {
          $(this).after('<div class="error-message" style="color:red;font-size:0.8em;align-self: flex-start;"></div>');
      }

      if (dob <= minAge && dob >= new Date('1900-01-01')) {
          $(this).css('outline', '1px solid green');
          $(this).next('.error-message').text('');
      } else {
          $(this).css('outline', '1px solid red');
          $(this).next('.error-message').text('You must be at least 10 years old.');
      }
  });

  // Validate email
  $('#email').on('input', function () {
      const email = $(this).val().trim();
      const emailRegex = /^[a-zA-Z0-9][a-zA-Z0-9._%+-]*@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,10}$/;
      const $error = $(this).next('.error-message');

      if (!$error.length) {
          $(this).after('<div class="error-message" style="color:red;font-size:0.8em;align-self: flex-start;"></div>');
      }

      if (emailRegex.test(email)) {
          $(this).css('outline', '1px solid green');
          $(this).next('.error-message').text('');
      } else {
          $(this).css('outline', '1px solid red');
          $(this).next('.error-message').text('Invalid email format.');
      }
  });

  // Validate password
  $('#password').on('input', function () {
      const password = $(this).val();
      const passwordRegex = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/;
      const $error = $(this).next('.error-message');

      if (!$error.length) {
          $(this).after('<div class="error-message" style="color:red;font-size:0.8em;align-self: flex-start;"></div>');
      }

      if (password.length >=8) {
            if (passwordRegex.test(password)) {
                $(this).css('outline', '1px solid green');
                $(this).next('.error-message').text('');
            } else {
                $(this).css('outline', '1px solid red');
                $(this).next('.error-message').text(
                    'Use A-Z, a-z, 0-9, !@#$%^&* in password'
                );
            }
        } else {
        $(this).css('outline', '1px solid red');
        $(this).next('.error-message').text(
            'Password must be at least 8 characters long'
        );
    }

      


      // Validate repassword field dynamically
        const rePassword = $('#repassword').val();
        const $reError = $('#repassword').next('.error-message');

        if (!$reError.length) {
            $('#repassword').after('<div class="error-message" style="color:red;font-size:0.8em;align-self: flex-start;"></div>');
        }

        if (rePassword === password && rePassword.length > 0) {
            $('#repassword').css('outline', '1px solid green');
            $('#repassword').next('.error-message').text('');
        } else if (rePassword.length > 0) {
            $('#repassword').css('outline', '1px solid red');
            $('#repassword').next('.error-message').text('Passwords do not match.');
        }
  });

  // Validate re-entered password
  $('#repassword').on('input', function () {
      const password = $('#password').val();
      const rePassword = $(this).val();
      const $error = $(this).next('.error-message');

      if (!$error.length) {
          $(this).after('<div class="error-message" style="color:red;font-size:0.8em;align-self: flex-start;"></div>');
      }

      if (password === rePassword) {
          $(this).css('outline', '1px solid green');
          $(this).next('.error-message').text('');
      } else {
          $(this).css('outline', '1px solid red');
          $(this).next('.error-message').text('Passwords do not match.');
      }
  });

  // Prevent form submission if there are validation errors
  $('form').on('submit', function (event) {
      let isValid = true;

      $('input').each(function () {
          if ($(this).css('outline-color') === 'rgb(255, 0, 0)') {
              isValid = false;
          }
      });

      if (!isValid) {
          event.preventDefault();
          // alert('Please fix validation errors before submitting.');
      }
  });
});

