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
//	transcript_accordion_att

//	Creates a linked blockquote with options for category, extra-info and icon

//	args:		$content - html content in the accordion 
//              $atts - attributes as follows:

//  $atts:      title  Title of the accordion (optional - defaults to "Transcript")
//				size	IF set to "full-width", transcript is set to 100% width (optional)

//  shortcode:  [transcript-accordion]

//	usage:			
//  [transcript-accordion]<p>Transcript content (wrap in p tags recommended).</p>[/transcript-accordion]

function transcript_accordion_att($atts, $content = null) {
	return doAccordion("transcript", $atts, $content);
}



//-----------------------------
//	bootstrap_accordion_att

//	Creates a linked blockquote with options for category, extra-info and icon

//	args:		$content - html content in the accordion 
//              $atts - attributes as follows:

//  $atts:      title   Title of the accordion (optional - defaults to "Transcript")
//              open    Set to true to open accordion by default.

//	calls:		doAccordion

//  shortcode:  [bs-accordion]

//	usage:			
//  [bs-accordion title="My accordion"]<p>Transcript content (wrap in p tags recommended).</p>[/bs-accordion]
//  Wrap multiple accordions in a div: 
//  <div class="accordion" id="accordion-example">
//  [bs-accordion title="My accordion 1"]<p>Transcript content</p>[/bs-accordion]
//  [bs-accordion title="My accordion 2"]<p>Transcript content</p>[/bs-accordion]
//  </div>

//  expected output:
//<div class="accordion-item transcript">
//    <p class="accordion-header" id="Transcript-head">
//      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#Transcript-body" aria-expanded="false" aria-controls="Transcript-body">
//        Transcript
//      </button>
//    </p>
//    <div id="Transcript-body" class="accordion-collapse collapse" aria-labelledby="Transcript-head">
//      <div class="accordion-body"><p>Transcript content</p></div>
//    </div>
//</div>

function bootstrap_accordion_att($atts, $content = null) {
	return doAccordion("regular", $atts, $content);
}



//-----------------------------
//	doAccordion

//	Outputs accordion code

//	Called from:	bootstrap_accordion_att
//                  transcript_accordion_att

//	args:			$type - Can be "transcript" or "regular"
//                  $atts - attribute object from shortcode functions
//					$content - html content in the accordion

//	calls:			generate_id

//	usage:			return doAccordion("transcript", $atts, $content);

function doAccordion($type, $atts, $content = null) {
    //If title attribute is omitted, default to "Transcript"
    $default = array(
        'title' => 'Transcript',
		'size' => '',
        'open' => ''
    );
    
    //merges user-defined attributes with a set of default values ($default)
    $a = shortcode_atts($default, $atts);
    
    //grab content from within the two shortcode tags
    $content = do_shortcode($content);
    
    //generate a unique id for head and body sections of the accordion
    $headId = generate_id($a['title'], "head");
    $bodyId = generate_id($a['title'], "body");
    
    //default state is h2
    $labelTag = 'h2';
    $extraClass = '';
    
    //these vars control whether accordion is open or not. It's closed by default
    $buttonState = 'collapsed';
    $ariaExpanded = 'false';
    $bodyState  = '';
    
    //if we have a attribut of open=true, set variable to make this happen
    if($a['open'] == 'true')
    {
        $buttonState = '';
        $ariaExpanded = 'true';
        $bodyState = 'show';
    }
    
    //if type is transcript, adjust some of the tags to style differently
    if ($type == 'transcript') {
        $labelTag = 'p';
        $extraClass = 'transcript';
		
		//this additional class stratches the transcript accordion to 100% of container width
		if($a['size'] == 'full-width')
		{
			$extraClass .= '  transcript-full-width';
		}
    }
    
    //output the html markup
    $output = '';
    
    $output .= '<div class="accordion-item ' . $extraClass . '">' . "\n";
    $output .= '<' . $labelTag .' class="accordion-header" id="' . $headId .'">' . "\n";
    $output .= '<button class="accordion-button ' . $buttonState . '" type="button" data-bs-toggle="collapse" data-bs-target="#' . $bodyId . '" aria-expanded="'. $ariaExpanded . '" aria-controls="' . $bodyId . '">';    
    $output .= $a['title'];
    $output .= '</button>' . "\n";
    $output .= '</' . $labelTag . '>' . "\n";
    $output .= '<div id="' . $bodyId . '" class="accordion-collapse collapse ' . $bodyState . '" aria-labelledby="' . $headId . '">' . "\n";
    $output .= '<div class="accordion-body">' . $content . '</div></div></div>';

    return $output;
}



//-----------------------------
//	generate_id

//	Generate unique id

//	Called from:	doAccordion

//	args:			$string  the title of the accordion
//                  $prefix either "head" or "body"

//	usage:			$headId = generate_id($a['title'], "head");
//	Expected output
//	"head-myTitle-4035"

function generate_id($string, $prefix) {
    //Make string lower case
    $lowercaseString = strtolower($string);
    
    //Replace spaces with hypens
    $hyphenatedString = str_replace(' ', '-', $lowercaseString);
    
    //add a random number on the end to ensure uniqueness (important for multiple transcript accordions)
    $randomNumber = rand(1000, 9999);
    return $prefix . '-' . $hyphenatedString . '-' . $randomNumber;
}


//-----------------------------
//	image_att

//	Creates an image, options for portrait, caption and transcript for the landing page

//	args:		$content - description of the landing page (max 280 characters)
//              $atts - attributes as follows:

//  $atts:      url         Absolute path to image
//              alt         Alt tag for the above image
//              caption     Attribution for the image (optional)  
//              aspect      "portrait" if omitted landscape is provided by default (optional)
//              left        Align image to the left - centred by default (optional)
//              border      Add a 1px border to the image (optional)
//              size        (optional)

//  shortcode:  [ll-image][/ll-image]

//	usage:			
//  [ll-image url='https://path.to/image' alt='Alt tag for the image' caption='Caption here' aspect='portrait' left='true' border='true' size='sm'][/ll-image]
//
//  [ll-image url='https://path.to/image' alt='Alt tag for the image']
//      [transcript-accordion]<p>Transcript content.</p>[/transcript-accordion]
//  [/ll-image]

//  Expected output
//<figure class="img-transcript">
//	<div>
//		<div>
//			<img src="my-image.jpg" alt="An example image" />
//		</div>
//		<div class="accordion-item transcript"> 
//			<!-- lots of additional accordion code goes here -->	
//		</div>
//	</div>
//</figure>
//    
//<figure class="img-transcript caption-side">
//	<div class="img-caption-wrap">
//		<div class="portrait">
//			<img src="my-image.jpg" alt="An example image" />
//		</div>
//		<figcaption>An example caption for this image.</figcaption>
//		<div class="accordion-item transcript"> 
//			<!-- lots of additional accordion code goes here -->	
//		</div>
//	</div>
//</figure>

function image_att ($atts, $content = null) {
    $default = array(
        'alt' => '',
        'url' => '',
        'border' => '',
        'left' => '',
        'aspect' => '',
        'size' => '',
        'caption' => ''
        
    );
    $a = shortcode_atts($default, $atts);
    $content = do_shortcode($content);
            
    //START Build figure tag
    $figureTag = '<figure class="';
            
    //if left = true, add class to align image left
    if($a['left'] != '') { 
        $figureTag .= 'img-left '; 
    }
            
    //If $content exists, there's a transcript, add a class
	if($content != null) {
		$figureTag .= 'img-transcript ';
	}
            
    //if portrait and there's a caption, add a class
    if($a['aspect'] == 'portrait' && $a['caption'] != '') { 
        $figureTag .= 'caption-side '; 
    }
    
    //add size attribute if required       
    if($a['size'] != '') { 
        
        $sizeSuffix = $a['size'];
        if($a['size'] == 'full-width')
        {
            $sizeSuffix = "full"; 
        }
        $figureTag .= 'img-width-' . $sizeSuffix . ' '; 
    }
    
    $figureTag .= '">';
    //END Build figure tag 
         
            
    //START Build outer div tag
    $outerDiv = '<div class="';
    
    //if theere's a caption, add in the class
    if($a['caption'] != '') { 
        $outerDiv .= 'img-caption-wrap '; 
    }
    $outerDiv .= '">';       
    //END Build outer div tag
      
            
    //START Build inner div tag
    $innerDiv = '<div class="';
    
    //if theere's a caption, add in the class
    if($a['aspect'] == 'portrait') { 
        $innerDiv .= 'portrait '; 
    }
    $innerDiv .= '">';       
    //END Build inner div tag
            
    $figCaptionTag = '';
            
    if($a['caption'] != '') { 
        $figCaptionTag .= '<figcaption>' . $a['caption'] . '</figcaption>' . "\n"; 
    }       
    
                       
            
    //Build <img> tag with alt tag, add border if present
    $imageTag = '<img src="' . $a['url'] . '" alt="' . $a['alt'] . '"';   
            
    if($a['border'] != '') { 
        $imageTag .= ' class="border"'; 
    }     
    $imageTag .= ' />' . "\n";
           
 
    //Start output phase       
    $output = '';
    $output .= $figureTag . "\n";
    $output .= $outerDiv . "\n";
    $output .= $innerDiv . "\n";
    $output .= $imageTag . "\n";
    $output .= '</div>' . "\n";
    $output .= $figCaptionTag;
    $output .= '</div>' . "\n";  
    
    //If $content exists, there's a transcript, add output from [transcript-accordion] or [lightweight-accordion]
	if($content != null) {
		$output .= $content;
	}        
                  
    $output .= '</figure>' . "\n";
            
    return $output; 
    
    /*$debug = '<pre><code>';
    $debug .= $imageTag;
    
    //$debug .= 'img: ' . $a['img'] . "\n";
    $debug .= 'alt: ' . $a['alt'] . "\n";
    $debug .= 'left: ' . $a['left'] . "\n";
    $debug .= 'border: ' . $a['border'] . "\n";
    $debug .= 'size: ' . $a['size'] . "\n";
    $debug .= '</code></pre>';
    
    
    return $debug;*/
}

//-----------------------------
//	video_att
//
//	Creates an image, options for portrait, caption and transcript for the landing page

//	args:		$content - description of the landing page (max 280 characters)
//              $atts - attributes as follows:

//  $atts:      url         Absolute path to video - eg https://www.youtube.com/embed/w_IEpVVdNrE
//              caption     Attribution for the video (optional)  
//              left        Align image to the left - centred by default (optional)
//              alert       html message for an alert banner (optional)


//  shortcode:  [ll-image][/ll-image]

//	usage:			
//  [ll-image img='' alt='Alt tag for the image' caption='Caption here' aspect='portrait' left='true' border='true' size='sm'][/ll-image]
//
//  [ll-video url='']
//      [transcript-accordion]<p>Transcript content.</p>[/transcript-accordion]
//  [/ll-video]

//  Expected output
//<figure class="video">
//	<div class="responsive-video">
//		<iframe src="https://www.youtube.com/embed/video-id" frameborder="0" 
//		allowfullscreen=""></iframe>
//	</div>
//	<figcaption>An example caption for this image.</figcaption>
//	<div class="accordion-item transcript">
//		<!-- lots of additional accordion code goes here -->	
//	</div>
//</figure>

function video_att($atts, $content = null) {
    $default = array(
        'url' => '',
        'left' => '',
        'caption' => '',
        'alert' => ''
        
    );
    $a = shortcode_atts($default, $atts);
    $content = do_shortcode($content);
        
    $output = '';
            
    //Build figure tag
    $output .= '<figure class="video ';
            
    //if left = true, add class to align image left
    if($a['left'] != '') { 
        $output .= 'img-left '; 
    }
    
    $output .= '">' . "\n";

    //if there's an alert message, call alert_banner_att to do the mark-up
    if($a['alert'] != '') { 
        $output .= doAlertBanner($a['alert']);   
    }      
    
    $output .= '<div class="responsive-video">' . "\n"; 
    $output .= '<iframe src="' . $a['url'] . '" frameborder="0" allowfullscreen=""></iframe>' . "\n";
            
    $output .= '</div>' . "\n"; 
     
    //If caption exists
    if($a['caption'] != '') { 
        $output .= '<figcaption>' . $a['caption'] . '</figcaption>' . "\n"; 
    }  
        
    //If $content exists, there's a transcript, add output from [transcript-accordion]
	if($content != null) {
		$output .= $content;
	}   
        
    $output .= '</figure>' . "\n";
            
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
    $output .= '<p class="small">' . $a['caption']  . '</p>' . "\n";
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
//	the_content_filter

//	Prevents empty <p> or <br> tags being added in between shortcodes

function the_content_filter($content) {
    
    //Add in shortcodes to this list
    $block = join("|",array("blockquote-nav", "bs-accordion", "transcript-accordion", "ll-image", "ll-video", "alert-banner", "landing-banner", "landing-list"));
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



//-----------------------------
//	Add shortcodes
//	Shortcodes and their respective functons are added here

add_shortcode('bs-accordion', 'bootstrap_accordion_att');
add_shortcode('transcript-accordion', 'transcript_accordion_att');
add_shortcode('ll-image', 'image_att');
add_shortcode('ll-video', 'video_att');
add_shortcode('alert-banner', 'alert_banner_att');
add_shortcode('blockquote-nav', 'blockquote_nav_att');
add_shortcode('landing-banner', 'landing_banner_att');
add_shortcode('landing-list', 'landing_list_att');


?>