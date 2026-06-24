(function () {
    document.addEventListener('DOMContentLoaded', function () {
        const filterButton = document.getElementById('filterButton');
        if (filterButton) {
            filterButton.addEventListener('click', function () {
                const filterSection = document.getElementById('filterSection');
                if (!filterSection) {
                    return;
                }

                filterSection.style.display = filterSection.style.display === 'none' || filterSection.style.display === ''
                    ? 'block'
                    : 'none';
            });
        }

        if (typeof PureCounter !== 'undefined') {
            new PureCounter();
        }

        if (typeof AOS !== 'undefined') {
            AOS.init({
                once: true
            });
        }

        if (window.jQuery && jQuery.fn.owlCarousel && jQuery('.owl-carousel').length) {
            jQuery('.owl-carousel').owlCarousel({
                loop: true,
                margin: 10,
                nav: false,
                dots: true,
                autoplay: true,
                autoplayTimeout: 3500,
                autoplayHoverPause: true,
                slideTransition: 'ease-in-out',
                responsive: {
                    0: {
                        items: 1
                    },
                    768: {
                        items: 2
                    },
                    1200: {
                        items: 4
                    }
                }
            });
        }

        if (typeof Swiper !== 'undefined' && document.querySelector('.swiper-container')) {
            new Swiper('.swiper-container', {
                slidesPerView: 4,
                spaceBetween: 20,
                loop: true,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev'
                },
                breakpoints: {
                    0: {
                        slidesPerView: 1,
                        spaceBetween: 10
                    },
                    768: {
                        slidesPerView: 2,
                        spaceBetween: 15
                    },
                    1024: {
                        slidesPerView: 3,
                        spaceBetween: 20
                    },
                    1200: {
                        slidesPerView: 4,
                        spaceBetween: 20
                    }
                }
            });
        }
    });
})();