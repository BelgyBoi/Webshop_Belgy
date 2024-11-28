document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function() {
            console.log('Add to cart clicked'); // Debugging line
            const productId = this.getAttribute('data-product-id');
            const productName = this.getAttribute('data-name');
            const productPrice = this.getAttribute('data-price');
            const productImage = this.getAttribute('data-image');

            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'classes/add_to_cart.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    console.log('Response:', xhr.responseText); // Debugging line
                    if (!sessionStorage.getItem('popupShown')) {
                        showPopup();
                        sessionStorage.setItem('popupShown', 'true'); // Ensure popup appears only once per session
                    }
                }
            };
            xhr.send(`product_id=${productId}&name=${productName}&price=${productPrice}&image=${productImage}`);
        });
    });

    function showPopup() {
        const popupOverlay = document.getElementById('popup-overlay');
        const goToCartButton = document.getElementById('go-to-cart');
        const keepShoppingButton = document.getElementById('keep-shopping');

        popupOverlay.style.display = 'flex';

        goToCartButton.onclick = function() {
            window.location.href = 'cart.php';
        };

        keepShoppingButton.onclick = function() {
            popupOverlay.style.display = 'none';
        };
    }
});
