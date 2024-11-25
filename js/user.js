$(document).ready(function() {
    // JavaScript for toggling password visibility
    const toggleCurrentPassword = document.getElementById('toggleCurrentPassword');
    const currentPassword = document.getElementById('current_password');

    toggleCurrentPassword.addEventListener('click', function() {
        const type = currentPassword.getAttribute('type') === 'password' ? 'text' : 'password';
        currentPassword.setAttribute('type', type);
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });

    const toggleNewPassword = document.getElementById('toggleNewPassword');
    const newPassword = document.getElementById('new_password');

    toggleNewPassword.addEventListener('click', function() {
        const type = newPassword.getAttribute('type') === 'password' ? 'text' : 'password';
        newPassword.setAttribute('type', type);
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });

    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    const confirmPassword = document.getElementById('confirm_password');

    toggleConfirmPassword.addEventListener('click', function() {
        const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
        confirmPassword.setAttribute('type', type);
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });

    // AJAX for verifying and updating passwords
    $('#current_password').on('blur', function() {
        $.ajax({
            url: 'classes/VerifyPassword.php',
            type: 'POST',
            data: {
                current_password: $('#current_password').val()
            },
            success: function(response) {
                var data = JSON.parse(response);
                if (data.status === 'success') {
                    $('#current_password').css('border', '1px solid green');
                } else {
                    $('#current_password').css('border', '1px solid red');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
            }
        });
    });

    $('#password-form').on('submit', function(event) {
        event.preventDefault();
        if ($('#current_password_error').is(':visible')) {
            return; // Do not submit the form if the current password is incorrect
        }
        $.ajax({
            url: 'classes/UpdatePassword.php',
            type: 'POST',
            data: {
                new_password: $('#new_password').val(),
                confirm_password: $('#confirm_password').val()
            },
            success: function(response) {
                var data = JSON.parse(response);
                $('#message').text(data.message);
                if (data.status === 'success') {
                    $('#password-form')[0].reset();
                    $('#current_password').css('border', '1px solid #ccc');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
            }
        });
    });
});
