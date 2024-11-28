<div id="hamburger-widget" class="widget">
    <button id="close-hamburger">Cancel</button>
    <div class="list">
        <?php if (!empty($categories)) {
            foreach ($categories as $cat) {
                echo '<a href="#" class="category-link" data-id="' . $cat['id'] . '">' . $cat['name'] . '</a>';
            }
        } else {
            echo 'No categories found';
        } ?>
        <!-- Unfilter Button -->
        <a href="#" class="unfilter-link">Show All Products</a>
    </div>
</div>

<div id="search-widget" class="widget">
    <button id="close-search">Cancel</button>
    <form action="" id="search-form">
        <input type="text" id="search-bar" placeholder="What are you looking for?">
        <div id="search-suggestions" class="suggestions"></div> <!-- Container for search suggestions -->
        <div id="search-results" class="products-list"></div> <!-- Ensure this class matches the one in data.js -->
    </form>
</div>
