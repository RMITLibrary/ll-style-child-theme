<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();
?>
  
<div class="container" id="page-content">
    <div class="row ">
        <!-- START content -->
        <div class="col-xl-8 order-first">
            <nav aria-label="breadcrumbs">
                <ul class="breadcrumbs">
                <li><a href="/">Home</a></li>
                <li><a href="/test-search">Search</a></li>
                <!-- UPDATE THIS -->
                </ul>
            </nav>
            <a id="main-content"></a>
            <h1><?php the_archive_title() ?></h1>
            <p class="lead">Select a page to view or select a related keyword to view other linked pages.</p>
            <!--<p class="lead text-muted col-md-8 offset-md-2 archive-description"><?php //echo category_description(); ?></div> -->
            
            <?php if ( have_posts() ) : ?>
                <div>
                    <ul class="list-link-expanded">
                    <?php while ( have_posts() ) : the_post(); 
                        
                        
                        
                    ?>
                        <li>
                            <h2 class="h3">
                                <a href="<?php echo esc_url( get_permalink() ); ?>">
                                    <?php echo esc_html( get_the_title() ); ?>
                                </a>
                            </h2>
                            <p><?php echo esc_html( get_the_excerpt() ); ?></p>
                        </li>
                    <?php endwhile; ?>
                    </ul>
                </div>
            <?php else : ?>
                <p><?php _e( 'Sorry, no pages matched your criteria.', 'textdomain' ); ?></p>
            <?php endif; ?>
            
            <nav class="btn-nav-container" aria-label="Previous and next links">
                <h2 class="btn-nav-prev">
                    <a href="#" onclick="goBack(); return false;">
                        Back
                    </a>
                </h2>
                </nav>
            <script>
            function goBack() {
                window.history.back();
            }
            </script>
        </div>
    </div>
    <!-- END content --> 
</div>
 
<?php get_footer();
