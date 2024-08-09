<?php

//-----------------------------
//	ll_code_example_att

//	Outputs a code example. 
//  Need to escape html and shortcodes [ - &lbrack; and ] - &rbrack; at editor level
//

//  args:       wrap="true" - sets the code block to wrap rather than scroll. //                            This is recommended for shortcode examples where 
//                            line breaks can break the code 

//  shortcode:  [ll-code][/ll-code]

//	usage:		[ll-code wrap="true"]Code example goes here[/ll-code] 

//  Expected output - fill in later
//<div class="highlight wrap-code">
//    <pre>
//        <code>[ll-video url="https://path.to/video-embed"][transcript]&lt;p&gt;Transcript content here.&lt;/p&gt;[/transcript][/ll-video]</code>
//    </pre>
//</div>


function attribution_att($atts, $content = null) {
    $default = array(
        'id' => 'my-attribution'
    );
    
    if($content == null) {
        $content = 'Images by Digital Learning, RMIT Library';
    }
    
    $a = shortcode_atts($default, $atts);
    
    //<p class="small" id="caption-text">Images by Digital Learning, RMIT Library</p>
    
    //grab content from within the two shortcode tags
    $content = do_shortcode($content);
	
    $tag = '<p class="small" id="' . $a['id'] . '">' . $content . '</p>';

    return $tag;
}

//add code to list (used in the_content_filter)
add_shortcode_to_list("attribution");

//add code to wordpress itself
add_shortcode('attribution', 'attribution_att');

?>