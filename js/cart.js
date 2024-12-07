document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.quantity-increase').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            const variantId = this.getAttribute('data-variant-id'); // Get the variant ID if any
            const input = document.querySelector(`.quantity-input[data-product-id="${productId}"][data-variant-id="${variantId}"]`);
            if (input) {
                input.value = parseInt(input.value) + 1;
                updateCart(productId, variantId, input.value);
            } else {
                console.error(`Input element not found for Product ID: ${productId} and Variant ID: ${variantId}`);
            }
        });
    });

    document.querySelectorAll('.quantity-decrease').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            const variantId = this.getAttribute('data-variant-id'); // Get the variant ID if any
            const input = document.querySelector(`.quantity-input[data-product-id="${productId}"][data-variant-id="${variantId}"]`);
            if (input) {
                if (parseInt(input.value) > 1) {
                    input.value = parseInt(input.value) - 1;
                    updateCart(productId, variantId, input.value);
                } else {
                    showPopup(productId, variantId);
                }
            } else {
                console.error(`Input element not found for Product ID: ${productId} and Variant ID: ${variantId}`);
            }
        });
    });

    document.querySelectorAll('.remove-item').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            const variantId = this.getAttribute('data-variant-id'); // Get the variant ID if any
            showPopup(productId, variantId);
        });
    });

    function updateCart(productId, variantId, quantity) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'classes/update_cart.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                console.log(xhr.responseText); // Inspect the response
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.status === 'success') {
                        // Update the total price
                        document.querySelector('.summary-total').textContent = `Total: BC${response.total.toFixed(2)}`;
                    } else {
                        alert('Error updating cart: ' + (response.message || 'Unknown error'));
                    }
                } catch (error) {
                    console.error('Error parsing JSON:', error);
                    console.error('Response text:', xhr.responseText);
                }
            }
        };
        xhr.send(`product_id=${productId}&variant_id=${variantId}&quantity=${quantity}`);
    }

    function removeItem(productId, variantId) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'classes/remove_from_cart.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                console.log(xhr.responseText); // Inspect the response
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.status === 'success') {
                        // Remove the item from the cart
                        document.querySelector(`.product-item[data-product-id="${productId}"][data-variant-id="${variantId}"]`).remove();
                        // Update the total price
                        document.querySelector('.summary-total').textContent = `Total: BC${response.total.toFixed(2)}`;
                    } else {
                        alert('Error removing item from cart: ' + (response.message || 'Unknown error'));
                    }
                } catch (error) {
                    console.error('Error parsing JSON:', error);
                    console.error('Response text:', xhr.responseText);
                }
            }
        };
        xhr.send(`product_id=${productId}&variant_id=${variantId}`);
    }

    function showPopup(productId, variantId) {
        const popupOverlay = document.getElementById('popup-overlay');
        popupOverlay.style.display = 'flex';

        const confirmRemoveButton = document.getElementById('confirm-remove');
        confirmRemoveButton.onclick = function() {
            removeItem(productId, variantId);
            popupOverlay.style.display = 'none';
        };

        const cancelRemoveButton = document.getElementById('cancel-remove');
        cancelRemoveButton.onclick = function() {
            popupOverlay.style.display = 'none';
        };
    }
});
