

<?php //set variables for easy application later on - field keys are difficult to remember what they apply to when you're writing code
//essential
$llkeywords = get_field( "field_6527440d6f9a2" );
$llcategory = get_field("field_65275ce3c7e36");
?>


<!-- keywords echo - https://www.advancedcustomfields.com/resources/taxonomy/ -->      
<?php if ($llkeywords ){ ?>

<?php 
$terms = get_field('field_6527440d6f9a2');
if( $terms ): ?>
<hr class="margin-top-xl">
<div class="keywords">
    <h2 class="h5">Keywords</h2>
    <ul>
    <?php foreach( $terms as $term ): ?>
            <li><a href="<?php echo esc_url( get_term_link($term)); ?>"><?php echo esc_html($term->name); ?></a></li>  
    <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?> 
<!-- keywords echo end -->
<?php } ?>



 














