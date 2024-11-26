document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.quantity-increase').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            const input = document.querySelector(`.quantity-input[data-product-id="${productId}"]`);
            input.value = parseInt(input.value) + 1;
            updateCart(productId, input.value);
        });
    });

    document.querySelectorAll('.quantity-decrease').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            const input = document.querySelector(`.quantity-input[data-product-id="${productId}"]`);
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
                updateCart(productId, input.value);
            }
        });
    });

    function updateCart(productId, quantity) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '/Webshop_Belgy/classes/update_cart.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                console.log(xhr.responseText); // Inspect the response
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.status === 'success') {
                        // Update the total price
                        document.querySelector('.summary-total').textContent = `Total: €${response.total.toFixed(2)}`;
                        // Update the specific product price
                        document.querySelector(`.product-price[data-product-id="${productId}"]`).textContent = `€${response.product_price.toFixed(2)}`;
                    } else {
                        alert('Error updating cart');
                    }
                } catch (error) {
                    console.error('Error parsing JSON:', error);
                    console.error('Response text:', xhr.responseText);
                }
            }
        };
        xhr.send(`product_id=${productId}&quantity=${quantity}`);
    }
});
