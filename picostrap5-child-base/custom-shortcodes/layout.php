<?php

//-----------------------------
//	ll_grid_att

//	Outputs a a css grid, avoiding blank p and br tags. 
//

//	args:		$content - the html markup to be put into the grid
//              $atts - attributes as follows:

//  $atts:      columns     3 or 4 (optional, 2 is the default)
//              gap         lg or true (makes gap 40px at larger screen sizes)
//              size        lg, md, sm (640px, 400px, 296px) - sets width of grid (optional, full width ids the default)

//  shortcode:  [ll-grid][/ll-grid]

//	usage:		[ll-grid wrap="true"]Markup goes here[/ll-grid] 

//  Expected output
//<div class="my-grid my-grid-3up grid-width-lg">
//    <div><!-- content --></div>
//    <div><!-- content --></div>
//    <div><!-- content --></div>
//</div>

function ll_grid_att($atts, $content = null) {
    $default = array(
        'columns' => '',
        'gap' => '',
        'size' => ''
    );
    $a = shortcode_atts($default, $atts);

    // Ensure content is processed correctly
    $content = do_shortcode(shortcode_unautop($content));

    $tag = '<div class="my-grid';

    // Apply optional class for 3 or 4 columns
    if ($a['columns'] == '3') {
        $tag .= ' my-grid-3up';
    } elseif ($a['columns'] == '4') {
        $tag .= ' my-grid-4up';
    }

    // Apply optional class for large gap
    if ($a['gap'] == 'lg' || $a['gap'] == 'true') {
        $tag .= ' gap-lg';
    }

    // Apply optional class for width classes
    if ($a['size'] == 'lg') {
        $tag .= ' grid-width-lg';
    } elseif ($a['size'] == 'md') {
        $tag .= ' grid-width-md';
    } elseif ($a['size'] == 'sm') {
        $tag .= ' grid-width-sm';
    }

    // Complete tag
    $tag .= '">';

    // Return the complete output
    return $tag . $content . '</div>';
}

//add code to list (used in the_content_filter)
add_shortcode_to_list("ll-grid");

//add code to wordpress itself
add_shortcode('ll-grid', 'll_grid_att');

?>