<?php
echo("</div>");
if ( get_theme_mod( 'sidebar' ) == '1') {
    if ( get_theme_mod( 'sidebar_align' ) == 'right') {
        echo('<div class="col-md-4 last sidebar">');
        dynamic_sidebar( 'home-right' );
        echo('</div>');
    }
}
echo('</div>');
?>
<div class="row 12-col" id="footer">
    <?php dynamic_sidebar( 'footer' ); ?>

</div>
<?php wp_footer(); /* this is used by many Wordpress features and plugins to work proporly */ ?>
</div>
</body>
</html>