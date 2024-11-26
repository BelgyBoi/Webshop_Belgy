document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function() {
            console.log('Add to cart clicked'); // Debugging line
            const productId = this.getAttribute('data-id');
            const productName = this.getAttribute('data-name');
            const productPrice = this.getAttribute('data-price');
            const productImage = this.getAttribute('data-image');

            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'classes/add_to_cart.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    console.log('Response:', xhr.responseText); // Debugging line
                    alert('Product added to cart!');
                }
            };
            xhr.send(`id=${productId}&name=${productName}&price=${productPrice}&image=${productImage}`);
        });
    });
});
