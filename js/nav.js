document.addEventListener('DOMContentLoaded', function() {
    // Existing functionality for hamburger and search widgets
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

    // Search bar functionality
    $('#search-bar').on('input', function() {
        let query = $(this).val();
        if (query.length > 0) {
            $.ajax({
                url: '/Webshop_Belgy/classes/Search.php',
                type: 'GET',
                data: { query: query },
                success: function(response) {
                    let suggestions = JSON.parse(response);
                    let suggestionsHtml = '';
                    let resultsHtml = '';
                    suggestions.forEach(function(item) {
                        suggestionsHtml += '<div class="suggestion-item">' + item.name + '</div>';
                        resultsHtml += `
                            <a class="product_item" href="focus.php?product_id=${item.id}">
                                <img class="product_image" src="${item.main_image_url}" alt="${item.name}">
                                <h2 class="product_name product_data">${item.name}</h2>
                                <p class="product_price product_data">€${item.price}</p>
                                <button class="product_button">Add to cart</button>
                            </a>
                        `;
                    });
                    $('#search-suggestions').html(suggestionsHtml).show();
                    $('#search-results').html(resultsHtml).show();
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                }
            });
        } else {
            $('#search-suggestions').hide();
            $('#search-results').hide();
        }
    });

    // Hide suggestions when an item is clicked or user clicks outside
    $(document).on('click', function(event) {
        if (!$(event.target).closest('#search-bar, #search-suggestions').length) {
            $('#search-suggestions').hide();
        }
    });

    // Handle suggestion click
    $(document).on('click', '.suggestion-item', function() {
        $('#search-bar').val($(this).text());
        $('#search-suggestions').hide();
    });

    // Handle Enter key press to filter products on index.php
    $('#search-form').on('submit', function(event) {
        event.preventDefault();
        let query = $('#search-bar').val();
        if (query.length > 0) {
            $.ajax({
                url: '/Webshop_Belgy/classes/Search.php',
                type: 'GET',
                data: { query: query },
                success: function(response) {
                    let results = JSON.parse(response);
                    let resultsHtml = '';
                    results.forEach(function(item) {
                        resultsHtml += `
                            <a class="product_item" href="focus.php?product_id=${item.id}">
                                <img class="product_image" src="${item.main_image_url}" alt="${item.name}">
                                <h2 class="product_name product_data">${item.name}</h2>
                                <p class="product_price product_data">€${item.price}</p>
                                <button class="product_button">Add to cart</button>
                            </a>
                        `;
                    });
                    $('.product_list').html(resultsHtml);
                    // Close the search widget
                    const widget = document.getElementById('search-widget');
                    widget.classList.remove('open');
                    setTimeout(() => {
                        widget.style.display = 'none';
                    }, 500);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                }
            });
        }
    });

    // Handle wallet click and toggle visibility
    const walletIcon = document.getElementById('wallet-icon');
    const walletMenu = document.getElementById('wallet-menu');
    const walletDropdown = document.getElementById('wallet-dropdown');

    // Show wallet menu on hover
    walletDropdown.addEventListener('mouseover', function() {
        walletDropdown.classList.add('open');
    });

    walletDropdown.addEventListener('mouseout', function(event) {
        if (!walletIcon.contains(event.relatedTarget)) {
            walletDropdown.classList.remove('open');
        }
    });
});
