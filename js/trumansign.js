/**
 * Created by gmarsh on 11/23/15.
 */
jQuery( window).ready( function(){
    setheights();
    setInterval(updateContent,300000);
} );


jQuery( window ).resize( setheights );

function setheights() {
    winheight = jQuery(window).height();
    jQuery('.slidecontent').height(winheight * .85  );
    jQuery('.sidebar').height(winheight * .85);

}

function updateContent(){
    jQuery( ".carousel-inner" ).load( ajax_object.ajax_url + '?action=get_ajax_content' );
    setheights();
    jQuery( "#footer" ).load( ajax_object.ajax_url + '?action=get_ajax_sidebar&sidebar=footer' );
//    jQuery( ".sidebar" ).load( ajax_object.ajax_url + '?action=get_ajax_sidebar&sidebar=home-right' );
    setheights();
    setInterval(updateContent,300000);
}