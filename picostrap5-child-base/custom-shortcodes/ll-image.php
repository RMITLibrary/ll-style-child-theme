<?php 
//-----------------------------
//	image_att

//	Creates an image, options for portrait, caption and transcript for the landing page

//	args:		$content - description of the landing page (max 280 characters)
//              $atts - attributes as follows:

//  $atts:      url         Absolute path to image
//              alt         Alt tag for the above image
//              caption     Attribution for the image (optional)  

//              align       center or centre -  to align img to centre (optional)
//              size        wide, md, sm - sets size of image (optional)

//              portrait    true - if omitted landscape is default (optional)
//              
//              border      true  - Adds a 1px border (optional)
//              shadow      true  - Adds a dropshadow (optional)
//              rounded     true  - Adds rounded corners (optional)
//              classes     adds whatever is placed in here into the figure class 
//                          (most definitely optional)


//  shortcode:  [ll-image][/ll-image]

//	usage:			
//  [ll-image url='https://path.to/image' alt='Alt tag for the image' caption='Caption here' portrait='true' centre='true' border='true' size='sm'][/ll-image]
//
//  [ll-image url='https://path.to/image' shadow='true' rounded='true' alt='Alt tag for the image']
//      [transcript-accordion]<p>Transcript content.</p>[/transcript-accordion]
//  [/ll-image]

//    Expected output
//    <figure>
//        <img src="my-image.jpg" alt="An example image" />
//        <figcaption>An example caption for this image.</figcaption>
//        <div class="accordion-item transcript"> 
//            <!-- lots of additional accordion code goes here -->	
//        </div>
//    </figure>
//
//    <figure class="portrait">
//        <div class="image-caption-wrapper">
//            <img src="my-image.jpg" alt="An example image" />
//            <figcaption>An example caption for this image.</figcaption>
//        </div>
//        <!-- START accordion item -->
//        <div class="accordion-item transcript">
//            <!-- lots of additional accordion code goes here -->
//        </div>
//        <!-- END accordion item -->
//    </figure>

function image_att ($atts, $content = null) {
    $default = array(
        'alt' => '',
        'url' => '',
        'border' => '',
        'shadow' => '',
        'rounded' => '',
        'align' => '',
        'portrait' => '',
        'size' => '',
        'caption' => '',
        'classes' => ''
    );
    $a = shortcode_atts($default, $atts);
    $content = do_shortcode($content);
            
    //START Build figure tag
    $figureTag = '<figure class="';
            
    //if align = center or centre, add class to align image to the centre
    if ($a['align'] == 'center' || $a['align'] == 'centre') {
        $figureTag .= 'centre '; 
    }
      
    //check for border
    if($a['border'] == 'true') { 
        $figureTag .= 'my-border '; 
    } 

    //check for shadow
    if($a['shadow'] == 'true') { 
        $figureTag .= 'drop-shadow '; 
    } 

    //check for rounded corners
    if($a['rounded'] == 'true') { 
        $figureTag .= 'round-corners '; 
    } 
    
            
    //if portrait is, add a class
    if($a['portrait'] == 'true') { 
        
        if($a['size'] == 'sm')
        {
            $figureTag .= 'portrait-small '; 
        }
        else
        {
            $figureTag .= 'portrait '; 
        } 
    }
    //add size attribute if required and portrait not specified       
    else if($a['size'] != '') { 

        if($a['size'] == 'wide')
        {
            $figureTag .= 'wide '; 
        }
        else
        {
            $figureTag .= 'img-width-' . $a['size'] . ' '; 
        } 
    }
    
    //if there's anything in clesses, add it (don't document this, for pros only)
    if($a['classes'] != '') { 
        $figureTag .= $a['classes'] . ' '; 
    } 
    
    $figureTag .= '">';
    //END Build figure tag 
            
    //Wrapper div not required for most cases
    $wrapperDiv = '';
    $wrapperDivEnd = '';
    
    //if aspect is portrait, create wrapper div code
    if($a['portrait'] == 'true') { 
        $wrapperDiv = '<div class="image-caption-wrapper">'; 
        $wrapperDivEnd = '</div>';
    }
            
    $figCaptionTag = '';
            
    if($a['caption'] != '') { 
        $figCaptionTag .= '<figcaption>' . $a['caption'] . '</figcaption>' . "\n"; 
    }       
             
            
    //Build <img> tag with alt tag, add border if present
    $imageTag = '<img src="' . $a['url'] . '" alt="' . $a['alt'] . '" />' . "\n";
           
 
    //Start output phase       
    $output = '';
    $output .= $figureTag . "\n";
    $output .= $wrapperDiv . "\n";
    $output .= $imageTag . "\n";
    $output .= $figCaptionTag;
    $output .= $wrapperDivEnd . "\n"; 
    
    //If $content exists, there's a transcript, add output from [transcript-accordion] 
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


//add the shortcode
add_shortcode('ll-image', 'image_att');

?>