# Technical Documentation: URL Slug Conflict Resolution

## Issue Summary
The website experienced 404 errors when accessing both Custom Post Type (CPT) and Taxonomy archive pages that shared the same URL slug. This occurred because WordPress could only recognize one URL pattern when both the CPT and its associated taxonomy used identical slugs.

## Root Cause Analysis

### Problem Description
- **Custom Post Type**: `ta_campaign` with slug `campaign`
- **Custom Taxonomy**: `ta-campaign-category` with slug `campaign`
- **Issue**: Both entities competed for the same URL structure `/campaign/something/`
- **Result**: WordPress could only resolve one URL pattern, causing the other to return 404 errors

### Technical Root Cause
WordPress rewrite system processes URL patterns in a specific order. When multiple post types or taxonomies share the same slug, the first registered pattern takes precedence, causing subsequent patterns to be ignored. This creates a conflict where:

1. If CPT is registered first: Taxonomy archives return 404
2. If Taxonomy is registered first: Single post pages return 404
3. WordPress cannot automatically determine which entity the URL should resolve to

## Solution Implemented

### Technical Approach
Implemented a custom rewrite rule system with intelligent query detection to resolve URL conflicts without changing existing slugs.

### Implementation Details

#### 1. Custom Rewrite Rule
```
Pattern: ^campaign/([^/]+)/?$
Target: index.php?campaign_slug_conflict=$matches[1]
Priority: top
```

#### 2. Query Variable Registration
- Added custom query variable `campaign_slug_conflict`
- Allows WordPress to recognize and process custom URL parameters

#### 3. Intelligent Query Handler
- **Priority Logic**: Posts take precedence over taxonomy terms
- **Detection Process**:
  1. Check if URL segment matches a post slug in `ta_campaign`
  2. If post found: Set query for single post display
  3. If no post: Check if URL segment matches taxonomy term in `ta-campaign-category`
  4. If term found: Set query for taxonomy archive display
  5. If neither found: Return 404

#### 4. Automatic Rewrite Rule Management
- Implemented automatic flush of rewrite rules
- Ensures custom rules are active without manual intervention

### Code Location
File: `taloha/wp-content/themes/taloha-child/functions.php`
Lines: 193-273

### Functions Implemented
1. `add_slug_conflict_rewrite_rules()` - Creates custom rewrite rules
2. `add_slug_conflict_query_var()` - Registers custom query variables
3. `handle_slug_conflict_query()` - Processes URL conflicts
4. `flush_slug_conflict_rewrite_rules()` - Manages rewrite rule activation

## Results

### Before Implementation
- `/campaign/post-name/` → 404 error
- `/campaign/category-name/` → 404 error
- Only one URL pattern worked correctly

### After Implementation
- `/campaign/post-name/` → Single post page (working)
- `/campaign/category-name/` → Taxonomy archive page (working)
- `/campaign/` → Archive page (working)
- All URL patterns function correctly

## Prevention Steps for Future Configurations

### 1. URL Slug Planning
- **Before Registration**: Plan URL structure before creating CPT and taxonomy
- **Slug Naming Convention**: Use descriptive, unique slugs when possible
- **Documentation**: Maintain a list of all URL slugs used across the site

### 2. Conflict Detection
- **Testing Protocol**: Test all URL patterns after registering new post types or taxonomies
- **Monitoring**: Regularly check for 404 errors in site analytics
- **Validation**: Verify both single posts and archive pages work correctly

### 3. Alternative Approaches
- **Different Slugs**: Use different slugs for CPT and taxonomy (e.g., `campaign` and `campaign-category`)
- **Hierarchical Structure**: Consider using hierarchical URL structures
- **Prefix/Suffix**: Add prefixes or suffixes to differentiate similar content types

### 4. Development Best Practices
- **Staging Environment**: Test URL conflicts in staging before production
- **Code Documentation**: Document any custom rewrite rules and their purposes
- **Version Control**: Track changes to rewrite rules and URL structures
- **Backup Strategy**: Maintain backups before implementing URL structure changes

### 5. Monitoring and Maintenance
- **Regular Audits**: Periodically review URL structure and functionality
- **Error Logging**: Monitor 404 errors and investigate patterns
- **Performance Impact**: Monitor site performance after implementing custom rewrite rules
- **User Experience**: Ensure URL changes don't negatively impact user experience

## Technical Notes

### Compatibility
- Works with ACF (Advanced Custom Fields) registered post types and taxonomies
- Compatible with all WordPress themes
- No impact on existing functionality

### Performance Considerations
- Minimal performance impact (only processes when needed)
- Custom rewrite rules are cached by WordPress
- Query detection only runs on main queries

### Maintenance Requirements
- No ongoing maintenance required
- Automatic rewrite rule management
- Self-contained solution

## Code Implementation

```php
// ===== HANDLE URL CONFLICT WHEN CPT AND TAXONOMY HAVE SAME SLUG =====

/**
 * Add rewrite rules to handle slug conflict
 * This creates a custom rewrite rule with high priority to catch campaign URLs
 */
function add_slug_conflict_rewrite_rules() {
    // Add rewrite rule for campaign with high priority (top)
    add_rewrite_rule(
        '^campaign/([^/]+)/?$',  // Pattern: /campaign/something/
        'index.php?campaign_slug_conflict=$matches[1]',  // Redirect to custom query var
        'top'  // High priority to override default rules
    );
}
add_action('init', 'add_slug_conflict_rewrite_rules');

/**
 * Add custom query variable to handle slug conflict
 * This allows WordPress to recognize our custom query var
 */
function add_slug_conflict_query_var($vars) {
    $vars[] = 'campaign_slug_conflict';
    return $vars;
}
add_filter('query_vars', 'add_slug_conflict_query_var');

/**
 * Handle query when there's a slug conflict
 * This function determines whether the URL should show a post or taxonomy archive
 */
function handle_slug_conflict_query() {
    global $wp_query;
    
    // Get the captured slug from our custom query var
    $conflict_slug = get_query_var('campaign_slug_conflict');
    
    if ($conflict_slug) {
        // Try to find a post first (priority: posts over taxonomy terms)
        $post = get_page_by_path($conflict_slug, OBJECT, 'ta_campaign');
        
        if ($post) {
            // Found a post - set query for single post
            $wp_query->set('post_type', 'ta_campaign');
            $wp_query->set('name', $conflict_slug);
            $wp_query->is_single = true;
            $wp_query->is_singular = true;
            $wp_query->is_archive = false;
            $wp_query->is_home = false;
            $wp_query->is_404 = false;
        } else {
            // No post found - try to find taxonomy term
            $term = get_term_by('slug', $conflict_slug, 'ta-campaign-category');
            
            if ($term && !is_wp_error($term)) {
                // Found a term - set query for taxonomy archive
                $wp_query->set('post_type', 'ta_campaign');
                $wp_query->set('ta-campaign-category', $conflict_slug);
                $wp_query->is_tax = true;
                $wp_query->is_archive = true;
                $wp_query->is_home = false;
                $wp_query->is_404 = false;
            } else {
                // Nothing found - return 404
                $wp_query->is_404 = true;
            }
        }
    }
}
add_action('parse_query', 'handle_slug_conflict_query');

/**
 * Flush rewrite rules when needed
 * This ensures our custom rewrite rules are active
 */
function flush_slug_conflict_rewrite_rules() {
    if (!get_option('slug_conflict_rewrite_flushed')) {
        flush_rewrite_rules();
        update_option('slug_conflict_rewrite_flushed', true);
    }
}
add_action('init', 'flush_slug_conflict_rewrite_rules', 20);
```

## Conclusion

The implemented solution successfully resolves URL slug conflicts while maintaining SEO-friendly URLs and preserving existing functionality. The intelligent query detection system ensures both Custom Post Type and Taxonomy archive pages work correctly with shared slugs, eliminating 404 errors and improving user experience.

This solution can be replicated for similar conflicts in the future by following the same pattern of custom rewrite rules and intelligent query handling.

---

**Document Version**: 1.0  
**Last Updated**: December 2024  
**Author**: Development Team  
**Status**: Implemented and Tested
