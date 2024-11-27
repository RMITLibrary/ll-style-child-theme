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


//-------------------------------------------
//	my_redirects_page

//	Generates the admin page for managing redirects with fields for old and new URLs

//	Called from:	my_redirects_menu

//	Calls:			None directly, but handles form submissions to update redirects

//	Usage:			Triggered by form submission on the admin page to save redirects
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
        echo '<div class="updated"><p>Redirects saved.</p></div>';
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

            echo '<div class="updated"><p>Redirects updated from CSV.</p></div>';
        }
    }

    // Function to remove fragment from URL - removes #if it's there
    function remove_url_fragment($url) {
        $parsed_url = parse_url($url);
        $clean_url = $parsed_url['scheme'] . '://' . $parsed_url['host'] . $parsed_url['path'];

        if (isset($parsed_url['query'])) {
            $clean_url .= '?' . $parsed_url['query'];
        }

        return $clean_url;
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

        // state delete function
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
        //end delete function

        // Call the function to update the row count display
        updateRowCount();

    </script>
    <?php
}


//-------------------------------------------
//	output_redirect_404_script_and_html

//	Outputs JavaScript for URL redirects and the HTML structure for redirect and 404 messages

//	Called from:	404.php and pages using the redirect-404 page template (currently https://lab.bitma.app/redirect-404/ )

//	Calls:			createBreadcrumbs (ensure this function exists in your theme)

//	Usage:			Call output_redirect_script_and_html() in template files to display redirect logic and messages
//-------------------------------------------

function output_redirect_404_script_and_html() {
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

    // Output the HTML and CSS
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

//check for /content and .html, legacy from the old site
//remove if present and redirect to that url
var contentBool = processOldSiteLinksAndRedirect(extractedPath);
console.log("Path contains /content: " + contentBool);

if(!contentBool)
{
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
}

function processOldSiteLinksAndRedirect(path) {
    // Remove ".html" if present
    if (path.endsWith('.html')) {
        path = path.slice(0, -5);
    }

    // Replace "/content/" with "/"
    if (path.startsWith('content/')) {
        path = path.replace('content/', '/');

        newPath = pathPrefix + path;

        // Redirect using the existing function
        doRedirect(newPath, 0);
        return true;
    }
    else {
        return false;
    }
}

function doRedirect(redirectUrl, delay = 5000) {
    // Redirect after 5 seconds
    setTimeout(function() {
        console.log('Redirecting to: ' + redirectUrl);
        window.location.href = redirectUrl;
    }, delay);
}

</script>

    <?php
}   //end myRedirectspage

?>