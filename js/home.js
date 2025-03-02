$(document).ready(function() {
    $(document).on('focus', 'textarea', function() {
        $(this).closest('.edit').css('outline', '1px solid rgb(0, 60, 255)');
    }).on('blur', 'textarea', function() {
        $(this).closest('.edit').css('outline', 'none');
    });

    $(document).on('focus', 'input[type="text"]', function() {
        $(this).closest('.data').css('outline', '1px solid #0d75bb');
    }).on('blur', 'input[type="text"]', function() {
        $(this).closest('.data').css('outline', 'none');
    });

    $(window).scroll(function() {
        if ($(this).scrollTop() > 100) {
            $('.slide-up').fadeIn();
        }
        else {
            $('.slide-up').fadeOut();
        }
    });

    $('.slide-up').on('click', function() {
        $('html, body').animate({ scrollTop: 0 }, 800);
        return false;
    });


        $(".share").on("click", function () {
            const shareUrl = window.location.origin + "/profile.php ?>"; 
        
            // Copy the URL to the clipboard
            navigator.clipboard.writeText(shareUrl).then(function () {
                alert("Profile link copied to clipboard!");
            }).catch(function (error) {
                alert("Failed to copy link. Please try again.");
            });
        });

        // Trigger the file input when the profile edit icon is clicked
        $(document).on("click", ".icon.profile", function (){//$(".icon.profile").on("click", function () runs only one time while the given run multiple times
            $("#profile_picture").click(); // Open the file selection dialog
        });

        $("#profile_picture").on("change", function () {
            const preview = $(".profile_pic"); // Profile picture for preview
            if ($("#profile_picture")[0].files && $("#profile_picture")[0].files[0]) {
                $(".icon.profile").replaceWith('<i class="fa fa-save save-img"></i>'); // Replace pencil with save icon
    
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.attr("src", e.target.result); // Set the preview image
                };
                reader.readAsDataURL($("#profile_picture")[0].files[0]);
            }
        });

            $(document).on("click", ".save-img", function () {
                var file = $("#profile_picture")[0].files[0]; // Get the selected file
                var formData = new FormData();
                formData.append("profile_picture", file);

                $.ajax({
                    url: 'update_profile.php',  // Server-side script to handle the upload
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        const result = JSON.parse(response);
                        console.log(response);
                        if (result.status === 'success') {
                            console.log(result);
                            alert('Profile picture updated successfully!');
                            $(".fa.fa-save.save-img").replaceWith('<i class="fas fa-pen icon profile"></i>');
                            $(".profile-pic.small").attr('src', result.profile_pic);
                        } else {
                            alert('Failed to update profile picture.');
                        }
                    },
                    error: function () {
                        alert('An error occurred while uploading the profile picture.');
                    }
                });
        });


  

    $('#img-1').on('change', function () {
        const file = this.files[0];
        const validImageTypes = ['image/jpeg', 'image/png', 'image/jpg'];

        // If there's an existing error message, remove it first
        const $error = $(this).siblings('.error-message');
        $error.remove();

        if (file && validImageTypes.includes(file.type)) {
            $('.upload-img').attr('src', URL.createObjectURL(file)); // Update profile picture preview
        } else {
            $(this).parent().prepend('<span class="error-message" style="color:red;font-size:0.8em; margin-top: 5px;float:left;">Please upload a valid image (JPEG/PNG/JPG).</span>');
        }
    });



    // Handle edit-icon click: enable the input field and replace the edit icon with a save icon
    $(document).on("click", ".edit-icon", function() {
        const inputField = $(this).siblings("input");

        // Enable the input field and set focus
        inputField.prop("readonly", false).prop("disabled", false).focus();

        // Replace the edit icon with save icon
        $(this).replaceWith('<i class="fa fa-save save-icon"></i>');
    });

    // $(document).on('click','.like, .dislike',function(e) {this.style.transform = 'scale(5)'});
    $(document).on('click', '.material-icons.like, .material-icons.dislike', function(e) {
        $(this).css({
            transform: 'scale(1.2)',
            transition: 'transform 0.1s'
        });
    
        // Optionally reset scale after a delay
        setTimeout(() => {
            $(this).css('transform', 'scale(1)');
        }, 300);
    });

    // Handle save-icon click: send the updated input value to the server
    $(document).on("click", ".save-icon", function() {
        const inputField = $(this).siblings("input");
        const updatedLevel = inputField.val(); // Get the input's new value
        const saveIcon = $(this);

        // AJAX request to save the input value
        $.ajax({
            url: 'update_profile.php', 
            type: 'POST',
            data: { level: updatedLevel }, // Send the updated value as data
            success: function(response) {
                const result = JSON.parse(response);
                if (result.status === 'success') {
                    alert('Update successful!');
                    inputField.prop("readonly", true).prop("disabled", true); // Disable input
                    saveIcon.replaceWith('<i class="fas fa-pen edit-icon"></i>'); // Replace save icon with edit icon
                } else if (result.status === 'update_failed') {
                    alert('Failed to update. Please try again.');
                } else if (result.status === 'unauthorized') {
                    alert('Please log in to make changes.');
                    window.location.href = 'login.php';
                }
            },
            error: function() {
                alert('Error occurred while updating. Please try again.');
            }
        });
    });
    

    $('.post-button').click(function () {
        // Prepare the form data
        if($('.content').val().trim()!= '') {
        let formData = new FormData($('#postForm')[0]);

        $.ajax({
            url: 'post_upload.php', // The PHP script that processes the post
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $('.post-button').text('Posting...');
            },
            success: function (response) {
                response=JSON.parse(response);
                //  alert(response.message);
                $('.post-button').text('Post'); // Reset button text
                $('#message').text(response.message); // Clear any previous message

                if (response.status === 'success') {
                    // fetchLatestPosts();
                    // Append the new post to the posts container
                    $('.posts').prepend(response.html);
                    $('#postForm')[0].reset(); // Clear the form
                } else {
                    $('#message').text(response.message);
                }
            },
            error: function () {
                $('.post-button').text('Post');
                $('#message').text('An error occurred. Please try again.');
            }
        
        }); 
        }
    });
    function fetchLatestPosts(){
        $.ajax({
            url: 'fetch_homepage.php', // The PHP script that processes the post
            type: 'POST'
        }); 
    }
    

    $(document).on('click', '.off', function () {
        if ($('.upload-img').attr('src')) {
            $('.upload-img').removeAttr('src'); // Remove the image source
            $(this).hide(); // Hide the .off button
            $('.add-image').show(); // Show the .add-image button
        }
    });
    
    
    // Handle image load event
    $('.upload-img').on('load', function () {
        $('.off').show(); // Show the .off button after the image is fully loaded
        $('.add-image').hide(); // Hide the .add-image button
    });

    
    $(".close").on("click", function() {
        const postId = $(this).data("post-id");
        const postSection = $(this).closest(".post");
    
        if (confirm("Are you sure you want to delete this post?")) {
          $.ajax({
            url: 'delete_post.php',
            type: 'POST',
            data: { post_id: postId },
            success: function(response) {
                postSection.slideUp(300, function() {
                    $(this).remove(); // Remove the post and all its elements
                    alert('Post Deleted successfully');
                });
            },
            error: function() {
              alert("Error occurred while deleting the post.");
            }
          });
        }
      });

});
    
    function like_update(id){
        jQuery.ajax({
            url: 'like_dislike.php',
            type: 'POST',
            data: 'reaction=like&post_id='+id,
            success: function (response) {
                var cur_count=jQuery('#like_loop_'+id).html();
                cur_count++;
                jQuery('#like_loop_'+id).html(cur_count);
            }
        });
    }

    function dislike_update(id){
        jQuery.ajax({
            url: 'like_dislike.php',
            type: 'POST',
            data: 'reaction=dislike&post_id='+id,
            success: function (response) {
                var cur_count=jQuery('#dislike_loop_'+id).html();
                cur_count++;
                jQuery('#dislike_loop_'+id).html(cur_count);
            }
        });
    }
