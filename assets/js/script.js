(function($) {

    $(function() {
        /**
         * Scroll horizontal through slides when clicking on arrows.
         */
        var i = 1;

        /**
         * Function to hide an element.
         */
        $.fn.hide = function() {
            this.css("display" , "none");
        };

        $.fn.show = function() {
            this.css("display" , "inline-block");
        };

        /**
         * Returns the amount of slides that fit in the window.
         * @return {Number}
         */
        var getVisibleSlideCount = function() {
            var ww = $(window).width();
            return 1;
        };

        var singleSlide = $(".uhsp-single-slide"),
            availableSlides = singleSlide.length;

        /**
         * Make the slider responsive.
         */
        $(window).on('resize', function() {
            var slideWidth = singleSlide.outerWidth(true);

            if (i > availableSlides - getVisibleSlideCount()) {
                i = availableSlides - getVisibleSlideCount();
            }

            $(".uhsp-slider-images").animate({
                scrollLeft: slideWidth * i
            }, 0, "linear");
        });

        /**
         * Show arrows when more then 3 slides.
         */
        if (availableSlides <= getVisibleSlideCount()) {
            $(".uhsp-slider-wrapper > .uhsp-right").hide();
            $(".uhsp-slider-wrapper > .uhsp-left").hide();
        };

        $(".uhsp-slider-wrapper > .uhsp-right").on('click', function() {
            $(".uhsp-slider-wrapper > .uhsp-left").show();
            if (i < availableSlides - getVisibleSlideCount()) {
                var slideWidth = singleSlide.outerWidth(true);
                i++;
                if (i >= availableSlides - getVisibleSlideCount()) {
                    $(this).hide();
                }

                $(".uhsp-slider-images").animate({
                    left: 0 - slideWidth * i
                }, 600, "swing");
            }
            changeState();
        });

        $(".uhsp-slider-wrapper > .uhsp-left").on('click', function() {
            $(".uhsp-slider-wrapper > .uhsp-right").show();
            if (i > 0) {
                var slideWidth = singleSlide.outerWidth(true);
                i--;
                if (i <= 0) {
                    $(this).hide();
                }

                $(".uhsp-slider-images").animate({
                    left: 0 - slideWidth * i
                }, 600, "swing");
            }
            changeState();
        });

        /**
         * Change the style of the slider text when you go to a different slide.
         */
        var changeState = function() {
            var ww = $(window).width(),
                slideCount = Number(i) + 1,
                barPosition = Number(i) * (100 / availableSlides) + "%";

            /**
             * When clicked on one the title, first remove all the selected states.
             */
            $(".uhsp-slider-titles li").removeClass("selected");

            /**
             * Give the title of the slide you're on the selected state.
             */
            $(".uhsp-title:nth-child(" + slideCount + ")").addClass("selected");

            /**
             * Position the hover bar under the selected title.
             */
            $(".uhsp-hover-bar").css("left", barPosition);

            /**
             * On a small screen, animate the titles in and out of the screen.
             */
            if (ww <= 667) {
                $('.uhsp-slider-titles ul').animate({left: i * "-100%"}, 600, "swing");
            }
        };

        /**
         * Slide and change the style of the slider text when you click on one.
         */
        var slideSlider = function() {
            $(".uhsp-slider-images").animate({
                left: 0 - singleSlide.outerWidth(true) * i
            }, 600, "swing");
        }


        $("li.uhsp-title").on('click', function() {
            $(".uhsp-slider-wrapper > .uhsp-right").show();
            $(".uhsp-slider-wrapper > .uhsp-left").show();
            i = $(this).index();
            if (i === 0) {
                $(".uhsp-slider-wrapper > .uhsp-left").hide();
            }
            if (i === (availableSlides - 1)) {
                $(".uhsp-slider-wrapper > .uhsp-right").hide();
            }
            slideSlider();
            changeState();
        });
    });
})(jQuery);
