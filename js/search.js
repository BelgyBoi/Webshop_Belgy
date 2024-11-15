document.getElementById('search-icon').addEventListener('click', function() {
    document.getElementById('fullscreen-search').style.display = 'flex';
});

document.getElementById('close-search').addEventListener('click', function() {
    document.getElementById('fullscreen-search').style.display = 'none';
});

    // Optional: Close the fullscreen search when clicking outside the search form
document.getElementById('fullscreen-search').addEventListener('click', function(event) {
    if (event.target === this) {
        this.style.display = 'none';
    }
});


