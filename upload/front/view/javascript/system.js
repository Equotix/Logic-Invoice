$(document).ready(function () {
    //Form submit for IE Browser
    $('button[type=\'submit\']').on('click', function () {
        $("form[id*='form-']").submit();
    });

    // Highlight any found errors
    $('.text-danger').each(function () {
        var element = $(this).parents('.form-group').addClass('has-error');
    });

    // Tooltips on hover
    $('[data-toggle=\'tooltip\']').tooltip({container: 'body', html: true});

    // Navbar
    $('body').scrollspy({
        target: '.navbar-custom',
        offset: 70
    })

    var navbar = $('.navbar');
    var navHeight = navbar.height();

    $(window).scroll(function () {
        if ($(this).scrollTop() >= navHeight) {
            navbar.addClass('navbar-color');
        } else {
            navbar.removeClass('navbar-color');
        }
    });

    if ($(window).width() <= 767) {
        navbar.addClass('custom-collapse');
    }

    $(window).resize(function () {
        if ($(this).width() <= 767) {
            navbar.addClass('custom-collapse');
        } else {
            navbar.removeClass('custom-collapse');
        }
    });

    $(window).trigger('scroll');
});