/*
* Frontend Script for Elementor
*/
; (function ($) {
    "use strict";

    var editMode = false;
    var isRellax = false;
    var currentDevice = '';


    var animatedProgressbar = function (id, type, value, strokeColor, trailColor, strokeWidth, strokeTrailWidth) {
        var triggerClass = '.ma-el-progress-bar-' + id;
        if ("line" == type) {
            new ldBar(triggerClass, {
                "type": 'stroke',
                "path": 'M0 10L100 10',
                "aspect-ratio": 'none',
                "stroke": strokeColor,
                "stroke-trail": trailColor,
                "stroke-width": strokeWidth,
                "stroke-trail-width": strokeTrailWidth
            }).set(value);
        }
        if ("line-bubble" == type) {
            new ldBar(triggerClass, {
                "type": 'stroke',
                "path": 'M0 10L100 10',
                "aspect-ratio": 'none',
                "stroke": strokeColor,
                "stroke-trail": trailColor,
                "stroke-width": strokeWidth,
                "stroke-trail-width": strokeTrailWidth
            }).set(value);
            $($('.ma-el-progress-bar-' + id).find('.ldBar-label')).animate({
                left: value + '%'
            }, 1000, 'swing');
        }
        if ("circle" == type) {
            new ldBar(triggerClass, {
                "type": 'stroke',
                "path": 'M50 10A40 40 0 0 1 50 90A40 40 0 0 1 50 10',
                "stroke-dir": 'normal',
                "stroke": strokeColor,
                "stroke-trail": trailColor,
                "stroke-width": strokeWidth,
                "stroke-trail-width": strokeTrailWidth,
            }).set(value);
        }
        if ("fan" == type) {
            new ldBar(triggerClass, {
                "type": 'stroke',
                "path": 'M10 90A40 40 0 0 1 90 90',
                "stroke": strokeColor,
                "stroke-trail": trailColor,
                "stroke-width": strokeWidth,
                "stroke-trail-width": strokeTrailWidth,
            }).set(value);
        }
    };


    var getElementSettings = function ($element, setting) {

        var elementSettings = {},
            modelCID = $element.data('model-cid');

        if (elementorFrontend.isEditMode() && modelCID) {
            var settings = elementorFrontend.config.elements.data[modelCID],
                type = settings.attributes.widgetType || settings.attributes.elType,
                settingsKeys = elementorFrontend.config.elements.keys[type];

            if (!settingsKeys) {
                settingsKeys = elementorFrontend.config.elements.keys[type] = [];

                jQuery.each(settings.controls, function (name, control) {
                    if (control.frontend_available) {
                        settingsKeys.push(name);
                    }
                });
            }

            jQuery.each(settings.getActiveControls(), function (controlKey) {
                if (-1 !== settingsKeys.indexOf(controlKey)) {
                    elementSettings[controlKey] = settings.attributes[controlKey];
                }
            });
        } else {
            elementSettings = $element.data('settings') || {};
        }

        return getItems(elementSettings, setting);

    };

    var getItems = function (items, itemKey) {
        if (itemKey) {
            var keyStack = itemKey.split('.'),
                currentKey = keyStack.splice(0, 1);

            if (!keyStack.length) {
                return items[currentKey];
            }

            if (!items[currentKey]) {
                return;
            }

            return this.getItems(items[currentKey], keyStack.join('.'));
        }

        return items;
    };



    var Master_Addons = {

        //
        //         try {
        //         (function($) {
        //
        //         })(jQuery);
        // } catch(e) {
        //         //We can also throw from try block and catch it here
        //         // No Error Show
        //     }
        //
        // Master Addons: Headlines

        MA_Headlines: function ($scope, $) {
            try {
                (function ($) {

                    /*----------- Animated Headlines --------------*/

                    //set animation timing
                    var animationDelay = 2500,
                        //loading bar effect
                        barAnimationDelay = 3800,
                        barWaiting = barAnimationDelay - 3000, //3000 is the duration of the transition on the loading bar - set in the scss/css file
                        //letters effect
                        lettersDelay = 50,
                        //type effect
                        typeLettersDelay = 150,
                        selectionDuration = 500,
                        typeAnimationDelay = selectionDuration + 800,
                        //clip effect
                        revealDuration = 600,
                        revealAnimationDelay = 1500;

                    initHeadline();


                    function initHeadline() {
                        //insert <i> element for each letter of a changing word
                        singleLetters($('.cd-headline.letters').find('b'));
                        //initialise headline animation
                        animateHeadline($('.cd-headline'));
                    }

                    function singleLetters($words) {
                        $words.each(function () {
                            var word = $(this),
                                letters = word.text().split(''),
                                selected = word.hasClass('is-visible');
                            for (i in letters) {
                                if (word.parents('.rotate-2').length > 0) letters[i] = '<em>' + letters[i] + '</em>';
                                letters[i] = (selected) ? '<i class="in">' + letters[i] + '</i>' : '<i>' + letters[i] + '</i>';
                            }
                            var newLetters = letters.join('');
                            word.html(newLetters).css('opacity', 1);
                        });
                    }

                    function animateHeadline($headlines) {
                        var duration = animationDelay;
                        $headlines.each(function () {
                            var headline = $(this);

                            if (headline.hasClass('loading-bar')) {
                                duration = barAnimationDelay;
                                setTimeout(function () { headline.find('.cd-words-wrapper').addClass('is-loading') }, barWaiting);
                            } else if (headline.hasClass('clip')) {
                                var spanWrapper = headline.find('.cd-words-wrapper'),
                                    newWidth = spanWrapper.width() + 10
                                spanWrapper.css('width', newWidth);
                            } else if (!headline.hasClass('type')) {
                                //assign to .cd-words-wrapper the width of its longest word
                                var words = headline.find('.cd-words-wrapper b'),
                                    width = 0;
                                words.each(function () {
                                    var wordWidth = $(this).width();
                                    if (wordWidth > width) width = wordWidth;
                                });
                                headline.find('.cd-words-wrapper').css('width', width);
                            };

                            //trigger animation
                            setTimeout(function () { hideWord(headline.find('.is-visible').eq(0)) }, duration);
                        });
                    }

                    function hideWord($word) {
                        var nextWord = takeNext($word);

                        if ($word.parents('.cd-headline').hasClass('type')) {
                            var parentSpan = $word.parent('.cd-words-wrapper');
                            parentSpan.addClass('selected').removeClass('waiting');
                            setTimeout(function () {
                                parentSpan.removeClass('selected');
                                $word.removeClass('is-visible').addClass('is-hidden').children('i').removeClass('in').addClass('out');
                            }, selectionDuration);
                            setTimeout(function () { showWord(nextWord, typeLettersDelay) }, typeAnimationDelay);

                        } else if ($word.parents('.cd-headline').hasClass('letters')) {
                            var bool = ($word.children('i').length >= nextWord.children('i').length) ? true : false;
                            hideLetter($word.find('i').eq(0), $word, bool, lettersDelay);
                            showLetter(nextWord.find('i').eq(0), nextWord, bool, lettersDelay);

                        } else if ($word.parents('.cd-headline').hasClass('clip')) {
                            $word.parents('.cd-words-wrapper').animate({ width: '2px' }, revealDuration, function () {
                                switchWord($word, nextWord);
                                showWord(nextWord);
                            });

                        } else if ($word.parents('.cd-headline').hasClass('loading-bar')) {
                            $word.parents('.cd-words-wrapper').removeClass('is-loading');
                            switchWord($word, nextWord);
                            setTimeout(function () { hideWord(nextWord) }, barAnimationDelay);
                            setTimeout(function () { $word.parents('.cd-words-wrapper').addClass('is-loading') }, barWaiting);

                        } else {
                            switchWord($word, nextWord);
                            setTimeout(function () { hideWord(nextWord) }, animationDelay);
                        }
                    }

                    function showWord($word, $duration) {
                        if ($word.parents('.cd-headline').hasClass('type')) {
                            showLetter($word.find('i').eq(0), $word, false, $duration);
                            $word.addClass('is-visible').removeClass('is-hidden');

                        } else if ($word.parents('.cd-headline').hasClass('clip')) {
                            $word.parents('.cd-words-wrapper').animate({ 'width': $word.width() + 10 }, revealDuration, function () {
                                setTimeout(function () { hideWord($word) }, revealAnimationDelay);
                            });
                        }
                    }

                    function hideLetter($letter, $word, $bool, $duration) {
                        $letter.removeClass('in').addClass('out');

                        if (!$letter.is(':last-child')) {
                            setTimeout(function () { hideLetter($letter.next(), $word, $bool, $duration); }, $duration);
                        } else if ($bool) {
                            setTimeout(function () { hideWord(takeNext($word)) }, animationDelay);
                        }

                        if ($letter.is(':last-child') && $('html').hasClass('no-csstransitions')) {
                            var nextWord = takeNext($word);
                            switchWord($word, nextWord);
                        }
                    }

                    function showLetter($letter, $word, $bool, $duration) {
                        $letter.addClass('in').removeClass('out');

                        if (!$letter.is(':last-child')) {
                            setTimeout(function () { showLetter($letter.next(), $word, $bool, $duration); }, $duration);
                        } else {
                            if ($word.parents('.cd-headline').hasClass('type')) { setTimeout(function () { $word.parents('.cd-words-wrapper').addClass('waiting'); }, 200); }
                            if (!$bool) { setTimeout(function () { hideWord($word) }, animationDelay) }
                        }
                    }

                    function takeNext($word) {
                        return (!$word.is(':last-child')) ? $word.next() : $word.parent().children().eq(0);
                    }

                    function takePrev($word) {
                        return (!$word.is(':first-child')) ? $word.prev() : $word.parent().children().last();
                    }

                    function switchWord($oldWord, $newWord) {
                        $oldWord.removeClass('is-visible').addClass('is-hidden');
                        $newWord.removeClass('is-hidden').addClass('is-visible');
                    }


                })(jQuery);
            } catch (e) {
                //We can also throw from try block and catch it here
                // No Error Show
            }

        },

        // Master Addons: Accordion
        MA_Accordion: function ($scope, $) {

            var $advanceAccordion = $scope.find(".ma-advanced-accordion"),
                $accordionHeader = $scope.find(".ma-advanced-accordion-header"),
                $accordionType = $advanceAccordion.data("accordion-type"),
                $accordionSpeed = $advanceAccordion.data("toogle-speed");

            // Open default actived tab
            $accordionHeader.each(function () {
                if ($(this).hasClass("active-default")) {
                    $(this).addClass("show active");
                    $(this)
                        .next()
                        .slideDown($accordionSpeed);
                }
            });

            // Remove multiple click event for nested accordion
            $accordionHeader.unbind("click");

            $accordionHeader.click(function (e) {
                e.preventDefault();

                var $this = $(this);

                if ($accordionType === "accordion") {
                    if ($this.hasClass("show")) {
                        $this.removeClass("show active");
                        $this.next().slideUp($accordionSpeed);
                    } else {
                        $this
                            .parent()
                            .parent()
                            .find(".ma-advanced-accordion-header")
                            .removeClass("show active");
                        $this
                            .parent()
                            .parent()
                            .find(".ma-accordion-tab-content")
                            .slideUp($accordionSpeed);
                        $this.toggleClass("show active");
                        $this.next().slideDown($accordionSpeed);
                    }
                } else {

                    // For acccordion type 'toggle'
                    if ($this.hasClass("show")) {
                        $this.removeClass("show active");
                        $this.next().slideUp($accordionSpeed);
                    } else {
                        $this.addClass("show active");
                        $this.next().slideDown($accordionSpeed);
                    }
                }
            });



        },



        // Master Addons: Tabs

        MA_Tabs: function ($scope, $) {

            try {
                (function ($) {

                    var $tabsWrapper = $scope.find('[data-tabs]').eq(0),
                        $tabEffect = $tabsWrapper.data('tab-effect');

                    $tabsWrapper.each(function () {

                        var tab = $(this);
                        var isTabActive = false;
                        var isContentActive = false;
                        tab.find('[data-tab]').each(function () {
                            if ($(this).hasClass('active')) {
                                isTabActive = true;
                            }
                        });
                        tab.find('.ma-el-advance-tab-content').each(function () {
                            if ($(this).hasClass('active')) {
                                isContentActive = true;
                            }
                        });
                        if (!isContentActive) {
                            tab.find('.ma-el-advance-tab-content').eq(0).addClass('active');
                        }
                        if (!isTabActive) {
                            tab.find('[data-tab]').eq(0).addClass('active');
                        }

                        if( $tabEffect == "hover"){
                            tab.find('[data-tab]').hover(function () {
                                tab.find('[data-tab]').removeClass('active');
                                tab.find('.ma-el-advance-tab-content').removeClass('active');
                                $(this).addClass('active');
                                tab.find('.ma-el-advance-tab-content').eq($(this).index()).addClass('active');
                            });
                        } else{
                            tab.find('[data-tab]').click(function () {
                                tab.find('[data-tab]').removeClass('active');
                                tab.find('.ma-el-advance-tab-content').removeClass('active');
                                $(this).addClass('active');
                                tab.find('.ma-el-advance-tab-content').eq($(this).index()).addClass('active');
                            });
                        }


                    });

                })(jQuery);
            } catch (e) {
                //We can also throw from try block and catch it here
                // No Error Show
            }


        },


        //Master Addons: Progressbar
        MA_ProgressBar: function ($scope, $) {

            try {
                (function ($) {

                    const $progressBarWrapper = $scope.find('[data-progress-bar]').eq(0);
                    $progressBarWrapper.waypoint(function () {
                        var element = $(this.element);
                        var id = element.data('id');
                        var type = element.data('type');
                        var value = element.data('progress-bar-value');
                        var strokeWidth = element.data('progress-bar-stroke-width');
                        var strokeTrailWidth = element.data('progress-bar-stroke-trail-width');
                        var color = element.data('stroke-color');
                        var trailColor = element.data('stroke-trail-color');
                        animatedProgressbar(id, type, value, color, trailColor, strokeWidth, strokeTrailWidth);
                        this.destroy();
                    }, {
                        offset: 'bottom-in-view'
                    });

                })(jQuery);
            } catch (e) {
                //We can also throw from try block and catch it here
                // No Error Show
            }


        },

        //Master Addons: Image Filter Gallery
        MA_Image_Filter_Gallery: function ($scope, $) {

            let $ma_el_image_filter_gallery_container = $scope.find('.ma-el-image-filter-gallery');
            let $ma_el_image_filter_gallery_nav = $scope.find('.ma-el-image-filter-nav');

            if ($.isFunction($.fn.imagesLoaded)) {
                $ma_el_image_filter_gallery_container.imagesLoaded(function () {
                    if ($.isFunction($.fn.isotope)) {
                        $ma_el_image_filter_gallery_container.isotope({
                            filter: '*',
                            itemSelector: '.ma-el-image-filter-item',
                        });
                    }
                });
            }

            $ma_el_image_filter_gallery_nav.on('click', 'li', function () {
                $ma_el_image_filter_gallery_nav.find('.active').removeClass('active');
                $(this).addClass('active');

                if ($.isFunction($.fn.isotope)) {
                    var selector = $(this).attr('data-filter');
                    $ma_el_image_filter_gallery_container.isotope({
                        filter: selector,
                    });
                    return false;
                }
            });




            if ($.isFunction($.fn.fancybox)) {
                $('.ma-el-fancybox').fancybox({
                    openEffect	: 'none',
                    closeEffect	: 'none',
                    afterLoad : function(instance, current) {
                        var pixelRatio = window.devicePixelRatio || 1;

                        if ( pixelRatio > 1.5 ) {
                        current.width  = current.width  / pixelRatio;
                        current.height = current.height / pixelRatio;
                        }
                    }
                });
            }


        },

        //Master Addons: Timeline
        MA_Timeline: function ($scope, $) {

            try {
                (function ($) {

                    var horzTimeline = $scope.find(".ma-el-timeline-slider-inner");

                    $(horzTimeline).slick({
                        slidesToShow: 3,
                        slidesToScroll: 1,
                        autoplay: false,
                        autoplaySpeed: 2000,
                        pauseOnHover: false,
                        adaptiveHeight: true,
                        // prevArrow: $('.ma-el-blog-timeline-slider-prev'),
                        // nextArrow: $('.ma-el-blog-timeline-slider-next'),
                        responsive: [
                            {
                                breakpoint: 768,
                                settings: {
                                    arrows: false,
                                    slidesToShow: 2
                                }
                            },
                            {
                                breakpoint: 480,
                                settings: {
                                    arrows: false,
                                    slidesToShow: 1
                                }
                            }
                        ]
                    });


                })(jQuery);
            } catch (e) {
                //We can also throw from try block and catch it here
                // No Error Show
            }


        },

        // Master Addons: Table of Contents
        MA_Table_Of_Contents: function ($scope, $) {

            var tableOfContent = $scope.find(".ma-el-table-of-content"),
                tableDesign = tableOfContent.data('design'),
                tableDropdownMode = tableOfContent.data('dropdown-mode');

            // var content = $scope.find(".ma-el-table-of-content"),
            //     headings = content.querySelectorAll('h1, h2, h3, h4, h5, h6, h7'),
            //     headingMap = {}

            // Array.prototype.forEach.call(headings, function (heading) {
            //     var id = heading.id ? heading.id : heading.textContent.trim().toLowerCase()
            //     .split(' ').join('-').replace(/[!@#$%^&*():]/ig, '')
            //     headingMap[id] = !isNaN(headingMap[id]) ? ++headingMap[id] : 0
            //     if (headingMap[id]) {
            //     heading.id = id + '-' + headingMap[id]
            //     } else {
            //     heading.id = id
            //     }
            // });

            tocbot.init(tableOfContent.data('settings'));


            // Offcanvas TOC
            if (tableDesign == "offcanvas") {
                $('.ma-el-toggle-button').on('click', function () {
                    $('body').addClass('show-sidemenu');
                });

                $('.ma-el-offcanvas-close').on('click', function () {
                    $('body').removeClass('show-sidemenu');
                });
            }

            if (tableDesign == "dropdown") {
                // Dropdown TOC
                $('.ma-el-toggle-button').on('click', function () {
                    $('body').addClass('show-sidemenu');
                });
            }



            // Dopdown on click
            if (tableDropdownMode === "click") {
                var ma_el_drop = $('.ma-el-drop').data('ma-el-drop');

                $('.ma-el-drop').removeClass('animated');
                $('.ma-el-drop').removeClass(ma_el_drop);

                $('.table-of-content-layout-dropdown .ma-el-toggle-button').on('click', function () {
                    $('body').removeClass('show-sidemenu');
                    $('.ma-el-drop').toggleClass('animated');
                    $('.table-of-content-layout-dropdown').toggleClass('show-sidemenu');
                    $('.ma-el-drop').toggleClass(ma_el_drop);
                });
            }

            if (tableDropdownMode === "hover") {
                $('.ma-el-toggle-button').on('hover', function () {
                    $('body').addClass('show-sidemenu');
                });
            }


        },




        //Master Addons: News Ticker
        MA_NewsTicker: function ($scope, $) {

            try {
                (function ($) {
                    $(window).load(function (e) {

                        var newsTickerWrapper = $scope.find(".ma-el-news-ticker"),
                            tickerType = newsTickerWrapper.data('tickertype'),
                            tickerid = newsTickerWrapper.data('tickerid'),
                            feedUrl = newsTickerWrapper.data('feedurl'),
                            feedAnimation = newsTickerWrapper.data('feedanimation'),
                            limitPosts = newsTickerWrapper.data('limitposts'),
                            tickerStyleEffect = newsTickerWrapper.data('scroll'),
                            autoplay = newsTickerWrapper.data('autoplay'),
                            timer = newsTickerWrapper.data('timer');

                        if (tickerType === "content") {

                            $("#" + tickerid + "").breakingNews({
                                effect: "" + tickerStyleEffect + "",
                                autoplay: autoplay,
                                timer: timer,
                                border: false,
                                feed: false,
                                feedlabels: false
                            });
                        }

                        if (tickerType === "feed") {

                            jQuery(function ($) {

                                var feed_container = $("#" + tickerid + ' .ma-el-ticker-content-inner');
                                // console.log( li );


                                $(feed_container).rss(feedUrl,
                                    {
                                        // how many entries do you want?
                                        // default: 4
                                        // valid values: any integer
                                        limit: limitPosts,

                                        // want to offset results being displayed?
                                        // default: false
                                        // valid values: any integer
                                        offsetStart: false, // offset start point
                                        offsetEnd: false, // offset end point

                                        // will request the API via https
                                        // default: false
                                        // valid values: false, true
                                        ssl: true,


                                        // which server should be requested for feed parsing
                                        // the server implementation is here: https://github.com/sdepold/feedr
                                        // default: feedrapp.info
                                        // valid values: any string
                                        // host: 'my-own-feedr-instance.com',


                                        // option to seldomly render ads
                                        // ads help covering the costs for the feedrapp server hosting and future improvements
                                        // default: true
                                        // valid values: false, true
                                        support: false,


                                        // formats the date with moment.js (optional)
                                        // default: 'dddd MMM Do'
                                        // valid values: see http://momentjs.com/docs/#/displaying/
                                        dateFormat: 'MMMM Do, YYYY',


                                        // localizes the date with moment.js (optional)
                                        // default: 'en'
                                        dateLocale: 'de',


                                        // outer template for the html transformation
                                        // default: "<ul>{entries}</ul>"
                                        // valid values: any string


                                        layoutTemplate: '<ul class="ma-el-ticker-content-items">{entries}</ul>',

                                        // inner template for each entry
                                        // default: '<li><a href="{url}">[{author}@{date}] {title}</a><br/>{shortBodyPlain}</li>'
                                        // valid values: any string
                                        // entryTemplate: '<p>{title}</p>',
                                        // entryTemplate: '<li><a href="{url}">[{author}@{date}] {title}</a>{teaserImage}{shortBodyPlain}</li>'
                                        entryTemplate: '<li> {teaserImage} <a href="{url}"> {title}</a></li>',

                                        // the effect, which is used to let the entries appear
                                        // default: 'show'
                                        // valid values: 'show', 'slide', 'slideFast', 'slideSynced', 'slideFastSynced'
                                        effect: feedAnimation,

                                    }, function () {

                                        $("#" + tickerid + "").breakingNews({
                                            effect: "" + tickerStyleEffect + "",
                                            autoplay: autoplay,
                                            timer: timer
                                        });
                                    })
                            });

                        }

                    }); // End of Window load

                })(jQuery);
            } catch (e) {
                //We can also throw from try block and catch it here
                // No Error Show
            }


        },


        /*
         * Master Addons: MA Blog Posts
         */

        MA_Blog: function ($scope, $) {
            var blogElement = $scope.find(".ma-el-blog-wrapper"),
                colsNumber = blogElement.data("col"),
                carousel = blogElement.data("carousel"),
                grid = blogElement.data("grid");

            $scope.find(".ma-el-blog-cats-container li a").click(function (e) {
                e.preventDefault();

                $scope
                    .find(".ma-el-blog-cats-container li .active")
                    .removeClass("active");

                $(this).addClass("active");

                var selector = $(this).attr("data-filter");

                blogElement.isotope({ filter: selector });

                return false;
            });

            var masonryBlog = blogElement.hasClass("ma-el-blog-masonry");

            if (masonryBlog && !carousel) {
                blogElement.imagesLoaded(function () {
                    blogElement.isotope({
                        itemSelector: ".ma-el-post-outer-container",
                        percentPosition: true,
                        animationOptions: {
                            duration: 750,
                            easing: "linear",
                            queue: false
                        }
                    });
                });
            }

            if (carousel && grid) {

                var autoPlay = blogElement.data("play"),
                    speed = blogElement.data("speed"),
                    fade = blogElement.data("fade"),
                    arrows = blogElement.data("arrows"),
                    dots = blogElement.data("dots"),
                    prevArrow = null,
                    nextArrow = null;

                if (arrows) {
                    prevArrow = "<div class='ma-el-team-carousel-prev'><i class='fa fa-angle-left'></i></div>",
                        nextArrow = "<div class='ma-el-team-carousel-next'><i class='fa fa-angle-right'></i></div>"
                    // prevArrow   = '<a type="button" data-role="none" class="carousel-arrow carousel-prev" aria-label="Next" role="button" style=""><i class="fas fa-angle-left" aria-hidden="true"></i></a>',
                    //     nextArrow   = '<a type="button" data-role="none" class="carousel-arrow carousel-next" aria-label="Next" role="button" style=""><i class="fas fa-angle-right" aria-hidden="true"></i></a>';
                } else {
                    prevArrow = prevArrow = '';
                }

                $(blogElement).slick({
                    infinite: true,
                    slidesToShow: colsNumber,
                    slidesToScroll: colsNumber,
                    responsive: [
                        {
                            breakpoint: 769,
                            settings: {
                                slidesToShow: 1,
                                slidesToScroll: 1
                            }
                        },
                        {
                            breakpoint: 481,
                            settings: {
                                slidesToShow: 1,
                                slidesToScroll: 1
                            }
                        }
                    ],
                    autoplay: autoPlay,
                    autoplaySpeed: speed,
                    nextArrow: nextArrow,
                    prevArrow: prevArrow,
                    fade: fade,
                    draggable: true,
                    dots: dots,
                    customPaging: function () {
                        return (
                            '<i class="fas fa-circle"></i>'
                        );
                    }
                });
            }
        },


        /**** MA Image Carousel ****/
        MA_Image_Carousel: function ($scope, $) {

            try {
                (function ($) {

                    let $imageCarouselWrapper = $scope.find('.ma-el-image-carousel').eq(0),
                        $carousel_nav = $imageCarouselWrapper.data("carousel-nav"),
                        $loop = ($imageCarouselWrapper.data("loop") !== undefined) ? $imageCarouselWrapper.data("loop") : false,
                        $slidesToShow = $imageCarouselWrapper.data("slidestoshow"),
                        $slidesToScroll = $imageCarouselWrapper.data("slidestoscroll"),
                        $autoPlay = ($imageCarouselWrapper.data("autoplay") !== undefined) ? $imageCarouselWrapper.data("autoplay") : false,
                        $autoplaySpeed = ($imageCarouselWrapper.data("autoplayspeed") !== undefined) ? $imageCarouselWrapper.data("autoplayspeed") : false,
                        $transitionSpeed = $imageCarouselWrapper.data("speed"),
                        $pauseOnHover = ($imageCarouselWrapper.data("pauseonhover") !== undefined) ? $imageCarouselWrapper.data("pauseonhover") : false


                    // Team Carousel
                    if ($carousel_nav == "arrows") {
                        var arrows = true;
                        var dots = false;
                    } else {
                        var arrows = false;
                        var dots = true;
                    }

                    $imageCarouselWrapper.slick({
                        infinite: $loop,
                        slidesToShow: $slidesToShow,
                        slidesToScroll: $slidesToScroll,
                        autoplay: $autoPlay,
                        autoplaySpeed: $autoplaySpeed,
                        speed: $transitionSpeed,
                        pauseOnHover: $pauseOnHover,
                        dots: dots,
                        arrows: arrows,
                        prevArrow: "<div class='ma-el-team-carousel-prev'><i class='fa fa-angle-left'></i></div>",
                        nextArrow: "<div class='ma-el-team-carousel-next'><i class='fa fa-angle-right'></i></div>",
                        rows: 0,
                        responsive: [
                            {
                                breakpoint: 1024,
                                settings: {
                                    slidesToShow: 3,
                                }
                            },
                            {
                                breakpoint: 576,
                                settings: {
                                    slidesToShow: 2,
                                }
                            }
                        ],
                    });

                })(jQuery);
            } catch (e) {
                //We can also throw from try block and catch it here
                // No Error Show
            }
        },


        /**** MA Team Slider ****/

        MA_TeamSlider: function ($scope, $) {

            let $teamCarouselWrapper = $scope.find('.ma-el-team-members-slider-section').eq(0),
                $team_preset = $teamCarouselWrapper.data("team-preset"),
                $ma_el_team_circle_image_animation = $teamCarouselWrapper.data("ma_el_team_circle_image_animation");

            if ($team_preset == "-content-drawer") {

                // console.log("Content Drawer Loaded");

                try {
                    (function ($) {

                        $('.gridder').gridderExpander({
                            scroll: false,
                            scrollOffset: 0,
                            scrollTo: "panel",                  // panel or listitem
                            animationSpeed: 400,
                            animationEasing: "easeInOutExpo",
                            showNav: true, // Show Navigation
                            nextText: "<span></span>", // Next button text
                            prevText: "<span></span>", // Previous button text
                            closeText: "", // Close button text
                            onStart: function () {
                                //Gridder Inititialized
                            },
                            onContent: function () {
                                //Gridder Content Loaded
                            },
                            onClosed: function () {
                                //Gridder Closed
                            }
                        });

                    })(jQuery);
                } catch (e) {
                    //We can also throw from try block and catch it here
                    // No Error Show
                }


            } else {

                try {
                    (function ($) {

                        let $teamCarouselWrapper = $scope.find('.ma-el-team-members-slider-section').eq(0),
                            $carousel_nav = $teamCarouselWrapper.data("carousel-nav"),
                            $loop = ($teamCarouselWrapper.data("loop") !== undefined) ? $teamCarouselWrapper.data("loop") : false,
                            $slidesToShow = $teamCarouselWrapper.data("slidestoshow"),
                            $slidesToScroll = $teamCarouselWrapper.data("slidestoscroll"),
                            $autoPlay = ($teamCarouselWrapper.data("autoplay") !== undefined) ? $teamCarouselWrapper.data("autoplay") : false,
                            $autoplaySpeed = ($teamCarouselWrapper.data("autoplayspeed") !== undefined) ? $teamCarouselWrapper.data("autoplayspeed") : false,
                            $transitionSpeed = $teamCarouselWrapper.data("speed"),
                            $pauseOnHover = ($teamCarouselWrapper.data("pauseonhover") !== undefined) ? $teamCarouselWrapper.data("pauseonhover") : false


                        // Team Carousel
                        if ($carousel_nav == "arrows") {
                            var arrows = true;
                            var dots = false;
                        } else {
                            var arrows = false;
                            var dots = true;
                        }

                        $teamCarouselWrapper.slick({
                            infinite: $loop,
                            slidesToShow: $slidesToShow,
                            slidesToScroll: $slidesToScroll,
                            autoplay: $autoPlay,
                            autoplaySpeed: $autoplaySpeed,
                            speed: $transitionSpeed,
                            mobileFirst:true,
                            pauseOnHover: $pauseOnHover,
                            dots: dots,
                            arrows: arrows,
                            prevArrow: "<div class='ma-el-team-carousel-prev'><i class='fa fa-angle-left'></i></div>",
                            nextArrow: "<div class='ma-el-team-carousel-next'><i class='fa fa-angle-right'></i></div>",
                            rows: 0,
                            responsive: [

                                    {
                                        breakpoint: 480,
                                        settings: {
                                            dots: dots,
                                            arrow: arrows,
                                            slidesToShow: 1,
                                            rows:1,
                                            slidesPerRow:1,
                                            slidesToScroll: 1
                                        }
                                    },
                                    {
                                        breakpoint: 600,
                                        settings: {
                                            dots: dots,
                                            arrow: arrows,
                                            slidesToShow: 2,
                                            slidesToScroll: 2
                                        }
                                    },
                                    {
                                        breakpoint: 1024,
                                        settings: {
                                            slidesToShow: 3,
                                            slidesToScroll: 3,
                                            infinite: true,
                                            centerMode: false,
                                            dots: dots,
                                            arrow: arrows
                                        }
                                    },

                            ],
                        });

                    })(jQuery);
                } catch (e) {
                    //We can also throw from try block and catch it here
                    // No Error Show
                }

            }

            // else if ($team_preset == "-circle-animation"){
            //     if($ma_el_team_circle_image_animation == "animation_svg_04"){
            //
            //     }
            // }

        },


        MA_ParticlesBG: function ($scope, $) {

            // try {
            //     (function($scope, $) {

            if ($scope.hasClass('ma-el-particle-yes')) {
                let id = $scope.data('id');
                //console.lgo(id);
                let element_type = $scope.data('element_type');
                let pdata = $scope.data('ma-el-particle');
                let pdata_wrapper = $scope.find('.ma-el-particle-wrapper').data('ma-el-pdata');

                if (typeof pdata != 'undefined' && pdata != '') {
                    if ($scope.find('.ma-el-section-bs').length > 0) {
                        $scope.find('.ma-el-section-bs').after('<div class="ma-el-particle-wrapper"' +
                            ' id="ma-el-particle-' + id + '"></div>');
                        particlesJS('ma-el-particle-' + id, pdata);
                    } else {

                        if (element_type == 'column') {

                            $scope.find('.elementor-column-wrap').prepend('<div class="ma-el-particle-wrapper"' +
                                ' id="ma-el-particle-' + id + '"></div>');
                        } else {
                            $scope.prepend('<div class="ma-el-particle-wrapper" id="ma-el-particle-' + id + '"></div>');
                        }

                        particlesJS('ma-el-particle-' + id, pdata);
                    }


                } else if (typeof pdata_wrapper != 'undefined' && pdata_wrapper != '') {

                    // $scope.prepend('<div class="ma-el-particle-wrapper" id="ma-el-particle-'+ id +'"></div>');
                    //console.log('calling particle js else', JSON.parse(pdata_wrapper));
                    if (element_type == 'column') {
                        $scope.find('.elementor-column-wrap').prepend('<div class="ma-el-particle-wrapper"' +
                            ' id="ma-el-particle-' + id + '"></div>');
                    }
                    else {
                        $scope.prepend('<div class="ma-el-particle-wrapper" id="ma-el-particle-' + id + '"></div>');
                    }

                    particlesJS('ma-el-particle-' + id, JSON.parse(pdata_wrapper));
                }

            }
            //
            //     })(jQuery);
            // } catch(e) {
            //     //We can also throw from try block and catch it here
            //     // No Error Show
            // }



        },

        MA_BgSlider: function ($scope, $) {
            var ma_el_slides = [];
            var ma_el_slides_json = [];
            var ma_el_transition;
            var ma_el_animation;
            var ma_el_custom_overlay;
            var ma_el_overlay;
            var ma_el_cover;
            var ma_el_delay;
            var ma_el_timer;
            var slider_wrapper = $scope.children('.ma-el-section-bs').children('.ma-el-section-bs-inner');

            if (slider_wrapper && slider_wrapper.data('ma-el-bg-slider')) {

                var slider_images = slider_wrapper.data('ma-el-bg-slider');
                ma_el_transition = slider_wrapper.data('ma-el-bg-slider-transition');
                ma_el_animation = slider_wrapper.data('ma-el-bg-slider-animation');
                ma_el_custom_overlay = slider_wrapper.data('ma-el-bg-custom-overlay');
                if (ma_el_custom_overlay == 'yes') {
                    ma_el_overlay = jltma_scripts.plugin_url + 'assets/lib/vegas/overlays/' + slider_wrapper.data('ma-el-bg-slider-overlay');
                } else {
                    if (slider_wrapper.data('ma-el-bg-slider-overlay')) {
                        ma_el_overlay = jltma_scripts.plugin_url + 'assets/lib/vegas/overlays/' + slider_wrapper.data('ma-el-bg-slider-overlay');
                    } else {
                        ma_el_overlay = jltma_scripts.plugin_url + 'assets/lib/vegas/overlays/' + slider_wrapper.data('ma-el-bg-slider-overlay');
                    }
                }

                ma_el_cover = slider_wrapper.data('ma-el-bg-slider-cover');
                ma_el_delay = slider_wrapper.data('ma-el-bs-slider-delay');
                ma_el_timer = slider_wrapper.data('ma-el-bs-slider-timer');

                if (typeof slider_images != 'undefined') {
                    ma_el_slides = slider_images.split(",");

                    jQuery.each(ma_el_slides, function (key, value) {
                        var slide = [];
                        slide.src = value;
                        ma_el_slides_json.push(slide);
                    });

                    slider_wrapper.vegas({
                        slides: ma_el_slides_json,
                        transition: ma_el_transition,
                        animation: ma_el_animation,
                        overlay: ma_el_overlay,
                        cover: ma_el_cover,
                        delay: ma_el_delay,
                        timer: ma_el_timer,
                        init: function () {
                            if (ma_el_custom_overlay == 'yes') {
                                var ob_vegas_overlay = slider_wrapper.children('.vegas-overlay');
                                ob_vegas_overlay.css('background-image', '');
                            }
                        }
                    });

                }
            }
        },

        MA_AnimatedGradient: function ($scope, $) {

            if ($scope.hasClass('ma-el-animated-gradient-yes')) {
                let id = $scope.data('id');
                let color = $scope.data('color');
                let angle = $scope.data('angle');
                let gradient_color = 'linear-gradient(' + angle + ',' + color + ')';
                let heading = $scope.find('.elementor-heading-title');
                $scope.css('background-image', gradient_color);

                if ($scope.hasClass('elementor-element-edit-mode')) {
                    color = $scope.find('.animated-gradient').data('color');
                    angle = $scope.find('.animated-gradient').data('angle');
                    let gradient_color_editor = 'linear-gradient(' + angle + ',' + color + ')';
                    // console.log(gradient_color_editor);
                    $scope.prepend('<div class="animated-gradient" style="background-image : ' + gradient_color_editor + ' "></div>');
                    //$scope.find('.animated-gradient').css('background-image', gradient_color_editor);
                    //$scope.find('.animated-gradient').css('background-color', 'red');
                }
                //$scope.css('position', 'relative');
                //$scope.css('background-color', 'black');

            }

        },


        MA_Scroll_Progress: function ($scope, $) {
            var currentState = document.body.scrollTop || document.documentElement.scrollTop;
            var pageHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            var scrollStatePercentage = (currentState / pageHeight) * 100;
            document.querySelector(".ma-el-page-scroll-indicator > .ma-el-scroll-indicator").style.width = scrollStatePercentage + "%";
        },

        MA_Image_Comparison: function ($scope, $) {
            $scope = $scope || $(this);

            // $scope.find('.ma-el-image-comparison').imagesLoaded( function(){
            // init before after slider
            $scope.find('.ma-el-image-comparison').each(function () {
                var $slider = $(this);
                $slider.twentytwenty({
                    default_offset_pct: $slider.data('offset') || 0.5,
                    orientation: 'horizontal'
                });
                // });
            });
        },


        MA_Content_Scroll_Indicator: function ($scope, $) {
            $scope.waypoint(function (direction) {
                // On scroll execute the scrollProgress function
                window.onscroll = function () { Master_Addons.MA_Scroll_Progress() };
            });
        },


        MA_PiechartsHandlerOnScroll: function ($scope, $) {

            $scope.waypoint(function (direction) {

                Master_Addons.MA_PiechartsHandler($(this.element), $);

            }, {
                offset: (window.innerHeight || document.documentElement.clientHeight) - 100,
                triggerOnce: true
            });
        },

        MA_PiechartsHandler: function ($scope, $) {

            $scope.find('.ma-el-piechart .ma-el-percentage').each(function () {

                var track_color = $(this).data('track-color');
                var bar_color = $(this).data('bar-color');

                $(this).easyPieChart({
                    animate: 2000,
                    lineWidth: 10,
                    barColor: bar_color,
                    trackColor: track_color,
                    scaleColor: false,
                    lineCap: 'square',
                    size: 220

                });

            });

        },

        StatsBarHandler: function ($scope, $) {

            $scope.find('.ma-el-stats-bar-content').each(function () {

                var dataperc = $(this).data('perc');

                $(this).animate({ "width": dataperc + "%" }, dataperc * 20);

            });

        },

        StatsBarHandlerOnScroll: function ($scope, $) {

            $scope.waypoint(function (direction) {

                Master_Addons.StatsBarHandler($(this.element), $);

            }, {
                offset: (window.innerHeight || document.documentElement.clientHeight) - 150,
                triggerOnce: true
            });

        },


        // Master Addons: Counter Up
        MA_Counter_Up: function( $scope, $ ) {
            var $counterup = $scope.find(".jltma-counter-up-number");

            if ( $.isFunction($.fn.counterUp) ) {
                $counterup.counterUp({
                    delay: 15,
                    time: 2000
                });
            }
        },


        // Master Addons: Countdown Timer
        MA_CountdownTimer: function ($scope, $) {

            var $countdownWidget = $scope.find(".ma-el-widget-countdown");
            $.fn.MasterCountDownTimer = function () {
                var $wrapper = $(this).find(".ma-el-countdown-wrapper "),
                    data = {
                        year: $wrapper.data("countdown-year"),
                        month: $wrapper.data("countdown-month"),
                        day: $wrapper.data("countdown-day"),
                        hour: $wrapper.data("countdown-hour"),
                        min: $wrapper.data("countdown-min"),
                        sec: $wrapper.data("countdown-sec")
                    },

                    targetDate = new Date(data.year, data.month, data.day, data.hour, data.min, data.sec);
                var $year = $wrapper.find('.ma-el-countdown-year'),
                    $month = $wrapper.find('.ma-el-countdown-month'),
                    $day = $wrapper.find('.ma-el-countdown-day'),
                    $hour = $wrapper.find('.ma-el-countdown-hour'),
                    $min = $wrapper.find('.ma-el-countdown-min'),
                    $sec = $wrapper.find('.ma-el-countdown-sec');

                setInterval(function () {
                    var diffTime = (Date.parse(targetDate) - Date.parse(new Date())) / 1000;

                    if (diffTime < 0) return;

                    $year.text(Math.floor(diffTime / (31536000))); // 1 year = 3153600 second
                    $month.text(Math.floor((diffTime / 2592000) % 12)); // 1 month = 2592000 second
                    $day.text(Math.floor((diffTime / 86400) % 365)); // 1 day = 86400 second
                    $hour.text(Math.floor((diffTime / 3600) % 24)); // 1 hour = 3600 second
                    $min.text(Math.floor((diffTime / 60) % 60)); // 1 min  = 60 second
                    $sec.text(Math.floor((diffTime) % 60));
                }, 1e3)
            }, $countdownWidget.each(function () {
                $(this).MasterCountDownTimer()
            })


        },

        /**
         * Fancybox popup
         */
        MA_Fancybox_Popup: function ($scope, $) {

            try {
                (function ($) {
                    if ($.isFunction($.fn.fancybox)) {
                        $("[data-fancybox]").fancybox({});
                    }
                })(jQuery);
            } catch (e) {
                //We can also throw from try block and catch it here
                // No Error Show
            }

        },

        /*
        * REVEAL
        */
        MA_Reveal: function ($scope, $) {
            var isReveal = false;
            // console.log('scrope', $scope );

            var elementSettings = getElementSettings($scope);
            // Master_Addons.getElementSettings = getElementSettings($scope);
            // console.log( 'ma setting', Master_Addons.getElementSettings );
            // console.log( 'setting', getElementSettings( $scope ) );

            var rev1;
            var revealAction = function () {
                rev1 = new RevealFx(revealistance, {
                    revealSettings: {
                        bgcolor: elementSettings.reveal_bgcolor,
                        direction: elementSettings.reveal_direction,
                        duration: Number(elementSettings.reveal_speed.size) * 100,
                        delay: Number(elementSettings.reveal_delay.size) * 100,
                        onCover: function (contentEl, revealerEl) {
                            contentEl.style.opacity = 1;
                        }
                    }
                });
            }

            var runReveal = function () {
                rev1.reveal();
            }

            if (elementSettings.enabled_reveal) {

                var revealId = '#reveal-' + $scope.data('id');
                var revealistance = document.querySelector(revealId);

                revealAction();

                var waypointOptions = {
                    offset: '100%',
                    triggerOnce: true
                };
                elementorFrontend.waypoint($(revealistance), runReveal, waypointOptions);
            }

        },

        /*
        * MA Rellax
        */
        MA_Rellax: function ($scope, $) {

            var elementSettings = getElementSettings($scope);
            var rellax = null;

            $(window).on('resize', function () {

                if (rellax) {
                    rellax.destroy();
                    if (rellax)
                        initRellax();
                }
            });

            var initRellax = function () {
                if (elementSettings.enabled_rellax) {

                    currentDevice = elementorFrontend.getCurrentDeviceMode();

                    var setting_speed = 'speed_rellax';
                    var value_speed = 0;

                    if (currentDevice != 'desktop') {
                        setting_speed = 'speed_rellax_' + currentDevice;
                    }

                    if (eval('elementSettings.' + setting_speed + '.size'))
                        value_speed = eval('elementSettings.' + setting_speed + '.size');


                    var rellaxId = '#rellax-' + $scope.data('id');

                    if ($(rellaxId).length)
                        rellax = new Rellax(rellaxId, {
                            speed: value_speed
                        }
                        );
                    isRellax = true;
                };
            };

            initRellax();

        },

        MA_Rellax_Final: function (panel, model, view) {
            Master_Addons.getElementSettings = getElementSettings($scope);

            // Master_Addons.initRellax($scope, $);


            // var WidgetElements_RellaxHandler = function (panel, model, view) {
            //console.log( $scope );
            //alert('Rellax');
            var $scope = view.$el;
            var scene = $scope.find('#scene');
            //var parallax = new Rellax( scene[0] );

            // };


        },

        /**
         * Restrict Content
         */
        MA_Restrict_Content_Ajax: function ($scope, $) {

            Master_Addons.getElementSettings = getElementSettings($scope);

            var $restrictwrapper    = $scope.find('.ma-el-restrict-content-wrap').eq(0),
                $scopeId            = $scope.data('id'),
                $restrict_layout    = $restrictwrapper.data('restrict-layout-type'),
                $restrict_type      = $restrictwrapper.data('restrict-type'),
                $error_message      = $restrictwrapper.data('error-message'),
                $rc_ajaxify         = $restrictwrapper.data('rc-ajaxify'),

                $storageID          = 'ma_el_rc_' + $scopeId,
                $formID             = $scope.find('.ma-el-restrict-form').eq(0).data('form-id'),

                // Content
                $content_div         = '#restrict-content-' + $scopeId,

                // Popup Settings
                $popup              = $scope.find( '.ma-el-restrict-content-popup-content' ),
                $content_pass       = $restrictwrapper.data('content-pass') ? $restrictwrapper.data('content-pass') : '',
                $popup_type         = $popup.data('popup-type') ? $popup.data('popup-type') : '',

                // Restrict Age
                $age_wrapper        = $scope.find('.ma-el-restrict-age-wrapper').eq(0),

                $restrict_age       = {
                    min_age             : $age_wrapper.data('min-age'),
                    age_type            : $age_wrapper.data('age-type'),
                    age_title           : $age_wrapper.data('age-title'),
                    age_content         : $age_wrapper.data('age-content'),
                    age_submit          : $( '#' + $formID ).find('button[name="submit"]').val(),
                    checkbox_msg        : $age_wrapper.data('checkbox-msg') ? $age_wrapper.data('checkbox-msg') : "",
                    empty_bday          : $age_wrapper.data('empty-bday') ? $age_wrapper.data('empty-bday') : "",
                    non_exist_bday      : $age_wrapper.data('non-exist-bday') ? $age_wrapper.data('non-exist-bday') : ""
                };


                //Check it the user has been accpeted the agreement
                if (localStorage.getItem($storageID)) {

                    $( '.ma-el-rc-button' ).addClass('d-none');
                    $('#' + $formID).addClass('d-none');
                    $('#ma-el-restrict-age-' + $scopeId).removeClass('card');
                    $('#ma-el-restrict-age-' + $scopeId).removeClass('text-center');
                    $('#restrict-content-' + $scopeId).addClass('d-block');

                } else{

                    // Dom Selector for Onpage/Popup
                    if ($restrict_layout == "popup") {
                        var dom_selector = '#ma-el-rc-modal-'  + $scopeId;
                    } else {
                        var dom_selector = '#ma-el-restrict-content-' + $scopeId;
                    }

                    $( dom_selector ).on( 'click', '.ma_el_ra_select', function() {
                        var wrap = $( this ).closest( '.ma_el_ra_select_wrap' );
                        if( !wrap.find( '.ma_el_ra_options' ).hasClass( 'ma_el_ra_active' ) ) {
                            $( '.ma_el_ra_options' ).removeClass( 'ma_el_ra_active' );
                            wrap.find( '.ma_el_ra_options' ).addClass( 'ma_el_ra_active' );
                            wrap.find( '.ma_el_ra_options' ).find( 'li:contains("' + wrap.find( '.ma_el_ra_select_val' ).html() + '")' ).addClass( 'ma_el_ra_active' );
                        }
                        else {
                            wrap.find( '.ma_el_ra_options' ).removeClass( 'ma_el_ra_active' );
                        }
                    });

                    $( dom_selector ).on( 'click', '.ma_el_ra_options ul li', function() {
                        var wrap = $( this ).closest( '.ma_el_ra_select_wrap' );
                        wrap.find( '.ma_el_ra_select_val' ).html( $( this ).html() );
                        wrap.find( 'select' ).val( $( this ).attr( 'data-val' ) );
                        wrap.find( '.ma_el_ra_options' ).removeClass( 'ma_el_ra_active' );
                    });

                    $( dom_selector ).on( 'mouseover', '.ma_el_ra_options ul li', function() {
                        if ( $( '.ma_el_ra_options ul li' ).hasClass( 'ma_el_ra_active' ) ) {
                            $( '.ma_el_ra_options ul li' ).removeClass( 'ma_el_ra_active' );
                        }
                    });

                    $( document ).click( function(e) {
                        if( $( e.target ).attr( 'class' ) != 'ma_el_ra_select' && !$( '.ma_el_ra_select' ).find( $( e.target ) ).length ) {
                            if( $( '.ma_el_ra_options.ma_el_ra_active' ).length ) {
                                $( '.ma_el_ra_options' ).removeClass( 'ma_el_ra_active' );
                            }
                        }
                    });


                    //Onload Fancybox
                    if( $popup_type == "windowload" || $popup_type=="windowloadfullscreen"){
                           $( "#ma-el-rc-modal-hidden" ).fancybox().trigger('click');
                    }else{
                        $("[data-fancybox]").fancybox({});
                    }

                    $( dom_selector ).on( 'submit', '#' + $formID , function(event) {
                        event.preventDefault();

                        var form = $( this );
                        form.find( '.ma_el_rc_result' ).remove();

                        $.ajax({
                            type: "POST",
                            url: jltma_scripts.ajaxurl,
                            data: {
                                action: 'ma_el_restrict_content',
                                fields: form.serialize(),
                                restrict_type: $restrict_type,
                                error_message: $error_message,
                                content_pass: $content_pass,
                                restrict_age: $restrict_age
                            },
                            cache: false,
                            success: function (result) {

                                try {
                                    result = jQuery.parseJSON( result );

                                    // console.log(result);

                                    if ( result['result'] == 'success' ) {

                                        $('#restrict-content-'+ $scopeId).removeClass('d-none').addClass('d-block');

                                        //Custom Classes add/remove
                                        $('#' + $formID).addClass('d-none');
                                        $('#ma-el-restrict-age-' + $scopeId).removeClass('card');
                                        $('#ma-el-restrict-age-' + $scopeId).removeClass('text-center');


                                        //Set a cookie to remember the state
                                        localStorage.setItem($storageID, true);
                                        $.fancybox.close();

                                        $('.ma-el-rc-button').addClass('d-none');

                                    } else if ( result['result'] == 'validate' ) {
                                        $( '#' + $formID + ' ' + '.ma_el_rc_submit' ).after( '<div class="ma_el_rc_result"><span class="eicon-info-circle-o"></span> ' + result['output'] + '</div>' );
                                    } else {
                                        throw 0;
                                    }
                                }
                                catch(err) {
                                    $(  '#' + $formID + ' ' + '.ma_el_rc_submit' ).after( '<div class="ma_el_rc_result"><span class="eicon-loading"></span> Failed, please try again.</div>' );
                                }

                            }
                        }); // ajax part end

                    }); // End of Submit Event


                } // localstorage


        },

        MA_Restrict_Content: function ($scope, $) {

            try {
                (function ($) {
                    Master_Addons.getElementSettings = getElementSettings($scope);

                    var $restrictwrapper = $scope.find('.ma-el-restrict-content-wrap').eq(0),
                        $scopeId = $scope.data('id'),
                        $restrict_layout = $restrictwrapper.data('restrict-layout-type'),
                        $restrict_type = $restrictwrapper.data('restrict-type'),

                        $storageID = 'ma_el_rc',

                        // Popup Settings
                        $popup = $scope.find('.ma-el-restrict-content-popup-content'),
                        $content_pass = $restrictwrapper.data('content-pass'),

                        // Restrict Age
                        $age_wrapper = $scope.find('.ma-el-restrict-age-wrapper').eq(0),
                        $min_age = $age_wrapper.data('min-age'),
                        $age_type = $age_wrapper.data('age-type'),
                        $age_title = $age_wrapper.data('age-title'),
                        $age_content = $age_wrapper.data('age-content'),
                        $checkbox_msg = $age_wrapper.data('checkbox-msg');

                    Master_Addons.MA_Restrict_Content_Ajax($scope, $);

                })(jQuery);
            } catch (e) {
                //We can also throw from try block and catch it here
                // No Error Show
            }
        },

        MA_Nav_Menu: function ($scope, $) {
            Master_Addons.getElementSettings = getElementSettings($scope);

            var $menuContainer = $scope.find(".jltma-nav-menu-element"),
                $menuID = $menuContainer.data("menu-id"),
                $menu_type = $menuContainer.data("menu-layout"),
                $menu_trigger = $menuContainer.data("menu-trigger"),
                $menu_offcanvas = $menuContainer.data("menu-offcanvas"),
                $menu_toggletype = $menuContainer.data("menu-toggletype"),
                $submenu_animation = $menuContainer.data("menu-animation"),
                $menu_container_id = $menuContainer.data("menu-container-id"),
                $sticky_type = $menuContainer.data("sticky-type"),
                navbar_height = $('#' + $menu_container_id).outerHeight(),
                menu_container_selector = $('#' + $menu_container_id);

            // refresh window on resize
            // $(window).on('resize',function(){location.reload();});


            /* One Page Menu */
            if ($menu_type == "onepage") {

                $(document).on('click', '.jltma-navbar-nav li a', function (e) {
                    if ($(this).attr('href')) {
                        var self = $(this),
                            el = self.get(0),
                            href = el.href,
                            hasHash = href.indexOf('#'),
                            enable = self.parents('.jltma-navbar-nav-default').hasClass('jltma-one-page-enabled');

                        if (hasHash !== -1 && (href.length > 1) && enable && (el.pathname == window.location.pathname)) {
                            e.preventDefault();
                            self.parents('.jltma-menu-container').find('.jltma-close').trigger('click');
                        }
                    }
                });

            } else {


                // Submenu Hover Animation Effect
                var submenu_animate_class = 'animated ' + $submenu_animation,
                    submenu_selector = $('.jltma-dropdown.jltma-sub-menu');
                $("#" + $menuID + " .jltma-menu-has-children").hover( function () {
                    if (submenu_selector.hasClass('fade-up')) {
                        submenu_selector.removeClass('fade-up');
                    }
                    if (submenu_selector.hasClass('fade-down')) {
                        submenu_selector.removeClass('fade-down');
                    }
                    $('.jltma-dropdown.jltma-sub-menu').addClass( $submenu_animation );
                });



                /* On Scroll Fixed Navbar */
                ///////////////// fixed menu on scroll for Desktop
                if ($sticky_type == "fixed-onscroll") {
                    if ($(window).width() > 768 ) {
                        $(function() {
                            $(window).scroll(function() {
                                var scroll = $(window).scrollTop();
                                if (scroll >= 10) {
                                    menu_container_selector.removeClass(''+$menu_container_id +'').addClass("jltma-on-scroll-fixed");
                                } else {
                                    menu_container_selector.removeClass("jltma-on-scroll-fixed").addClass(''+$menu_container_id +'');
                                }
                            });
                        });
                    }
                }


                if ($sticky_type == "sticky-top") {
                    if ($(window).width() > 768 ) {
                        $(function() {
                            $(window).scroll(function() {
                                var scroll = $(window).scrollTop();
                                if (scroll >= 10) {
                                    menu_container_selector.removeClass(''+$menu_container_id +'').addClass("sticky-top");
                                } else {
                                    menu_container_selector.removeClass("sticky-top").addClass(''+$menu_container_id +'');
                                }
                            });
                        });
                    }
                }


                if ($sticky_type == "smart-scroll") {

                    // add padding top to show content behind navbar
                    $('body').css('padding-top', navbar_height + 'px');
                        menu_container_selector.addClass('jltma-smart-scroll');

                    //////////////////////// detect scroll top or down
                    if ($('.jltma-smart-scroll').length > 0) { // check if element exists
                        var last_scroll_top = 0;

                        $(window).on('scroll', function() {
                            var scroll_top = $(this).scrollTop();
                            if(scroll_top < last_scroll_top) {
                                $('.jltma-smart-scroll').removeClass('scrolled-down').addClass('scrolled-up');
                            }
                            else {
                                $('.jltma-smart-scroll').removeClass('scrolled-up').addClass('scrolled-down');
                            }
                            last_scroll_top = scroll_top;
                        });
                    }
                }


                if ($sticky_type == "nav-fixed-top") {
                    if ($(window).width() > 768 ) {
                        $(function() {
                            // add padding top to show content behind navbar
                            // $('body').css('padding-top', $('#' + $menu_container_id ).outerHeight() + 'px');
                            $('body').css('padding-top', navbar_height + 'px');
                            menu_container_selector.addClass('jltma-fixed-top');

                        });
                    }
                }



                // Menu Settings Megamenu Trigger Effect
                if ($('.jltma-has-megamenu').hasClass('jltma-megamenu-click')) {
                    $('li.jltma-megamenu-click').on('click', function (e) {
                        e.preventDefault;
                        e.stopPropagation();
                        $(this).toggleClass("show");
                        $('.dropdown-menu.jltma-megamenu').toggleClass("show");
                    });
                }
                // else {
                //     $('.jltma-has-megamenu').on('hover', function (e) {
                //         e.preventDefault;
                //         e.stopPropagation();
                //         $(this).toggleClass("show");
                //         $('.dropdown-menu.jltma-megamenu').toggleClass("show");
                //     });
                // }


                if ($menu_toggletype == "toggle") {

                    // Menu Toggle
                    $("#" + $menuID + " .navbar-nav.toggle .jltma-menu-dropdown-toggle").click(function (e) {
                        $(this).parents(".dropdown").toggleClass("open");
                        e.stopPropagation();
                    });
                }





                if ($menu_offcanvas == "toggle-bar") {
                    $(".jltma-nav-panel .navbar-toggler").on("click", function (e) {
                        $('.jltma-burger').toggleClass("jltma-close");
                    });
                }

                // Off Canvas Menu
                if ($menu_offcanvas == "offcanvas" || $menu_offcanvas == "overlay") {

                    // /// offcanvas onmobile
                    $(".jltma-nav-panel .navbar-toggler").on("click", function (e) {
                        e.preventDefault();
                        e.stopPropagation();
                        var offcanvas_id = $(this).attr('data-trigger');
                        $(offcanvas_id).toggleClass("show");
                        $('body').toggleClass("offcanvas-active");
                        $(".jltma-nav-panel ").toggleClass("offcanvas-nav");
                        if ($menu_offcanvas == "overlay") {
                            $(".jltma-nav-panel ").toggleClass("offcanvas-overlay");
                        }
                    });

                    /// Close menu when pressing ESC
                    $(document).on('keydown', function (event) {
                        if (event.keyCode === 27) {
                            $(".mobile-offcanvas").removeClass("show");

                            $(".desktop-offcanvas").removeClass("show");

                            $("body").removeClass("overlay-active");
                        }
                    });

                    $(".btn-close, .jltma-nav-panel .offcanvas-nav, .jltma-nav-panel.desktop .jltma-close, .jltma-close").click(function (e) {
                        $(".jltma-nav-panel ").removeClass("offcanvas-nav");
                        $(".mobile-offcanvas").removeClass("show");

                        $(".desktop-offcanvas").removeClass("show");

                        $("body").removeClass("offcanvas-active");
                        if ($menu_offcanvas == "overlay") {
                            $(".jltma-nav-panel ").removeClass("offcanvas-overlay");
                        }
                    });
                }



            }






        },


        initEvents: function ($scope, $) {

            var mainContainer = document.querySelector('.main-wrap'),
                openCtrl = document.getElementById('btn-search'),
                closeCtrl = document.getElementById('btn-search-close'),
                searchContainer = document.querySelector('.search'),
                inputSearch = searchContainer.querySelector('.search__input');

            openCtrl.addEventListener('click', Master_Addons.openSearch);
            closeCtrl.addEventListener('click', Master_Addons.closeSearch);
            document.addEventListener('keyup', function (ev) {
                // escape key.
                if (ev.keyCode == 27) {
                    Master_Addons.closeSearch();
                }
            });
        },
        openSearch: function ($scope, $) {
            var mainContainer = document.querySelector('.main-wrap'),
                searchContainer = document.querySelector('.search'),
                inputSearch = searchContainer.querySelector('.search__input');
            // Master_Addons.initEvents($scope, $);

            mainContainer.classList.add('main-wrap--move');
            searchContainer.classList.add('search--open');
            setTimeout(function () {
                inputSearch.focus();
            }, 600);
        },

        closeSearch: function ($scope, $) {
            var mainContainer = document.querySelector('.main-wrap'),
                openCtrl = document.getElementById('btn-search'),
                closeCtrl = document.getElementById('btn-search-close'),
                searchContainer = document.querySelector('.search'),
                inputSearch = searchContainer.querySelector('.search__input');

            mainContainer.classList.remove('main-wrap--move');
            searchContainer.classList.remove('search--open');
            inputSearch.blur();
            inputSearch.value = '';
        },

        MA_Header_Search: function ($scope, $) {
            $('body').addClass('js');
            Master_Addons.initEvents($scope, $);

            // function init() {
            //     initEvents();
            // }



            // init();

        }







    };




    $(window).on('elementor/frontend/init', function () {
        if (elementorFrontend.isEditMode()) {
            editMode = true;
        }

        //Global Scripts
        elementorFrontend.hooks.addAction('frontend/element_ready/global', Master_Addons.MA_AnimatedGradient);
        elementorFrontend.hooks.addAction('frontend/element_ready/global', Master_Addons.MA_BgSlider);
        elementorFrontend.hooks.addAction('frontend/element_ready/global', Master_Addons.MA_ParticlesBG);
        elementorFrontend.hooks.addAction('frontend/element_ready/global', Master_Addons.MA_Reveal);
        elementorFrontend.hooks.addAction('frontend/element_ready/global', Master_Addons.MA_Rellax);


        //Element Scripts
        elementorFrontend.hooks.addAction('frontend/element_ready/ma-headlines.default', Master_Addons.MA_Headlines);
        elementorFrontend.hooks.addAction('frontend/element_ready/ma-advanced-accordion.default', Master_Addons.MA_Accordion);
        elementorFrontend.hooks.addAction('frontend/element_ready/ma-tabs.default', Master_Addons.MA_Tabs);
        elementorFrontend.hooks.addAction('frontend/element_ready/ma-progressbar.default', Master_Addons.MA_ProgressBar);
        elementorFrontend.hooks.addAction('frontend/element_ready/ma-team-members-slider.default', Master_Addons.MA_TeamSlider);
        elementorFrontend.hooks.addAction('frontend/element_ready/ma-image-carousel.default', Master_Addons.MA_Image_Carousel);
        elementorFrontend.hooks.addAction('frontend/element_ready/ma-blog-post.default', Master_Addons.MA_Blog);
        elementorFrontend.hooks.addAction('frontend/element_ready/ma-news-ticker.default', Master_Addons.MA_NewsTicker);
        elementorFrontend.hooks.addAction('frontend/element_ready/ma-table-of-contents.default', Master_Addons.MA_Table_Of_Contents);
        elementorFrontend.hooks.addAction('frontend/element_ready/ma-el-countdown-timer.default', Master_Addons.MA_CountdownTimer);
        elementorFrontend.hooks.addAction('frontend/element_ready/jltma-counter-up.default', Master_Addons.MA_Counter_Up);
        elementorFrontend.hooks.addAction('frontend/element_ready/ma-piecharts.default', Master_Addons.MA_PiechartsHandler);
        elementorFrontend.hooks.addAction('frontend/element_ready/ma-timeline.default', Master_Addons.MA_Timeline);
        elementorFrontend.hooks.addAction('frontend/element_ready/ma-image-filter-gallery.default', Master_Addons.MA_Image_Filter_Gallery);
        // elementorFrontend.hooks.addAction('frontend/element_ready/ma-image-filter-gallery.default', Master_Addons.MA_Fancybox_Popup);
        elementorFrontend.hooks.addAction('frontend/element_ready/ma-scroll-indicator.default', Master_Addons.MA_Content_Scroll_Indicator);
        // elementorFrontend.hooks.addAction('frontend/element_ready/ma-el-image-comparison.default', Master_Addons.MA_Image_Comparison);
        elementorFrontend.hooks.addAction('frontend/element_ready/ma-el-restrict-content.default', Master_Addons.MA_Restrict_Content);
        elementorFrontend.hooks.addAction('frontend/element_ready/ma-navmenu.default', Master_Addons.MA_Nav_Menu);
        elementorFrontend.hooks.addAction('frontend/element_ready/ma-search.default', Master_Addons.MA_Header_Search);
        elementorFrontend.hooks.addAction('frontend/element_ready/ma-piecharts.default', Master_Addons.MA_PiechartsHandlerOnScroll);
        elementorFrontend.hooks.addAction('frontend/element_ready/ma-progressbars.default', Master_Addons.StatsBarHandlerOnScroll);


        if (elementorFrontend.isEditMode()) {
            elementorFrontend.hooks.addAction('frontend/element_ready/ma-piecharts.default', Master_Addons.MA_PiechartsHandler);
            elementorFrontend.hooks.addAction('frontend/element_ready/ma-progressbars.default', Master_Addons.StatsBarHandler);
            elementorFrontend.hooks.addAction('frontend/element_ready/ma-news-ticker.default', Master_Addons.MA_NewsTicker);
            elementorFrontend.hooks.addAction('frontend/element_ready/ma-image-filter-gallery.default', Master_Addons.MA_Image_Filter_Gallery);
        }




    });

})(jQuery);