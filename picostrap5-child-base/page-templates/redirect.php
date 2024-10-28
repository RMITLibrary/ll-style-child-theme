<?php
/*
Template Name: Redirect Page
*/
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
        </ul>
        </nav>
            <a id="main-content"></a>
            <h1 class="margin-top-zero">Archived Page</h1>
            <p>This content has been updated or relocated. You will be redirected shortly to the latest version. Please update your links if necessary.</p>

            <script>
                // JavaScript variable with the slug to redirect to
                var redirectSlug = '<?php echo esc_js(get_field('redirect_slug')); ?>';

                // Get the site URL
                var siteUrl = '<?php echo esc_url(home_url()); ?>';

                // Redirect after 5 seconds
                setTimeout(function() {
                    window.location.href = siteUrl + '/' + redirectSlug;
                }, 5000);
            </script>

        </div>
        <!-- END content --> 
    </div>
</div>

<?php get_footer();