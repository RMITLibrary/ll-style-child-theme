<?php

//-------------------------------------------
//    write_redirects_js_file

//    Creates and writes a JavaScript file containing URL mappings for redirects
//    Now fetches redirects from the Redirection plugin's database tables

//    Called from:    Redirection plugin hooks (redirection_redirect_updated, redirection_redirect_deleted)

//    Calls:            None directly

//    Usage:            Automatically called by WordPress when redirects are updated
//-------------------------------------------

// Disable WordPress's canonical redirect feature
// This prevents WordPress from automatically redirecting URLs to their canonical versions.
//remove_filter('template_redirect', 'redirect_canonical'); removed as it was causing the wordpress to break as we only use slugs in some instances, and wordpress was redirecting for us

// Prevent the Redirection plugin from performing any redirects by returning false for the source URL.
// This effectively disables the plugin's redirect functionality for source URLs.
add_filter('redirection_url_source', '__return_false');

// Prevent the Redirection plugin from performing any redirects by returning false for the target URL.
// This effectively disables the plugin's redirect functionality for target URLs.
add_filter('redirection_url_target', '__return_false');

// Disable logging of 404 errors by the Redirection plugin.
// This prevents the plugin from recording 404 errors in its logs.
add_filter('redirection_log_404', function ($log) {
  return false;
}, 1);


function write_redirects_js_file()
{
  global $wpdb;

  error_log('write_redirects_js_file function called!'); // Added logging

  // Query the Redirection plugin's table
  $table_name = $wpdb->prefix . 'redirection_items';
  $redirects = $wpdb->get_results(
    "SELECT url, action_data, regex FROM $table_name WHERE action_type = 'url' AND status = 'enabled'",
    ARRAY_A
  );

  $url_mappings = [];
  if ($redirects) {
    foreach ($redirects as $redirect) {
      if ($redirect['regex'] == 1) {
        $url_mappings[] = [
          'regex' => true,
          'pattern' => $redirect['url'],
          'newPath' => $redirect['action_data'],
        ];
      } else {
        $url_mappings[] = [
          'oldPath' => $redirect['url'],
          'newPath' => $redirect['action_data'],
        ];
      }
    }
  }


  $dir = get_stylesheet_directory() . '/js/';
  $js_file_path = $dir . 'redirects.js'; // Path to save the JS file

  // Check if directory exists and is writable
  if (!is_dir($dir)) {
    error_log('Directory does not exist: ' . esc_html($dir));
    return;
  }

  if (!is_writable($dir)) {
    error_log('Directory is not writable: ' . esc_html($dir));
    return;
  }

  // Prepare JS content
  $js_content = "const urlMappings = " . json_encode($url_mappings) . ";\n";
  $js_content .= "console.log('urlMappings.length: ' + urlMappings.length);\n";

  // Write the JavaScript content to the file
  if (file_put_contents($js_file_path, $js_content) === false) {
    error_log('Failed to write JS file to: ' . esc_html($js_file_path));
  } else {
    error_log('JS file successfully written to: ' . esc_html($js_file_path));
  }
}

// Hook into Redirection plugin's actions
add_action('redirection_redirect_updated', 'write_redirects_js_file');
add_action('redirection_redirect_deleted', 'write_redirects_js_file');

//-------------------------------------------
//    output_redirect_404_script_and_html

//    Outputs JavaScript for URL redirects and the HTML structure for redirect and 404 messages

//    Called from:    404.php and pages using the redirect-404 page template (currently https://lab.bitma.app/redirect-404/ )

//    Calls: createBreadcrumbs (ensure this function exists in your theme)

//    Usage:            Call output_redirect_script_and_html() in template files to display redirect logic and messages
//-------------------------------------------

function output_redirect_404_script_and_html()
{
  // Output the HTML and CSS
?>

  <script src="<?php echo esc_url(get_stylesheet_directory_uri()); ?>/js/redirects.js?v=<?php echo time(); ?>"></script>


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
        <?php echo createBreadcrumbs(get_post()); ?>
        <a id="main-content"></a>

        <!-- START redirect container -->
        <div id="redirect-container">
          <h1 class="margin-top-zero">Archived page</h1>
          <p>This content has been updated or relocated. You will be redirected shortly to the latest version. Please update your links if necessary.</p>
        </div>
        <!-- END redirect container -->

        <!-- START 404 container -->
        <div id="four-oh-container">
          <h1 class="margin-top-zero">Page not found</h1>
          <p class="lead">We're sorry, but the page you're looking for doesn't exist. <br />It might have been moved or deleted.</p>

          <p>Use the search bar below to find what you're looking for or <a href="search/#keywords">browse keywords</a> to find related content.</p>

          <!-- START search -->
          <div class="search-container label-side" style="max-width: 592px;">
            <label for="searchInput">
              <h2 class="h4">
                Search <span class="visually-hidden">this website:</span>
              </h2>
            </label>
            <div class="input-group">
              <input type="search" id="searchInput" class="form-control">
              <button type="submit" id="searchButton" class="btn btn-primary">
                <div class="mag-glass"></div><span class="visually-hidden">Search</span>
              </button>
            </div>
          </div>
          <!-- END search -->
        </div>
        <!-- END 404 container -->
      </div>
      <!-- END content -->
    </div>
  </div>

  <script>
    // References to DOM objects
    const fourOhInfo = document.getElementById("four-oh-container");
    const redirectInfo = document.getElementById("redirect-container");

    // Prefix for the environment; set to '' for live and '/preview' for test
    const pathPrefix = '';

    // Function to extract the path after the domain and remove the prefix if present
    function extractPath(url) {
      const urlObj = new URL(url);
      let path = urlObj.pathname;

      // Remove the path prefix if present
      if (path.startsWith(pathPrefix)) {
        path = path.substring(pathPrefix.length);
      }

      return path; // Do NOT remove leading or trailing slashes
    }

    // Function to normalise a path by trimming leading and trailing slashes
    function normalizePath(path) {
      if (typeof path === 'string') {
        return path.replace(/^\/|\/$/g, '');
      } else {
        console.error('normalizePath was called with a non-string value:', path);
        return ''; // Or handle it in another way, like returning null or undefined
      }
    }


    // Function to replace the old URL path with the new URL path, adding the prefix
    function replaceUrlPath(url, newPath) {
      const urlObj = new URL(url);
      urlObj.pathname = pathPrefix + newPath;
      return urlObj.toString();
    }

    // Function to process old site links and redirect if necessary
    function processOldSiteLinksAndRedirect(path) {
      // Remove ".html" if present
      if (path.endsWith('.html')) {
        path = path.slice(0, -5);
      }

      // Replace "/content/" with "/"
      if (path.startsWith('content/')) {
        const newPath = path.replace('content/', '/');
        doRedirect(pathPrefix + newPath, 0);
        return true;
      }
      return false;
    }

    // Function to perform the redirect after all checks and URL processing
    function doRedirect(redirectUrl, delay = 2000) {
      setTimeout(() => {
        console.log('Redirecting to: ' + redirectUrl);
        window.location.href = redirectUrl;
      }, delay);
    }

    // Ensure URL has a trailing slash if necessary
    function ensureTrailingSlash() {
      const myPath = window.location.pathname;
      if (!myPath.endsWith("/") && !myPath.endsWith(".html")) {
        const newPath = myPath + "/";
        window.location.replace(newPath + window.location.search + window.location.hash);
      }
    }

    // Main execution
    function main() {
      // Ensure the current URL has a trailing slash if no .html is present
      ensureTrailingSlash();

      // Get the current URL
      const currentURL = window.location.href;

      // Extract the path from the current URL
      const extractedPath = extractPath(currentURL);

      // Debugging log to check the extracted path
      console.log("Extracted Path: " + extractedPath);

      // Process old site links and redirect if applicable
      const contentBool = processOldSiteLinksAndRedirect(extractedPath);

      // If no redirect occurred from old site links
      if (!contentBool) {
        // Check if urlMappings is defined before using it.
        if (typeof urlMappings !== 'undefined') {
          // First, try to find an exact match (non-regex)
          let mapping = urlMappings.find(mapping => !mapping.regex && normalizePath(mapping.oldPath) === normalizePath(extractedPath));

          // If no exact match, then try regex matching
          if (!mapping) {
            mapping = urlMappings.find(mapping => {
              if (mapping.regex) {
                try {
                  const regex = new RegExp(mapping.pattern);
                  return regex.test(extractedPath);
                } catch (e) {
                  console.error('Invalid regex pattern:', mapping.pattern, e);
                  return false; // Skip this mapping if the regex is invalid
                }
              }
              return false;
            });
          }

          // If a matching mapping is found, redirect to the new URL
          if (mapping) {
            let newUrl;
            if (mapping.regex) {
              try {
                const regex = new RegExp(mapping.pattern);
                const matches = extractedPath.match(regex);
                if (matches) {
                  // If there are matches, do the replacement if needed
                  newUrl = mapping.newPath.replace(/\$(\d+)/g, (_, groupIndex) => matches[groupIndex] || '');
                } else {
                  // If no matches, use the new path as is
                  newUrl = mapping.newPath;
                }
              } catch (e) {
                console.error('Error during regex replacement:', e);
                newUrl = mapping.newPath;
              }
            } else {
              // Non-regex match, use replaceUrlPath for oldPath/newPath
              newUrl = replaceUrlPath(currentURL, mapping.newPath);
            }
            console.log("Match found! Redirecting to: " + newUrl);

            //Change page title to relect change
            document.title = "Redirecting you to the new page...";

            // Display redirect information
            redirectInfo.style.display = "block";

            // Perform the redirect
            doRedirect(newUrl);
          } else {
            // If no mapping is found, display 404 information
            console.log("No match found. Displaying 404 info.");
            fourOhInfo.style.display = "block";
          }
        } else {
          // urlMappings is not defined, display 404 information
          console.log("urlMappings is not defined. Displaying 404 info.");
          fourOhInfo.style.display = "block";
        }
      }
    }

    main();
  </script>
  <!-- script to punt search input to /search via query string -->
  <script type="text/javascript" src="/wp-content/themes/picostrap5-child-base/js/search-home.js?v=1.0.1"></script>

<?php
}
