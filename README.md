# LL Style Child Theme Repository

This repository contains custom WordPress themes and plugins. Below are details related to the structure and usage.

## 📁 Repository Structure

- **ll-style-child-theme**
  - **picostrap5-child-base**: Custom child theme based on Picostrap5.
  - **picostrap5**: Original parent theme for customization.

- **plugins**
  - Custom plugins for enhanced functionality. Uploaded separately to `/wp-content/plugins` on your WordPress deployment.

## 🛠 AB Test Lite Plugin

**Version:** 1.2  
**Author:** RMIT Library

A lightweight A/B testing solution for WordPress with GA4 event tracking. Works with static site generators and full WordPress installations.

### 📊 Features

- Simple shortcode implementation
- GA4 event tracking (views, engagements, bounces, clicks)
- Cookie-based variant persistence
- Debug mode for testing
- Mobile-responsive
- No external dependencies

### 🚀 Installation

1. Upload the `ab-test-lite` folder to `/wp-content/plugins/`
2. Activate the plugin through 'Plugins' in WordPress
3. Use the shortcodes in your content

### 📝 Usage

#### Basic A/B Test
```
[variation-test var-test-name="button_test"]
  [variation var-test="a"]
    <button class="track-button">Variant A</button>
  [/variation]
  [variation var-test="b"]
    <button class="track-button">Variant B</button>
  [/variation]
[/variation-test]
```

#### Tracking Events
- Add `track-` prefix to any class to track clicks:
  ```
  <button class="track-button">Click Me</button>
  ```

#### Debug Mode
Add `?debug_ab=true` to the URL to see all variants

### 📈 GA4 Events

| Event Name         | Triggered When                     | Parameters                         |
|--------------------|-----------------------------------|-----------------------------------|
| `ab_test_view`    | Test variation is displayed        | `ab_test_name`, `ab_test_variant` |
| `ab_test_click`   | Tracked element is clicked        | `ab_test_name`, `ab_test_variant`, `click_class` |
| `ab_test_engagement` | User views variant for ≥3 seconds | `ab_test_name`, `ab_test_variant`, `dwell_time_seconds` |
| `ab_test_bounce`  | User leaves before 3 seconds      | `ab_test_name`, `ab_test_variant` |

### 🛠 Development

- **Debug Mode**: Add `?debug_ab=true` to URL
- **Force Variant**: Add `?force_variant=a` to test specific variants
- **Clear Test Cookie**: Delete the `abtest_[testname]` cookie to reset a test

---

Place plugins in the designated `/wp-content/plugins` directory within your WordPress environment for activation and usage.

---

For theme customization, use **picostrap5-child-base** as the active theme, ensuring modifications persist even with theme updates.