<?php get_header(); ?>
    <div id="slide-carousel" class="carousel<?php if ( get_theme_mod( 'slideeffect' ) == '1' ) {
		echo " slide";
	} ?>" data-interval="false">

        <!-- Wrapper for slides -->
        <div class="carousel-inner" role="listbox">
			<?php get_template_part( "slides" ); ?>
        </div>
        <!-- End Wrapper for slides -->

		<?php if ( get_theme_mod( 'indicators' ) == '1' ) { ?>
            <ol class="carousel-indicators"></ol>
		<?php } ?>
    </div>
<?php get_footer(); ?>