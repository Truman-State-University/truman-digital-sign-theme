<?php if ( have_posts() ) : while ( have_posts() ) : the_post();
    // get all the meta settings
	$postid         = get_the_ID();
	$custom         = get_post_custom( $postid );
	$bgcolor        = $custom["bgcolor"][0];
	$textcolor      = $custom["textcolor"][0];
	$hidetitle      = $custom["hidetitle"][0];
	$fittext        = $custom["fittext"][0];
	$slideimage     = $custom["slideimage"][0];
	$slideimagesize = $custom["slideimagesize"][0];
	$slideduration  = $custom["slideduration"][0];
	$slidevideo     = $custom["slidevideo"][0];
	$youTubeId      = $custom["youTubeId"][0];
	$videoSound     = $custom["videoSound"][0];
	$youtubeSound   = $custom["youtubeSound"][0];

	if ( $slideimage != "" ) {
		$stylestr = " background-image: url('{$slideimage}'); background-size: {$slideimagesize}; background-repeat: no-repeat";
	} else {
		$stylestr = "";
	}
	if ( $fittext == "1" ) {
		$fittextstr = " fittext";
	} else {
		$fittextstr = "";
	}
	if ( $slideduration == "" ) {
		$slideduration = 8;
	}

	?>

    <div class="item<?php if ( $wp_query->current_post == 0 ) {
		echo " active";
	} ?>" data-interval="<?php echo $slideduration * 1000; ?>">
        <div class="slidecontent"
             style="background-color: <?php echo $bgcolor; ?>; color: <?php echo $textcolor; ?>; <?php echo $stylestr; ?>">
			<?php if ( $slidevideo != "" ) {
				?>
                <video controls="false" class="slidevideo" <?php if ( $videoSound != "1" ) {
					echo "muted=\"true\"";
				} ?> playsinline>
                    <source src="<?php echo $slidevideo; ?>" type="video/mp4"/>
                </video>
				<?php
			} ?>
            <div class="youtube" id="youtube<?php echo $postid; ?>"
                 data-video="<?php echo $youTubeId; ?>" <?php if ( $youtubeSound != "1" ) {
				echo "data-muted=\"1\"";
			} ?>>

            </div>
            <div class="textcontent<?php echo $fittextstr; ?>">
				<?php if ( $hidetitle != "1" ) {
					echo( "<h1 class=\"slidetitle\">" . get_the_title() . "</h1>" );
				} ?>
                <div class="textbody">
                    <article><?php the_content(); ?></article>
                </div>
				<?php echo $the_query->current_post; ?>
            </div>
        </div>

    </div>
<?php endwhile; else: ?>
<?php endif;
