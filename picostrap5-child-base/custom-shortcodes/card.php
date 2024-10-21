<?php

//-----------------------------
//	ll_card_att

//	Outputs a bootstrap card
//

//	args:		$content - the html markup to be put into the grid
//              $atts - attributes as follows:

//  $atts:      title           Optional card title
//              heading-tag     h3, h4, h5 etc. - default is h2

//  shortcode:  [ll-card][/ll-card]

//	usage:		[ll-card title="Short report" heading-tag="h3"]Markup goes here[/ll-grid] 

//  Expected output
// <div class="card">
// 		<div class="card-body">
// 			<h3 class="card-title">Short report</h3>
// 			<ul>
// 				<li>Title page</li>
// 				<li>Introduction</li>
// 				<li><span class="highlight-1">Discussion</span></li>
// 				<li>Recommendations</li>
// 				<li>References</li>
// 			</ul>
// 		</div>
// 	</div>

function ll_card_att($atts, $content = null) {
    $default = array(
        'title' => '',
        'heading-tag' => 'h3',
        'heading-size' => '',
        'float' => '',
        'img' => '',
        'attibution-id' => '',
        'alt' => '',
        'classes' => ''
    );
    $a = shortcode_atts($default, $atts);

     //default state is h3
     $labelTag = 'h3';

    // Ensure content is processed correctly
    $content = do_shortcode(shortcode_unautop($content));

    $output = '<div class="card ';

    if($a['float'] == 'true') {
        $output .= ' float-right ';
    }

     //if there's anything in clesses, add it (don't document this, for web devs only)
     if($a['classes'] != '') { 
        $output .= $a['classes'] . ' '; 
    } 

    $output .= '">' . "\n";

    //if there's an img property
    if($a['img'] != '') {  
        $image_atts = array(
            'url' => $a['img'],
            'attribution-id' => $a['attibution-id'],
            'alt' => $a['alt']
        );

        $output .= image_att($image_atts);
    }

    
    $output .= '<div class="card-body">' . "\n";

    //if heading tag has a value, update $labelTag, otherwise it will retain h3 as default
    if($a['heading-tag'] != '')
    {
        // Sanitize the heading level to prevent invalid HTML
        $allowed_headings = array('h1', 'h2', 'h3', 'h4', 'h5', 'h6');
        if (in_array($a['heading-tag'], $allowed_headings)) {
            $labelTag = $a['heading-tag'];
        }
    }

    // Apply optional title div, allow to alter size via additional class
    if ($a['title'] != '') {
        $output .= '<' . $labelTag .' class="card-title ' . $a['heading-size'] . '">';    
        $output .= $a['title'];
        $output .= '</' . $labelTag . '>' . "\n";
    }

    //add the content itself
    $output .= $content;

    //close divs
    $output .= "\n" . '</div></div>' . "\n";
            
    return $output; 
}
add_shortcode_to_list("ll-card");

//add code to wordpress itself
add_shortcode('ll-card', 'll_card_att');

?>