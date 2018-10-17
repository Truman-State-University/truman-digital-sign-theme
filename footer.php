<?php
echo( "</div>" );
if ( get_theme_mod( 'sidebar' ) == '1' ) {
	if ( get_theme_mod( 'sidebar_align' ) == 'right' ) {
		$sidebarwidth = get_theme_mod( 'sidebar_width' );
		echo( '<div class="col-md-' . $sidebarwidth . ' last sidebar">' );
		dynamic_sidebar( 'home-right' );
		echo( '</div>' );
	}
}
echo( '</div>' );
?>
<div class="row 12-col" id="footer">
	<?php dynamic_sidebar( 'footer' ); ?>
</div>
<?php wp_footer(); ?>
</div>
<div id="screensaver">
    <div id="screensaver_content">
		<?php dynamic_sidebar( 'screensaver' ); ?>
    </div>
</div>
</body>
</html>