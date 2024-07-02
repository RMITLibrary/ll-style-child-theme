<?php
                /*wp_nav_menu(
                    array(
                        'theme_location' => 'primary',
                        'container' => false,
                        'menu_class' => '',
                        'fallback_cb' => '__return_false',
                        'items_wrap' => '<ul id="%1$s" class="navbar-nav me-auto mb-2 mb-md-0 %2$s">%3$s</ul>',
                        'walker' => new bootstrap_5_wp_nav_menu_walker()
                    )
                );*/
				
				
				
				
				
                ?>
<!-- ******************* The Navbar Area ******************* -->
<div id="wrapper-navbar" itemscope itemtype="http://schema.org/WebSite">

<header>
<a href="#main-content" class="visually-hidden-focusable">Skip to main content</a>
<div class="top-navigation">
    <div class="container">
        <div class="row">
            <div class="col-auto left-nav">
                <div class="rmit-logo"><span class="visually-hidden">RMIT University logo</span></div>
                <a href="/home" class="h2">Learning lab</a>
            </div>
            <div class="col">       
                <ul>
                    <li class="hide-sm">
                        <a href="https://www.rmit.edu.au/library">Library<span class="visually-hidden"> homepage</span></a>
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
						<?php echo doContextMenuAccordion('Assessments', 4266); ?>
						<?php echo doContextMenuAccordion('Writing fundamentals', 4257); ?>
						<!-- START Subject support - special case -->
						<div class="accordion-item">
							<h2 class="accordion-header" id="accordion-head-subject-support">
								<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accordion-body-subject-support" aria-expanded="true" aria-controls="accordion-body-subject-support">
								Subject support
							  	</button>
							</h2>
							<div id="accordion-body-subject-support" class="accordion-collapse collapse" aria-labelledby="accordion-head-subject-support" style="">
								<div class="accordion-body">
									<ul>
										<li><a href="/art-and-design/">Art and design</a></li>
										<li><a href="">Chemistry</a></li>
										<li><a href="">Legal studies</a></li>
										<li><a href="">Life sciences</a></li>
										<li><a href="">Maths</a></li>
										<li><a href="">Nursing</a></li>
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
</header> <!-- .site-navigation -->
    <?php

    //AS A TEST / DEMO for a mock-up megamenu
    //include("nav-static-mega.php");
    ?>
</div><!-- #wrapper-navbar end -->
