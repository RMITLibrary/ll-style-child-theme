<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();
?>

<div class="container" id="page-content">

	<?php if( function_exists( 'aioseo_breadcrumbs' ) ) aioseo_breadcrumbs(); ?>
	<a id="main-content"></a>
	<h1 class="margin-top-zero"><?php the_title(); ?></h1>	
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

<?php get_footer();
