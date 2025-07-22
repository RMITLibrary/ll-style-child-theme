<?php

//-----------------------------
//	title_icon
//
//	Creates a heading element with an icon and optional dark mode styling.
//	args:		$content - text within the heading
//              $atts - attributes as follows:
//
//  $atts:      heading-tag    Specifies the heading level (h1-h6), default is 'h2'.
//              type           CSS class name for distinguishing styles.
//							   aboriginal-flag, torres-strait-flag, quiz are 
//			   				   predefined values, images etc. coded into css
//
//              img            (very much optional) URL of the icon image for the light mode.
//              img-dark       (very much optional) URL of the icon image for the dark mode.
//              alt            (very much optional) Alternate text for the icon image.
//
//  shortcode:  [title-icon]
//	usage:			
//  [title-icon heading-tag='h3' type='quiz-icon' img='https://path.to/light-icon.svg' img-dark='https://path.to/dark-icon.svg' alt='Quiz Icon']Your Heading Content[/title-icon]
//	Expected output:
//  <style>
//      .quiz-icon::before { 
//          background-image: url("https://path.to/light-icon.svg"); 
//          content: '' / 'Quiz Icon'; 
//      } 
//      @media (prefers-color-scheme: dark) { 
//          .quiz-icon::before { 
//              background-image: url("https://path.to/dark-icon.svg"); 
//          } 
//      }
//  </style>
//  <h3 class="title-icon quiz-icon">Your Heading Content</h3>


function title_icon($atts, $content = null) {
	$default = array(
        'heading-tag' => '',
        'type' => '',
		'img' => '',
		'img-dark' => '',
        'alt' => ''
    );
    
    //merges user-defined attributes with a set of default values ($default)
    $a = shortcode_atts($default, $atts);
    
    //grab content from within the two shortcode tags
    $content = do_shortcode($content);
	
    $headingTag = 'h2';

    if($a['heading-tag'] != '')
    {
        // Sanitize the heading level to prevent invalid HTML
        $allowed_headings = array('h1', 'h2', 'h3', 'h4', 'h5', 'h6');
        if (in_array($a['heading-tag'], $allowed_headings)) {
            $headingTag = $a['heading-tag'];
        }
    }

    $type = $a['type'];
    $heading .= '<' . $headingTag . ' class="title-icon ' . $type . '">';
	$heading .= $content . '</' . $headingTag . '>';

	$style = '';

	if($a['img'] != '') {
		
		$style = '<style>';

		$style .= '.' . $a['type'] .'::before { background-image: url("' . $a['img'] . '"); ' ;

		if($a['alt'] != '') {
			$style .= "content: '' / '" . $a['alt'] . "'; ";
		}

		$style .= ' } ';

		if($a['img-dark'] != '') {
			$style .= '@media (prefers-color-scheme: dark) { .' . $a['type'] .'::before { background-image: url("' . $a['img-dark'] . '"); } }' ;
		}

		$style .=  '</style>';
	}
    
    $output = $style . $heading .  "\n";
	return $output;
}

//add code to list (used in the_content_filter)
add_shortcode_to_list("title-icon");

//add code to wordpress itself
add_shortcode('title-icon', 'title_icon');

?>