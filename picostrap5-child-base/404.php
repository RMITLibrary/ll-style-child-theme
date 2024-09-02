<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package picostrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();
 
?>

<div class="container" id="page-content">
    <div class="row ">
        <!-- START content -->
        <div class="col-xl-8 order-first">
            <?php echo createBreadcrumbs($post); ?>
            <a id="main-content"></a>
            <h1 class="margin-top-zero">Page not found</h1>
			<p>We're sorry, but the page you're looking for doesn't exist. As this is a prototype, not all pages of the final website are included.</p>
			<ul class="link-list">
				<li><a href="#" onclick="goBack()">Go back to the previous page</a></li>
				<li><a href="/">Go to the home page</a></li>
			</ul>
			<p>If you have any questions or need assistance, please <a href="mailto:digital.learning.library@rmit.edu.au">contact us</a>.</p>
        </div>
        <!-- END content --> 
		<script>
        function goBack() {
            window.history.back();
        }
    	</script>
    </div>
</div>

<?php
get_footer();
