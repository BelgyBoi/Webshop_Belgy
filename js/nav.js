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
