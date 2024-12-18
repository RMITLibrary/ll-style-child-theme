<?php 

function my_redirects_menu() {
    add_menu_page(
        'Manage Redirects',
        'Redirects',
        'manage_options',
        'my-redirects',
        'my_redirects_page'
    );
}
add_action('admin_menu', 'my_redirects_menu');

// Function to remove fragment from URL - removes # if it's there, define it outside
function remove_url_fragment($url) {
    $parsed_url = parse_url($url);
    $clean_url = $parsed_url['scheme'] . '://' . $parsed_url['host'] . $parsed_url['path'];

    if (isset($parsed_url['query'])) {
        $clean_url .= '?' . $parsed_url['query'];
    }

    return $clean_url;
}

//-------------------------------------------
//    my_redirects_page

//    Generates the admin page for managing redirects with fields for old and new URLs

//    Called from:    my_redirects_menu

//    Calls:            None directly, but handles form submissions to update redirects

//    Usage:            Triggered by form submission on the admin page to save redirects
//-------------------------------------------
function my_redirects_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    // Handle form submission
    if (isset($_POST['redirects'])) {
        check_admin_referer('save_redirects');
        $redirects = [];

        foreach ($_POST['redirects']['old'] as $key => $old_url) {
            $new_url = $_POST['redirects']['new'][$key];
            if (!empty($old_url) && !empty($new_url)) {
                $redirects[] = [
                    'old' => sanitize_text_field($old_url),
                    'new' => sanitize_text_field($new_url),
                ];
            }
        }

        update_option('my_redirects', $redirects);

        // Generate the physical JavaScript file
        write_redirects_js_file($redirects);

        echo '<div class="updated"><p>Redirects saved!</p></div>';
    }

    // Handle CSV upload
    if (isset($_POST['upload_csv']) && check_admin_referer('upload_redirects_csv')) {
        if (!empty($_FILES['redirects_csv']['tmp_name'])) {
            $csv_file = fopen($_FILES['redirects_csv']['tmp_name'], 'r');
            $new_redirects = [];

            while (($line = fgetcsv($csv_file)) !== FALSE) {
                if (count($line) === 2) {
                    $old_url = sanitize_text_field($line[0]);
                    $new_url = sanitize_text_field($line[1]);
                    $new_redirects[$old_url] = $new_url;
                }
            }
            fclose($csv_file);

            // Get existing redirects
            $existing_redirects = get_option('my_redirects', []);

            // Convert existing redirects to associative array
            $existing_redirects_assoc = [];
            foreach ($existing_redirects as $redirect) {
                $existing_redirects_assoc[$redirect['old']] = $redirect['new'];
            }

            // Merge and update redirects
            $combined_redirects = array_merge($existing_redirects_assoc, $new_redirects);
            $redirects = [];
            foreach ($combined_redirects as $old => $new) {
                $redirects[] = ['old' => $old, 'new' => $new];
            }
            update_option('my_redirects', $redirects);

            // Generate the physical JavaScript file
            write_redirects_js_file($redirects);

            echo '<div class="updated"><p>Redirects updated from CSV.</p></div>';
        }
    }

    // Get existing redirects
    $redirects = get_option('my_redirects', array());

    // Sort the redirects by the 'old' path, ignoring '/'
    usort($redirects, function($a, $b) {
        $aOld = str_replace('/', '', $a['old']);
        $bOld = str_replace('/', '', $b['old']);
        return strcmp($aOld, $bOld);
    });

    // Display form
    ?>
    <style>
        .admin-redirect {   
             width: 100%; 
             max-width: 1200px;
        }

        .admin-redirect input {
            width: 100%;
        }

        .add-button {
            marign-right: 16px;
        }

        hr {
            border-top: 1px solid #BFBFBF;
            margin-top: 16px;
        }
    </style>
    <div class="wrap">
        <h1>Manage Redirects</h1>
        <!-- <p>To delete a redirect, clear both fields in the table row and click "Save Redirects"</p> -->
        <p id="row-count"></p>
        <form method="post" enctype="multipart/form-data">
        <div class="buttons"><button type="button" class="button-secondary add-button" onclick="addRow()">Add new redirect</button>
        <input type="submit" value="Save Redirects" class="button-primary" />
        <a href="#bulk-upload-anchor">Bulk upload</a>
        </div>
        <hr />
            <?php wp_nonce_field('save_redirects'); ?>
            <table cellpadding="5" class="admin-redirect">
                <thead>
                    <tr>
                        <th>Old Path</th>
                        <th>New Path</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($redirects as $redirect): ?>
                        <tr>
                            <td><input type="text" name="redirects[old][]" value="<?php echo esc_attr($redirect['old']); ?>" /></td>
                            <td><input type="text" name="redirects[new][]" value="<?php echo esc_attr($redirect['new']); ?>" /></td>
                            <td style="width: 45px;"><a href="#" class="delete-row">Delete</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </form>
        <hr />
        <a id="bulk-upload-anchor"></a>
        <h2>Bulk Upload Redirects</h2>
        <p>Upload a CSV file. Duplicates will be ignored. Format as follows:<br />
        Old and new paths divided by a comma, each entry has a new line:</p>
        <p>
        /gonzo, /the-great-gonzo/<br />
        /kermit, /the-frog/<br />
        /scooter, /backstage-help/
        </p>
        <form method="post" enctype="multipart/form-data">
            <?php wp_nonce_field('upload_redirects_csv'); ?>
            <input type="file" name="redirects_csv" accept=".csv" required>
            <input type="submit" name="upload_csv" value="Upload CSV" class="button-secondary">
        </form>
    </div>
    <script>
        function addRow() {
            var table = document.querySelector('table tbody');
            var newRow = document.createElement('tr');
            newRow.innerHTML = '<td><input type="text" name="redirects[old][]" /></td><td><input type="text" name="redirects[new][]" /></td>';
            table.insertBefore(newRow, table.firstChild);
            updateRowCount();
        }

        function updateRowCount() {
            var table = document.querySelector('table tbody');
            var rowCount = table.children.length;
            document.getElementById('row-count').textContent = 'Number of redirects: ' + rowCount;
        }

        // handle delete function
        document.addEventListener('DOMContentLoaded', function() {
            // Get all delete links
            const deleteLinks = document.querySelectorAll('.delete-row');

            // Add click event listener to each delete link
            deleteLinks.forEach(function(link) {
                link.addEventListener('click', function(event) {
                    event.preventDefault(); // Prevent default link behavior

                    // Find the parent row and remove it
                    const row = this.closest('tr');
                    if (row) {
                        row.remove();
                    }
                });
            });
        });

        // Call the function to update the row count display
        updateRowCount();

    </script>
    <?php
}

//-------------------------------------------
//    write_redirects_js_file

//    Creates and writes a JavaScript file containing URL mappings for redirects

//    Called from:    my_redirects_page after updating redirects via the admin interface

//    Calls:            None directly

//    Usage:            Invoke write_redirects_js_file($redirects) to generate
//                      and update a static JS file with the latest redirects
//-------------------------------------------
function write_redirects_js_file($redirects) {
    $dir = get_stylesheet_directory() . '/js/';
    $js_file_path = $dir . 'redirects.js'; // Path to save the JS file

    // Check if directory exists and is writable
    if (!is_dir($dir)) {
        echo '<div class="error"><p>Directory does not exist: ' . esc_html($dir) . '</p></div>';
        return;
    }

    if (!is_writable($dir)) {
        echo '<div class="error"><p>Directory is not writable: ' . esc_html($dir) . '</p></div>';
        return;
    }

    // Prepare JS content
    $js_content = "const urlMappings = [\n";
    foreach ($redirects as $redirect) {
        $js_content .= "{ oldPath: '" . esc_js($redirect['old']) . "', newPath: '" . esc_js($redirect['new']) . "' },\n";
    }
    $js_content .= "];\n";
    $js_content .= "console.log('urlMappings.length: ' + urlMappings.length);\n";

    // Write the JavaScript content to the file
    if (file_put_contents($js_file_path, $js_content) === false) {
        echo '<div class="error"><p>Failed to write JS file to: ' . esc_html($js_file_path) . '</p></div>';
    } else {
        echo '<div class="updated"><p>JS file successfully written to: ' . esc_html($js_file_path) . '</p></div>';
    }
}

//-------------------------------------------
//    output_redirect_404_script_and_html

//    Outputs JavaScript for URL redirects and the HTML structure for redirect and 404 messages

//    Called from:    404.php and pages using the redirect-404 page template (currently https://lab.bitma.app/redirect-404/ )

//    Calls: createBreadcrumbs (ensure this function exists in your theme)

//    Usage:            Call output_redirect_script_and_html() in template files to display redirect logic and messages
//-------------------------------------------

function output_redirect_404_script_and_html() {
    // Output the JavaScript for redirects
    // $redirects = get_option('my_redirects', array());

    // if ($redirects) {
    //     $redirectCount = count($redirects); // Count the number of redirects
    //     echo "<script type='text/javascript'>\n";
    //     echo "const urlMappings = [\n";
    //     foreach ($redirects as $redirect) {
    //         echo "{ oldPath: '" . esc_js($redirect['old']) . "', newPath: '" . esc_js($redirect['new']) . "' },\n";
    //     }
    //     echo '];';
    //     echo "console.log('urlMappings.length: ' + $redirectCount);\n"; // Output the length of the array
    //     echo '</script>';
    // }

    // Output the HTML and CSS
    ?>

<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/redirects.js?v=<?php echo time(); ?>"></script>

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
                            <button type="submit"  id="searchButton" class="btn btn-primary"><div class="mag-glass"></div><span class="visually-hidden">Search</span></button>
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

// Function to extract and normalise the path after the domain
function extractAndNormalisePath(url) {
    const urlObj = new URL(url);
    let path = urlObj.pathname;

    // Remove the path prefix if present
    if (path.startsWith(pathPrefix)) {
        path = path.substring(pathPrefix.length);
    }

    // Normalise the path by trimming leading and trailing slashes
    return path.replace(/^\/|\/$/g, '');
}

// Function to normalise a path by trimming leading and trailing slashes
function normalisePath(path) {
    return path.replace(/^\/|\/$/g, '');
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
function doRedirect(redirectUrl, delay = 5000) {
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

    // Extract and normalise the path from the current URL
    const extractedPath = extractAndNormalisePath(currentURL);

    // Debugging log to check the extracted path
    console.log("Extracted Path: " + extractedPath);

    // Process old site links and redirect if applicable
    const contentBool = processOldSiteLinksAndRedirect(extractedPath);

    // If no redirect occurred from old site links
    if (!contentBool) {
        // Find a URL mapping that matches the extracted path
        const mapping = urlMappings.find(mapping => normalisePath(mapping.oldPath) === extractedPath);

        // If a matching mapping is found, redirect to the new URL
        if (mapping) {
            const newUrl = replaceUrlPath(currentURL, mapping.newPath);
            console.log("Match found! Redirecting to: " + newUrl);

            // Display redirect information
            redirectInfo.style.display = "block";

            // Perform the redirect
            doRedirect(newUrl);
        } else {
            // If no mapping is found, display 404 information
            console.log("No match found. Displaying 404 info.");
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
?>