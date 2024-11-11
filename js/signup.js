
document.addEventListener('DOMContentLoaded', (event) => {

    const togglePassword1 = document.getElementById('togglePassword1');
    const password = document.getElementById('password');
    
    const togglePassword2 = document.getElementById('togglePassword2');
    const confirmPassword = document.getElementById('confirm_password');

    togglePassword1.addEventListener('click', function() {
        // Toggle the type attribute
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        // Toggle the icon
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });

    togglePassword2.addEventListener('click', function() {
        // Toggle the type attribute
        const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
        confirmPassword.setAttribute('type', type);
        // Toggle the icon
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });
});
