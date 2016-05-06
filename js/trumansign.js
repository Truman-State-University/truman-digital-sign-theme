/**
 * Created by gmarsh on 11/23/15.
 */
var t;
jQuery( window).ready( function(){
    setheights();
    updateIndicators();
    setInterval(updateContent,300000);

    var start = jQuery('#slide-carousel').find('.active').attr('data-interval');
    if(typeof t == 'undefined'){
        t = setTimeout("jQuery('#slide-carousel').carousel('next');", start);
    }
    startVideo();


    jQuery('#slide-carousel').bind('slide.bs.carousel', function (e) {
        clearTimeout(t);
        if (currentvideo) {
            currentvideo.pause();
            currentvideo.currentTime = 0;
        }
    })

    jQuery('#slide-carousel').on('slid.bs.carousel', function () {
        var duration = jQuery(this).find('.active').attr('data-interval');
        t = setTimeout("jQuery('#slide-carousel').carousel('next');", duration);
        startVideo();
    })

} );


jQuery( window ).resize( setheights );

function setheights() {
    winheight = jQuery(window).height();
    jQuery('.slidecontent').height(winheight * .85  );
    jQuery('.sidebar').height(winheight * .85);
    jQuery('#footer').height(winheight * .15);
}

function updateContent(){
    if (ajax_object.refresh_slides == '1') {
        jQuery(".carousel-inner").load(ajax_object.ajax_url + '?action=get_ajax_content', function () {
            setheights();
            updateIndicators();
        });
    }
    if (ajax_object.refresh_footer == '1') {
        jQuery("#footer").load(ajax_object.ajax_url + '?action=get_ajax_sidebar&sidebar=footer', function () {
            if (jQuery('#Date').html() == "") {
                // Create two variable with the names of the months and days in an array
                var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sept", "Oct", "Nov", "Dec"];
                var dayNames = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

                var newDate = new Date();
                // Extract the current date from Date object
                newDate.setDate(newDate.getDate());
                jQuery('#Date').html(dayNames[newDate.getDay()] + ", " + newDate.getDate() + ' ' + monthNames[newDate.getMonth()] + ' ' + newDate.getFullYear());
            }
        });
    }
    if (ajax_object.refresh_sidebar == '1') {
        jQuery(".sidebar").load(ajax_object.ajax_url + '?action=get_ajax_sidebar&sidebar=home-right');
    }
}

function updateIndicators() {
    var bootCarousel = jQuery(".carousel");
    if (jQuery(".carousel-indicators")) {
        var indicators = jQuery(".carousel-indicators");
        indicators.empty();
        bootCarousel.find(".carousel-inner").children(".item").each(function (index) {
            (index === 0) ?
                indicators.append("<li data-target='#slide-carousel' data-slide-to='" + index + "' class='active'></li>") :
                indicators.append("<li data-target='#slide-carousel' data-slide-to='" + index + "'></li>");
        });
    }
}

function startVideo() {
    if (jQuery('#slide-carousel .active').find('.slidevideo').length > 0) {
        currentvideo = jQuery('#slide-carousel .active').find('.slidevideo')[0];
        currentvideo.controls = false;
        currentvideo.loop = true;
        currentvideo.play();
    } else {
        currentvideo = "";
    }
}