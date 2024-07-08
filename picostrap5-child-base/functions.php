<?php
/*
        _               _                  _____        _     _ _     _   _   _                         
       (_)             | |                | ____|      | |   (_) |   | | | | | |                        
  _ __  _  ___ ___  ___| |_ _ __ __ _ _ __| |__     ___| |__  _| | __| | | |_| |__   ___ _ __ ___   ___ 
 | '_ \| |/ __/ _ \/ __| __| '__/ _` | '_ \___ \   / __| '_ \| | |/ _` | | __| '_ \ / _ \ '_ ` _ \ / _ \
 | |_) | | (_| (_) \__ \ |_| | | (_| | |_) |__) | | (__| | | | | | (_| | | |_| | | |  __/ | | | | |  __/
 | .__/|_|\___\___/|___/\__|_|  \__,_| .__/____/   \___|_| |_|_|_|\__,_|  \__|_| |_|\___|_| |_| |_|\___|
 | |                                 | |                                                                
 |_|                                 |_|                                                                

                                                       
*************************************** WELCOME TO PICOSTRAP ***************************************

********************* THE BEST WAY TO EXPERIENCE SASS, BOOTSTRAP AND WORDPRESS *********************

    PLEASE WATCH THE VIDEOS FOR BEST RESULTS:
    https://www.youtube.com/playlist?list=PLtyHhWhkgYU8i11wu-5KJDBfA9C-D4Bfl

*/

// DE-ENQUEUE PARENT THEME BOOTSTRAP JS BUNDLE
add_action( 'wp_print_scripts', function(){
    wp_dequeue_script( 'bootstrap5' );
}, 100 );

// ENQUEUE THE BOOTSTRAP JS BUNDLE (AND EVENTUALLY MORE LIBS) FROM THE CHILD THEME DIRECTORY
add_action( 'wp_enqueue_scripts', function() {
    //enqueue js in footer, defer
	
    //wp_enqueue_script( 'bootstrap5-childtheme', get_stylesheet_directory_uri() . "/js/bootstrap.bundle.min.js#deferload", array(), null, true );
	//LC replaced the enqueue script above with the line below... to fix a problem with picostrap theme and the latest version of WordPress
    wp_enqueue_script( 'bootstrap5-childtheme', get_stylesheet_directory_uri() . "/js/bootstrap.bundle.min.js", array(), null, array('strategy' => 'defer', 'in_footer' => true) );

    //optional: lottie (maybe...)
    //wp_enqueue_script( 'lottie-player', 'https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js#deferload', array(), null, true );

    //optional: rellax 
    //wp_enqueue_script( 'rellax', 'https://cdnjs.cloudflare.com/ajax/libs/rellax/1.12.1/rellax.min.js#deferload', array(), null, true );

}, 101);

    
    
// ENQUEUE YOUR CUSTOM JS FILES, IF NEEDED 
add_action( 'wp_enqueue_scripts', function() {	   
    
    //UNCOMMENT next row to include the js/custom.js file globally
    //wp_enqueue_script('custom', get_stylesheet_directory_uri() . '/js/custom.js#deferload', array(/* 'jquery' */), null, true); 

    //UNCOMMENT next 3 rows to load the js file only on one page
    //if (is_page('mypageslug')) {
    //    wp_enqueue_script('custom', get_stylesheet_directory_uri() . '/js/custom.js#deferload', array(/* 'jquery' */), null, true); 
    //}  

}, 102);

// OPTIONAL: ADD MORE NAV MENUS
//register_nav_menus( array( 'third' => __( 'Third Menu', 'picostrap' ), 'fourth' => __( 'Fourth Menu', 'picostrap' ), 'fifth' => __( 'Fifth Menu', 'picostrap' ), ) );
// THEN USE SHORTCODE:  [lc_nav_menu theme_location="third" container_class="" container_id="" menu_class="navbar-nav"]


// CHECK PARENT THEME VERSION as Bootstrap 5.2 requires an updated SCSSphp, so picostrap5 v2 is required
add_action( 'admin_notices', function  () {
    if( (pico_get_parent_theme_version())>=2.1) return; 
	$message = __( 'This Child Theme requires at least Picostrap Version 2.1.0  in order to work properly. Please update the parent theme.', 'picostrap' );
	printf( '<div class="%1$s"><h1>%2$s</h1></div>', esc_attr( 'notice notice-error' ), esc_html( $message ) );
} );

// FOR SECURITY: DISABLE APPLICATION PASSWORDS. Remove if needed (unlikely!)
add_filter( 'wp_is_application_passwords_available', '__return_false' );

// ADD YOUR CUSTOM PHP CODE DOWN BELOW /////////////////////////
// 
//if( function_exists( 'aioseo_breadcrumbs' ) ) aioseo_breadcrumbs();

// Breadcrumb
//get_template_part( 'template-parts/header/site-header' );
// 
if (!function_exists('aioseo_breadcrumbs')) :
  function aioseo_breadcrumbs() {
    /*if (!is_home()) {
	
      echo '<nav class="breadcrumb mb-4 mt-2 bg-light py-2 px-3 small rounded">';
      echo '<a href="' . home_url('/') . '">' . ('<i class="fas fa-home"></i>') . '</a><span class="divider">&nbsp;/&nbsp;</span>';
      if (is_category() || is_single()) {
        the_category(' <span class="divider">&nbsp;/&nbsp;</span> ');
        if (is_single()) {
          echo ' <span class="divider">&nbsp;/&nbsp;</span> ';
          the_title();
        }
      } elseif (is_page()) {
        echo the_title();
      }
      echo '</nav>';
    }*/
  }
  //add_filter('breadcrumbs', 'breadcrumbs');
endif;
// Breadcrumb END



/*<?php
		get_template_part( 'template-parts/header/site-header' );
		if ( function_exists( 'aioseo_breadcrumbs' ) ) aioseo_breadcrumbs(); 
?>*/

/**
 * This function modifies the main WordPress query to include an array of 
 * post types instead of the default 'post' post type.
 *
 * @param object $query The main WordPress query.
 */
function tg_include_custom_post_types_in_search_results( $query ) {
  if ( $query->is_main_query() && $query->is_search() && ! is_admin() ) {
      $query->set( 'post_type', array( 'post', 'page' ) );
  }
}
add_action( 'pre_get_posts', 'tg_include_custom_post_types_in_search_results' );

add_filter('acf/fields/taxonomy/result', 'my_acf_fields_taxonomy_result', 10, 4);
function my_acf_fields_taxonomy_result( $text, $term, $field, $post_id ) {
    $text;
    return $text;
}


if ( ! function_exists( 'custom_taxonomy' ) ) {

// Register Custom Taxonomy
function custom_taxonomy() {

	$labels = array(
		'name'                       => _x( 'Keywords', 'Taxonomy General Name', 'text_domain' ),
		'singular_name'              => _x( 'Keyword', 'Taxonomy Singular Name', 'text_domain' ),
		'menu_name'                  => __( 'Taxonomy', 'text_domain' ),
		'all_items'                  => __( 'All Items', 'text_domain' ),
		'parent_item'                => __( 'Parent Item', 'text_domain' ),
		'parent_item_colon'          => __( 'Parent Item:', 'text_domain' ),
		'new_item_name'              => __( 'New Item Name', 'text_domain' ),
		'add_new_item'               => __( 'Add New Item', 'text_domain' ),
		'edit_item'                  => __( 'Edit Item', 'text_domain' ),
		'update_item'                => __( 'Update Item', 'text_domain' ),
		'view_item'                  => __( 'View Item', 'text_domain' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'text_domain' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'text_domain' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'text_domain' ),
		'popular_items'              => __( 'Popular Items', 'text_domain' ),
		'search_items'               => __( 'Search Items', 'text_domain' ),
		'not_found'                  => __( 'Not Found', 'text_domain' ),
		'no_terms'                   => __( 'No items', 'text_domain' ),
		'items_list'                 => __( 'Items list', 'text_domain' ),
		'items_list_navigation'      => __( 'Items list navigation', 'text_domain' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	register_taxonomy( 'taxonomy', array( 'page' ), $args );

}
add_action( 'init', 'custom_taxonomy', 0 );

}
//-----------------------------
// STOP WORDPRESS REMOVING TAGS
// from https://www.denisbouquet.com/stop-wordpress-removing-tags-without-plugins/

function tags_tinymce_fix( $init )
{
  // html elements being stripped
  $init['extended_valid_elements'] = 'div[*],p[*],br[*]';
  // don't remove line breaks
  $init['remove_linebreaks'] = false;
  // convert newline characters to BR
  $init['convert_newlines_to_brs'] = true;
  // don't remove redundant BR
  $init['remove_redundant_brs'] = false;
  // pass back to wordpress
  return $init;
}
add_filter('tiny_mce_before_init', 'tags_tinymce_fix');
//-----------------------------

//-----------------------------
// CUSTOM FUNCTIONS BELOW HERE


//-----------------------------
//Create custom breadcrumbs
function createBreadcrumbs($thePost)
{
	$parent = get_post_parent($thePost);
	$grandParent = get_post_parent($parent);
	$greatGrandParent = get_post_parent($grandParent);
	
	$output = '';

	$output .= '<nav aria-label="breadcrumbs">';
	$output .= '<ul class="breadcrumbs">';
	
	$output .= '<li><a href="/">Home</a></li>';
	if($greatGrandParent->ID) {
		$output .= '<li><a href="/' . $greatGrandParent->post_name . '">' . formatAfterTheColon(get_the_title($greatGrandParent)) . '</a>';
	}
	
	if($grandParent->ID) {
		$output .= '<li><a href="/' . $grandParent->post_name . '">' . formatAfterTheColon(get_the_title($grandParent)) . '</a>';
	}
	
	if($parent->ID) {
		$output .= '<li><a href="/' . $parent->post_name . '">' . formatAfterTheColon(get_the_title($parent)) . '</a>';
	}
	
	$output .= '</ul>';
	$output .= '</nav>';
	return $output;
	
	/*
	<nav aria-label="breadcrumbs">
    <ul class="breadcrumbs">
        <li><a href="link">Home</a></li>
        <li><a href="link">Item 1</a></li>
        <li><a href="link">Item 2</a></li>
        <li><a href="link">Item 3</a></li>
        <li><a href="link">Item 4</a></li>
        <li><a href="link">Item 5</a></li>
    </ul>
</nav>
	*/
}



//-----------------------------
//Format text to split sting at colon and return second part capitalised
function formatAfterTheColon($string)
{
    // Split the string at colon
    $parts = explode(':', $string, 2); // Limit to 2 parts to handle colons within the string correctly

    if (count($parts) === 2) {
        // Capitalise the first character of the second part
        $parts[1] = ucfirst(trim($parts[1]));
        return $parts[1];
    } else {
        // Handle cases where there might not be a colon
        return $string;
    }
}


//-----------------------------
// Functions to create Context Menu items

function doContextMenuAccordion($title, $pageId)
{
	$headId = 'accordion-head-' . $pageId;
	$bodyId = 'accordion-body-' . $pageId;

	$output = '';

	$output .= '<div class="accordion-item">';
	$output .= '<h2 class="accordion-header" id="' . $headId .'">';
	$output .= '<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#' . $bodyId . '" aria-expanded="false" aria-controls="' . $bodyId . '">';    
	$output .= $title;
	$output .= '</button>';
	$output .= '</h2>';
	$output .= '<div id="' . $bodyId . '" class="accordion-collapse collapse" aria-labelledby="' . $headId . '">';
	$output .= '<div class="accordion-body"><ul>' . doChildrenList($pageId) . '</ul></div></div></div>';

	return $output;
}

//-----------------------------
// Grab children of certain page id

function doChildrenList($pageId)
{
	return wp_list_pages(
		array(
			'child_of' => $pageId,
			'depth' => 1,
			'title_li' => null,
			'echo' => false
		)
	);
}

include('function-inc/home-page.php');
include('custom-shortcodes.php');