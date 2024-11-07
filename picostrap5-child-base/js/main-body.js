// START Script to show and hide hamburger menu
(function() {
    // Grab both the menu button, the page content and footer
    var menuButton = document.getElementById("menu-button");
    var pageContent = document.getElementById("theme-main"); 
    var footer = document.getElementById("wrapper-footer-colophon"); 

    // Store display status of menu
    var menuDisplay = false;

    // Store content scroll position
    var scrollPosition;

    // Listen for click - call showHideMenu
    menuButton.addEventListener("click", showHideMenu);

    function showHideMenu() {
        if (menuDisplay == false) {
            menuDisplay = true;
            scrollPosition = window.scrollY;
            pageContent.style.display = "none"; 
            footer.style.display = "none"; 
        } else {
            menuDisplay = false;
            pageContent.style.display = "block"; 
            footer.style.display = "block"; 
            document.documentElement.style.scrollBehavior = "auto";
            window.scroll(0, scrollPosition);
            document.documentElement.style.scrollBehavior = "smooth";
        }
    }
})();
// END Script to show and hide hamburger menu
    

// START Script to turn on embed mode

// Removes top nav, footer, breadcrumbs, right nav, keywords and prev/next by default
// Options to remove h1, p lead and show prev/next
// Sample query string:
// ?iframe=true&hide-title=true&hide-intro=true
 
// iframe - set to true to enable embed mode
// hide-title - set to true to hide title
// hide-intro - set to true to hid first <p class="lead">
// show-prev-next - set to true to show previous and next buttons
 
// Unlikely hide-title and hide-intro would be used in concert with show-prev-next 

(function() {
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const embedBool = urlParams.get('iframe');
    const hideTitle = urlParams.get('hide-title');
    const hideIntro = urlParams.get('hide-intro');
    const showPrevNext = urlParams.get('show-prev-next');

    if (embedBool == 'true') {
        embedThisPage();
        handleEmbedLinks();
    }

    // embedThisPage hides or removes markup to optimise the display of a page inside an iframe,
    // depending on additional query string values
    function embedThisPage() {
        // pick up the relevant objects in the page
        var nav = document.querySelector('header');
        var breadcrumbs = document.querySelector('nav[aria-label="breadcrumbs"]');
        var rightNav = document.querySelector('div.col-xl-4.order-last');
        var myFooter = document.getElementById("wrapper-footer-colophon"); 
        
        //These are layout divs, looking to remove bootstrap styling to go full width
        var containerDiv = document.getElementById("page-content");
        var contentDiv = document.querySelector('div.order-first');
        
        //grab content below prev/next buttons
        var additionalInfo = document.getElementById('additional-info');
        
        //grab landing banner for landing pages
        var landingBanner = document.querySelector("div.landing-banner"); 

        // hide nav and footer (we have footer var from script above)
        nav.style.display = "none";
        myFooter.style.display = "none";

        //If breadcrumbs, right nav, additional info or landing banner exists, hide them
        if (breadcrumbs) { breadcrumbs.style.display = "none"; }
        if (rightNav) { rightNav.remove(); }
        if (additionalInfo) { additionalInfo.style.display = "none"; }
        if (landingBanner) { 
            landingBanner.classList.remove("landing-banner"); 
            var landingImage = document.querySelector("figure");
            landingImage.style.marginTop = "0";
        }

        //Remove bootstrap classes that provide adaptive styling
        if (containerDiv) { 
            containerDiv.classList.remove("container"); 
            containerDiv.style.paddingTop = '0';
            containerDiv.style.marginRight = "2rem";
        }
        if (contentDiv) { contentDiv.classList.remove("col-xl-8"); }

        //Process optional query string vars to hide title, intro, prev next buttons
        if (hideTitle == 'true') {
            var myTitle = document.querySelector('h1');
            myTitle.style.display = "none";
        }
        if (hideIntro == 'true') {
            var firstLeadParagraph = document.querySelector('p.lead');
            firstLeadParagraph.style.display = "none";
        }
        if (showPrevNext != 'true') {
            var btnNavContainer = document.querySelector('.btn-nav-container');
            if (btnNavContainer) { btnNavContainer.style.display = "none"; }
        }
    }

    // handeEmbedLinks either adds the query string or target="_top" depending on context
    function handleEmbedLinks() {
        const links = document.querySelectorAll('a');
        links.forEach(link => {
            const href = link.getAttribute('href');
            if (href !== null) {
                if (showPrevNext == 'true') {
                    if (href.startsWith('http://') || href.startsWith('https://')) {
                        addTargetTopToLink(link);
                    } else {
                        link.setAttribute('href', href + queryString);
                    }
                } else {
                    addTargetTopToLink(link);
                }
            }
        });
    }

    function addTargetTopToLink(link) {
        link.setAttribute('target', '_top');
    }
})();
// END Script to turn on embed mode

// START Dark mode
//There is additional code located in the <head> section of each page. It's not linked to an exterrnal js to minimise flash between content.
(function() {
      'use strict';

      // Function to get the stored theme from local storage
      const getStoredTheme = () => localStorage.getItem('theme');

      // Function to set the theme in local storage
      const setStoredTheme = theme => localStorage.setItem('theme', theme);

      // Function to determine the preferred theme
      const getPreferredTheme = () => {
        const storedTheme = getStoredTheme(); // Retrieve the stored theme
        if (storedTheme) {
          return storedTheme; // If a theme is stored, return it
        }
        // If no theme is stored, check the system preference for dark mode
        return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
      };

      // Function to apply the specified theme
      const setTheme = theme => {
        if (theme === 'auto') {
          // If theme is 'auto', set it based on system preference
          document.documentElement.setAttribute('data-bs-theme', (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'));
        } else {
          // Otherwise, set the theme explicitly
          document.documentElement.setAttribute('data-bs-theme', theme);
        }
      };

      // Function to update the UI to reflect the active theme
      const showActiveTheme = theme => {
        const themeSwitcher = document.querySelector('#theme-switcher');
        if (!themeSwitcher) {
          return; // If no theme switcher element is found, exit
        }
        // Remove 'checked' attribute from all radio buttons
        document.querySelectorAll('[data-bs-theme-value]').forEach(element => {
          element.checked = false;
        });
        // Add 'checked' attribute to the selected theme radio button
        const btnToActive = document.querySelector(`[data-bs-theme-value="${theme}"]`);
        if (btnToActive) {
          btnToActive.checked = true;
        }
      };

      // Listen for changes to the system's dark mode preference
      window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
        const storedTheme = getStoredTheme();
        if (storedTheme !== 'light' && storedTheme !== 'dark') {
          setTheme(getPreferredTheme()); // Update the theme if it's set to 'auto'
        }
      });

      // When the DOM content is loaded, initialise the theme switcher
      window.addEventListener('DOMContentLoaded', () => {
        showActiveTheme(getPreferredTheme()); // Show the active theme
        // Add click event listeners to all theme toggle radio buttons
        document.querySelectorAll('[data-bs-theme-value]').forEach(toggle => {
          toggle.addEventListener('change', () => {
            const theme = toggle.getAttribute('data-bs-theme-value');
            setStoredTheme(theme); // Store the selected theme
            setTheme(theme); // Apply the selected theme
            showActiveTheme(theme); // Update the UI
          });
        });
      });
    })();
    // END Dark mode
    