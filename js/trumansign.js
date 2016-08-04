/**
 * Created by gmarsh on 11/23/15.
 */
var t;

jQuery.ajaxSetup ({
    // Disable caching of AJAX responses
    cache: false
});

jQuery( window).ready( function(){
    setheights();
    updateIndicators();
    setInterval(updateContent,300000);

    var start = jQuery('#slide-carousel').find('.active').attr('data-interval');
    if(typeof t == 'undefined'){
        t = setTimeout("jQuery('#slide-carousel').carousel('next');", start);
    }
    startVideo();
    sidebarscripts = jQuery('.sidebar script');
    footerscripts = jQuery('#footer script');

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
    jQuery.post( ajax_object.ajax_url + '?action=get_theme_mods', function( data ) {
        console.log(data);
        if (data != ajax_object.theme_mods) {
            location.reload(true);
            return;
        }

    });
    if (ajax_object.refresh_slides == '1') {
        jQuery(".carousel-inner").load(ajax_object.ajax_url + '?action=get_ajax_content', function () {
            setheights();
            updateIndicators();
        });
    }
    if (ajax_object.refresh_footer == '1') {
        jQuery("#footer").load(ajax_object.ajax_url + '?action=get_ajax_sidebar&sidebar=footer', function () {
            jQuery.each(footerscripts, function (key, value) {
                jQuery.getScript(value.src);
            });
        });
    }
    if (ajax_object.refresh_sidebar == '1') {
        jQuery(".sidebar").load(ajax_object.ajax_url + '?action=get_ajax_sidebar&sidebar=home-right', function () {
            jQuery.each(sidebarscripts, function (key, value) {
                jQuery.getScript(value.src);
            });
        });
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


