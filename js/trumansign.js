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
    jQuery('#footer').height(winheight * .15);
}

function updateContent(){
    jQuery( ".carousel-inner" ).load( ajax_object.ajax_url + '?action=get_ajax_content', function() {
        setheights();
    } );
    jQuery( "#footer" ).load( ajax_object.ajax_url + '?action=get_ajax_sidebar&sidebar=footer', function() {
        if (jQuery('#Date').html() == "") {
            // Create two variable with the names of the months and days in an array
            var monthNames = [ "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sept", "Oct", "Nov", "Dec" ];
            var dayNames= ["Sun","Mon","Tue","Wed","Thu","Fri","Sat"];

            var newDate = new Date();
            // Extract the current date from Date object
            newDate.setDate(newDate.getDate());		jQuery('#Date').html(dayNames[newDate.getDay()] + ", " + newDate.getDate() + ' ' + monthNames[newDate.getMonth()] + ' ' + newDate.getFullYear());
        }
    } );
//    jQuery( ".sidebar" ).load( ajax_object.ajax_url + '?action=get_ajax_sidebar&sidebar=home-right' );

}