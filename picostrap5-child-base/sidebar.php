
<!-- this style changes the page link style in the sidebar nav 
to indicate the current page and removes bullets from unordered lists. 
The wp_list_pages function allocates the classes automatically. 
This should be moved to a stylesheet once style is determined. -->

<style>
[aria-current="page"] {
  pointer-events: none !important; 
  cursor: default;
  text-decoration: none !important;
  color: #000054;
}
</style>

	
<?php
/*
$greatGrandParent = '';		//Section e.g. Art and design
$grandParent = '';			//Section e.g. Artist statement
$parent = '';			//Section e.g. 
*/

$parent = get_post_parent($post);
$grandParent = get_post_parent($parent);
$greatGrandParent = get_post_parent($grandParent);

?>

<nav class="right-nav" aria-label="Section Menu">

<?php
/*
4 screnarios to deal with:
, it's 4 levels down so
*/
if($greatGrandParent->ID && $grandParent->ID) {

	//echo('//deepest level - 5 levels down<br />');
    //Send grandparent id to list grandparent children, then use $parent to recursively to the subpages 
	echo doNavHeading($greatGrandParent, 'h2');
	echo doNavHeading($grandParent, 'h3');
	doChildrenManual($grandParent->ID, $post, $parent);
} 
elseif($grandParent->ID && $parent->ID) {

	//echo('//middle level - 4 levels down<br />');
	echo doNavHeading($grandParent, 'h2');
	echo doNavHeading($parent, 'h3');
	doChildrenManual($parent->ID, $post);
}
elseif($parent->ID) {
	//echo('//shallowest level - 3 levels down<br />');
	/* Weirdly greatGrandParent is showing up the same as $parent*/
	echo doNavHeading($parent, 'h2');
	echo doNavHeading($post, 'h3', 'selected');
	doChildrenManual($post->ID, null);
		
}
else
{
	//echo('//At a section, unlikely - 2 levels down<br />');
	echo doNavHeading($post, 'h2', 'selected');
	//Handle list of other sections here echo doNavHeading($post, 'h3');
}
    
?>
</nav>

<?php

/*echo('<p>id: ' . $greatGrandParent->ID . ' greatGrandParent: ' . $greatGrandParent->post_name . '<br/>');
echo('id: ' . $grandParent->ID . ' grandParent: '. $grandParent->post_name . '<br/>');
echo('id: ' . $parent->ID . ' parent: ' . $parent->post_name . '<br/>');
echo("---</p>");*/

function doChildrenManual($parent_id, $thePost, $thePostParent = null)
{
	// $parent_id is the ID of the parent or grandparent post

	// Set up the arguments for the query
	$args = array(
		'post_type'      => 'page',
		'post_status'    => 'publish',
		'posts_per_page' => -1,           // Get all children
		'post_parent'    => $parent_id,
		'orderby'        => 'menu_order',
		'order'          => 'ASC'  
	);

	// Create a new query
	$child_query = new WP_Query($args);

	// Check if the query returns any posts
	if ($child_query->have_posts()) {
		echo '<ul>';
		// Loop through the posts
		while ($child_query->have_posts()) {
			$child_query->the_post();
			// Output the title and link to the post
            
            $post_slug = get_post_field('post_name', get_the_ID());
            
			if($thePost->ID == get_the_ID()) {
                //If there's a match with current post, do selected code and do children pages (recursively)
                echo '<li><a href="/' . $post_slug . '" class="selected"  aria-current="page">' . formatAfterTheColon(get_the_title()) . '</a>';
				doChildrenManual($thePost->ID, $thePost);
			}
			else {
                //Otherwise, output the link
                echo '<li><a href="/' . $post_slug . '">' . formatAfterTheColon(get_the_title()) . '</a>';
                //If thePostParent->ID exists, we are in a subpage.
                //If thePostParent->ID matches the loop item, do children pages (recursively)
                if($thePostParent->ID == get_the_ID())
                {
                    doChildrenManual($thePostParent->ID, $thePost);
                }
                echo '</li>';
			}  
		}
		echo '</ul>';
	} else {
		// No children found
		//echo 'No child posts found.<br />';
	}

	// Reset post data
	wp_reset_postdata();
}

function doNavHeading($myPost, $tag, $selected = null)
{
	$output = '';
	
	$title = get_the_title($myPost);
	$slug = $myPost->post_name;
	
	if($selected == true)
	{
		$output .= '<' . $tag . ' class="selected">' . $title . '</' . $tag . '>';
	}
	else
	{
		$output .= '<' . $tag . '><a href="/' . $slug . '">' . $title . '</a></' . $tag . '>';
	}	
		
	return $output;
}

?>



<!--<script language="javascript">
//this script shortens strings for titles that repeat the section title at the start
//it currently looks for the characters colon and space ": " in the page title
//this requires strict naming conventions for the title that use a colon 
//to separate the repeated section title from the descriptive page title
//the regex pattern to look for - in this case, text that comes after these characters ": "
const regex = /:\s(.*)/; 
list.querySelectorAll('a').forEach(a => {
  //get the text from inside all the <a> tags inside the div with the id "list"
  const text = a.innerHTML; 
  //find matches with the regex pattern
  const match = text.match(regex); 
  if(match) {
	//replace what's in each <a> tag that matches the pattern and convert the first character to upper case
	a.innerHTML = match[1].charAt(0).toUpperCase() + match[1].slice(1); 
  }
})
//as above, but for Heading 6
list.querySelectorAll('h4').forEach(h4 => {
  const text = h4.innerHTML; 
  const match = text.match(regex);
  if(match) {
	h4.innerHTML = match[1].charAt(0).toUpperCase() + match[1].slice(1);
  }
})
</script>-->
		