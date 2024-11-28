function showPopup() {
    const popupOverlay = document.getElementById('popup-overlay');
    popupOverlay.style.display = 'flex';

    const goToCartButton = document.getElementById('go-to-cart');
    goToCartButton.onclick = function() {
        window.location.href = 'cart.php';
    };

    const keepShoppingButton = document.getElementById('keep-shopping');
    keepShoppingButton.onclick = function() {
        popupOverlay.style.display = 'none';
    };
}
