 // Get the full URL
 var fullUrl = window.location.href;
 console.log('Full URL:', fullUrl);
 console.log('Redirect Path:', redirectPath);
 
 // Create a URL object
 var url = new URL(fullUrl);
 
 // Define the part of the path to search for
 var searchPath = '/documentation/redirect/';
 console.log('Search Path:', searchPath);
 
 // Find the index of the searchPath in the full URL
 var searchIndex = fullUrl.indexOf(searchPath);
 
 // Extract the prefix up to the search path
 var prefix = fullUrl.substring(0, searchIndex);
 console.log('Prefix:', prefix);
 
 // Construct the full redirect URL
 var redirectUrl = prefix + redirectPath;
 
 // Redirect after 5 seconds
 setTimeout(function() {
     console.log('Redirecting to:', redirectUrl);
     window.location.href = redirectUrl;
 }, 5000);

