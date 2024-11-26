$(document).ready(function() {
    $('.carousel').slick({
        dots: false,
        infinite: true,
        speed: 300,
        slidesToShow: 1,
        adaptiveHeight: true,
        asNavFor: '.carousel-thumbnails',
        prevArrow: '<button type="button" class="slick-prev">Previous</button>',
        nextArrow: '<button type="button" class="slick-next">Next</button>'
    });

    $('.carousel-thumbnails').slick({
        slidesToShow: 5,
        slidesToScroll: 1,
        asNavFor: '.carousel',
        dots: false,
        centerMode: true,
        focusOnSelect: true,
        arrows: false // Remove next and previous arrows
    });

    $('.add-to-cart').on('click', function() {
        const productId = $(this).data('product-id');
        $.ajax({
            url: 'add_to_cart.php',
            type: 'POST',
            data: { product_id: productId },
            success: function(response) {
                alert('Product added to cart');
            }
        });
    });

    // Star rating hover and click functionality
    $('.star-rating .fa-star').on('mouseover', function() {
        const onStar = parseInt($(this).data('value'), 10); // Get the value of the hovered star
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
        const onStar = parseInt($(this).data('value'), 10); // Get the value of the clicked star
        const stars = $(this).parent().children('i.fa-star');

        if ($(this).hasClass('selected') && $(this).next().hasClass('selected') === false) {
            // Deselect if clicking on the selected star again
            $(this).removeClass('selected');
            $('#rating').val(0);
        } else {
            // Select stars
            stars.removeClass('selected');
            for (let i = 0; i < onStar; i++) {
                stars.eq(i).addClass('selected');
            }
            $('#rating').val(onStar); // Update the hidden input with the rating value
        }
    });
});
