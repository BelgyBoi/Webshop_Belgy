$(document).ready(function(){
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
});
