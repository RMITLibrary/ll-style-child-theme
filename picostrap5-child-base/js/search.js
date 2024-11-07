var dataURL = "../wp-content/uploads/pages.json";
var debug = false;

//set default variables for search
var threshold = 0.4;
var distance = 1200;
var mySearchLocation = 0;
var useExtendedSearch = false;
var minMatchCharLength = 4;

// set dataURL depending on siteURL
var fullUrl = window.location.href;
// Create a URL object
var url = new URL(fullUrl);

console.log("Search script 1.0.4: " +fullUrl);

    
//Check query string for debug=true or query=[search text].
const queryStringSearch = window.location.search;
const urlParamsSearch = new URLSearchParams(queryStringSearch);
const debugBool = urlParamsSearch.get('debug');
const searchString = urlParamsSearch.get('query');
    
//If  debug=true, show debug interface
if(debugBool == 'true') {
    debug = true;
    var debugInterface = document.getElementById("search-debug");
    debugInterface.style.display = "block";
}

console.log("dataURL: " +dataURL);

// Fetch the JSON data
fetch(dataURL)
    .then(response => response.json())
    .then(data => {
        function performSearch() {
 
            var query = document.getElementById('searchInput').value;
            
            //if debug is true, populate search variables with values from debug interface
            if(debug == true)
            {
                threshold = parseFloat(document.getElementById('threshold').value);
                distance = parseInt(document.getElementById('distance').value, 10);
                mySearchLocation = parseInt(document.getElementById('location').value, 10);
                useExtendedSearch = document.getElementById('useExtendedSearch').checked;
                minMatchCharLength = parseInt(document.getElementById('minMatchCharLength').value, 10);
            }

            //build options object
            const options = {
                keys: ['title', 'content'], // Specify the fields to search
                threshold: threshold,
                distance: distance,
                location: mySearchLocation,
                minMatchCharLength: minMatchCharLength,
                includeScore: true,
                includeMatches: true,
            };

            console.log("Perform Search: " +query);

            // START if statement to avoid blank queries
            //If query isn't blank, then perform the search
            if(query != "")
            {
                //Build fuse object
                const fuse = new Fuse(data, options);
                const results = fuse.search(query);

                //Get reference to html to display results
                const resultsList = document.getElementById('results');
                resultsList.innerHTML = '';

                const resultsCounter = document.getElementById('results-counter');

                //update results counter
                if(results.length == 0) {
                    resultsCounter.innerHTML = 'No results found.';
                }
                else if(results.length == 1) {
                    resultsCounter.innerHTML = results.length +' result found.'; 
                }
                else {
                    resultsCounter.innerHTML = results.length +' results found.';
                }
                
                //loop through results displaying each
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
                        //create and format html element
                        var li = document.createElement('li');
                        var itemOutput = '<a href="..' + link + '"><h3 class="text">' + title + '</h3></a><p>' + snippet + '</p>';
                        
                        //if debug is true, so score and match vars
                        if (debug === true) itemOutput += '<p class="small">Score: ' + score + " &nbsp;&nbsp;&nbsp;&nbsp;Matches: " + matches + "</p>";

                        //add element to the page
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

        //Snippet formats the text underneath the search result
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



