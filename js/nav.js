document.addEventListener('DOMContentLoaded', function() {
    // Existing functionality
    document.getElementById('hamburger-menu').addEventListener('click', function(event) {
        event.preventDefault();
        const widget = document.getElementById('hamburger-widget');
        widget.style.display = 'flex';
        setTimeout(() => {
            widget.classList.add('open');
        }, 10);
    });

    document.getElementById('search-link').addEventListener('click', function(event) {
        event.preventDefault();
        const widget = document.getElementById('search-widget');
        widget.style.display = 'flex';
        setTimeout(() => {
            widget.classList.add('open');
        }, 10);
    });

    document.getElementById('close-hamburger').addEventListener('click', function() {
        const widget = document.getElementById('hamburger-widget');
        widget.classList.remove('open');
        setTimeout(() => {
            widget.style.display = 'none';
        }, 500);
    });

    document.getElementById('close-search').addEventListener('click', function() {
        const widget = document.getElementById('search-widget');
        widget.classList.remove('open');
        setTimeout(() => {
            widget.style.display = 'none';
        }, 500);
    });

    // Check login status and set the user page link accordingly
    const userPageLink = document.getElementById('user-page');

    // Fetch login status from PHP and update the link
    fetch('LoginCheck.php')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Login status:', data); // Debugging line
            if (data.loggedin) {
                userPageLink.href = 'user.php';
            } else {
                userPageLink.href = 'login.php';
            }
        })
});
