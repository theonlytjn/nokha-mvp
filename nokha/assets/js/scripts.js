jQuery(document).foundation();
/*
These functions make sure WordPress
and Foundation play nice together.
*/

jQuery(document).ready(function () {

    // Remove empty P tags created by WP inside of Accordion and Orbit
    jQuery('.accordion p:empty, .orbit p:empty').remove();

    // Makes sure last grid item floats left
    jQuery('.archive-grid .columns').last().addClass('end');

    // Adds Flex Video to YouTube and Vimeo Embeds
    jQuery('iframe[src*="youtube.com"], iframe[src*="vimeo.com"]').each(function () {
        if (jQuery(this).innerWidth() / jQuery(this).innerHeight() > 1.5) {
            jQuery(this).wrap("<div class='widescreen flex-video'/>");
        } else {
            jQuery(this).wrap("<div class='flex-video'/>");
        }
    });

    // Scroll to specific values
    // scrollTo is the same
    window.scroll({
        top: 2500,
        left: 0,
        behavior: 'smooth'
    });

    // Scroll certain amounts from current position
    window.scrollBy({
        top: 0, // could be negative value
        left: 0,
        behavior: 'smooth'
    });

});

jQuery(function () {

    jQuery(document).on('scroll', function () {

        if (jQuery(window).scrollTop() > 100) {
            jQuery('.smoothscroll-top').addClass('show');
        } else {
            jQuery('.smoothscroll-top').removeClass('show');
        }
    });

    jQuery('.smoothscroll-top').on('click', scrollToTop);
});

function scrollToTop() {
    verticalOffset = typeof (verticalOffset) != 'undefined' ? verticalOffset : 0;
    element = jQuery('body');
    offset = element.offset();
    offsetTop = offset.top;
    jQuery('html, body').animate({
        scrollTop: offsetTop
    }, 600, 'linear');
}
