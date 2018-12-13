<?php
/**
 * Invent Penn State functions and definitions

 */


if (! current_user_can('manage_options')) {
	add_filter('show_admin_bar', '__return_false');
}

if (function_exists('register_sidebar')) {

	register_sidebar(array(
		'name' => 'Widgetized Area',
		'id'   => 'widgetized-area',
		'description'   => 'This is a widgetized area.',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4>',
		'after_title'   => '</h4>'
	));

}



if ( ! function_exists( 'essentials_setup' ) ) :

function essentials_setup() {

	// Add RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );

	// add featured image for every post type
	//add_theme_support('post-thumbnails');
	add_theme_support( 'post-thumbnails' );
	//add_theme_support( 'post-formats', array( 'aside', 'link', 'gallery', 'status', 'quote', 'image' ) );



	// Image sizes
	set_post_thumbnail_size( 672, 372, true );


    add_image_size( 'ip_sm', 253, 146, array( 'center', 'center' ) );

    add_image_size( 'ip_lg', 750, 410, array( 'center', 'center' ) );





	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'primary'   => __( 'Main primary menu'),
		'secondary' => __( 'Secondary menu in the top-right header'),
		'tertiary' => __( 'Tertiary menu in the header with member login'),
		'footer' => __( 'Menu in the footer' ),
		'misc' => __( 'Misc menu in the footer' ),
	) );




}
endif;
add_action( 'after_setup_theme', 'essentials_setup' );




/**
 * Enqueue scripts and styles for the front end.
 */
function essentials_scripts() {



	wp_enqueue_script('jquery', ("https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"),'','',true);

	wp_enqueue_script('bootstrap', get_stylesheet_directory_uri() . '/assets/js/bootstrap.min.js?' .time(),array( 'jquery' ),'',true);


	wp_enqueue_script('offcanvas-js', get_stylesheet_directory_uri() . '/assets/js/offcanvas.js?'.time(),array( 'jquery'),'',true);

	wp_enqueue_script('response', get_stylesheet_directory_uri() . '/assets/js/response.min.js?'.time(),array( 'jquery'),'',true);

	wp_enqueue_script('bowser', get_stylesheet_directory_uri() . '/assets/js/bowser.min.js?'.time(),array( 'jquery'),'',true);

	wp_enqueue_script('modernizr-latest', get_stylesheet_directory_uri() . '/assets/js/modernizr-latest.js?'.time(),array( 'jquery'),'',true);

	wp_enqueue_script('picturefill', get_stylesheet_directory_uri() . '/assets/js/picturefill.min.js?'.time(),array( 'jquery'),'',true);

	wp_enqueue_script('slideout', get_stylesheet_directory_uri() . '/assets/js/slideout.min.js?'.time(),array( 'jquery'),'',true);


	wp_enqueue_script('matchHeight', get_stylesheet_directory_uri() . '/assets/js/jquery.matchHeight.js?'.time(),array( 'jquery'),'',true);

	wp_enqueue_script('cycle', get_stylesheet_directory_uri() . '/assets/js/jquery.cycle2.min.js?'.time(),array( 'jquery'),'',true);



	wp_enqueue_script('plugins', get_stylesheet_directory_uri() . '/assets/js/plugins.js?'.time(),array( 'jquery', 'cycle', 'response'),'',true);

	//wp_enqueue_script('navigator', get_stylesheet_directory_uri() .'/assets/js/navigator.js?'.time(),array( 'jquery', 'plugins'),'',true);


	wp_enqueue_style( 'bootstrap-styles', get_stylesheet_directory_uri() .'/assets/css/bootstrap.min.css?' .time() );

    wp_enqueue_style( 'fontawesome', ("https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css"));

	wp_enqueue_style( 'styles', get_stylesheet_directory_uri() .'/assets/css/styles.css?' .time() );

	wp_enqueue_style( 'offcanvas-css', get_stylesheet_directory_uri() .'/assets/css/offcanvas.css?' .time() );


	wp_enqueue_style( 'patches', get_stylesheet_directory_uri() .'/assets/css/patches.css',array('styles'));

	//wp_enqueue_style( 'navigator', 'http://resourcenavigator.psu.edu/wp-content/themes/ips/assets/css/navigator.css',array('styles'));

	wp_enqueue_style( 'v2', get_stylesheet_directory_uri() .'/assets/css/v2.css',array('styles'));

	wp_enqueue_style( 'blue-boxes', get_stylesheet_directory_uri() .'/assets/css/blue-boxes.css',array('styles'));



}



add_action( 'wp_enqueue_scripts', 'essentials_scripts', 30 );


function my_admin_theme_style() {
	wp_enqueue_style('admin-style', get_template_directory_uri() . '/assets/css/admin-styles.css');
}
add_action('admin_enqueue_scripts', 'my_admin_theme_style');



/*
* Send Gravity forms javascript to footer
*/

add_filter("gform_init_scripts_footer", "init_scripts");
function init_scripts() {
return true;
}

//allow typekit fonts in Admin

/*add_filter("mce_external_plugins", "tomjn_mce_external_plugins");
function tomjn_mce_external_plugins($plugin_array){
	$plugin_array['typekit']  =  get_template_directory_uri().'/assets/js/typekit.tinymce.js';
    return $plugin_array;
}
*/






//custom excerpt
function get_post_excerpt($post_id,$elips, $length, $cta, $class, $string){
$the_post = get_post($post_id); //Gets post ID
$the_excerpt = $the_post->post_content; //Gets post_content to be used as a basis for the excerpt
if($string){
	$the_excerpt = $string;
}
$the_excerpt = $content = apply_filters('the_content', $the_excerpt); //keep formatting
$the_excerpt = str_replace('\]\]\>', ']]&gt;', $the_excerpt); //keep formatting continued..
$excerpt_length = $length; //Sets excerpt length by word count
$the_excerpt = strip_tags($the_excerpt, '<p>'); //Strips tags and images (except for <p> tags)
$words = explode(' ', $the_excerpt, $excerpt_length + 1);
if(count($words) > $excerpt_length) :
array_pop($words);
if($elips){
array_push($words,'... <a class="read-more '.$class.'" href="'. get_permalink($post_id) . '">' . $cta . '</a>');
}
else{
	array_push($words,'...');
}

$the_excerpt = implode(' ', $words);
endif;
echo $the_excerpt;
}




//Page Slug Body Class

function my_class_names($classes) {
    global $wp_query;
	global $post;
    $arr = array();



    if(is_page()) {
    $page_id = $wp_query->get_queried_object_id();
    $arr[] = 'page-' . $post->post_name;

    }

     if(!is_page('home')) {

    $arr[] = 'interior';

    }




    if(is_single() && !is_search()) {
    $post_id = $wp_query->get_queried_object_id();
    $arr[] = 'post-' . $post->post_name;
    }


	if(is_search()) {
	 $arr[] = 'search-results';
	}

	if(is_user_logged_in()){
		 $arr[] = 'logged-in';
	}


	if(is_404()){
		 $arr[] = 'page-404';
	}

    return $arr;
}

add_filter('body_class','my_class_names');




// Adds classes for custom post types to body_class() and post_class()
function fb_add_body_class( $class ) {
	global $post;

		if(!is_search()){
			$post_type =  get_post_type($post->ID);
		}


		$class[] = $post_type;
		if(!is_search()){
			$class[] = 'type-' . $post_type;
		}
		if ( is_single() ) {
			$class[] = 'single';
		}
		else if ( is_archive() ) {
	   		$class[] = 'page-archive';
			if(!is_page(1125)){
				$class[] = 'interior';
		}
}

	return $class;
}
add_filter( 'body_class', 'fb_add_body_class' );


// add has_category class to post_class

function category_has_cats( $classes ) {
	global $post;
	if(has_category('', $post->ID)){
		$classes[] = 'has_cats';
	}
	else{
		$classes[] = 'no_cats';
	}

	return $classes;
}
add_filter( 'post_class', 'category_has_cats' );







if( function_exists('acf_add_options_page') ) {

	acf_add_options_page(array(
		'page_title' 	=> 'Theme General Settings',
		'menu_title' 	=> 'Theme Settings',
		'menu_slug' 	=> 'theme-general-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));
/*
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Theme Header Settings',
		'menu_title'	=> 'Header',
		'parent_slug'	=> 'theme-general-settings',
	));
	*/

	acf_add_options_sub_page(array(
		'page_title' 	=> 'Misc Settings',
		'menu_title'	=> 'Misc',
		'parent_slug'	=> 'theme-general-settings',
	));

	acf_add_options_sub_page(array(
		'page_title' 	=> 'Theme Footer Settings',
		'menu_title'	=> 'Footer',
		'parent_slug'	=> 'theme-general-settings',
	));

}


// Allow HTML descriptions in WordPress Menu
remove_filter( 'nav_menu_description', 'strip_tags' );
add_filter( 'wp_setup_nav_menu_item', 'cus_wp_setup_nav_menu_item' );
function cus_wp_setup_nav_menu_item( $menu_item ) {
     $menu_item->description = apply_filters( 'nav_menu_description', $menu_item->post_content );
     return $menu_item;
}




function stop_removing_tags(){
    remove_filter('the_content', 'wpautop');

}

/*========================================
Add Styles to WYSIWYG
=========================================*/



// Add Formats Dropdown Menu To MCE
if ( ! function_exists( 'wpex_style_select' ) ) {
	function wpex_style_select( $buttons ) {
		array_push( $buttons, 'styleselect' );
		return $buttons;
	}
}
add_filter( 'mce_buttons', 'wpex_style_select' );

// Add new styles to the TinyMCE "formats" menu dropdown
if ( ! function_exists( 'wpex_styles_dropdown' ) ) {
	function wpex_styles_dropdown( $settings ) {

		// Create array of new styles
		$new_styles = array(
			array(
				'title'	=> __( 'Custom Styles', 'wpex' ),
				'items'	=> array(
					array(
						'title'		=> __('Green Button','wpex'),
						'selector'	=> 'a',
						'classes'	=> 'btn btn-green'
					),

					array(
						'title'		=> __('Orange Button','wpex'),
						'selector'	=> 'a',
						'classes'	=> 'orange-btn'
					),

					array(
						'title'		=> __('Orange Button No Arrow','wpex'),
						'selector'	=> 'a',
						'classes'	=> 'orange-btn no-arrow'
					),

					array(
						'title'		=> __('Large Grey Text','wpex'),
						'selector'	=> 'p',
						'classes'	=> 'large-grey-text'
					),

					array(
						'title'		=> __('Center Photo','wpex'),
						'selector'	=> 'p',
						'classes'	=> 'aligncenter'
					),
					
					array(
						'title'		=> __('Three Quarter Width','wpex'),
						'selector'	=> 'p',
						'classes'	=> ['col-md-8', 'nlp']
					),

				),
			),
		);

		// Merge old & new styles
		$settings['style_formats_merge'] = true;

		// Add new styles
		$settings['style_formats'] = json_encode( $new_styles );

		// Return New Settings
		return $settings;

	}
}
add_filter( 'tiny_mce_before_init', 'wpex_styles_dropdown' );

//Geocode each map location and pass lng/lat back to admin


function geocode_address($post_id)
{

	$address = get_field('address', $post_id);
	//$custom_fields = get_post_custom();
	if($address)
	{
		$resp = wp_remote_get( "http://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($address)."&sensor=false" );
		if ( 200 == $resp['response']['code'] ) {
			$body = $resp['body'];
			$data = json_decode($body);
			if($data->status=="OK"){
				echo 'ok';
				$latitude = $data->results[0]->geometry->location->lat;
				$longitude = $data->results[0]->geometry->location->lng;
				update_field('latitude', $latitude, $post_id);
				update_field('longitude', $longitude, $post_id);
				//update_post_meta($post_id, "latitude", $latitude);
				//update_post_meta($post_id, "longitude", $longitude);
			}
			else{
				echo 'something went wrong';
			}
		}
	}
}
add_action('save_post', 'geocode_address');


//RSS Link to source
add_filter( 'wprss_ftp_link_post_title', '__return_true' );



function get_excerpt_by_id( $post_id = 0 ) {
	global $post;
	$save_post = $post;
	$post = get_post( $post_id );
	setup_postdata( $post );
	$excerpt = get_the_excerpt();
	$post = $save_post;
	wp_reset_postdata( $post );
	return $excerpt;
}


/* Add custom field to attachment */
function ic_image_attachment_add_custom_fields($form_fields, $post) {
	$form_fields["ic-custom-field"] = array(
		"label" => __("Photo Credit"),
		"input" => "text",
		"value" => get_post_meta($post->ID, "ic-custom-field", true),
		//"helps" => __("This descriptions appears below input field."),
	);
	return $form_fields;
}
add_filter("attachment_fields_to_edit", "ic_image_attachment_add_custom_fields", null, 2);

/* Save custom field value */
function ic_image_attachment_save_custom_fields($post, $attachment) {
	if(isset($attachment['ic-custom-field'])) {
		update_post_meta($post['ID'], 'ic-custom-field', $attachment['ic-custom-field']);
	} else {
		delete_post_meta($post['ID'], 'ic-custom-field');
	}
	return $post;
}
add_filter("attachment_fields_to_save", "ic_image_attachment_save_custom_fields", null , 2);





function remove_menus(){


  remove_menu_page( 'edit.php' );    //Posts
  remove_menu_page( 'edit-comments.php' );//Comments
  remove_menu_page( 'edit.php?post_type=psu_news' );    //Posts
  remove_menu_page( 'edit.php?post_type=staff' );    //Posts
  //remove_menu_page( 'edit.php?post_type=tribe_events' );    //Posts
  remove_menu_page( 'edit.php?post_type=wprss_feed' );    //Posts
   //remove_menu_page( 'edit.php?post_type=acf-field-group' );


}
add_action( 'admin_menu', 'remove_menus' );


function remove_plugin_admin_menu() {

        //remove_menu_page('ultimatewpqsf');
        //remove_menu_page('cptui_main_menu');

        //global $menu; print_r( $menu );


}
add_action( 'admin_menu', 'remove_plugin_admin_menu', 9999 );

add_action( 'wp_footer', function() {
    if ( ! function_exists( 'is_gfiframe_template' ) || ! is_gfiframe_template() ) {
        return;
    }
    echo '------------------------------------';
} );


function query_hubs( $attr ) {
    ob_start();
    get_template_part( 'template-parts/content', 'hubs' );
    return ob_get_clean();
}

add_shortcode('get_hubs','query_hubs' );


function get_the_navigator( $attr ) {
    ob_start();
    get_template_part( 'template-parts/content', 'navigator' );
    return ob_get_clean();
}

add_shortcode('get_navigator','get_the_navigator' );


function query_map( $attr ) {
    ob_start();

    echo '<div id="map" style="width:100%; height:515px;">';
   include (TEMPLATEPATH . '/includes/map.php');

    return ob_get_clean();
}

add_shortcode('get_map','query_map' );


 do_action( 'wcf_form_search_before', 1563, 'show_results');

function showResults(){
	return 'hey there';
}







add_action( 'init', 'cptui_register_my_cpts' );
function cptui_register_my_cpts() {
	$labels = array(
		"name" => __( 'IP Items', '' ),
		"singular_name" => __( 'IP Item', '' ),
		);

	$args = array(
		"label" => __( 'IP Items', '' ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"show_ui" => true,
		"show_in_rest" => false,
		"rest_base" => "",
		"has_archive" => false,
		"show_in_menu" => true,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => array( "slug" => "ip_item", "with_front" => true ),
		"query_var" => true,
				
		"supports" => array( "title", "editor", "thumbnail" ),				
	);
	register_post_type( "ip_item", $args );

// End of cptui_register_my_cpts()
}



/**
 * Shortens an UTF-8 encoded string without breaking words.
 *
 * @param  string $string     string to shorten
 * @param  int    $max_chars  maximal length in characters
 * @param  string $append     replacement for truncated words.
 * @return string
 */
function utf8_truncate( $string, $max_chars = 280, $append = "..." )
{
    $string = strip_tags( $string );
    $string = html_entity_decode( $string, ENT_QUOTES, 'utf-8' );
    // \xC2\xA0 is the no-break space
    $string = trim( $string, "\n\r\t .-;–,—\xC2\xA0" );
    $length = strlen( utf8_decode( $string ) );

    // Nothing to do.
    if ( $length < $max_chars )
    {
        return $string;
    }

    // mb_substr() is in /wp-includes/compat.php as a fallback if
    // your the current PHP installation doesn’t have it.
    $string = mb_substr( $string, 0, $max_chars, 'utf-8' );

    // No white space. One long word or chinese/korean/japanese text.
    if ( FALSE === strpos( $string, ' ' ) )
    {
        return $string . $append;
    }

    // Avoid breaks within words. Find the last white space.
    if ( extension_loaded( 'mbstring' ) )
    {
        $pos   = mb_strrpos( $string, ' ', 'utf-8' );
        $short = mb_substr( $string, 0, $pos, 'utf-8' );
    }
    else
    {
        // Workaround. May be slow on long strings.
        $words = explode( ' ', $string );
        // Drop the last word.
        array_pop( $words );
        $short = implode( ' ', $words );
    }

    return $short . $append;
}



