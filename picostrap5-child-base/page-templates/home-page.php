<?php
/**
 * Template Name: Home Page
 *
 * Template for displaying a page just with the header and footer area and a "naked" content area in between.
 * Good for landingpages and other types of pages where you want to add a lot of custom markup.
 *
 * @package UnderStrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();
?>
<div class="container" id="page-content">
<div id="main-content"></div>
   <!-- START home banner -->
		<div class="home-banner">
            <span class="background-image" role="img" aria-label="A desk with a laptop, a house plant, a mug filled with pencils, an open book and a hot cup of coffee. Book shelves are shown in the background. There’s a cat on the lower shelf."></span>
            <h1>Learning lab</h1>
        </div>
        <!-- END home banner -->
         <!-- START home intro -->
        <div class="home-intro">
            <p class="lead">The Learning Lab provides foundation skills and study support materials for writing, assessments, referencing, health sciences, physics, chemistry, maths and much more.</p>
            <p class="small" id="caption-text">Image by Digital Learning, RMIT Library</p>
            
            <form role="search" action="/search/">
                <label class="h4" for="search">
                    Search <span class="visually-hidden">this website:</span>
                </label>
                <div class="input-group">
                    <input type="search" id="search" class="form-control">
                    <button type="submit" class="btn btn-primary"><div class="mag-glass"></div><span class="visually-hidden">Search</span></button>
                </div>
            </form>
        </div>
        <!-- END home intro -->
        <!-- START home panels -->
        <div class="home-panel-container">
            <a href="/university-essentials/" class="home-panel">
                <img src="https://rmitlibrary.github.io/cdn/learninglab/illustration/uni-essentials.png" alt="A backpack, headphones, mobile phone, laptop and a stack of books with an apple on top." />
                <h2 class="link-large">University Essentials</h2>
                <p>New to uni? University essentials has you covered. Find out more about topics as diverse as group work, critical thinking and even artificial intelligence.</p>
            </a>
            
            <a href="/writing-fundamentals/" class="home-panel">
                <img src="https://rmitlibrary.github.io/cdn/learninglab/illustration/writing.png" alt="A desk with a laptop and a hot cup of coffee. Book shelves are shown in the background." />
                <h2 class="link-large">Writing fundamentals</h2>
                <p>Essential resources for writing skills needed in tertiary study. From paragraph structure to academic style, your writing needs are covered.</p>
            </a>
            <a href="/assessments/" class="home-panel">
                <img src="https://rmitlibrary.github.io/cdn/learninglab/illustration/assessments.png" alt="A page with green ticks on it, a mug filled with pencils, a calendar and a house plant." />
                <h2 class="link-large">Assessments</h2>
                <p>All the resources to help you get started with your assessments. Get assistance structuring essays, presentations, reports and more.</p>
            </a>
            <a href="/referencing/" class="home-panel">
                <img src="https://rmitlibrary.github.io/cdn/learninglab/illustration/referencing.png" alt="A student reading a book on a couch, surrounded by large quote marks." />
                <h2 class="link-large">Referencing</h2>
                <p>Find out how to correctly use different referencing styles in academic writing to avoid plagiarism and get better marks. </p>
            </a>
            <a href="/subject-areas/" class="home-panel">
                <img src="https://rmitlibrary.github.io/cdn/learninglab/illustration/subject-support.png" alt="Shelves with scales, books, test tubes, paint brushes, a calculator and a picture frame." />
                <h2 class="link-large">Subject support</h2>
                <p>Specific resources for specific subjects. Whether it's statistics or art, chemistry or nursing, subject support can help.</p>
            </a>
        </div>
        <!-- END home panels -->
	
</div>

<?php get_footer();
