<?php

$pagelist = get_pages('sort_column=menu_order&sort_order=asc');
$pages = array();
foreach ($pagelist as $page) {
   $pages[] += $page->ID;
}

$current = array_search(get_the_ID(), $pages);
$prevID = $pages[$current-1];
$nextID = $pages[$current+1];

$prevTitle = formatAfterTheColon(get_the_title($prevID));
$nextTitle = formatAfterTheColon(get_the_title($nextID));

?>
<nav class="btn-nav-container" aria-label="Previous and next links">
<?php if (!empty($prevID)) { ?>
<h2>
    <a class="btn btn-nav-prev" href="<?php echo get_permalink($prevID); ?>">
        <span aria-hidden="true"><?php echo $prevTitle ; ?></span>
        <span class="visually-hidden">Previous page: <?php echo $prevTitle; ?></span>
    </a>
</h2>
    

<?php }
$is_last_page = get_query_var('is_last_page');
if (!empty($nextID && $is_last_page != 'true')) { 

?>

<h2>
    <a class="btn btn-nav-next" href="<?php echo get_permalink($nextID); ?>">
        <span aria-hidden="true"><?php echo $nextTitle ; ?></span>
        <span class="visually-hidden">Next page: <?php echo $nextTitle ; ?></span>
    </a>
</h2>
	
<?php } ?>
</nav><!-- .navigation -->