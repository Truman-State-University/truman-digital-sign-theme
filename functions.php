<?php
// Home Right Widget
// Location: homepage to the right of narrow slider
register_sidebar(array('name'=>'home-right',
		'before_widget' => '<div class="widget-area widget-sidebar">',
		'after_widget' => '</div>',
		'before_title' => '<h4>',
		'after_title' => '</h4>',
));

register_sidebar(array('name'=>'footer',
    'before_widget' => '<div class="widget-area widget-footer col-lg-4 col-md-4 col-sm-4">',
    'after_widget' => '</div>',
    'before_title' => '<h4>',
    'after_title' => '</h4>',
));

add_filter('widget_text', 'do_shortcode');
add_action( 'wp_enqueue_scripts', 'trumansign_scripts' );
add_action( 'admin_print_scripts-post-new.php', 'trumansign_admin_script', 11 );
add_action( 'admin_print_scripts-post.php', 'trumansign_admin_script', 11 );
add_action("add_meta_boxes", "trumansign_meta_boxes");
add_action('save_post', 'trumansign_save_details');
add_action( 'customize_register', 'trumansign_customize_register' );
add_action('after_setup_theme', 'remove_admin_bar');
add_action( 'wp_head', 'trumansign_custom_css_output');
add_action('wp_ajax_get_ajax_content', 'trumansign_ajaxcontent');
add_action('wp_ajax_nopriv_get_ajax_content', 'trumansign_ajaxcontent');
add_action('wp_ajax_get_ajax_sidebar', 'trumansign_ajaxsidebar');
add_action('wp_ajax_nopriv_get_ajax_sidebar', 'trumansign_ajaxsidebar');
add_action('pre_get_posts','change_num_posts');
add_filter('widget_text', 'do_shortcode');


function trumansign_scripts()
{
    wp_enqueue_style('bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css');
    wp_enqueue_style('theme-style', get_stylesheet_uri());
    wp_enqueue_script('bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js', array('jquery'), '3.3.4', true);
    wp_enqueue_script('theme-scripts', get_template_directory_uri() . '/js/trumansign.js', array('jquery'));
    wp_localize_script('theme-scripts', 'ajax_object',
        array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'refresh_slides' => get_theme_mod('refresh_slides', 1),
            'refresh_sidebar' => get_theme_mod('refresh_sidebar', 1),
            'refresh_footer' => get_theme_mod('refresh_footer', 1)
        )
    );
}



function trumansign_admin_script() {
    global $post;
    $post_type = $post->post_type;
    if( 'post' == $post_type ) {
        wp_enqueue_script('jquery-minicolors-script', get_stylesheet_directory_uri() . '/jquery-minicolors-master/jquery.minicolors.js', array('jquery'));
        wp_enqueue_script('trumansign-admin', get_stylesheet_directory_uri() . '/js/trumansign-admin.js', array('jquery'));
        wp_enqueue_style('jquery-minicolors-script', get_stylesheet_directory_uri() . '/jquery-minicolors-master/jquery.minicolors.css');
    }
}

function trumansign_customize_register( $wp_customize )
{
	$wp_customize->add_section(
			'trumansign_settings',
			array(
					'title' => 'Digital Sign Settings',
					'description' => 'Settings for Digital Sign.',
					'priority' => 35,
			)
	);


	$wp_customize->add_setting(
			'sidebar'
	);

	$wp_customize->add_control(
			'sidebar',
			array(
					'type' => 'checkbox',
					'label' => 'Show Sidebar',
					'section' => 'trumansign_settings',
			)
	);

	$wp_customize->add_setting(
			'sidebar_align',
			array(
					'default' => 'left',
			)
	);

	$wp_customize->add_control(
			'sidebar_align',
			array(
					'type' => 'select',
					'label' => 'Sidebar on Which Side:',
					'section' => 'trumansign_settings',
					'choices' => array(
							'left' => 'left',
							'right' => 'right',
					),
			)
	);

    $wp_customize->add_setting(
        'refresh_slides',
			array(
                'default' => '1',
            )
    );

    $wp_customize->add_control(
        'refresh_slides',
        array(
            'type' => 'checkbox',
            'label' => 'Auto Refresh Slides',
            'section' => 'trumansign_settings',
        )
    );

    $wp_customize->add_setting(
        'refresh_sidebar',
        array(
            'default' => '1',
        )
    );

    $wp_customize->add_control(
        'refresh_sidebar',
        array(
            'type' => 'checkbox',
            'label' => 'Auto Refresh Sidebar',
            'section' => 'trumansign_settings',
        )
    );
    $wp_customize->add_setting(
        'refresh_footer',
        array(
            'default' => '1',
        )
    );

    $wp_customize->add_control(
        'refresh_footer',
        array(
            'type' => 'checkbox',
            'label' => 'Auto Refresh Footer',
            'section' => 'trumansign_settings',
        )
    );

    $wp_customize->add_setting(
        'indicators',
        array(
            'default' => '0',
        )
    );

    $wp_customize->add_control(
        'indicators',
        array(
            'type' => 'checkbox',
            'label' => 'Show Indicators',
            'section' => 'trumansign_settings',
        )
    );



    $colors = array(
        array('name' => 'sidebar_background_color',
            'default' => '#000000',
            'label' => 'Sidebar Background Color',
        ),
        array('name' => 'sidebar_text_color',
            'default' => '#ffffff',
            'label' => 'Sidebar Text Color',
        ),
        array('name' => 'footer_background_color',
            'default' => '#000000',
            'label' => 'Footer Background Color',
        ),
        array('name' => 'footer_text_color',
            'default' => '#ffffff',
            'label' => 'Footer Text Color',
        ),

    );

    foreach ($colors as $color) {
        $wp_customize->add_setting( $color['name'], array(
            'default' => $color['default'],
            'sanitize_callback' => 'sanitize_hex_color',
        ) );

        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $color['name'], array(
            'label'        => $color['label'],
            'section'    => 'trumansign_settings',
            'settings'   => $color['name'],
        ) ) );
    }

    $wp_customize->add_setting(
        'sidebar_background_image'
    );

    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            'sidebar_background_image',
            array(
                'label'      => 'Sidebar Background Image',
                'section'    => 'trumansign_settings',
                'settings'   => 'sidebar_background_image',
            )
        )
    );

    $wp_customize->add_setting(
        'sidebar_background_image_size',
        array(
            'default' => 'cover',
        )
    );

    $wp_customize->add_control(
        'sidebar_background_image_size',
        array(
            'type' => 'select',
            'label' => 'Sidebar Background Image Size:',
            'section' => 'trumansign_settings',
            'choices' => array(
                'cover' => 'Crop to Fit',
                'contain' => 'Fit Inside',
                '100% 100%' => 'Stretch'
            ),
        )
    );

    $wp_customize->add_setting(
        'slideeffect'
    );

    $wp_customize->add_control(
        'slideeffect',
        array(
            'type' => 'checkbox',
            'label' => 'Use Slide Effect:',
            'section' => 'trumansign_settings',
        )
    );

    $wp_customize->add_setting(
        'sidebar_width',
        array(
            'default' => '4',
        )
    );

    $wp_customize->add_control(
        'sidebar_width',
        array(
            'type' => 'select',
            'label' => 'Sidebar Width:',
            'section' => 'trumansign_settings',
            'choices' => array(
                '1' => '1/12',
                '2' => '1/6',
                '3' => '1/4',
                '4' => '1/3',
                '5' => '5/12',
                '6' => '1/2'
            ),
        )
    );
}

function trumansign_custom_css_output() {
    echo '<style type="text/css" id="custom-theme-css">';
    echo '.sidebar {';
    echo 'background-color: '. get_theme_mod( 'sidebar_background_color', '' ) . ';';
    echo 'color: '. get_theme_mod( 'sidebar_text_color', '' ) . ';';
    echo 'background-image: url(' . get_theme_mod( 'sidebar_background_image', '' ) . ');';
    echo 'background-size: ' . get_theme_mod( 'sidebar_background_image_size', '' ) . ';';
    echo 'background-repeat: no-repeat;';
    echo '}';
    echo '#footer {background-color: '. get_theme_mod( 'footer_background_color', '' ) . ';}';
    echo '#footer a, .clock, .clock ul li {color: '. get_theme_mod( 'footer_text_color', '' ) . '; }';
    echo '</style>';
}


function trumansign_meta_boxes()
{
    add_meta_box("slidecolor", "Slide Settings", "trumansign_metaboxes", "post", "normal", "high");
}


function trumansign_metaboxes()
{
    global $post;
    $custom = get_post_custom($post->ID);
    $bgcolor = $custom["bgcolor"][0];
    $textcolor = $custom["textcolor"][0];
    $hidetitle = $custom["hidetitle"][0];
    $slideimage = $custom["slideimage"][0];
    $slideimagesize = $custom["slideimagesize"][0];
    $slidevideo = $custom["slidevideo"][0];
    $slideduration = $custom["slideduration"][0];
    if ($slideduration == '') {
        $slideduration = 8;
    }
    ?>
    <p><label for="bgcolor">Select Background Color: </label>
        <input type="text" id="bgcolor" name="bgcolor" value="<?php echo $bgcolor; ?>" style="height: auto;"></p>
    <p><label for="slideimage">Background Image: </label>
        <input type="text" id="slideimage" name="slideimage" value="<?php echo $slideimage; ?>" style="height: auto;" size="70">
        <input type="button" class="button custom_media" name="slideimage_button" id="slideimage_button" value="Upload/Browse"/>
    </p>
    <p><label for="slideimagesize">Background Image Size: </label>
        <select name="slideimagesize" id="slideimagesize">
            <option value="cover"<?php if ($slideimagesize == "cover") { echo "selected=\"selected\""; }; ?>>Crop to Fit</option>
            <option value="contain"<?php if ($slideimagesize == "contain") { echo "selected=\"selected\""; }; ?>>Fit Inside</option>
            <option value="100% 100%"<?php if ($slideimagesize == "100% 100%") { echo "selected=\"selected\""; }; ?>>Stretch</option>
        </select>
    </p>
    <p><label for="slidevideo">Slide Video (Upload MP4 file):</label>
        <input type="text" id="slidevideo" name="slidevideo" value="<?php echo $slidevideo; ?>" style="height: auto;" size="70">
        <input type="button" class="button custom_media" name="slidevideo_button" id="slidevideo_button" value="Upload/Browse"/>
    </p>
    <p><label for="textcolor">Select Text Color: </label>
        <input type="text" id="textcolor" name="textcolor" value="<?php echo $textcolor; ?>" style="height: auto;"></p>
    <p><label for="hidetitle">Hide Title: </label>
        <input type="checkbox" id="hidetitle" name="hidetitle" value="1" <?php if ($hidetitle == 1) { echo "checked=\"checked\""; }; ?>" /></p>
    <p><label for="slideduration">Slide Duration: </label>
        <select name="slideduration" id="slideduration">
            <?php for ($i = 1; $i <= 20; $i++) {
                ?>
                <option value="<?php echo $i; ?>"<?php if ($slideduration == $i) { echo "selected=\"selected\""; }; ?>><?php echo $i; ?></option>
                <?php
            }
            ?>
        </select> Seconds
    </p>

    <?php
}

function trumansign_save_details($post_id)
{
    global $post;
    $slug = 'book';
    if ( 'post' != $post->post_type ) {
        return;
    }
    if ( wp_is_post_revision( $post_id ) ) {
        return;
    }

    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
        return $post_id;
    }
    update_post_meta($post_id, "bgcolor", $_POST["bgcolor"]);
    update_post_meta($post_id, "textcolor", $_POST["textcolor"]);
    if ($_POST["hidetitle"] == "1") {
        update_post_meta($post_id, "hidetitle", "1");
    } else {
        delete_post_meta($post_id, "hidetitle");
    }
    update_post_meta($post_id, "slideimage", $_POST["slideimage"]);
    update_post_meta($post_id, "slidevideo", $_POST["slidevideo"]);
    update_post_meta($post_id, "slideimagesize", $_POST["slideimagesize"]);
    update_post_meta($post_id, "slideduration", $_POST["slideduration"]);

}

function remove_admin_bar() {
    show_admin_bar(false);
}

function trumansign_ajaxcontent() {
    define( 'WP_USE_THEMES', false );
    global $wp_query;
    query_posts('post_status=publish&posts_per_page=-1');
    ob_start();
    get_template_part( 'slides');
    $content = ob_get_contents();
    ob_end_clean();
    echo preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $content);
    die();
}

function trumansign_ajaxsidebar() {
    $sidebar = $_GET['sidebar'];
    ob_start();
    if ($sidebar == 'footer') {
        dynamic_sidebar('footer');
    } else {
        dynamic_sidebar('home-right');
    }
    $sidebar = ob_get_contents();
    ob_end_clean();
    echo preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $sidebar);
    die();
}

function change_num_posts($qry) {
    if ( $qry->is_main_query() ) {
        $qry->set('posts_per_page','-1');
    }

}
?>