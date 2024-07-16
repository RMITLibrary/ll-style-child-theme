
<?php
// Get the value of 'additional_resources' using ACF's get_field function
$additional_resources = get_field('additional_resources');
if (!empty($additional_resources)) {
    echo '<hr class="margin-top-xl">';
	echo strip_tags_before_echo($additional_resources);
} 
?>
