<?php

//-----------------------------
//	blockquote_nav_att

//	Creates a linked blockquote with options for category, extra-info and icon

//	args:		$content - text in the blockquote (optional)
//              $atts - attributes as follows:

//  $atts:      title       Title of the blockquote
//              link        URL to link to (rmith.eduau is the default)
//              category    Small text shown above title (optional)
//              extra-info  Small text shown below content (optional)
//              icon        Absolute path to icon (optional - svg preferred)

//  shortcode:  [blockquote-nav]

//	usage:			
//  [blockquote-nav category='Category' link='/cohesion' title='This is the title' extra-info='Extra information' icon='https://path.to/icon.svg']This is the blockquote content.[/blockquote-nav]

//	Expected output
//  <blockquote class="complex">
//	<a href="mylink">
//		<div class="content">
//			<p class="category">Category</p>
//			<h3>This is a title </h3>
//			<p>This is the blockquote content.</p>
//			<small>Extra information</small>
//		</div>
//		<div class="icon-wrap">
//			<img src="my-icon.png" alt="" />
//		</div>
//	</a>
//  </blockquote>

function blockquote_nav_att($atts, $content = null) {
	$default = array(
        'link' => 'https://www.rmit.edu.au',
        'category' => '',
		'title' => 'My blockquote nav',
		'extra-info' => '',
		'icon' => ''
    );
    
    //merges user-defined attributes with a set of default values ($default)
    $a = shortcode_atts($default, $atts);
    
    //grab content from within the two shortcode tags
    $content = do_shortcode($content);
	
	$output = '';
    
    $output .= '<blockquote class="complex">' . "\n";
    $output .= '<a href="' . $a['link'] .'">' . "\n";
    $output .= '<div class="content">' . "\n";
	
    //If $category exists, add it to the output
	if($a['category'] != '') {
		$output .= '<p class="category">'. $a['category'] . '</p>' . "\n";
	}
	
    //Title has to exist
    $output .= '<h3>' . $a['title'] . '</h3>' . "\n";
    
    //If $content exists, add it to the output
	if($content != null) {
		$output .= '<p>' . $content . '</p>' . "\n";
	}
	
     //If extra-info exists, add it to the output
	if($a['extra-info'] != '') {
		$output .= '<small>'. $a['extra-info'] . '</small>' . "\n";
	}
	
    $output .= '</div>';
	
     //If icon exists, add it to the output
	if($a['icon'] != '') {
		$output .= '<div class="icon-wrap">';
		$output .= '<img src="'. $a['icon'] . '" alt="" />';
		$output .= '</div>' . "\n";
	}
	
    $output .= '</a></blockquote>';
	return $output;
}




//-----------------------------
//	alert_banner_att

//	Creates an alert banner in the bootstrap style

//	args:		$atts:

//  alert:  The message, html can be included

// called from:  video_att

//  shortcode:  [alert-banner]

//	usage:			
//  [alert-banner mess='<strong>Warning!</strong> Message goes here' /]

//  Expected output
//<div class="alert alert-danger alert-dismissible">
//    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
//    <strong>Warning!</strong> Message here.
//</div>
    
function alert_banner_att($atts) {
    
    return doAlertBanner($att['alert']);
}
    

function doAlertBanner($content)
{ 
    $output = '<div class="alert alert-danger alert-dismissible">'  . "\n";
    $output .= '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' . "\n";
    $output .= wp_kses_post($content);
    $output .= '</div>';

    return $output;    
}

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
        'caption' => 'Image by Digital Learning, RMIT Library',
        'img' => 'https://rmitlibrary.github.io/cdn/learninglab/illustration/home-default.png',
        'alt' => 'A vector illustration showing a desk featuring various items a student might need'
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
    
    if($a['category'] != '') {
		$output .= '<h2 class="h3">'. $a['category'] . '</h2>' . "\n";
	}
    
    //doChildrenList() is defined in functions.php
    $output .= '<ul class="link-list">'. doChildrenList($pageId) . '</ul>' . "\n";
    $output .= '</div>';

    return $output;
}


//-----------------------------
//	ll_code_example_att

//	Outputs a code example. 
//  Note to output shortcode examples, you'll need to escape html and [ - &lbrack;  and ] - &rbrack; at editor level

//  shortcode:  [ll-code][/ll-code]

//	usage:			
//  [landing-list category='Optional category /]

//  Expected output - fill in later


function ll_code_example_att($atts, $content = null) {
    $default = array(
        'wrap' => ''
    );
    $a = shortcode_atts($default, $atts);
	
    $tag = '<div class="highlight"><pre><code>';
    
    if($a['wrap'] != '') {
		$tag = '<div class="highlight wrap-code"><pre><code>';
	}
    
    // Remove <br> tags from the content
    $content = str_replace(array('<br>', '<br />'), '', $content);
	
	//Replace random curly quotes with straight ones (oh, wordpress)
	//$content = str_replace(array('”', '″', '“'), '&quot;', $content);
    
    return $tag . $content . '</code></pre></div>';
}


//-----------------------------
//	the_content_filter

//	Prevents empty <p> or <br> tags being added in between shortcodes

function the_content_filter($content) {
    
    //Add in shortcodes to this list
    $block = join("|",array("blockquote-nav", "bs-accordion", "transcript-accordion", "ll-image", "ll-video", "alert-banner", "landing-banner", "landing-list", "transcript","ll-accordion", "ll-code"));
    $rep = preg_replace("/(<p>)?\[($block)(\s[^\]]+)?\](<\/p>|<br \/>?)?/","[$2$3]",$content);
    $rep = preg_replace("/(<p>)?\[\/($block)](<\/p>|<br \/>?)?/","[/$2]",$rep);
    return $rep;
}
add_filter("the_content", "the_content_filter");


//-----------------------------
//	strip_tags_before_echo

//When the above isn't working, use this function right before echoing 
//content to definitely get rid of <br> and <p></p> (but not <br />)

//called by: Additional_resources page-template

function strip_tags_before_echo($content) {
    // Strip out <br> tags
    $content = preg_replace('/<br\s*\/?>/', '', $content);
    
    // Strip out <p></p> tags
    $content = preg_replace('/<p[^>]*>[\s|&nbsp;]*<\/p>/', '', $content);
    
    // Return the stripped content
    return $content;
}

// include files with code for specific shortcodes. Shortcodes are added in these files too.

include('accordion.php');	//handles ll-accordion and transcript
include('image.php');	//handles ll-image shortcode
include('video.php');	//handles ll-video shortcode

//-----------------------------
//	Add shortcodes
//	Shortcodes and their respective functons are added here

add_shortcode('alert-banner', 'alert_banner_att');
add_shortcode('blockquote-nav', 'blockquote_nav_att');
add_shortcode('landing-banner', 'landing_banner_att');
add_shortcode('landing-list', 'landing_list_att');
add_shortcode('ll-code', 'll_code_example_att');

?>