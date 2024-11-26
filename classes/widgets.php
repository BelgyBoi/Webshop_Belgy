<div id="hamburger-widget" class="widget">
    <button id="close-hamburger">Cancel</button>
    <div class="list">
        <?php
            if (!empty($categories)) {
                foreach ($categories as $cat) {
                    echo '<a href="#" class="category-link" data-id="' . $cat['id'] . '">' . $cat['name'] . '</a>';
                }
            } else {
                echo 'No categories found';
            }
        ?>
    </div>
</div>

<div id="search-widget" class="widget">
    <button id="close-search">Cancel</button>
    <form action="" id="search-form">
        <input type="text" id="search-bar" placeholder="What are you looking for?">
        <div id="search-suggestions" class="suggestions"></div> <!-- Container for search suggestions -->
        <div id="search-results" class="product_list"></div> <!-- Container for displaying search results -->
    </form>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>