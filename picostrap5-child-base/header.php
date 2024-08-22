<?php
// Exit if accessed directly.
defined('ABSPATH') || exit;

?>
<!doctype html>
<html <?php language_attributes(); ?> class="nav-fixed">

<head>

	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- wp_head -->
	<?php wp_head(); ?>
	<!-- /wp_head -->
    
    <style>
    <?php 
      // Fix menu overlap bug..
      if ( is_admin_bar_showing() ) {
          echo '#wp-admin-bar-root-default { top: -1.5rem !important; }'; 
          echo '#wp-admin-bar-top-secondary { top: -1.5rem !important; }'; 
      }
    ?>
    </style>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>

	<?php 
    // Custom filter to check if header elements should be displayed. To disable, use: add_filter('picostrap_enable_header_elements', '__return_false');
    /*if (apply_filters('picostrap_enable_header_elements', true)):

        //check if LC option is set to "Handle Header"    
        if (!function_exists('lc_custom_header')) {
            //use the built-in theme header elements 
            get_template_part( 'partials/header', 'optional-topbar' ); 
            get_template_part( 'partials/header', 'navbar' );
        } else {
            //use the LiveCanvas Custom Header
            lc_custom_header(); 
        }

    endif;*/
    
	?>
	
<header>
<a href="#main-content" class="visually-hidden-focusable">Skip to main content</a>
<div class="top-navigation">
    <div class="container">
        <div class="row">
            <div class="col-auto left-nav">
                <div class="rmit-logo"><span class="visually-hidden">RMIT University logo</span></div>
				<h2>
					<!-- Explicitly turn off one bit of text and turn on the other to deal with JAWS bug - https://github.com/alphagov/govuk-frontend/issues/1643 -->
					<a href="/home">
						<span aria-hidden="true">Learning lab</span>
						<span class="visually-hidden">Learning lab homepage</span>
					</a>
				</h2>
            </div>
            <div class="col">       
                <ul>
                    <li class="hide-sm">
                        <a href="https://www.rmit.edu.au/library">
							<span aria-hidden="true">Library</span>
							<span class="visually-hidden">Library homepage</span>
						</a>
                    </li>
                    <!--<li class="search">
                        <a id="search2">
                            <div class="search-label">Search</div>
                            <div class="mag-glass"></div>
                        </a>
                    </li>-->
                    <li class="menu">
                        <button id="menu-button" 
                        class="btn btn-primary collapsed" type="button" data-bs-toggle="collapse" 
                        data-bs-target="#context-menu" data-bs-display="static" aria-expanded="false" 
                        aria-controls="context-menu">Click for main menu</button>
                    </li>
                </ul> 
            </div>   
        </div>
    </div>
</div>
<nav id="context-menu" class="collapse" aria-label="Main Menu">
    <div class="container nav-container not-wordpress">
        <div class="row">
            <!-- START menu -->
            <div class="col-xl-8">
				<div class="accordion accordion-white" id="context-menu-accordion">	
						<?php 
                            //Identify the page ids of each landing page, doContextMenuAccordion 
                            //will generate the accordion code and list of child pages.
							echo doContextMenuAccordion('University essentials', 6823);
							echo doContextMenuAccordion('Writing fundamentals', 6825); 
                            echo doContextMenuAccordion('Assessments', 6828);
                            echo doContextMenuAccordion('Referencing', 2545);
                        ?>
						<!-- START Subject support
                        special case. Effectively each of the child pages here is a section page. For the nav, however, we want toshow these under the banner of subject support. -->
						<div class="accordion-item">
							<h2 class="accordion-header" id="accordion-head-subject-support">
								<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accordion-body-subject-support" aria-expanded="false" aria-controls="accordion-body-subject-support">
								Subject support
							  	</button>
							</h2>
							<div id="accordion-body-subject-support" class="accordion-collapse collapse" aria-labelledby="accordion-head-subject-support" style="">
								<div class="accordion-body">
									<ul>
										<li><a href="/art-and-design/">Art and design</a></li>
										<li><a href="/chemistry/">Chemistry</a></li>
										<li><a href="/law-resources/">Legal studies</a></li>
										<li><a href="/maths-statistics/">Mathematics and statistics</a></li>
										<li><a href="/resources-nursing/">Nursing</a></li>
										<li><a href="/physics/">Physics</a></li>
										<!-- <li><a href="//">Life sciences</a></li> -->
									</ul>
								</div>
							  </div>
						</div>
						<!-- END subject support - special case -->
                </div>	
            </div>
            <!-- END menu -->
        </div>
    </div>
</nav>
</header>

	<main id='theme-main'>
