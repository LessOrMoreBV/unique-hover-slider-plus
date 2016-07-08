(function($) {

    $(function() {
        /**
         * Function to hide an element.
         * @return {void}
         */
        $.fn.hide = function() {
            this.css("display" , "none");
        };

        /**
         * Function to show an element.
         * @return {void}
         */
        $.fn.show = function() {
            this.css("display" , "inline-block");
        };

        /**
         * Reusable variable for the arrows.
         * @type {Element}
         */
        var rightArrow = $(".uhsp-right"),
            leftArrow =  $(".uhsp-left");

        /**
         * Returns the amount of slides that fit in the window.
         * @return {Number}
         */
        var getVisibleSlideCount = function() {
            var ww = $(window).width();
            return 1;
        };

        /**
         * Check how many slides there are.
         * @type {Element}
         */
        var singleSlide = $(".uhsp-single-slide"),
            availableSlides = singleSlide.length;

        // Add class to slider to check how many slides there are.
        $('.uhsp-slider-wrapper').addClass('uhsp-amount-' + availableSlides);

        /**
         * Add selected state to the middle slide and set i.
         * @return {void}
         */
        var middleSlide = Math.round(availableSlides / 2);
        $('.uhsp-title:nth-child(' + middleSlide + ')').addClass('selected');
        var i = middleSlide - 1;

        /**
         * Make the slider responsive.
         * @return {void}
         */
        $(window).on('resize', function() {
            if (i > availableSlides - getVisibleSlideCount()) {
                i = availableSlides - getVisibleSlideCount();
            }

            $(".uhsp-slider-images").css({
                left: -singleSlide.outerWidth(true) * i
            });
        });

        /**
         * Hide arrows when there are no more slides then the one(s) visible.
         */
        if (availableSlides <= getVisibleSlideCount()) {
            rightArrow.hide();
            leftArrow.hide();
        };

        /**
         * Hide left arrow when there's just one more slide then the one(s) visible.
         */
        if (availableSlides == (getVisibleSlideCount() * 2)) {
            leftArrow.hide();
        };

        /**
         * When clicked on the right arrow, add one to i.
         * Hide the right arrow if there are no more slides to the right.
         * Slide to the next slide.
         * Highlight the title of the slide you go to.
         * Show the left arrow so you can slide back.
         * @return {void}
         */
        rightArrow.on('click', function() {
            i++;

            if (i >= availableSlides - getVisibleSlideCount()) {
                $(this).hide();
            }

            slideSlider();
            changeTitleState();
            leftArrow.show();
        });

        /**
         * When clicked on the left arrow, subtract one from i.
         * Hide the left arrow if there are no more slides to the left.
         * Slide to the previous slide.
         * Highlight the title of the slide you go to.
         * Show the right arrow so you can slide back.
         * @return {void}
         */
        leftArrow.on('click', function() {
            i--;

            if (i <= 0) {
                $(this).hide();
            }

            slideSlider();
            changeTitleState();
            rightArrow.show();
        });

        /**
         * When clicked on one of the titles, set i to the index of that title.
         * Hide the arrows.
         * Slide to the slide belonging to that title.
         * Highlight the title you clicked on.
         * Show the left or right arrow(s) if there are slides to the left and or right.
         * @return {void}
         */
        $("li.uhsp-title").on('click', function() {
            rightArrow.hide();
            leftArrow.hide();
            i = $(this).index();
            if (i > 0) {
                leftArrow.show();
            }
            if (i < (availableSlides - 1)) {
                rightArrow.show();
            }

            slideSlider();
            changeTitleState();
        });

        /**
         * Change the style of the slider text when you go to a different slide.
         * @return {void}
         */
        var changeTitleState = function() {
            var ww = $(window).width(),
                slideCount = Number(i) + 1,
                barPosition = Number(i) * (100 / availableSlides) + "%";

            // When clicked on one the title, first remove all the selected states.
            $(".uhsp-title").removeClass("selected");

            // Give the title of the slide you're on the selected state.
            $(".uhsp-title:nth-child(" + slideCount + ")").addClass("selected");

            // Position the hover bar under the selected title.
            $(".uhsp-hover-bar").css("left", barPosition);

            // On a small screen, animate the titles in and out of the screen.
            if (ww <= 667) {
                var slideMobile = Number(i) * -100 + '%';

                $('.uhsp-title-list').animate({left: slideMobile}, 600, "swing");
            }
        };

        /**
         * Slide and change the style of the slider text when you click on one.
         * @return {void}
         */
        var slideSlider = function() {
            $(".uhsp-slider-images").animate({
                left: 0 - singleSlide.outerWidth(true) * i
            }, 600, "swing");
        }
    });
})(jQuery);