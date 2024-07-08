<?php
/**
 * Template Name:  Landing Page
 *

 */
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();
?>

<div class="container" id="page-content">
  	<div class="container">
		<?php echo createBreadcrumbs($post); ?>
        <a id="main-content"></a>
		<?php 

		if ( have_posts() ) : 
			while ( have_posts() ) : the_post();
				the_content();
			endwhile;
		else :
			_e( 'Sorry, no posts matched your criteria.', 'textdomain' );
		endif;
		?>
		<?php //get_template_part( 'page-templates/taxonomy', 'page' ); ?>
	</div>
</div>

<?php get_footer();

