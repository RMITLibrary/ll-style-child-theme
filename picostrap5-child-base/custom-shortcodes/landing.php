<?php

//-----------------------------
//	landing_banner_att

//	Creates a banner for the landing page

//	args:		$content - description of the landing page (max 280 characters)
//              $atts - attributes as follows:

//  $atts:      title       Title of the banner
//              img         Absolute path to image
//              alt         Alt tag for the above image
//              caption     Attribution for the image   

//  shortcode:  [landing-banner]

//	usage:			
//  [landing-banner title='My title' img='https://path.to/image' alt='description of the image' caption='Image by creator name']Description of the landing page[/landing-banner]

//  Expected output
//  <div class="landing-banner">
//      <figure aria-labelledby="caption-text"><img src="https://path.to/image" alt="description of the image" /></figure>
//      <div class="landing-content">
//          <div class="red-bar"></div>
//          <h1>Mytitle</h1>
//          <p class="lead">Description of the landing page. Max chars: 280</p>
//          <p class="small"  id="caption-text">Image by creator name</p>
//      </div>
//  </div>

function landing_banner_att($atts, $content = null) {
    $default = array(
        'caption' => 'Image by <a href="https://rmit.edu.au/">RMIT</a>, licensed under <a href="https://creativecommons.org/licenses/by/4.0/">CC BY-NC 4.0</a>.',
        'img' => 'https://rmitlibrary.github.io/cdn/learninglab/illustration/landing/home-default.png',
        'alt' => ''
    );
    $a = shortcode_atts($default, $atts);
    $content = do_shortcode($content);
    
    $output = '';
    
    $output .= '<div class="landing-banner">' . "\n";
    $output .= '<figure aria-labelledby="caption-text">' . "\n";
    $output .= '<img src="' . $a['img'] . '" alt="' . $a['alt'] . '" />' . "\n";   
    $output .= '</figure>' . "\n";
    $output .= '<div class="landing-content">' . "\n";
    $output .= '<div class="red-bar"></div>' . "\n";
    $output .= '<h1>' . get_the_title() . '</h1>' . "\n";
    $output .= '<p class="lead">' . $content  . '</p>' . "\n";
    $output .= '<p class="small" id="caption-text">' . $a['caption']  . '</p>' . "\n";
    $output .= '</div></div>';

    return $output;

}



//-----------------------------
//	landing_list_att

//	Lists the child pages on a landing page

//	args:		$atts - attributes as follows:

//  $atts:      category    Optional category

//  calls:      doChildrenList (in functions.php)

//  shortcode:  [landing-banner /]

//	usage:			
//  [landing-list category='Optional category /]

//  Expected output - change to Landing list in css?
//<div class="landing-list">
//    <h2 class="h3">Optional category</h2>
//    <ul class="link-list">
//        <li><a href="">Link 1</a></li>
//        <li><a href="">Link 2</a></li>
//        <li><a href="">Link 3</a></li>
//        <li><a href="">Link 4</a></li>
//        <li><a href="">Link 5</a></li>
//    </ul>
//</div>

function landing_list_att($atts) {
    $default = array(
        'category' => ''
    );
    $a = shortcode_atts($default, $atts);
    
    //get the id ofthe page we are on
    $pageId = get_the_ID();

    $output = '';
    $output .= '<div class="landing-list">' . "\n";
    
    //this won't ever get used as there's no way of differentiating 
    //category while still using page list :(
    if($a['category'] != '') {
		$output .= '<h2 class="h3">'. $a['category'] . '</h2>' . "\n";
	}
    
    //doChildrenList() is defined in functions.php
    $output .= '<ul class="link-list">'. doChildrenList($pageId) . '</ul>' . "\n";
    $output .= '</div>';

    return $output;
}


//-----------------------------
//	home_panel_atts

//	Creates a home panel, with an image, title, escription and a link

//	args:		$atts - attributes as follows:

//  $atts:      link        	Url where the panel links to
//				title       	Title of the banner
//              img         	Absolute path to image
//              description		A short description

//  shortcode:  [home-panel]

//	usage:			
//  [home-panel title='Numbers and measurement' link='/arithmetic/' img='path.to.img']<p>Description</p>[/home-panel]

//  Expected output
// <a href="/arithmetic/" class="home-panel">
//     <img src="path.to.img" alt="">
//     <h2 class="link-large">Numbers and measurement</h2>
//     <p>Description</p>
// </a>

function home_panel_atts($atts, $content = null) {
    // Extract attributes passed to the shortcode
    $atts = shortcode_atts(
        array(
            'link' => '#',
            'title' => '',
            'img' => '',
        ), 
        $atts, 
        'home-panel'
    );

    // Determine the class based on whether an image is provided
    $class = 'home-panel';
    if (empty($atts['img'])) {
        $class = 'home-panel-no-img';
    }

    // Build the HTML output for a single home panel
    $output = '<a href="' . esc_url($atts['link']) . '" class="' . esc_attr($class) . '">';
    if (!empty($atts['img'])) {
        $output .= '<img src="' . esc_url($atts['img']) . '" alt="">';
    }
    $output .= '<h2 class="link-large">' . esc_html($atts['title']) . '</h2>';
    $output .= '<p>' . do_shortcode($content) . '</p>';
    $output .= '</a>';
    
    return $output;
}



//-----------------------------
//	home_panel_container_atts

//	Creates a home panel container, to hold panels

//	args:		$atts
//  shortcode:  [home-panel-container]

//	usage:			
//  [home-panel-container]
//	... list of [home-panel] shortcodes
//  [/home-panel-container]

//  Expected output
// <div class="home-panel-container">
// ..Series of home-panels
// </div>

function home_panel_container_atts($atts, $content = null) {
    // Build the HTML output for the container
    $output = '<div class="home-panel-container">';
    $output .= do_shortcode($content);
    $output .= '</div>';

    return $output;
}


//add code to list (used in the_content_filter)
add_shortcode_to_list("landing-banner");
add_shortcode_to_list("landing-list");
add_shortcode_to_list("home-panel");
add_shortcode_to_list("home-panel-container");

//add code to wordpress itself
add_shortcode('landing-banner', 'landing_banner_att');
add_shortcode('landing-list', 'landing_list_att');

add_shortcode('home-panel', 'home_panel_atts');
add_shortcode('home-panel-container', 'home_panel_container_atts');

?>