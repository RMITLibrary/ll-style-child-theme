<?php

/*function subscribe_link_att($atts, $content = null) {
    $default = array(
        'link' => '#',
    );
    $a = shortcode_atts($default, $atts);
    $content = do_shortcode($content);

    return 'Follow us on '.$content.'';

}
add_shortcode('subscribe', 'subscribe_link_att');*/

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

function bootstrap_accordion_att($atts, $content = null) {
    $default = array(
        'title' => 'My accordion',
        'type' => 'Regular'
    );
    $a = shortcode_atts($default, $atts);
    $content = do_shortcode($content);
    
    $headId = generate_id($a['title'], "head");
    $bodyId = generate_id($a['title'], "body");
    
    $labelTag = 'h2';
    $extraClass = '';
    
    if ($a['type'] == 'transcript') {
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

function the_content_filter($content) {
    $block = join("|",array("blockquote-nav", "bs-accordion"));
    $rep = preg_replace("/(<p>)?\[($block)(\s[^\]]+)?\](<\/p>|<br \/>?)?/","[$2$3]",$content);
    $rep = preg_replace("/(<p>)?\[\/($block)](<\/p>|<br \/>?)?/","[/$2]",$rep);
    return $rep;
}
add_filter("the_content", "the_content_filter");


add_shortcode('bs-accordion', 'bootstrap_accordion_att');
add_shortcode('blockquote-nav', 'blockquote_nav_att');

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

?>