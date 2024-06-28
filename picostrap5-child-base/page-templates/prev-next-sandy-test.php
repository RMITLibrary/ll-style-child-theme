<?php

function formatAfterTheColon($string)
{
    // Split the string at colon
    $parts = explode(':', $string, 2); // Limit to 2 parts to handle colons within the string correctly

    if (count($parts) === 2) {
        // Capitalise the first character of the second part
        $parts[1] = ucfirst(trim($parts[1]));
        return $parts[1];
    } else {
        // Handle cases where there might not be a colon
        return $string;
    }
}

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

    <a class="btn btn-nav-prev" href="<?php echo get_permalink($prevID); ?>"><span class="visually-hidden">Previous: </span><?php echo $prevTitle; ?></a>

<?php }
if (!empty($nextID)) { ?>

<a class="btn btn-nav-next" href="<?php echo get_permalink($nextID); ?>"><span class="visually-hidden">Next: </span><?php echo $nextTitle ; ?></a>
<?php } ?>
</nav><!-- .navigation -->