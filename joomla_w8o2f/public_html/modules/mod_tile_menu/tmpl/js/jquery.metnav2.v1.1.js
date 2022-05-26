/* ---------------------------------------------
MetNav 2 v.1.1
http://sktajbir.com
Copyright (c) 2013 Sk. Tajbir
------------------------------------------------ */

(function ($) {
    $.fn.metnav2 = function (options) {
        var metNavSettings = $.extend({
            shadow: true,
            activatePlusMinus: false
        }, options);

        var shadowClass = "no-shadow";
        var plusMinusClass = "plus-minus";

        if (metNavSettings.shadow) {
            shadowClass = "shadow";
        }

        $(this).each(function () {
            var self = $(this);

            $(self).addClass("m-tile").addClass(shadowClass);
            var tileOneWidth = $(self).height();
            var titleMarginLeft = parseInt($(this).children("label").css("marginLeft").substring(2, 0))

            $(self).children("ul").children("li").each(function () {
                var countSubMenu = $(this).children("ul").children("li").length;

                if (countSubMenu > 0 && metNavSettings.activatePlusMinus) {
                    $(this).children("a").append('<span class="' + plusMinusClass + '">+</span>');
                }
            });

            $(self).mouseenter(function () {
                var tileHeight = $(self).height();
                $(self).bind("HeightChange", function () {
                    if ($(self).children("ul").height() + 8 > tileHeight) {
                        $(self).children("ul").addClass(shadowClass);
                        $(self).removeClass(shadowClass);
                    }
                    else {
                        $(self).children("ul").removeClass(shadowClass);
                        $(self).addClass(shadowClass);
                    }
                });

                $(this).children("img").stop().animate({
                    opacity: 1
                }, 400);

                var marginLeft = $(this).width() - $(this).children("label").width() - titleMarginLeft;
                $(this).children("label").stop().animate({
                    marginLeft: marginLeft
                }, 250);

                $(this).children("ul").children("li").first().stop().fadeIn(200, function GoNext() {
                    $(".tile").trigger("HeightChange");
                    $(this).next().stop().fadeIn(200, GoNext);
                });
            });

            $(self).mouseleave(function () {
                $(this).children("img").stop().animate({
                    opacity: .5
                }, 400);

                $(this).children("label").stop().animate({
                    marginLeft: titleMarginLeft
                }, 550);

                $(this).children("ul").children("li").last().stop().fadeOut(50, function next() {
                    $(this).prev().stop().fadeOut(50, next);
                });

                $(this).addClass(shadowClass).children("ul").removeClass(shadowClass);

                $(self).children("ul").children("li").each(function () {
                    $(this).children("ul").stop().slideUp();
                    $(this).find(".plus-minus").html("+");
                });
            });

            $(self).children("ul").children("li").mouseenter(function () {
                if ($(this).children("ul").children("li").length > 0 && $(this).children("ul").css("display").indexOf("none") != -1) {
                    $(self).children("ul").children("li").each(function () {
                        $(this).children("ul").stop().slideUp();
                        $(this).find(".plus-minus").html("+");
                    });

                    $(this).find(".plus-minus").html("-");
                    $(this).children("ul").slideDown();
                }
            });
        });
    }
})(jQuery);
