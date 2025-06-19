<?php
/**
 * Plugin Name: AB Test Lite
 * Description: Lightweight shortcode-based A/B testing with GA4 click + dwell tracking.
 * Version: 1.1
 * Author: Your Name
 */

add_shortcode('variation-test', function($atts, $content = null) {
    $atts = shortcode_atts(['var-test-name' => 'default'], $atts);
    $test_name = sanitize_title($atts['var-test-name']);

    preg_match_all('/var-test="(.*?)"/', $content, $matches);
    $variants = array_unique(array_filter($matches[1]));

    if (empty($variants)) return 'Test 124';

    $forced = isset($_GET['force_variant']) ? sanitize_text_field($_GET['force_variant']) : null;
    $debug = isset($_GET['debug_ab']) && $_GET['debug_ab'] === 'true';

    if ($debug) {
        $selected = '__debug'; // Show all variants in debug mode
    } else {
        $selected = ($forced && in_array($forced, $variants)) ? $forced : $variants[array_rand($variants)];
    }

    $content = do_shortcode($content);
    
    // Strip out <br> tags
    $content = preg_replace('/<br\s*\/?>/', '', $content);

    ob_start(); ?>
    <div class="variation-test" data-test-name="<?php echo esc_attr($test_name); ?>" data-variant="<?php echo esc_attr($selected); ?>">
        <?php echo $content; ?>
    </div>
    <noscript><div style="color:red;">This content requires JavaScript to display.</div></noscript>
    <?php return ob_get_clean();
});

add_shortcode('variation', function($atts, $content = null) {
    $atts = shortcode_atts(['var-test' => 'a', 'click-track' => ''], $atts);
    $variant = strtolower(trim($atts['var-test']));
    $click_class = esc_attr($atts['click-track']);
    return '<div class="ab-variant ab-variant-' . $variant . '" data-variant="' . $variant . '"><div class="' . $click_class . '">' . do_shortcode($content) . '</div></div>';
});

add_action('wp_footer', function () {
    echo '<script src="' . plugin_dir_url(__FILE__) . 'assets/ab-test.js" defer></script>';
});
