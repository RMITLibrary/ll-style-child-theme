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
            <!-- START menu column -->
            <div class="col-xl-8">
				<!-- START menu -->
            	<div class="accordion accordion-white" id="context-menu-accordion">	
						<div class="accordion-item">
<h2 class="accordion-header" id="accordion-head-6823">
<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accordion-body-6823" aria-expanded="false" aria-controls="accordion-body-6823">University essentials</button>
</h2>
<div id="accordion-body-6823" class="accordion-collapse collapse" aria-labelledby="accordion-head-6823">
<div class="accordion-body"><ul><li class="page_item page-item-3339 page_item_has_children"><a href="/university-essentials/acting-academic-integrity/">Acting with academic integrity</a></li>
<li class="page_item page-item-3347 current_page_item"><a href="/university-essentials/artificial-intelligence-tools/" aria-current="page">Artificial intelligence tools</a></li>
<li class="page_item page-item-3117"><a href="/university-essentials/critical-thinking-and-argument-analysis/">Critical thinking and argument analysis</a></li>
<li class="page_item page-item-2501 page_item_has_children"><a href="/university-essentials/getting-started/">Getting started with Uni</a></li>
<li class="page_item page-item-3097 page_item_has_children"><a href="/university-essentials/group-work/">Group work</a></li>
<li class="page_item page-item-2496"><a href="/university-essentials/english/">Improve your English</a></li>
<li class="page_item page-item-6844 page_item_has_children"><a href="/university-essentials/study-essentials/">Study essentials</a></li>
<li class="page_item page-item-3313 page_item_has_children"><a href="/university-essentials/sustainability/">Sustainability</a></li>
</ul></div></div></div><div class="accordion-item">
<h2 class="accordion-header" id="accordion-head-6825">
<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accordion-body-6825" aria-expanded="false" aria-controls="accordion-body-6825">Writing fundamentals</button>
</h2>
<div id="accordion-body-6825" class="accordion-collapse collapse" aria-labelledby="accordion-head-6825">
<div class="accordion-body"><ul><li class="page_item page-item-2522 page_item_has_children"><a href="/writing-fundamentals/academic-style/">Academic style</a></li>
<li class="page_item page-item-2534 page_item_has_children"><a href="/writing-fundamentals/academic-word-list-tool/">Academic word list tool</a></li>
<li class="page_item page-item-2521"><a href="/writing-fundamentals/spelling-tips/">Spelling tips</a></li>
<li class="page_item page-item-3295"><a href="/writing-fundamentals/understanding-your-audience/">Understanding your audience</a></li>
<li class="page_item page-item-2537 page_item_has_children"><a href="/writing-fundamentals/writing-coursework/">Writing for coursework</a></li>
<li class="page_item page-item-2855"><a href="/writing-fundamentals/writing-workplace/">Writing for the workplace</a></li>
<li class="page_item page-item-3107"><a href="/writing-fundamentals/writing-paragraphs/">Writing paragraphs</a></li>
<li class="page_item page-item-2520"><a href="/writing-fundamentals/writing-sentences/">Writing sentences</a></li>
<li class="page_item page-item-2535"><a href="/writing-fundamentals/useful-websites/">Useful websites</a></li>
</ul></div></div></div><div class="accordion-item">
<h2 class="accordion-header" id="accordion-head-6828">
<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accordion-body-6828" aria-expanded="false" aria-controls="accordion-body-6828">Assessments</button>
</h2>
<div id="accordion-body-6828" class="accordion-collapse collapse" aria-labelledby="accordion-head-6828">
<div class="accordion-body"><ul><li class="page_item page-item-2617"><a href="/assessments/annotated-bibliographies/">Annotated bibliographies</a></li>
<li class="page_item page-item-2578 page_item_has_children"><a href="/assessments/case-studies/">Case studies</a></li>
<li class="page_item page-item-2516"><a href="/assessments/essays/">Essays</a></li>
<li class="page_item page-item-7168 page_item_has_children"><a href="/assessments/getting-started-with-assignments/">Getting started with assignments</a></li>
<li class="page_item page-item-2536"><a href="/assessments/literature-review/">Literature review</a></li>
<li class="page_item page-item-7204 page_item_has_children"><a href="/assessments/presentations/">Presentations</a></li>
<li class="page_item page-item-2580"><a href="/assessments/reflective-writing-1/">Reflective writing</a></li>
<li class="page_item page-item-2544"><a href="/assessments/reports/">Reports</a></li>
</ul></div></div></div><div class="accordion-item">
<h2 class="accordion-header" id="accordion-head-2545">
<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accordion-body-2545" aria-expanded="false" aria-controls="accordion-body-2545">Referencing</button>
</h2>
<div id="accordion-body-2545" class="accordion-collapse collapse" aria-labelledby="accordion-head-2545">
<div class="accordion-body"><ul><li class="page_item page-item-3329"><a href="/referencing/using-other-peoples-ideas/">What is referencing?</a></li>
<li class="page_item page-item-3346"><a href="/referencing/understanding-citations/">Understanding citations</a></li>
<li class="page_item page-item-3330"><a href="/referencing/when-referencing-isnt-needed/">When referencing isn't needed</a></li>
<li class="page_item page-item-3331"><a href="/referencing/quoting/">Quoting</a></li>
<li class="page_item page-item-3337"><a href="/referencing/paraphrasing-4/">Paraphrasing</a></li>
<li class="page_item page-item-3334"><a href="/referencing/summarising-0/">Summarising</a></li>
<li class="page_item page-item-3338"><a href="/referencing/synthesising/">Synthesising</a></li>
<li class="page_item page-item-3333"><a href="/referencing/integrating-ideas-reporting-words/">Integrating ideas with reporting words</a></li>
<li class="page_item page-item-3336"><a href="/referencing/getting-help-referencing/">Easy Cite and referencing help</a></li>
</ul></div></div></div>						<!-- START Subject support
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
            	<!-- END menu -->	
				<!-- Start theme switcher -->
				<div id="theme-switcher">
					<h2 class="h5">Theme</h2>
					<div class="theme-bg">
						<div class="form-check form-check-inline">
							<input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" data-bs-theme-value="auto" value="option1">
							<label class="form-check-label" for="inlineRadio1">System</label>
						</div>
						<div class="form-check form-check-inline">
							<input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" data-bs-theme-value="light" value="option2">
							<label class="form-check-label" for="inlineRadio2">Light</label>
						</div>
						<div class="form-check form-check-inline">
							<input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio3" data-bs-theme-value="dark" value="option3">
							<label class="form-check-label" for="inlineRadio3">Dark</label>
						</div>
					</div>
				</div>
				<!-- End theme switcher -->
            </div>
            <!-- END menu column -->
        </div>
    </div>
</nav>
</header>

	<main id='theme-main'>
