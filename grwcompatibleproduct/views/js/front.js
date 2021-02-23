$(document).ready(function() {
    const quantity = parseInt(jQuery('#splide-products--compatibles').attr('data-list-number'));
    if(jQuery('#splide-products--compatibles').length > 0){
        if(quantity > 4){
            var splide = new Splide( '#splide-products--compatibles', {
                type   : 'slide',
                pagination: false,
                arrows: true,
                autoplay: true,
                perPage: 4,
                perMove: 1,
                breakpoints: {
                    '992': {
                        perPage: 3,
                    },
                    '768': {
                        perPage: 2,
                    },
                    '576': {
                        perPage: 1,
                    },
                }
            } ).mount();
        }else{
            var splide = new Splide( '#splide-products--compatibles', {
                type   : 'slide',
                pagination: false,
                arrows: true,
                autoplay: true,
                perPage: 4,
                perMove: 1,
                breakpoints: {
                    '3000': {
                        destroy: true,
                    },
                    '992': {
                        perPage: 3,
                    },
                    '768': {
                        perPage: 2,
                    },
                    '576': {
                        perPage: 1,
                    },
                }
            } ).mount();
        }
    }
});