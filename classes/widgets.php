<div id="hamburger-widget" class="widget">
        <button id="close-hamburger">Cancel</button>
        <div class="list">
            <?php
                if(!empty($categories)){
                    foreach($categories as $cat){
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
        </form>
    </div>