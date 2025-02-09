(function () {
    var expand;
    expand = function () {
        var $input, $search;
        $search = $('.search');
        $input = $('.input');
        if ($search.hasClass('close')) {
            $search.removeClass('close');
            $input.removeClass('square');
        } else {
            $search.addClass('close');
            $input.addClass('square');
        }
        if ($search.hasClass('close')) {
            $input.focus();
        } else {
            $input.blur();
        }
    };
$(function () {
    var $accordion, $wideScreen;
    $accordion = $('#accordion').children('li');
    $wideScreen = $(window).width() > 767;
    if ($wideScreen) {
        $accordion.on('mouseenter click', function (e) {
            var $this;
            e.stopPropagation();
            $this = $(this);
            if ($this.hasClass('out')) {
                $this.addClass('out');
            } else {
                $this.addClass('out');
                $this.siblings().removeClass('out');
            }
        });
    } else {
        $accordion.on('touchstart touchend', function (e) {
            var $this;
            e.stopPropagation();
            $this = $(this);
            if ($this.hasClass('out')) {
                $this.addClass('out');
            } else {
                $this.addClass('out');
                $this.siblings().removeClass('out');
            }
        });
    }
});
$(function () {
    var $container, $menu, $menubtn, $navbar;
    $menubtn = $('#hb');
    $navbar = $('.navbar');
    $menu = $('.navigation');
    $container = $('.site-inner');
    $menubtn.on('click', function (e) {
        if ($menubtn.hasClass('active')) {
            $menubtn.removeClass('active');
            $menu.removeClass('slide-right');
            $container.removeClass('slide-right');
            $navbar.removeClass('slide-right');
        } else {
            $menubtn.addClass('active');
            $menu.addClass('slide-right');
            $container.addClass('slide-right');
            $navbar.addClass('slide-right');
        }
    });
});
$(function () {
    var $button, clickOrTouch;
    clickOrTouch = 'click touchstart';
    $button = $('#search-button');
    $button.on(clickOrTouch, expand);
});
$(function () {
    var $box;
    $box = $('.sm-box');
    $box.on('click', function (e) {
        e.preventDefault();
        var $this;
        $this = $(this);
        if ($this.hasClass('active')) {
            $this.removeClass('active');
        } else {
            $this.addClass('active');
        }
    });
});
}.call(this));