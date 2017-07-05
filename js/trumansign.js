/**
 * Created by gmarsh on 11/23/15.
 */
var t;
var loadtime = new Date().getTime();
var youTubeReady = false;
var youTubePlaying = null;
var players = new Array();

jQuery.ajaxSetup ({
    // Disable caching of AJAX responses
    cache: false
});
jQuery( window ).load( function() {
    applyTextFit();
});
jQuery( window).ready( function(){
    setheights();
    updateIndicators();

    var start = jQuery('#slide-carousel').find('.active').attr('data-interval');
    if(typeof t == 'undefined'){
        t = setTimeout("jQuery('#slide-carousel').carousel('next');", start);
    }
    startVideo();

    jQuery('#slide-carousel').bind('slid.bs.carousel', function (e) {
        if (jQuery("#slide-carousel .active").index() == 0) {
            checkForRefresh();
        }

        clearTimeout(t);
        if (currentvideo) {
            currentvideo.pause();
            currentvideo.currentTime = 0;
        }
        if (youTubePlaying == true) {
            players[youTubePlaying].stopVideo();
            players[youTubePlaying].seekTo(0);
            youTubePlaying = null;
        }
        var duration = jQuery(this).find('.active').attr('data-interval');
        applyTextFit();
        t = setTimeout("jQuery('#slide-carousel').carousel('next');", duration);
        startVideo();
    })

    jQuery.post( ajax_object.ajax_url + '?action=get_content_hash', function( data, status ) {
        if (status == 'success') {
            ajax_object.content_hash = data;
        }
    });


} );

function onYouTubeIframeAPIReady() {
    youTubeReady = true;
}
function checkForRefresh() {
    var now = new Date().getTime();
    if (now - loadtime > ajax_object.update_interval) {
        jQuery.post( ajax_object.ajax_url + '?action=get_content_hash', function( data, status ) {
            if ((data != ajax_object.content_hash) && (status == 'success')) {
                window.location.href = window.location.origin + "?" + data;
                return;
            } else {
                loadtime = new Date().getTime();
            }

        });
    }
}


jQuery( window ).resize( setheights );

function setheights() {
    winheight = jQuery(window).height();
    jQuery('.slidecontent').height(winheight * (100-ajax_object.footer_height)/100  );
    jQuery('.sidebar').height(winheight * (100-ajax_object.footer_height)/100);
    if (ajax_object.footer_height != 0) {
        jQuery('#footer').height(winheight * (ajax_object.footer_height) / 100);
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
    youTubeId = jQuery('#slide-carousel .active .youtube').data('video');
    playerId = jQuery('#slide-carousel .active .youtube').attr('id');
        console.log(youTubeId);
    var ytduration = parseInt(jQuery('#slide-carousel .active').attr('data-interval')/1000);
    console.log(ytduration);
    if (youTubeId != '') {
        if (youTubeReady) {
            if (players[playerId]) {
                players[playerId].playVideo();
            } else {
                players[playerId] = new YT.Player(playerId, {
                    height: '100%',
                    width: '100%',
                    playerVars: {
                        autoplay: 1,
                        loop: 1,
                        controls: 0,
                        showinfo: 0,
                        autohide: 1,
                        modestbranding: 1,
                        vq: 'hd1080',
                        end: ytduration
                    },
                    videoId: youTubeId,
                    events: {
                        'onReady': onPlayerReady,
                        'onStateChange': onPlayerStateChange
                    }
                });
                youTubePlaying = playerId;
            }
        } else {
            {
                console.log('setting timeout');
                setTimeout(startVideo, 100);
            }
        }
    }
}

function onPlayerReady(event) {
    event.target.playVideo();
}

var done = false;
function onPlayerStateChange(event) {

}
function stopVideo() {
    player.stopVideo();
}

function applyTextFit() {
    if (jQuery('#slide-carousel .active').find('.fittext').length > 0) {
        currentslide = jQuery('#slide-carousel .active').find('.fittext')[0];
        textFit(currentslide);
        jQuery(currentslide).removeClass('fittext')
    }
}


