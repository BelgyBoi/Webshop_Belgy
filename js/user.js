$(document).ready(function() {
    // JavaScript for toggling password visibility
    const toggleCurrentPassword = document.getElementById('toggleCurrentPassword');
    const currentPassword = document.getElementById('current_password');

    if (toggleCurrentPassword && currentPassword) {
        toggleCurrentPassword.addEventListener('click', function() {
            const type = currentPassword.getAttribute('type') === 'password' ? 'text' : 'password';
            currentPassword.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    }

    const toggleNewPassword = document.getElementById('toggleNewPassword');
    const newPassword = document.getElementById('new_password');

    if (toggleNewPassword && newPassword) {
        toggleNewPassword.addEventListener('click', function() {
            const type = newPassword.getAttribute('type') === 'password' ? 'text' : 'password';
            newPassword.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    }

    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    const confirmPassword = document.getElementById('confirm_password');

    if (toggleConfirmPassword && confirmPassword) {
        toggleConfirmPassword.addEventListener('click', function() {
            const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPassword.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    }

    // AJAX for verifying and updating passwords
    $('#current_password').on('blur', function() {
        $.ajax({
            url: 'classes/VerifyPassword.php',
            type: 'POST',
            data: {
                current_password: $('#current_password').val()
            },
            success: function(response) {
                try {
                    var data = JSON.parse(response);
                    if (data.status === 'success') {
                        $('#current_password').css('border', '1px solid green');
                    } else {
                        $('#current_password').css('border', '1px solid red');
                    }
                } catch (error) {
                    console.error('JSON parse error:', error);
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
                try {
                    var data = JSON.parse(response);
                    $('#message').text(data.message);
                    if (data.status === 'success') {
                        $('#password-form')[0].reset();
                        $('#current_password').css('border', '1px solid #ccc');
                    }
                } catch (error) {
                    console.error('JSON parse error:', error);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {
    fetch('user.php', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('User Data:', data);
                const emailElement = document.querySelector('p.email');
                const firstnameElement = document.querySelector('p.firstname');
                const lastnameElement = document.querySelector('p.lastname');
                const currencyElement = document.querySelector('p.currency');

                // Check if elements exist before setting their text content
                if (emailElement) emailElement.textContent = 'Email: ' + data.email;
                if (firstnameElement) firstnameElement.textContent = 'First Name: ' + data.firstname;
                if (lastnameElement) lastnameElement.textContent = 'Last Name: ' + data.lastname;
                if (currencyElement) currencyElement.textContent = 'Currency: ' + data.currency + ' BC';
            } else {
                console.error('Error:', data.message);
                window.location.href = 'login.php';
            }
        })
        .catch(error => console.error('Error fetching user data:', error));
});
