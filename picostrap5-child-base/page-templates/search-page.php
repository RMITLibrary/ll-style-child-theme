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
<h1 class="margin-top-zero">Search the learning lab</h1>
<!-- search input --> 
<input type="text" id="searchInput" placeholder="Search..."><button id="searchButton">Search</button>
<div class="search-debug">
    <div>
        <label for="threshold">
            <a href="https://www.fusejs.io/api/options.html#threshold" target="docs">Threshold:</a>
        </label>
        <input type="number" id="threshold" min="0" max="1" step="0.05" value="0.4">
    </div>
    <div>
        <label for="distance">
            <a href="https://www.fusejs.io/api/options.html#distance" target="docs">Distance:</a>
        </label>
        <input type="number" id="distance" min="0" max="10000" step="50" value="800">
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
        <input type="number" id="minMatchCharLength" min="1" max="100" step="1" value="2">
    </div>
    <div>
        <label for="useExtendedSearch" class="small">
            <a href="https://www.fusejs.io/api/options.html#useextendedsearch" target="_blank">Use Extended Search:</a>
        </label>
        <input type="checkbox" id="useExtendedSearch">
    </div>

</div>
<p>
Data set lives here: <a href="/wp-content/uploads/pages.json" target="_blank" rel="noopener">/wp-content/uploads/pages.json</a>
<ul id="results"></ul>
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

        // Output the keyword as a list item with a link
        echo '<li><a href="' . esc_url($link) . '">' . esc_html($keyword->name) . '</a></li>';
    }

    // Close the last section
    echo '</ul></section>';
} else {
    // Output message if no keywords are found or there's an error
    echo 'No keywords found.';
}
?>
    </div>
</div>
<!-- END col-xs-12 -->

<script>
    var debug = true;

    // Fetch the JSON data
    fetch('/wp-content/uploads/pages.json')
        .then(response => response.json())
        .then(data => {
            function performSearch() {
                const query = document.getElementById('searchInput').value;
                const threshold = parseFloat(document.getElementById('threshold').value);
                const distance = parseInt(document.getElementById('distance').value, 10);
                const location = parseInt(document.getElementById('location').value, 10);
                const useExtendedSearch = document.getElementById('useExtendedSearch').checked;
                const minMatchCharLength = parseInt(document.getElementById('minMatchCharLength').value, 10);

                const options = {
                    keys: ['title', 'content'], // Specify the fields to search
                    threshold: threshold,
                    distance: distance,
                    location: location,
                    minMatchCharLength: minMatchCharLength,
                    includeScore: true,
                    includeMatches: true,
                };

                const fuse = new Fuse(data, options);
                const results = fuse.search(query);
                const resultsList = document.getElementById('results');
                resultsList.innerHTML = '';

                results.forEach(function(result) {
                    var title = result.item.title;
                    var content = result.item.content;
                    var link = result.item.link;
                    var snippet = getSnippet(content, query);
                    var score = result.score.toFixed(2); // Get format the score
                    var matches = result.matches; // Get matches
                    var keywords = result.item.keywords;
                    
                    //only output result if keywords doesn't contain "documentation"
                    if (keywords && !keywords.includes("documentation")) {
                        var li = document.createElement('li');

                        var itemOutput = '<a href="' + link + '"><h3>' + title + '</h3></a><p>' + snippet + '</p>';
                        if(debug == true) itemOutput += '<p class="small">Score: ' + score +" &nbsp;&nbsp;&nbsp;&nbsp;Matches: " + matches +"</p>";

                        li.innerHTML = itemOutput;
                    }
                    
                    resultsList.appendChild(li);
                });
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
</div>

<?php get_footer();
