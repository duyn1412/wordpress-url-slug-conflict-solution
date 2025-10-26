# WordPress URL Slug Conflict Resolution

## Problem
When custom post type and custom taxonomy have the same slug, WordPress will only recognize one of them and the other will return 404.

## Solution
The code in `functions.php` will automatically handle this conflict by:

1. **Hook into parse_query**: Intercept WordPress query processing
2. **URL Detection**: Based on the second segment to determine if it's a post or taxonomy
3. **Query Processing**: Set correct query variables for WordPress to display the right content

## How It Works

### URL Structure
- `/campaign/` → Archive page (all campaigns)
- `/campaign/post-name/` → Single campaign post
- `/campaign/category-name/` → Campaign category archive

### Detection Logic
1. **Check post first**: Look for any post with slug = `post-name`
2. **If post found**: Set query to display single post
3. **If no post found**: Look for taxonomy term with slug = `category-name`
4. **If term found**: Set query to display taxonomy archive
5. **If nothing found**: Return 404

## Requirements
- CPT: `ta_campaign` (slug: `campaign`)
- Taxonomy: `ta-campaign-category` (slug: `campaign`)

## Notes
- Code will automatically run when there's a slug conflict
- No additional configuration needed
- Compatible with all themes
- Works with CPT and taxonomy registered by ACF