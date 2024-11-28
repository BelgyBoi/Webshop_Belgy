document.addEventListener('DOMContentLoaded', (event) => {
    const togglePassword = document.getElementById('togglePassword');
    const currentPassword = document.getElementById('current_password');

    togglePassword.addEventListener('click', function() {
        // Toggle the type attribute
        const type = currentPassword.getAttribute('type') === 'password' ? 'text' : 'password';
        currentPassword.setAttribute('type', type);
        // Toggle the icon
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });

    // Handle form submission
    const loginForm = document.querySelector('form');
    loginForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const email = document.querySelector('input[name="email"]').value;
        const password = currentPassword.value;

        // Send AJAX request to the server
        fetch('login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ email: email, password: password })
        })
        .then(response => response.text())  // Get response as text
        .then(text => {
            console.log('Response:', text);  // Log the response text
            try {
                const data = JSON.parse(text);  // Parse JSON
                if (data.success) {
                    if (data.isAdmin) {
                        // Redirect to admin page
                        window.location.href = 'admin.php';
                    } else {
                        // Redirect to regular user page
                        window.location.href = 'index.php';
                    }
                } else {
                    // Handle login failure
                    alert('Invalid email or password');
                }
            } catch (e) {
                console.error('Error parsing JSON:', e);
                alert('An error occurred. Please try again.');
            }
        })
    });
});
