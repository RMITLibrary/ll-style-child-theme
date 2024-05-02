
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
li.page_item {
    list-style:none;
	padding-left: 1.2rem;
	text-decoration: none;
	font-size: 1rem;
}
ul
{
    list-style-type:none;
    padding:0px;
}
.sidebar-nv, .child_item {
 {
	text-decoration: none !important;
}
</style>
<div class="wrapper bg-light p-3" id="wrapper-main-sidebar">		
<section>
<nav class="right-nav" aria-label="Section Menu">	
<?php
//start dynamic sidebar nav - hierarchical
// see example of this sidebar here: http://ll-nav.blackaeonium.net/art-and-design/artist-statement/
if ( $post->post_parent) {
	//if there is a parent
	$grandparent = get_post_parent( $post->post_parent ); //get a grandparent post
	$parentpost = get_post_parent( $post ); //get the parent post
	if ( $grandparent ) {
		// if there is a grandparent
		$greatgrand = get_post_parent( $parentpost->post_parent ); //get a great-grandparent post
		if ( $greatgrand ) {
			// if there is a great-grandparent
			$ggslug = $greatgrand->post_name; // get the slug for the great-grandparent post
			$ggtitle = get_the_title( $greatgrand->ID ); //get the title for the great-grandparent post
 			echo '<h4 class="sidebar-nv"><a href="/'.$ggslug.'">'.$ggtitle.'</a></h4><hr>'; //output the great-grandparent link
			$gslug = $grandparent->post_name; // get the slug for the grandparent post
			$gtitle = get_the_title( $grandparent->ID ); //get the title for the grandparent post
 			echo '<h5 class="sidebar-nv"><a href="/'.$gslug.'">'.$gtitle.'</a></h5>'; //output the grandparent link
			$children = wp_list_pages( array(
				'title_li' => '', //no list heading
				'child_of' => $post->post_parent, // output sub pages of the parent of the current post
				'depth'	   => 2, //the depth of how many children/grandchildren in the hierarchy to display - if there is a parent (i.e. not the first ancestor page) display two
				'link_before' => '<div class="child_item">', // HTML to put before the child link
				'link_after' => '</div>',  // HTML to put before the child link
				'echo'     => 0 // don't echo the list (list is echoed later in the doc)
			) );
			$post_data = get_post($post->post_parent); //get the data for the parent post
			$parent_slug = $post_data->post_name; //get the slug for the parent post
			$title = get_the_title( $post->post_parent ); //get the title for the parent post
			if ( $children ) {
				echo ('<div id="list"><h6 class="sidebar-nv"><a href="/'.$parent_slug.'">'.$title.'</a></h6>');//output parent link at H6
				echo ('<ul class="sidebar-indent">'.$children.'</ul></div>'); //output children
			}		
		} else {
			//if no great-grandparent
 			$gslug = $grandparent->post_name; // get the slug for the grandparent post
			$gtitle = get_the_title( $grandparent->ID ); //get the title for the grandparent post
 			echo '<h4 class="sidebar-nv"><a href="/'.$gslug.'">'.$gtitle.'</a></h4><hr>'; //outout the grandparent link
			$children = wp_list_pages( array(
				'title_li' => '', //no list heading
				'child_of' => $post->post_parent, // output sub pages of the parent of the current post
				'depth'	   => 2, //the depth of how many children/grandchildren in the hierarchy to display - if there is a parent (i.e. not the first ancestor page) display two
				'link_before' => '<div class="child_item">', // HTML to put before the child link
				'link_after' => '</div>',  // HTML to put before the child link
				'echo'     => 0 // don't echo the list (list is echoed later in the doc)
			) );
			$post_data = get_post($post->post_parent); //get the data for the parent post
			$parent_slug = $post_data->post_name; //get the slug for the parent post
			$title = get_the_title( $post->post_parent ); //get the title for the parent post
			//this echo is showing nieces and nephews which need to be removed and only show children of self
			//if we reduce depth, we don't get children
			//if we change child_of to self, we don't get siblings - this is how the WP walker works.
			//need to rewrite the algorithm to only show children of current page
			if ( $children ) {
				echo ('<h5 class="sidebar-nv"><a href="/'.$parent_slug.'">'.$title.'</a></h5>');//output parent link at H5
				echo ('<div id="list"><ul>'.$children.'</ul></div>'); //output children
			}	
		}
	} else {
		//if no grandparent
 		$children = wp_list_pages( array(
		'title_li' => '', //no list heading
		'child_of' => $post->post_parent, // output sub pages of the parent of the current post
		'depth'	   => 2, //the depth of how many children/grandchildren in the hierarchy to display - if there is a parent (i.e. not the first ancestor page) display two
		'link_before' => '<div class="child_item">', // HTML to put before the child link
		'link_after' => '</div>',  // HTML to put before the child link
		'echo'     => 0 // don't echo the list (list is echoed later in the doc)
		) );
		$post_data = get_post($post->post_parent); //get the data for the parent post
		$parent_slug = $post_data->post_name; //get the slug for the parent post
		$title = get_the_title( $post->post_parent ); //get the title for the parent post
		if ( $children ) {
			echo ('<h4 class="sidebar-nv"><a href="/'.$parent_slug.'">'.$title.'</a></h4><hr>');//output parent link at H4
			echo ('<div id="list"><ul>'.$children.'</ul></div>'); //output children
		}		
	}
} else {
	//if no parent
	$children = wp_list_pages( array(
		'title_li' => '', //no list heading
		'child_of' => $post->ID, // output subpages of the current post
		'depth'	   => 1, //the depth of how many children/grandchildren in the hierarchy to display - if no parent, then only display one (i.e. first ancestor page which has no parent)
		'echo'     => 0 // don't echo the list (list is echoed later in the doc)
	) );
	$slug = $post->post_name; // get the slug for this post
	$title = get_the_title( $post->ID ); //get the title for this post
	if ( $children ) {
		echo ('<h4 class="sidebar-nv"><a href="/'.$slug.'">'.$title.'</a></h4><hr>');//output the parent link at H4
		echo ('<div id="list"><h5 class="sidebar-nv">'.$children.'</h5></div>'); //output the children
	}
}
?>
</nav>
</section>			
<?php //end dynamic sidebar nav ?>
</div>
<script language="javascript">
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
list.querySelectorAll('h6').forEach(h6 => {
  const text = h6.innerHTML; 
  const match = text.match(regex);
  if(match) {
	h6.innerHTML = match[1].charAt(0).toUpperCase() + match[1].slice(1);
  }
})
</script>
		