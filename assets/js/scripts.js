/* Portfolio - Добавена проверка за Isotope */
$(window).load(function() {
    var $cont = $('.portfolio-group');
    if ($cont.length > 0 && $.fn.isotope) {
        $cont.isotope({
            itemSelector: '.portfolio-group .portfolio-item',
            masonry: {columnWidth: $('.isotope-item:first').width(), gutterWidth: -20, isFitWidth: true},
            filter: '*',
        });

        $('.portfolio-filter-container a').click(function() {
            $cont.isotope({
                filter: this.getAttribute('data-filter')
            });
            return false;
        });
    }

    var lastClickFilter = null;
    $('.portfolio-filter a').click(function() {
        if (lastClickFilter === null) {
            $('.portfolio-filter a').removeClass('portfolio-selected');
        } else {
            $(lastClickFilter).removeClass('portfolio-selected');
        }
        lastClickFilter = this;
        $(this).addClass('portfolio-selected');
    });
});

/* Image Hover - Добавена проверка за Modernizr */
$(document).ready(function(){
    if (typeof Modernizr !== 'undefined' && Modernizr.touch) {
        $(".close-overlay").removeClass("hidden");
        $(".image-hover figure").click(function(e){
            if (!$(this).hasClass("hover")) {
                $(this).addClass("hover");
            }
        });
        $(".close-overlay").click(function(e){
            e.preventDefault();
            e.stopPropagation();
            if ($(this).closest(".image-hover figure").hasClass("hover")) {
                $(this).closest(".image-hover figure").removeClass("hover");
            }
        });
    } else {
        $(".image-hover figure").mouseenter(function(){
            $(this).addClass("hover");
        }).mouseleave(function(){
            $(this).removeClass("hover");
        });
    }
});

/* Thumbs animations */
$(function () {
    $(".thumbs-gallery i").css("opacity", 0);

    $(".thumbs-gallery i").hover(
        function () {
            $(this).stop().animate({ opacity: 0 }, 300);
            $(".thumbs-gallery i").not($(this)).stop().animate({ opacity: 0.4 }, 300);
        }, 
        function () {
            $(".thumbs-gallery i").stop().animate({ opacity: 0 }, 300);
        }
    );
});

/* Mobile Menu - Инициализация */
$(function(){
    if ($.fn.slicknav) {
        $('#hornavmenu').slicknav({
            prependTo: '#hornav',
            label: 'МЕНЮ'
        });
        $( "div.slicknav_menu" ).addClass( "hidden-lg" );
    }
});

/* Sticky Div - Добавена проверка за Sticky */
$(window).load(function(){
    if ($.fn.sticky) {
        $("#hornav").sticky({ topSpacing: 0 });
    }
});
