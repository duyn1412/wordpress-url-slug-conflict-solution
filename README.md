# WordPress URL Slug Conflict Resolution

## Overview
This repository contains a complete solution for resolving URL slug conflicts in WordPress when Custom Post Types and Taxonomies share the same slug.

## Problem
When both a Custom Post Type and its associated Taxonomy use the same URL slug (e.g., `campaign`), WordPress can only recognize one URL pattern, causing the other to return 404 errors.

## Solution
Implemented a custom rewrite rule system with intelligent query detection that:
- Prioritizes posts over taxonomy terms
- Automatically detects and resolves URL conflicts
- Maintains SEO-friendly URLs
- Works with ACF-registered post types and taxonomies

## Files
- [`URL-SLUG-CONFLICT-RESOLUTION.md`](URL-SLUG-CONFLICT-RESOLUTION.md) - Complete technical documentation
- [`functions.php`](functions.php) - Implementation code
- [`README-url-conflict-fix.md`](README-url-conflict-fix.md) - Quick reference guide

## Quick Start
1. Copy the code from `functions.php` to your theme's functions.php
2. Go to Settings > Permalinks and click "Save Changes"
3. Test your URLs

## URL Structure
- `/campaign/` → Archive page (all campaigns)
- `/campaign/post-name/` → Single post page
- `/campaign/category-name/` → Taxonomy archive page

## Compatibility
- WordPress 5.0+
- ACF (Advanced Custom Fields)
- All themes
- PHP 7.4+

## Technical Details
- **Custom Rewrite Rules**: High-priority rules to catch conflicting URLs
- **Intelligent Query Detection**: Automatically determines post vs taxonomy
- **SEO-Friendly**: Maintains clean URLs without prefixes
- **Performance Optimized**: Minimal impact on site performance

## Author
**Duy Nguyen** - WordPress Expert with 6+ years experience in I.T.

[GitHub Profile](https://github.com/duyn1412)

## License
This project is open source and available under the [MIT License](LICENSE).

## Contributing
Contributions, issues, and feature requests are welcome! Feel free to check the [issues page](https://github.com/duyn1412/wordpress-url-slug-conflict-solution/issues).

## Support
If you found this helpful, please give it a ⭐️!
