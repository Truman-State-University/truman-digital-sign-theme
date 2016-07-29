<!DOCTYPE html>
<html lang="en">
  <head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<link rel="alternate" type="application/rss+xml" title="<?php bloginfo( 'name' ); ?>" href="<?php bloginfo( 'rss2_url' ); ?>" />
	<link rel="alternate" type="application/atom+xml" title="<?php bloginfo( 'name' ); ?>" href="<?php bloginfo( 'atom_url' ); ?>" />

	<?php wp_head(); ?>

</head>

<body>
<div class="container-fluid">
<?php
echo('<div class="row row-no-padding">');
	if ( get_theme_mod( 'sidebar' ) == '1') {
		$sidebarwidth = get_theme_mod( 'sidebar_width', 4 );
		$contentwidth = 12 - $sidebarwidth;
		if ( get_theme_mod( 'sidebar_align' ) == 'right') {
			echo('<div class="col-md-'.$contentwidth.' col-sm-'.$contentwidth.' first">');
		} else {
			if ( get_theme_mod( 'sidebar_align' ) == 'left') {
				echo('<div class="col-md-'.$sidebarwidth.' col-sm-'.$sidebarwidth.' first sidebar">');
				dynamic_sidebar( 'home-right' );
				echo('</div>');
			}
			echo('<div class="col-md-'.$contentwidth.' last">');
		}
	} else {
		echo('<div class="col-md-12">');
	}