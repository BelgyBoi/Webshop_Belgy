document.getElementById('hamburger-menu').addEventListener('click', function(event) {
    event.preventDefault();
    document.getElementById('hamburger-widget').classList.toggle('open');
});

document.getElementById('search-link').addEventListener('click', function(event) {
    event.preventDefault();
    document.getElementById('search-widget').classList.toggle('open');
});

document.getElementById('close-hamburger').addEventListener('click', function() {
    document.getElementById('hamburger-widget').classList.remove('open');
});

document.getElementById('close-search').addEventListener('click', function() {
    document.getElementById('search-widget').classList.remove('open');
});


