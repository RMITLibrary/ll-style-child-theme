<?php


function blockquote_nav_att($atts, $content = null) {
	$default = array(
        'link' => 'https://www.rmit.edu.au',
        'category' => '',
		'title' => 'My blockquote nav',
		'extra-info' => '',
		'icon' => ''
    );
	
	$a = shortcode_atts($default, $atts);
    $content = do_shortcode($content);
	
	$output = '';
    
    $output .= '<blockquote class="complex">';
    $output .= '<a href="' . $a['link'] .'">';
    $output .= '<div class="content">'; 
	
	if($a['category'] != '') {
		$output .= '<p class="category">'. $a['category'] . '</p>';
	}
	
    $output .= '<h3>' . $a['title'] . '</h3>';
	if($content != null) {
		$output .= '<p>' . $content . '</p>';
	}
	
	if($a['extra-info'] != '') {
		$output .= '<small>'. $a['extra-info'] . '</small>';
	}
	
    $output .= '</div>';
	
	if($a['icon'] != '') {
		$output .= '<div class="icon-wrap">';
		$output .= '<img src="'. $a['icon'] . '" alt="" />';
		$output .= '</div>';
	}
	
    $output .= '</a></blockquote>';
	return $output;
}

/*
<blockquote class="complex">
	<a href="mylink">
		<div class="content">
			<p class="category">Category</p>
			<h3>This is a title </h3>
			<p>This is the blockquote content.</p>
			<small>Extra information</small>
		</div>
		<div class="icon-wrap">
			<img src="my-icon.png" alt="" />
		</div>
	</a>
</blockquote>

*/

function transcript_accordion_att($atts, $content = null) {
	return doAccordion("transcript", $atts, $content);
}

function bootstrap_accordion_att($atts, $content = null) {
	return doAccordion("regular", $atts, $content);
}

function doAccordion($type, $atts, $content = null) {
    $default = array(
        'title' => 'Transcript'
    );
    $a = shortcode_atts($default, $atts);
    $content = do_shortcode($content);
    
    $headId = generate_id($a['title'], "head");
    $bodyId = generate_id($a['title'], "body");
    
    $labelTag = 'h2';
    $extraClass = '';
    
    if ($type == 'transcript') {
        $labelTag = 'p';
        $extraClass = 'transcript';
    }
    
    $output = '';
    
    $output .= '<div class="accordion-item ' . $extraClass . '">';
    $output .= '<' . $labelTag .' class="accordion-header" id="' . $headId .'">';
    $output .= '<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#' . $bodyId . '" aria-expanded="false" aria-controls="' . $bodyId . '">';    
    $output .= $a['title'];
    $output .= '</button>';
    $output .= '</' . $labelTag . '>';
    $output .= '<div id="' . $bodyId . '" class="accordion-collapse collapse" aria-labelledby="' . $headId . '">';
    $output .= '<div class="accordion-body">' . $content . '</div></div></div>';

    return $output;
}

function generate_id($string, $prefix) {
    $lowercaseString = strtolower($string);
    $hyphenatedString = str_replace(' ', '-', $lowercaseString);
    return $prefix . '-' . $hyphenatedString;
}


/*
[bs-accordion title='Item 1 ']

[bs-accordion title='Transcript' type='transcript']

*/
/*

<div class="accordion-item">
        <h2 class="accordion-header" id="headingOne">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
        Accordion Item #1
        </button>
    </h2>
    <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne">
        <div class="accordion-body">
        First item content goes here
        </div>
    </div>
</div>

<div class="accordion-item transcript">
		<p class="accordion-header" id="Transcript-head">
		  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#Transcript-body" aria-expanded="false" aria-controls="Transcript-body">
			Transcript
		  </button>
		</p>
		<div id="Transcript-body" class="accordion-collapse collapse" aria-labelledby="Transcript-head">
		  <div class="accordion-body">
			 <!-- content goes here -->
		  </div>
		</div>
	</div>

*/

function landing_banner_att($atts, $content = null) {
    $default = array(
        'caption' => 'Image by Digital Learning, RMIT Library',
        'img' => 'https://rmitlibrary.github.io/cdn/learninglab/illustration/home-default.png',
        'alt' => 'A vector illustration showing a desk featuring variousitems a student might need'
    );
    $a = shortcode_atts($default, $atts);
    $content = do_shortcode($content);
    
    $output = '';
    
    $output .= '<div class="landing-banner">';
    $output .= '<figure aria-labelledby="caption-text">';
    $output .= '<img src="' . $a['img'] . '" alt="' . $a['alt'] . '" />';    
    $output .= '</figure>';
    $output .= '<div class="landing-content">';
    $output .= '<div class="red-bar"></div>';
    $output .= '<h1>' . get_the_title() . '</h1>';
    $output .= '<p class="lead">' . $content  . '</p>';
    $output .= '<p class="small">' . $a['caption']  . '</p>';
    $output .= '</div></div>';

    return $output;

}


/* 
[landing-banner img='https://path.to/image' alt='description of the image' caption='Image by creator name']Description of the landing page[/landing-banner]

<div class="landing-banner">
			<figure aria-labelledby="caption-text"><img src="/images/assignments.png" alt="A vector illustration showing a desk featuring..." /></figure>
			<div class="landing-content">
				<div class="red-bar"></div>
				<h1>Title</h1>
				<p class="lead">New to uni? University essentials has you covered. Find out more about topics as diverse as group work, critical thinking and even artificial intelligence.</p>
				<p class="small"  id="caption-text">Photo by &lt;insert Photographer name&gt; on Unsplash</p>
			</div>
		</div>
        */

function landing_list_att($atts) {
    $default = array(
        'category' => ''
    );
    $a = shortcode_atts($default, $atts);
    
    $pageId = get_the_ID();

    $output = '';
    $output .= '<div class="landing-link">';
    
    if($a['category'] != '') {
		$output .= '<h2 class="h3">'. $a['category'] . '</h2>';
	}
    
    //doChildrenList() is defined in functions.php
    $output .= '<ul class="link-list">'. doChildrenList($pageId) . '</ul>';
    $output .= '</div>';

    return $output;
}

/*



<div class="landing-link">
				<h2 class="h3">Optional category</h2>
				<ul class="link-list">
					<li><a href="">Link 1</a></li>
					<li><a href="">Link 2</a></li>
					<li><a href="">Link 3</a></li>
					<li><a href="">Link 4</a></li>
					<li><a href="">Link 5</a></li>
				</ul>
			</div>

*/



function the_content_filter($content) {
    $block = join("|",array("blockquote-nav", "bs-accordion", "transcript-accordion", "landing-banner", "landing-list"));
    $rep = preg_replace("/(<p>)?\[($block)(\s[^\]]+)?\](<\/p>|<br \/>?)?/","[$2$3]",$content);
    $rep = preg_replace("/(<p>)?\[\/($block)](<\/p>|<br \/>?)?/","[/$2]",$rep);
    return $rep;
}
add_filter("the_content", "the_content_filter");



add_shortcode('bs-accordion', 'bootstrap_accordion_att');
add_shortcode('transcript-accordion', 'transcript_accordion_att');
add_shortcode('blockquote-nav', 'blockquote_nav_att');
add_shortcode('landing-banner', 'landing_banner_att');
add_shortcode('landing-list', 'landing_list_att');


?>