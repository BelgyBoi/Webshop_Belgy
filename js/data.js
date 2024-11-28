document.addEventListener('DOMContentLoaded', function() {
    const categoryLinks = document.querySelectorAll('.category-link');
    const unfilterLink = document.querySelector('.unfilter-link');
    const allProducts = document.getElementById('all-products');
    const filteredProducts = document.getElementById('filtered-products');

    categoryLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const categoryId = link.getAttribute('data-id');
            console.log(`Category ID: ${categoryId}`);

            fetch(`classes/get_products.php?category_id=${categoryId}`)
                .then(response => response.json())
                .then(data => {
                    console.log('Data:', data);
                    filteredProducts.innerHTML = ''; // Clear existing products

                    data.forEach(product => {
                        console.log('Product:', product); // Log each product to verify
                        const productItem = document.createElement('div');
                        productItem.classList.add('product_item'); // Consistent class name
                        productItem.innerHTML = `
                            <a class="product_link" href="focus.php?product_id=${product.id}">
                                <img class="product_image" src="${product.main_image_url}" alt="${product.name}">
                                <div class="product_data">
                                    <h2 class="product_name">${product.name}</h2>
                                    <p class="product_price">€${product.price}</p>
                                </div>
                            </a>
                            <button class="product_button add-to-cart" data-product-id="${product.id}" data-name="${product.name}" data-price="${product.price}" data-image="${product.main_image_url}">Add to cart</button>
                        `;
                        filteredProducts.appendChild(productItem);
                    });

                    if (data.length === 0) {
                        filteredProducts.innerHTML = '<p>No products found in this category.</p>';
                    }

                    allProducts.classList.add('hidden'); // Hide all products
                    filteredProducts.classList.remove('hidden'); // Show filtered products
                    closeWidget(); // Close the widget after clicking a category
                })
                .catch(error => console.error('Error fetching products:', error));
        });
    });

    // Unfilter functionality to show all products
    unfilterLink.addEventListener('click', function(e) {
        e.preventDefault();
        console.log('Unfilter clicked, showing all products.');
        allProducts.classList.remove('hidden'); // Show all products
        filteredProducts.classList.add('hidden'); // Hide filtered products
        closeWidget(); // Close the widget after clicking
    });

    function closeWidget() {
        const widget = document.getElementById('hamburger-widget');
        widget.style.display = 'none';
    }
});
