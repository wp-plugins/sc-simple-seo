<?php
/**
 * Plugin Name: SC Simple SEO
 * Plugin URI: https://profiles.wordpress.org/sergiuscosta
 * Description: A very simple SEO plugin
 * Version: 1.1.1
 * Author: Sergio Costa
 * Author URI: http://sergiocosta.net.br/
 * Text Domain: scseo
 * License: GPLv2 or later
 */

add_action( 'admin_menu', 'scseo_add_admin_menu' );
add_action( 'admin_init', 'scseo_settings_init' );
function scseo_add_admin_menu() { 

	add_menu_page( 'SC Simple SEO', 'SC Simple SEO', 'manage_options', 'sc_simple_seo', 'sc_simple_seo_options_page' );

}

function scseo_settings_init() { 

	register_setting( 'scSimpleSEO', 'scseo_settings' );

	add_settings_section(
		'scseo_scSimpleSEO_section', 
		__( 'SC Simple SEO', 'scseo' ), 
		'scseo_settings_section_callback', 
		'scSimpleSEO'
	);

	// tags
	add_settings_field( 
		'scseo_tags', 
		__( 'Insert tags associated to the site subject (separated by commas)', 'scseo' ), 
		'scseo_tags_render', 
		'scSimpleSEO', 
		'scseo_scSimpleSEO_section' 
	);

    // author
    add_settings_field( 
        'scseo_author', 
        __( 'Insert authors names (separated by commas)', 'scseo' ), 
        'scseo_author_render', 
        'scSimpleSEO', 
        'scseo_scSimpleSEO_section' 
    );

    // site subject
    add_settings_field( 
        'scseo_subject', 
        __( 'Insert the site subject', 'scseo' ), 
        'scseo_subject_render', 
        'scSimpleSEO', 
        'scseo_scSimpleSEO_section' 
    );

    // city and state
    add_settings_field( 
        'scseo_location', 
        __( 'Insert your location', 'scseo' ), 
        'scseo_location_render', 
        'scSimpleSEO', 
        'scseo_scSimpleSEO_section' 
    );

    // coordinates
    add_settings_field( 
        'scseo_coordinates', 
        __( 'Insert the coordinates (Google Maps)', 'scseo' ), 
        'scseo_coordinates_render', 
        'scSimpleSEO', 
        'scseo_scSimpleSEO_section' 
    );

	// analytics
	add_settings_field( 
		'scseo_analytics', 
		__( 'Insert the ID of your Google Analytics', 'scseo' ), 
		'scseo_analytics_render', 
		'scSimpleSEO', 
		'scseo_scSimpleSEO_section' 
	);


}

// tags
function scseo_tags_render() { 
	$options = get_option( 'scseo_settings' ); ?>
	<input type='text' name='scseo_settings[scseo_tags]' value='<?php echo $options['scseo_tags']; ?>'> <?php
}

// author
function scseo_author_render() { 
    $options = get_option( 'scseo_settings' ); ?>
    <input type='text' name='scseo_settings[scseo_author]' value='<?php echo $options['scseo_author']; ?>'> <?php
}

// subject
function scseo_subject_render() { 
    $options = get_option( 'scseo_settings' ); ?>
    <input type='text' name='scseo_settings[scseo_subject]' value='<?php echo $options['scseo_subject']; ?>'> <?php
}

// city and state
function scseo_location_render() { 
    $options = get_option( 'scseo_settings' ); ?>
    <input type='text' name='scseo_settings[scseo_location]' value='<?php echo $options['scseo_location']; ?>' placeholder="Salvador, Bahia"> <?php
}

// coordinates
function scseo_coordinates_render() { 
    $options = get_option( 'scseo_settings' ); ?>
    <input type='text' name='scseo_settings[scseo_coordinates]' value='<?php echo $options['scseo_coordinates']; ?>' placeholder="-13.000671, -38.452555"> <?php
}

// analytics
function scseo_analytics_render() { 
	$options = get_option( 'scseo_settings' ); ?>
	<input type='text' name='scseo_settings[scseo_analytics]' value='<?php echo $options['scseo_analytics']; ?>' placeholder='UA-XXXXXX-X'> <?php
}


function scseo_settings_section_callback() { 
	echo __( 'Insert the required information for a better SEO', 'scseo' );
}


function sc_simple_seo_options_page() { ?>
	<form action='options.php' method='post'>
		
		<?php
		settings_fields( 'scSimpleSEO' );
		do_settings_sections( 'scSimpleSEO' );
		submit_button();
		?>
		
	</form> <?php
}

function inserthead() {
        $options = get_option( 'scseo_settings' );

        // General Variables
        $mtitle_default = get_bloginfo('name');
        $title_default  = get_bloginfo('name');
        $keys_default   = "'" . $options['scseo_tags'] . "'";
        $mlink_default  = get_bloginfo('url');
        $link_default   = get_bloginfo('url');
        $desc_default   = get_bloginfo('description');
        // $image_default = $options['scseo_logo'];
        if (is_single() || is_page()) {
            $title_default = get_the_title($post->ID);
            $link_default  = get_permalink();
            if(has_post_thumbnail()){
                $image_ID      = get_post_thumbnail_id(get_the_id());
                $image_default = wp_get_attachment_image_src($image_ID, 'thumbnail');
                $image_default = $image_default[0];
            } else {
                // $image_default = $options['scseo_logo'];
            }
        }

        $posttags = get_the_tags($post->ID);
    ?>

    <?php if ($options['scseo_author']) 		{ ?> <meta name="author" content="<?php echo $options['scseo_author']; ?>" /> <?php } ?>
    <?php if ($options['scseo_tags']) 			{ ?> <meta name="keywords" content="<?php echo $keys_default; ?>, <?php if($posttags){foreach($posttags as $tag){echo $tag->name . ', ';}}; ?>" /> <?php } ?>
    <meta name="description" content="<?php echo $desc_default; ?>" />

    <meta name="copyright" content="&copy; Copyright <?php echo date('Y'); ?> <?php echo $title_default; ?>" /> 
    <?php
        if(is_single() || is_page() || is_category() || is_home()) {
            echo '<meta name="robots" content="all,noodp" />';
            echo "\n";
        }
        else if(is_archive()) {
            echo '<meta name="robots" content="noarchive,noodp" />';
            echo "\n";
        }
        else if(is_search() || is_404()) {
            echo '<meta name="robots" content="noindex,noarchive" />';
            echo "\n";
        }
    ?>

    <!-- Opeh Graph Meta Tags -->
    <meta property="og:title" content="<?php echo $title_default; ?>"/>
    <meta property="og:type" content="website"/>
    <meta property="og:url" content="<?php echo $link_default; ?>"/>
    <meta property="og:image" content="<?php echo $image_default; ?>"/>
    <meta property="og:site_name" content="<?php echo $title_default; ?>"/>
    <meta property="og:description" content="<?php echo $desc_default; ?>"/> 

    <!-- Dublin Core Meta Tags -->
    <!-- <meta name="dc.language" content="UK"> -->
    <meta name="dc.source" content="<?php echo $mlink_default; ?>">
    <meta name="dc.relation" content="<?php echo $link_default; ?>">
    <meta name="dc.title" content="<?php echo $mtitle_default; ?>">
    <meta name="dc.keywords" content="<?php echo $keys_default; ?>, <?php if($posttags){foreach($posttags as $tag){echo $tag->name . ', ';}}; ?>">
    <meta name="dc.subject" content="<?php echo $options['scseo_subject']; ?>">
    <meta name="dc.description" content="<?php echo $desc_default; ?>"> 

    <!-- Google Geo Location -->
    <!-- <meta name="geo.region" content="BR-BA" /> -->
    <meta name="geo.placename" content="<?php echo $options['scseo_location']; ?>" />
    <meta name="geo.position" content="<?php echo $options['scseo_coordinates']; ?>" />
    <meta name="ICBM" content="<?php echo $options['scseo_coordinates']; ?>" /> <?php

}
add_action('wp_head', 'inserthead');

/*
 * Google Analytics
 */
function google_analytics() { 
    $options = get_option( 'scseo_settings' ); ?>
    <script type='text/javascript'>
        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', '<?php echo $options["scseo_analytics"]; ?>']);
        _gaq.push(['_trackPageview']);
        (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
        })();
    </script> <?php }
add_action('wp_footer', 'google_analytics'); 

?>