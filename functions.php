<?php

require_once('clockwidget.php');
$trumansign = new TrumanDigitalSign();



class TrumanDigitalSign
{
const SCREENSAVER_TIMES = array(
    '0:00' => '12:00 am',
    '0:30' => '12:30 am',
    '1:00' => '1:00 am',
    '1:30' => '1:30 am',
    '2:00' => '2:00 am',
    '2:30' => '2:30 am',
    '3:00' => '3:00 am',
    '3:30' => '3:30 am',
    '4:00' => '4:00 am',
    '4:30' => '4:30 am',
    '5:00' => '5:00 am',
    '5:30' => '5:30 am',
    '6:00' => '6:00 am',
    '6:30' => '6:30 am',
    '7:00' => '7:00 am',
    '7:30' => '7:30 am',
    '8:00' => '8:00 am',
    '8:30' => '8:30 am',
    '9:00' => '9:00 am',
    '9:30' => '9:30 am',
    '10:00' => '10:00 am',
    '10:30' => '10:30 am',
    '11:00' => '11:00 am',
    '11:30' => '11:30 am',
    '12:00' => '12:00 pm',
    '12:30' => '12:30 pm',
    '13:00' => '1:00 pm',
    '13:30' => '1:30 pm',
    '14:00' => '2:00 pm',
    '14:30' => '2:30 pm',
    '15:00' => '3:00 pm',
    '15:30' => '3:30 pm',
    '16:00' => '4:00 pm',
    '16:30' => '4:30 pm',
    '17:00' => '5:00 pm',
    '17:30' => '5:30 pm',
    '18:00' => '6:00 pm',
    '18:30' => '6:30 pm',
    '19:00' => '7:00 pm',
    '19:30' => '7:30 pm',
    '20:00' => '8:00 pm',
    '20:30' => '8:30 pm',
    '21:00' => '9:00 pm',
    '21:30' => '9:30 pm',
    '22:00' => '10:00 pm',
    '22:30' => '10:30 pm',
    '23:00' => '11:00 pm',
    '23:30' => '11:30 pm'
    );

    public function __construct() {

        add_action('init', array($this,'register_sidebars'));
        add_action('wp_enqueue_scripts', array($this, 'trumansign_scripts'));
        add_action('admin_print_scripts-post-new.php', array($this, 'trumansign_admin_script'), 11);
        add_action('admin_print_scripts-post.php', array($this, 'trumansign_admin_script'), 11);
        add_action('add_meta_boxes', array($this, 'trumansign_meta_boxes'));
        add_action('save_post', array($this, 'trumansign_save_details'));
        add_action('customize_register', array($this, 'trumansign_customize_register'));
        add_action('after_setup_theme', array($this, 'remove_admin_bar'));
        add_action('wp_head', array($this, 'trumansign_custom_css_output'));
        add_action('wp_ajax_get_content_hash', array($this, 'trumansign_ajaxcontenthash'));
        add_action('wp_ajax_nopriv_get_content_hash', array($this, 'trumansign_ajaxcontenthash'));
        add_action('wp_ajax_get_time', array($this, 'trumansign_ajaxtime'));
        add_action('wp_ajax_nopriv_get_time', array($this, 'trumansign_ajaxtime'));
        add_action('pre_get_posts', array($this, 'change_num_posts'));
        add_filter('widget_text', 'do_shortcode');
        add_action('widgets_init', array($this, 'register_truman_sign_clock_widget'));
    }

    public function register_sidebars() {
        // Sidebar Widgets
        // Location: left or right side
        register_sidebar(array('name' => 'home-right',
            'before_widget' => '<div class="widget-area widget-sidebar">',
            'after_widget' => '</div>',
            'before_title' => '<h4>',
            'after_title' => '</h4>',
        ));

        // Footer Widgets
        register_sidebar(array('name' => 'footer',
            'before_widget' => '<div class="widget-area widget-footer col-lg-4 col-md-4 col-sm-4 col-xs-4">',
            'after_widget' => '</div>',
            'before_title' => '<h4>',
            'after_title' => '</h4>',
        ));

	    // Screensaver Widgets
	    register_sidebar(array('name' => 'screensaver',
           'before_widget' => '',
           'after_widget' => '',
           'before_title' => '',
           'after_title' => '',
	    ));

    }
    public function trumansign_scripts() {
        $my_theme = wp_get_theme();
        wp_enqueue_style('bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css');
        wp_enqueue_style('theme-style', get_stylesheet_uri(), null, $my_theme->get( 'Version' ));
        wp_enqueue_style('josefin', 'https://fonts.googleapis.com/css?family=Josefin+Sans:400,700');
        wp_enqueue_script('bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js', array('jquery'), '3.3.4', true);
        wp_enqueue_script('theme-scripts', get_template_directory_uri() . '/js/trumansign.js', array('jquery'), $my_theme->get( 'Version' ));
        wp_enqueue_script('textfill', get_template_directory_uri() . '/js/jquery.textfill.min.js', '0.6.0');
        wp_enqueue_script('iframe_api', 'https://www.youtube.com/iframe_api');
        wp_localize_script('theme-scripts', 'ajax_object',
            array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'footer_height' => get_theme_mod('footer_height', 15),
                'update_interval' => get_theme_mod('update_interval', 5)* MINUTE_IN_SECONDS * 1000,
                'screensaver_enabled' => get_theme_mod('enable_screensaver', 0),
                'screensaver_start' => get_theme_mod('screensaver_start', '10:00'),
                'screensaver_stop' => get_theme_mod('screensaver_stop', '7:00'),
            )
        );
    }


    public function trumansign_admin_script() {
        global $post;
        $post_type = $post->post_type;
        if ('post' == $post_type) {
            wp_enqueue_script(
                'jquery-minicolors-script',
                get_template_directory_uri() . '/jquery-minicolors-master/jquery.minicolors.js',
                array(
                    'jquery'
                )
            );
            wp_enqueue_script(
                'trumansign-admin',
                get_template_directory_uri() . '/js/trumansign-admin.js',
                array(
                    'jquery'
                )
            );
            wp_enqueue_style(
                'jquery-minicolors-script',
                get_template_directory_uri() . '/jquery-minicolors-master/jquery.minicolors.css'
            );
        }
    }

    public function trumansign_customize_register($wp_customize) {
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
            array(
                'name' => 'sidebar_background_color',
                'default' => '#000000',
                'label' => 'Sidebar Background Color',
            ),
            array(
                'name' => 'sidebar_text_color',
                'default' => '#ffffff',
                'label' => 'Sidebar Text Color',
            ),
            array(
                'name' => 'footer_background_color',
                'default' => '#000000',
                'label' => 'Footer Background Color',
            ),
            array(
                'name' => 'footer_text_color',
                'default' => '#ffffff',
                'label' => 'Footer Text Color',
            ),

        );

        foreach ($colors as $color) {
            $wp_customize->add_setting(
                $color['name'],
                array(
                    'default' => $color['default'],
                    'sanitize_callback' => 'sanitize_hex_color',
                )
            );

            $wp_customize->add_control(
                new WP_Customize_Color_Control(
                    $wp_customize,
                    $color['name'],
                    array(
                        'label' => $color['label'],
                        'section' => 'trumansign_settings',
                        'settings' => $color['name'],
                    )
                )
            );
        }

        $wp_customize->add_setting(
            'sidebar_background_image'
        );

        $wp_customize->add_control(
            new WP_Customize_Image_Control(
                $wp_customize,
                'sidebar_background_image',
                array(
                    'label' => 'Sidebar Background Image',
                    'section' => 'trumansign_settings',
                    'settings' => 'sidebar_background_image',
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
        $wp_customize->add_setting(
            'footer_height',
            array(
                'default' => 15,
                'sanitize_callback' => array($this, 'sanitize_percentage'),
            )
        );

        $wp_customize->add_control(
            'footer_height',
            array(
                'type' => 'number',
                'label' => 'Footer Height (%)',
                'section' => 'trumansign_settings',
                'input_attrs' => array(
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                )
            )

        );

        $wp_customize->add_setting(
            'update_interval',
            array(
                'default' => 5,
                'sanitize_callback' => 'absint',
            )
        );

        $wp_customize->add_control(
            'update_interval',
            array(
                'type' => 'number',
                'label' => 'Check for Updates Every ___ Minutes',
                'section' => 'trumansign_settings'
            )
        );


	    $wp_customize->add_setting(
		    'default_duration',
		    array(
			    'default' => 8,
			    'sanitize_callback' => 'absint',
		    )
	    );

	    $wp_customize->add_control(
		    'default_duration',
		    array(
			    'type' => 'number',
			    'label' => 'Default Slide Duration (in seconds)',
			    'section' => 'trumansign_settings'
		    )
	    );


	    $wp_customize->add_setting(
		    'enable_screensaver',
		    array(
			    'default' => 0
		    )
	    );

	    $wp_customize->add_control(
		    'enable_screensaver',
		    array(
			    'type' => 'checkbox',
			    'label' => 'Enable Screensaver:',
			    'section' => 'trumansign_settings',
		    )
	    );

	    $wp_customize->add_setting(
		    'screensaver_start',
		    array(
			    'default' => '22:00'
		    )
	    );

	    $wp_customize->add_control(
		    'screensaver_start',
		    array(
			    'type' => 'select',
			    'label' => 'Screensaver Start Time:',
			    'section' => 'trumansign_settings',
			    'choices' => $this::SCREENSAVER_TIMES
			    )
	    );


	    $wp_customize->add_setting(
		    'screensaver_stop',
		    array(
			    'default' => '7:00'
		    )
	    );

	    $wp_customize->add_control(
		    'screensaver_stop',
		    array(
			    'type' => 'select',
			    'label' => 'Screensaver End Time:',
			    'section' => 'trumansign_settings',
			    'choices' => $this::SCREENSAVER_TIMES
		    )
	    );

    }



    public function trumansign_custom_css_output() {
        echo '<style type="text/css" id="custom-theme-css">';
        echo '.sidebar {';
        echo 'background-color: ' . get_theme_mod('sidebar_background_color', '') . ';';
        echo 'color: ' . get_theme_mod('sidebar_text_color', '') . ';';
        echo 'background-image: url(' . get_theme_mod('sidebar_background_image', '') . ');';
        echo 'background-size: ' . get_theme_mod('sidebar_background_image_size', '') . ';';
        echo 'background-repeat: no-repeat;';
        echo '}';
        echo '#footer {background-color: ' . get_theme_mod('footer_background_color', '') . '; color: ' . get_theme_mod('footer_text_color', '#fff') . '; height: ' . get_theme_mod('footer_height', 15) . '%;}';
        echo '#footer a, .clock {color: ' . get_theme_mod('footer_text_color', '#fff') . '; }';
        echo '</style>';
    }


    public function trumansign_meta_boxes() {
            add_meta_box(
                "slidecolor",
                "Slide Settings",
                array($this, "trumansign_metaboxes"),
                "post",
                "normal",
                "high"
            );
        global $current_screen;
        if ($current_screen->action != 'add') {
            add_meta_box(
                "previewmeta",
                "Preview",
                array($this, "preview_meta_box"),
                "post",
                "normal",
                "high"
            );
        }
    }


    public function trumansign_metaboxes() {
        global $post;
        $custom = get_post_custom($post->ID);
        $bgcolor = $custom["bgcolor"][0];
        $textcolor = $custom["textcolor"][0];
        $hidetitle = $custom["hidetitle"][0];
        $fittext = $custom["fittext"][0];
        $slideimage = $custom["slideimage"][0];
        $slideimagesize = $custom["slideimagesize"][0];
        $slidevideo = $custom["slidevideo"][0];
        $videoSound = $custom["videoSound"][0];
        $youTubeId =  $custom["youTubeId"][0];
        $youtubeSound = $custom["youtubeSound"][0];
        $slideduration = $custom["slideduration"][0];
        if ($slideduration == '') {
            $slideduration = get_theme_mod( 'default_duration' );
	        if ($slideduration == 0 || is_null($slideduration)) {
	            $slideduration = 8;
            }
        }
        ?>
        <p><label for="bgcolor">Select Background Color: </label>
            <input type="text" id="bgcolor" name="bgcolor" value="<?php echo $bgcolor; ?>" style="height: auto;"></p>
        <p><label for="slideimage">Background Image: </label>
            <input type="text" id="slideimage" name="slideimage" value="<?php echo $slideimage; ?>"
                   style="height: auto;" size="70">
            <input type="button" class="button custom_media" name="slideimage_button" id="slideimage_button"
                   value="Upload/Browse"/>
            <div id="slideimagedimensions" style="margin-left: 125px"></div>
        </p>
        <p><label for="slideimagesize">Background Image Size: </label>
            <select name="slideimagesize" id="slideimagesize">
                <option value="cover"<?php if ($slideimagesize == "cover") {
                    echo "selected=\"selected\"";
                }; ?>>Crop to Fit
                </option>
                <option value="contain"<?php if ($slideimagesize == "contain") {
                    echo "selected=\"selected\"";
                }; ?>>Fit Inside
                </option>
                <option value="100% 100%"<?php if ($slideimagesize == "100% 100%") {
                    echo "selected=\"selected\"";
                }; ?>>Stretch
                </option>
            </select>
            For an exact fit, image should be
            <?php if (get_theme_mod( 'sidebar' ) == 0) {
                echo '1920';
            } else {
                $sidebarwidth = get_theme_mod( 'sidebar_width', 4 );
                echo (1920 * (12-$sidebarwidth)/12);
            }
            $footerheight = get_theme_mod( 'footer_height', 15 );
            ?> x <?php echo (1080 * (100-$footerheight)/100); ?> based on your current <a href="<?php echo admin_url( 'customize.php?autofocus[section]=trumansign_settings'); ?>">footer and sidebar settings</a>.
        </p>
        <p><label for="slidevideo">Slide Video (Upload MP4 file):</label>
            <input type="text" id="slidevideo" name="slidevideo" value="<?php echo $slidevideo; ?>"
                   style="height: auto;" size="70">
            <input type="button" class="button custom_media" name="slidevideo_button" id="slidevideo_button"
                   value="Upload/Browse"/>
            <input type="checkbox" id="videoSound" name="videoSound" value="1" <?php if ($videoSound == 1) { echo " checked=\"checked\""; }; ?>/> <label for="videoSound">Allow Sound?</label>
        </p>
        <p><label for="slidevideo">YouTube Video ID:</label>
            <input type="text" id="youTubeId" name="youTubeId" value="<?php echo $youTubeId; ?>"
                   style="height: auto;" size="70">
            <input type="checkbox" id="youtubeSound" name="youtubeSound" value="1" <?php if ($youtubeSound == 1) { echo " checked=\"checked\""; }; ?>/> <label for="youtubeSound">Allow Sound?</label>
        </p>
        <p><label for="textcolor">Select Text Color: </label>
            <input type="text" id="textcolor" name="textcolor" value="<?php echo $textcolor; ?>" style="height: auto;">
        </p>
        <p><label for="hidetitle">Hide Title: </label>
            <input type="checkbox" id="hidetitle" name="hidetitle" value="1" <?php if ($hidetitle == 1) {
                echo "checked=\"checked\"";
            }; ?>" /></p>
        <p><label for="fittext">Scale Text Automatically to Fit: </label>
            <input type="checkbox" id="fittext" name="fittext" value="1" <?php if ($fittext == 1) {
                echo "checked=\"checked\"";
            }; ?>" /></p>

        <p><label for="slideduration">Slide Duration: </label>
            <input type="number" name="slideduration" id="slideduration" value="<?php echo $slideduration; ?>"/ style="text-align: right; width: 100px"> Seconds
        </p>
        <input type="hidden" name="truman_sign_metaboxes_submitted" value="1" />
        <?php
    }

    public function preview_meta_box() {
        global $post;
        ?>
        <iframe id="preview" src="<?php echo get_the_permalink($post)?>?preview=true" style="width: 100%"></iframe>
        <?php
    }
    public function trumansign_save_details($post_id) {
        global $post;
        if ('post' != $post->post_type) {
            return;
        }
        if (wp_is_post_revision($post_id)) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }
        if ($_POST['truman_sign_metaboxes_submitted'] == "1") {
            update_post_meta($post_id, "bgcolor", $_POST["bgcolor"]);
            update_post_meta($post_id, "textcolor", $_POST["textcolor"]);
            if ($_POST["hidetitle"] == "1") {
                update_post_meta($post_id, "hidetitle", "1");
            } else {
                delete_post_meta($post_id, "hidetitle");
            }
            if ($_POST["fittext"] == "1") {
                update_post_meta($post_id, "fittext", "1");
            } else {
                delete_post_meta($post_id, "fittext");
            }
            update_post_meta($post_id, "slideimage", $_POST["slideimage"]);
            update_post_meta($post_id, "slidevideo", $_POST["slidevideo"]);
            update_post_meta($post_id, "youTubeId", $_POST["youTubeId"]);
            update_post_meta($post_id, "slideimagesize", $_POST["slideimagesize"]);
            update_post_meta($post_id, "slideduration", $_POST["slideduration"]);
            if ($_POST["videoSound"] == "1") {
                update_post_meta($post_id, "videoSound", "1");
            } else {
                delete_post_meta($post_id, "videoSound");
            }
            if ($_POST["youtubeSound"] == "1") {
                update_post_meta($post_id, "youtubeSound", "1");
            } else {
                delete_post_meta($post_id, "youtubeSound");
            }
        }
    }

    public function remove_admin_bar() {
        show_admin_bar(false);
    }

    public function trumansign_ajaxcontenthash() {
        echo $this->get_content_hash();
        die();
    }


    public function trumansign_ajaxtime() {
        echo time()*1000;
        die();
    }

    public function get_content_hash() {
        $my_theme = wp_get_theme();
        $content = $my_theme->get( 'Version' );
        //get slides
        define('WP_USE_THEMES', false);
        global $wp_query;
        query_posts('post_status=publish&posts_per_page=-1');
        ob_start();
        get_template_part('slides');
        $content .= ob_get_contents();
        ob_end_clean();
        //get sidebar
        ob_start();
        dynamic_sidebar('home-right');
        $content .= ob_get_contents();
        ob_end_clean();
        //get footer
        ob_start();
        dynamic_sidebar('footer');
        $content .= ob_get_contents();
        ob_end_clean();
        $content .= serialize(get_theme_mods());
        return md5($content);
    }

    public function change_num_posts($qry) {
        if ($qry->is_main_query()) {
            $qry->set('posts_per_page', '-1');
        }
    }

    public function register_truman_sign_clock_widget() {
        register_widget('Truman_Sign_Clock_Widget');
    }

    public function sanitize_percentage( $input ) {
            if ( !is_numeric($input) ) {
                return 15;
            }

            if ( $input >= 0 && $input <= 100 ) {
                return $input;
            } else {
                return 15;
            }
    }
}