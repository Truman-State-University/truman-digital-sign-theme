/**
 * Created by gmarsh on 11/23/15.
 */
jQuery( window).ready( setheights );


jQuery( window ).resize( setheights );

function setheights() {
    winheight = jQuery(window).height();
    jQuery('.slidecontent').height(winheight * .85  );
    jQuery('.sidebar').height(winheight * .85);

}