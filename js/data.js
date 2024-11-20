document.addEventListener('DOMContentLoaded', (e) => {
    const categoryLinks = document.querySelectorAll('.category-link');
    const productList = document.querySelector('.products-list'); // Ensure this matches the class in your HTML

    categoryLinks.forEach((link) => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const categoryId = link.getAttribute('data-id');
            console.log(`Category ID: ${categoryId}`);

            fetch(`classes/get_products.php?category_id=${categoryId}`)
                .then(response => {
                    console.log('Response:', response);
                    return response.json();
                })
                .then(data => {
                    console.log('Data:', data);
                    productList.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(product => {
                            const productItem = document.createElement('div');
                            productItem.classList.add('product-item');
                            productItem.innerHTML = `
                                <img src="${product.main_image_url}" alt="${product.name}">
                                <h2>${product.name}</h2>
                                <p>${product.description}</p>
                                <p>${product.price}</p>
                            `;
                            productList.appendChild(productItem);
                        });
                    } else {
                        productList.innerHTML = '<p>No products found in this category.</p>';
                    }
                })
            .catch(error => console.error('Error fetching products:', error));
        });
    });
});
