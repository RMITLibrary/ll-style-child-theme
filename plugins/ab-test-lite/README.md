# AB Test Lite Plugin

**Version:** 1.1  
**Author:** Your Name

This plugin provides shortcode-based A/B testing for WordPress with full GA4 event tracking, designed to work even on static snapshot sites.

---

## ✅ Features

- [x] `[variation-test]` and `[variation]` shortcodes
- [x] Random variant selection per user
- [x] `?force_variant=c` for QA/debug
- [x] `?debug_ab=true` to show all variants side-by-side
- [x] GA4 `dataLayer` event tracking:
  - `ab_test_view`
  - `ab_test_click`
  - `ab_test_engagement` (≥ 3s dwell)
  - `ab_test_bounce` (< 3s dwell)
- [x] Cookie-based variant persistence (optional)

---

## 🛠 Usage

### Shortcode Example:

```
[variation-test var-test-name="cta-comparison"]
  [variation var-test="a" click-track="track-a"]
    <button class="track-a">Click A</button>
  [/variation]
  [variation var-test="b" click-track="track-b"]
    <button class="track-b">Click B</button>
  [/variation]
[/variation-test]
```

---

## 🎯 Tracking Overview

### Tracked Events (sent to `dataLayer`)

| Event              | Trigger                          |
|-------------------|----------------------------------|
| `ab_test_view`     | Page load                        |
| `ab_test_click`    | Click on `.track-*` elements     |
| `ab_test_engagement` | ≥ 3s in-view of variant        |
| `ab_test_bounce`   | < 3s in-view                     |

---

## ⚙️ URL Params

| Param            | Purpose                           |
|------------------|-----------------------------------|
| `?force_variant=a` | Force a specific variant         |
| `?debug_ab=true`   | Show all variants side-by-side   |

---

## 🔁 Persistence (Cookies)

- The plugin sets a cookie (`abtest_{testname}`) to remember the user's selected variant across visits.
- This ensures consistent experience and more accurate analytics.

---

## 🚀 Installation

1. Upload the ZIP to WordPress > Plugins > Add New > Upload
2. Activate the plugin
3. Use shortcodes on pages/posts

