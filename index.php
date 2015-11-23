<?php get_header(); ?>
	<div id="slide-carousel" class="carousel slide" data-ride="carousel" data-pause="false" data-interval="8000">

		<!-- Wrapper for slides -->
		<div class="carousel-inner" role="listbox">
			<?php if (have_posts()) : while (have_posts()) : the_post();
				$postid = get_the_ID();
				$custom = get_post_custom($postid);
				$bgcolor = $custom["bgcolor"][0];
				$textcolor = $custom["textcolor"][0];
				$hidetitle = $custom["hidetitle"][0];
				$slideimage = $custom["slideimage"][0];
				$slideimagesize = $custom["slideimagesize"][0];

				if ($slideimage != "") {
					$stylestr = " background-image: url('{$slideimage}'); background-size: {$slideimagesize}; background-repeat: no-repeat";
				} else {
					$stylestr = "";
				}
				?>

			<div class="item<?php if ($wp_query->current_post == 0) { echo " active"; } ?>">
				<div class="slidecontent" style="background-color: <?php echo $bgcolor;?>; color: <?php echo $textcolor;?>; <?php echo $stylestr;?>">

					<div class="textcontent">
						<?php if ($hidetitle != "1") {
							echo("<h1>" . get_the_title() ."</h1>");
						} ?>					<?php the_content();?>
						<?php echo $the_query->current_post;?>
					</div>
				</div>

			</div>
			<?php endwhile; else: ?>
			<?php endif; ?>


	</div>
<?php get_footer(); ?>