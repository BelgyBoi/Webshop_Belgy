$(document).ready(function() {
    const initialProductPrice = $('.product-price').text().replace('BC', '').trim();

    function initializeFeatures() {
        const $productCarousel = $('.product-carousel');
        const $variantCarousel = $('.variant-carousel');
        const $productThumbnails = $('.product-thumbnails');
        const $variantThumbnails = $('.variant-thumbnails');

        function initSlickCarousel($carousel, asNavFor) {
            $carousel.slick({
                dots: false,
                infinite: true,
                speed: 300,
                slidesToShow: 1,
                adaptiveHeight: true,
                asNavFor: asNavFor,
                prevArrow: '<button type="button" class="slick-prev">←</button>',
                nextArrow: '<button type="button" class="slick-next">→</button>'
            });
        }

        if ($productCarousel.length > 0) {
            initSlickCarousel($productCarousel, '.product-thumbnails');
        }

        if ($variantCarousel.length > 0) {
            initSlickCarousel($variantCarousel, '.variant-thumbnails');
        }

        if ($productThumbnails.length > 0) {
            $productThumbnails.slick({
                slidesToShow: 5,
                slidesToScroll: 1,
                asNavFor: '.product-carousel',
                dots: false,
                centerMode: true,
                focusOnSelect: true,
                arrows: false
            });
        }

        if ($variantThumbnails.length > 0) {
            $variantThumbnails.slick({
                slidesToShow: 5,
                slidesToScroll: 1,
                asNavFor: '.variant-carousel',
                dots: false,
                centerMode: true,
                focusOnSelect: true,
                arrows: false
            });
        }

        // Variant click handler to show variant images and update price
       // Variant click handler to show variant images and update price
$('.variant-link').on('click', function(event) {
    event.preventDefault();
    const variantId = $(this).data('variant-id');
    console.log('Variant ID:', variantId);

    // Fetch variant price via AJAX
    $.ajax({
        url: 'classes/get_variant_price.php',
        type: 'GET',
        data: { variant_id: variantId },
        success: function(response) {
            console.log('AJAX response:', response); // Debugging
            response = typeof response === 'string' ? JSON.parse(response) : response;
            if (response.status === 'success' && response.price !== undefined) {
                console.log('Price fetched successfully:', response.price); // Logging
                // Update the price
                $('.product-price').text(`BC${response.price}`);
    
                // Update the add-to-cart button to include variant ID
                $('.add-to-cart').data('variant-id', variantId);
            } else {
                console.error('Error fetching variant price:', response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX error:', status, error);
        }
    });
    

    // Hide product carousel and show variant carousel
    $productCarousel.addClass('hidden');
    $productThumbnails.addClass('hidden');
    $variantCarousel.removeClass('hidden').slick('setPosition');
    $variantThumbnails.removeClass('hidden').slick('setPosition');
});

        // Handle clicking on product link to switch back to product images
        $('.product-link').on('click', function(event) {
            event.preventDefault();

            $variantCarousel.addClass('hidden');
            $variantThumbnails.addClass('hidden');
            $productCarousel.removeClass('hidden').slick('setPosition');
            $productThumbnails.removeClass('hidden').slick('setPosition');

            // Remove variant ID from add-to-cart button
            $('.add-to-cart').removeData('variant-id');

            // Restore default product price
            $('.product-price').text(`BC${initialProductPrice}`);
        });

        // Star rating hover and click functionality
        $('.star-rating .fa-star').on('mouseover', function() {
            const onStar = parseInt($(this).data('value'), 10);
            $(this).parent().children('i.fa-star').each(function(e) {
                if (e < onStar) {
                    $(this).addClass('hover');
                } else {
                    $(this).removeClass('hover');
                }
            });
        }).on('mouseout', function() {
            $(this).parent().children('i.fa-star').removeClass('hover');
        });

        $('.star-rating .fa-star').on('click', function() {
            const onStar = parseInt($(this).data('value'), 10);
            const stars = $(this).parent().children('i.fa-star');

            if ($(this).hasClass('selected') && $(this).next().hasClass('selected') === false) {
                $(this).removeClass('selected');
                $('#rating').val(0);
            } else {
                stars.removeClass('selected');
                for (let i = 0; i < onStar; i++) {
                    stars.eq(i).addClass('selected');
                }
                $('#rating').val(onStar);
            }
        });

        // Handle delete rating
        $('.delete-rating').on('click', function() {
            const ratingId = $(this).data('rating-id');
            $.ajax({
                url: 'classes/delete_ratings.php', // Ensure this matches your endpoint
                type: 'POST',
                data: { rating_id: ratingId },
                success: function(response) {
                    if (response === 'success') {
                        location.reload(); // Reload the page to update the ratings display
                    } else {
                        alert('Error deleting rating');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', status, error);
                }
            });
        });

        // Handle add to cart
        $('.add-to-cart').on('click', function() {
            const productId = $(this).data('product-id');
            const variantId = $(this).data('variant-id');
            console.log('Product ID:', productId, 'Variant ID:', variantId);

            $.ajax({
                url: 'classes/add_to_cart.php',
                type: 'POST',
                data: { product_id: productId, variant_id: variantId },
                success: function(response) {
                    console.log('AJAX response:', response);
                    if (!sessionStorage.getItem('popupShown')) {
                        showPopup();
                        sessionStorage.setItem('popupShown', 'true');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', status, error);
                }
            });
        });

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
    }

    // Initialize features on document ready
    initializeFeatures();
});
