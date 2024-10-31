<?php
/**
 * Template Name: Search page
 *
 * Template for displaying a page just with the header and footer area and a "naked" content area in between.
 * Good for landing pages and other types of pages where you want to add a lot of custom markup.
 *
 * @package UnderStrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();
?>
<div class="container" id="page-content">

<!-- FUSE script --> 
<script src="https://cdn.jsdelivr.net/npm/fuse.js"></script>
<div class="col-xl-8">
<nav aria-label="breadcrumbs">
<ul class="breadcrumbs">
<li><a href="/">Home</a></li>
</ul>
</nav>
<a id="main-content"></a>
<!-- START search -->
<div class="search-container">
    <label for="search"><h1 class="margin-top-zero">Search the learning lab</h1></label>
    <div class="input-group">
        <input type="search" id="searchInput" class="form-control">
        <button type="submit"  id="searchButton" class="btn btn-primary"><div class="mag-glass"></div><span class="visually-hidden">Search</span></button>
    </div>
</div>
<!-- END search -->
<!-- START debug -->
        <div id="search-debug" class="search-debug">
            <div>            
                <!-- START debug -->
                <label for="threshold">
                    <a href="https://www.fusejs.io/api/options.html#threshold" target="docs">Threshold:</a>
                </label>
                <input type="number" id="threshold" min="0" max="1" step="0.05" value="0.4">
                </div>
                <div>
                    <label for="distance">
                        <a href="https://www.fusejs.io/api/options.html#distance" target="docs">Distance:</a>
                    </label>
                    <input type="number" id="distance" min="0" max="10000" step="50" value="1200">
                </div>
                <div>
                    <label for="location">
                        <a href="https://www.fusejs.io/api/options.html#location" target="docs">Location:</a>
                    </label>
                    <input type="number" id="location" min="0" max="10000" step="50" value="0">
                </div>
                <div>
                    <label for="minMatchCharLength" class="small">
                        <a href="https://www.fusejs.io/api/options.html#minmatchcharlength" target="docs">Min Match Char Length:</a>
                    </label>
                    <input type="number" id="minMatchCharLength" min="1" max="100" step="1" value="4">
                </div>
                <div>
                <label for="useExtendedSearch" class="small">
                    <a href="https://www.fusejs.io/api/options.html#useextendedsearch" target="_blank">Use Extended Search:</a>
                </label>
                <input type="checkbox" id="useExtendedSearch">
            </div>
        </div>
<!-- END debug -->
<!-- START results div -->
<div id="results-container" class="collapse">
    <div>
        <h2 id="results-title" tabindex="0">Search results</h2>
        <p id="results-counter" class="small"></p>
        <ul class="list-link-expanded" id="results"></ul> 
        <p>
            <a href="#searchInput">Search again</a>
        </p> 
    </div>   
</div>
<!-- END results div -->
<hr>
<h2>Browse keywords</h2>
            <p>These pages of similar topics aim to make it quicker and easier to find the content you need. Select any keyword to see all pages linked to that specific term.</p>
</div>
    <!-- END col-xs-8 -->
    <div class="col-xl-12">
        <div class="keyword-listing">
<?php
// Retrieve all terms from the 'keyword' taxonomy
$keywords = get_terms(array(
    'taxonomy' => 'keyword',    // Specify the taxonomy slug
    'orderby' => 'name',        // Order terms by name
    'order' => 'ASC',           // Sort in ascending order
    'hide_empty' => true,       // Exclude terms with no posts/pages
));

// Check if keywords are retrieved successfully
if (!empty($keywords) && !is_wp_error($keywords)) {
    $current_letter = ''; // Initialize variable to track the current starting letter

    // Loop through each keyword
    foreach ($keywords as $keyword) {
        // Get the first letter of the keyword name and convert to uppercase
        $first_letter = strtoupper($keyword->name[0]);

        // Check if the first letter has changed
        if ($first_letter !== $current_letter) {
            // Close previous section if it's not the first letter
            if ($current_letter !== '') {
                echo '</ul></section>';
            }

            // Update the current letter and start a new section
            $current_letter = $first_letter;
            echo '<section><h2>' . esc_html($current_letter) . '</h2><ul>';
        }

        // Get the link for the current keyword
        $link = get_term_link($keyword);


        // Output the keyword as a list item with a link, exclude "Documentation" and "Archive"
        if ($keyword->name != "Documentation" && $keyword->name != "Archive") {
            echo '<li><a href="' . esc_url($link) . '">' . esc_html($keyword->name) . '</a></li>';
        }
       
    }

    // Close the last section
    echo '</ul></section>';
} else {
    // Output message if no keywords are found or there's an error
    echo 'No keywords found.';
}
?>
            <p>
    Data set lives here: <a href="/wp-content/uploads/pages.json" target="_blank" rel="noopener">/wp-content/uploads/pages.json</a></p>
    </div>
</div>
<!-- END col-xs-12 -->
</div>

<script>
    var dataURL = '/wp-content/uploads/pages.json';
    var debug = false;
    
    //Check query string for debug=true. If it is there, show debug interface
    const queryStringSearch = window.location.search;
    const urlParams = new URLSearchParams(queryStringSearch);
    const debugBool = urlParams.get('debug');
    const searchString = urlParams.get('query');
    
    if(debugBool == 'true') {
        debug = true;
        
        var debugInterface = document.getElementById("search-debug");
        debugInterface.style.display = "block";
    }

    console.log("searchString: "+ searchString);
    //console.log("debugBool: "+ debugBool);
    
    // Fetch the JSON data
    fetch(dataURL)
        .then(response => response.json())
        .then(data => {
            function performSearch() {
                
                var query = document.getElementById('searchInput').value;
                var threshold = 0.4;
                var distance = 1200;
                var location = 0;
                var useExtendedSearch = false;
                var minMatchCharLength = 4;
                
                if(debug == true)
                {
                    threshold = parseFloat(document.getElementById('threshold').value);
                    distance = parseInt(document.getElementById('distance').value, 10);
                    location = parseInt(document.getElementById('location').value, 10);
                    useExtendedSearch = document.getElementById('useExtendedSearch').checked;
                    minMatchCharLength = parseInt(document.getElementById('minMatchCharLength').value, 10);
                }

                const options = {
                    keys: ['title', 'content'], // Specify the fields to search
                    threshold: threshold,
                    distance: distance,
                    location: location,
                    minMatchCharLength: minMatchCharLength,
                    includeScore: true,
                    includeMatches: true,
                };

                // START if to avoid blank queries
                if(query != "")
                {
                    const fuse = new Fuse(data, options);
                    const results = fuse.search(query);
                    const resultsList = document.getElementById('results');
                    resultsList.innerHTML = '';

                    const resultsCounter = document.getElementById('results-counter');

                    if(results.length == 0) {
                        resultsCounter.innerHTML = 'No results found.';
                    }
                    else if(results.length == 1) {
                        resultsCounter.innerHTML = results.length +' result found.'; 
                    }
                    else {
                        resultsCounter.innerHTML = results.length +' results found.';
                    }
                    
                    results.forEach(function(result) {
                        var title = result.item.title;
                        var content = result.item.content;
                        var link = result.item.link;
                        var snippet = getSnippet(content, query);
                        var score = result.score.toFixed(2); // Get format the score
                        var matches = result.matches; // Get matches
                        var keywords = result.item.keywords;
                        
                        // Only output result if keywords don't contain "documentation", "archive", or "redirect"
                        if (keywords && !keywords.some(keyword => {
                            const lowerKeyword = keyword.toLowerCase();
                            return ["documentation", "archive", "redirect"].includes(lowerKeyword);
                        })) {
                            var li = document.createElement('li');

                            var itemOutput = '<a href="' + link + '"><h3 class="text">' + title + '</h3></a><p>' + snippet + '</p>';
                            if (debug === true) itemOutput += '<p class="small">Score: ' + score + " &nbsp;&nbsp;&nbsp;&nbsp;Matches: " + matches + "</p>";

                            li.innerHTML = itemOutput;
                            resultsList.appendChild(li);
                        }
                    });
                    
                    // Select the element
                    var collapseElement = document.getElementById('results-container');

                    // Create a new Bootstrap Collapse instance
                    var collapseInstance = new bootstrap.Collapse(collapseElement, {
                        toggle: false // Prevents automatic toggle
                    });

                    // Use the show method to expand
                    collapseInstance.show();
                    document.getElementById("results-title").focus();
                }
                //END if to avoid blank queries
            }

            function getSnippet(content, query) {
                // Return the first 160 characters if the query is empty
                if (!query) return content.substring(0, 160);

                // Find the index of the query in the content, case-insensitive
                var index = content.toLowerCase().indexOf(query.toLowerCase());

                // If the query is found
                if (index !== -1) {
                    // Determine the start position for the snippet
                    var start = Math.max(0, index - 80);

                    // Try to find the start of a sentence or a space
                    var sentenceStart = content.lastIndexOf('.', start) + 1;
                    var spaceStart = content.lastIndexOf(' ', start) + 1;

                    // Choose the maximum of sentenceStart or spaceStart if they are within limits
                    if (sentenceStart > start - 80) start = sentenceStart;
                    else if (spaceStart > start - 80) start = spaceStart;

                    // Determine the end position for the snippet
                    var end = Math.min(content.length, index + 80);

                    // Extract the snippet from the content
                    var snippet = content.substring(start, end).trim();

                    // Escape special characters in the query for regex
                    var escapedQuery = query.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');

                    // Create a regex to highlight the query in the snippet
                    var regex = new RegExp('(' + escapedQuery + ')', 'gi');

                    // Highlight the query in the snippet
                    snippet = snippet.replace(regex, '<span class="highlight-1">$1</span>');

                    return snippet;
                }

                // If the query is not found, return the first 160 characters
                return content.substring(0, 160);
            }

            //If queryString has query=something then do the search. Used to make home screen search work.
            if(searchString != null)
            {
                //place searchString into input and then execute search
                document.getElementById('searchInput').value = searchString;
                performSearch();
            }

            // Listen for button click
            document.getElementById('searchButton').addEventListener('click', performSearch);

            // Listen for enter key
            document.getElementById('searchInput').addEventListener('keypress', function(event) {
                if (event.key === 'Enter') {
                    performSearch();
                }
            });
        })
        .catch(function(error) {
            console.error('Error fetching JSON:', error);
        });
</script>
<?php get_footer();
