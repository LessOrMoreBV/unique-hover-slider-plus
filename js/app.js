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

        /**
         * Make the slider responsive.
         */
        $(window).on('resize', function() {
            var slideWidth = $(".uhsp-single-slide").outerWidth(true);

            if (i > $(".uhsp-single-slide").length - getVisibleSlideCount()) {
                i = $(".uhsp-single-slide").length - getVisibleSlideCount();
            }

            $(".uhsp-slider-images").animate({
                scrollLeft: slideWidth * i
            }, 0, "linear");
        });

        /**
         * Show arrows when more then 3 slides.
         */
        if ($(".uhsp-single-slide").length <= getVisibleSlideCount()) {
            $(".uhsp-slider-wrapper > .uhsp-right").hide();
            $(".uhsp-slider-wrapper > .uhsp-left").hide();
        };

        $(".uhsp-slider-wrapper > .uhsp-right").on('click', function() {
            $(".uhsp-slider-wrapper > .uhsp-left").show();
            if (i < $(".uhsp-single-slide").length - getVisibleSlideCount()) {
                var slideWidth = $(".uhsp-single-slide").outerWidth(true);
                i++;
                if (i >= $(".uhsp-single-slide").length - getVisibleSlideCount()) {
                    $(this).hide();
                }

                $(".uhsp-slider-images").animate({
                    left: 0 - slideWidth * i
                }, 600, "swing");
            }
        });

        $(".uhsp-slider-wrapper > .uhsp-left").on('click', function() {
            $(".uhsp-slider-wrapper > .uhsp-right").show();
            if (i > 0) {
                var slideWidth = $(".uhsp-single-slide").outerWidth(true);
                i--;
                if (i <= 0) {
                    $(this).hide();
                }

                $(".uhsp-slider-images").animate({
                    left: 0 - slideWidth * i
                }, 600, "swing");
            }
        });

        /**
         * Change the style of the slider text when you go to a different slide.
         */
        var changeState = function() {
            var ww = $(window).width();
            $(".uhsp-slider-titles li").removeClass("selected");

            if (i === 0) {
                $(".uhsp-first-title").addClass("selected");
                $(".uhsp-hover-bar").css("left", "calc(3% + 20px)");
                if (ww <= 667) {
                    $('.uhsp-slider-titles ul').animate({left: 0}, 600, "swing");
                }
            } else if (i === 1) {
                $(".uhsp-second-title").addClass("selected");
                $(".uhsp-hover-bar").css("left", "36.3%");
                if (ww <= 667) {
                    $('.uhsp-slider-titles ul').animate({left: "-100%"}, 600, "swing");
                }
            } else if (i === 2) {
                $(".uhsp-third-title").addClass("selected");
                $(".uhsp-hover-bar").css("left", "calc((100% - 20px) - 30%)");
                if (ww <= 667) {
                    $('.uhsp-slider-titles ul').animate({left: "-200%"}, 600, "swing");
                }
            }
        };

        /**
         * Slide and change the style of the slider text when you click on one.
         */
        var slideSlider = function() {
            $(".uhsp-slider-images").animate({
                left: 0 - $(".uhsp-single-slide").outerWidth(true) * i
            }, 600, "swing");
        }

        
        $(".uhsp-left").on('click', function() {
            changeState();
        });

        $(".uhsp-right").on('click', function() {
            changeState();
        });

        $("li.uhsp-first-title").on('click', function() {
            $(".uhsp-slider-wrapper > .uhsp-right").show();
            $(".uhsp-slider-wrapper > .uhsp-left").hide();
            i = 0;
            slideSlider();
            changeState();
        });

        $("li.uhsp-second-title").on('click', function() {
            $(".uhsp-slider-wrapper > .uhsp-right").show();
            $(".uhsp-slider-wrapper > .uhsp-left").show();
            i = 1;
            slideSlider();
            changeState();
        });

        $("li.uhsp-third-title").on('click', function() {
            $(".uhsp-slider-wrapper > .uhsp-right").hide();
            $(".uhsp-slider-wrapper > .uhsp-left").show();
            i = 2;
            slideSlider();
            changeState();
        });
    });
})(jQuery);
