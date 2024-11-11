
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
});
