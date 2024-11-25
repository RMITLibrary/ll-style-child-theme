<?php
/**
 * Template Name: Redirect 404
 *

 */
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();

// Output the JavaScript for redirects
$redirects = get_option('my_redirects', array());

if ($redirects) {
    $redirectCount = count($redirects); // Count the number of redirects
    echo "<script type='text/javascript'>\n";
    echo "const urlMappings = [\n";
    foreach ($redirects as $redirect) {
        echo "{ oldPath: '" . esc_js($redirect['old']) . "', newPath: '" . esc_js($redirect['new']) . "' },\n";
    }
    echo '];';
    echo "console.log('urlMappings.length: ' + $redirectCount);\n"; // Output the length of the array
    echo '</script>';
}

?>

<style>
    #redirect-container {
        display: none;
    }

    #four-oh-container {
        display: none;
    }
</style>

<div class="container" id="page-content">
    <div class="row ">
        <!-- START content -->
        <div class="col-xl-8 order-first">
            <?php echo createBreadcrumbs($post); ?>
            <a id="main-content"></a>
            
            <!-- START redirect container -->
            <div id="redirect-container">
                <h1 class="margin-top-zero">Archived Page</h1>
                <p>This content has been updated or relocated. You will be redirected shortly to the latest version. Please update your links if necessary.</p>  
            </div>
            <!-- END redirect container -->

            <!-- START 404 container -->
            <div id="four-oh-container">
                <h1 class="margin-top-zero">Page not found</h1>
                <p>We're sorry, but the page you're looking for doesn't exist.</p>
            </div>
            <!-- END 404 container -->
        </div>
        <!-- END content --> 
    </div>
</div>

<script>

// References to DOM objects
var fourOhInfo = document.getElementById("four-oh-container");
var redirectInfo = document.getElementById("redirect-container");

// Prefix for the environment; set to '' for live and '/preview' for test
//const pathPrefix = '/preview';
const pathPrefix = '';

// Function to extract the path after the domain, considering the prefix for the environment
function extractPathAfterDomain(url) {
    const urlObj = new URL(url);
    let path = urlObj.pathname;

    // Remove the path prefix if present
    if (path.startsWith(pathPrefix)) {
        path = path.substring(pathPrefix.length);
    }

    return path;
}

// Function to normalize a path by trimming leading and trailing slashes
function normalizePath(path) {
    return path.replace(/^\/|\/$/g, '');
}

// Function to replace the old URL path with the new URL path, adding the prefix
function replaceUrlPath(url, newPath) {
    const urlObj = new URL(url);
    urlObj.pathname = pathPrefix + newPath;
    return urlObj.toString();
}

// Use window.location to get the current URL
const currentURL = window.location.href;

// Extract and normalize the path after the domain
const extractedPath = normalizePath(extractPathAfterDomain(currentURL));

// Log the extracted path for debugging
console.log("Extracted Path: " + extractedPath);

// Compare the normalized extracted path with the normalized oldPath in the list
const mapping = urlMappings.find(function(mapping) {
    const normalizedOldPath = normalizePath(mapping.oldPath);
    // Log each comparison for debugging
    console.log("Comparing: " + normalizedOldPath + " with " + extractedPath);
    return normalizedOldPath === extractedPath;
});

if (mapping) {
    // Construct the new URL using the newPath from the mapping
    // Show the redirect info
    const newUrl = replaceUrlPath(currentURL, mapping.newPath);
    console.log("Match found! New URL: " + newUrl);
    redirectInfo.style.display = "block";
    doRedirect(newUrl);
} else {
    // No match, show the 404 info
    console.log("No match found.");
    fourOhInfo.style.display = "block";
}

function doRedirect(redirectUrl) {
    // Redirect after 5 seconds
    setTimeout(function() {
        console.log('Redirecting to: ' + redirectUrl);
        window.location.href = redirectUrl;
    }, 5000);
}

</script>

<?php get_footer();

