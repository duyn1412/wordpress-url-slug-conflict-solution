<?php
function my_theme_enqueue_styles() { 
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}

add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );

/* events slider */
function dp_ocp_items_carousel_content( $content, $props ) {
	if ( $props['module_id'] === 'ocp-custom-items' ) {
		ob_start();

		$args = array(
			'post_type' => 'project',
			'posts_per_page' => 6,
			'project_category' => 'upcoming-events'
		);

		$project_query = new WP_Query( $args );

		if ( $project_query->have_posts() ) {
			while ( $project_query->have_posts() ) {
				$project_query->the_post();
				$preview_text = get_field( 'ta_event_preview_text' );
				$event_duration = get_field( 'ta_event_duration' );
				?>
				<div class="dp_oc_item">
					<div class="ta-event-image"><a href="<?php the_permalink(); ?>">
						
							
						
						<?php 
						if ( has_post_thumbnail() ) {
							the_post_thumbnail( 'medium_large', array( 'class' => 'dp_oc_image_thumb' ) );
						}
						?>
						
					</a>
					</div>
					
					<div class="ta-event-content">
						
							<?php 
				
				if ($event_duration) {
					echo '<div class="ta-event-duration" style="margin-bottom: 10px;">'.$event_duration.'</div>';
				}
				 ?>
						
						<h2 class="dp_oc_image_title" style="margin-top:0;"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<div class="dp_oc_image_content">
							<?php echo $preview_text; ?>
						</div>
					</div>
				</div>
				<?php
			}
		} else {
			echo '<p>No projects found.</p>';
		}

		wp_reset_postdata();

		$content = ob_get_clean();
	}

	return $content;
}

add_filter( 'dp_ocp_items_carousel_content', 'dp_ocp_items_carousel_content', 10, 2 );

add_filter( 'dp_ocp_owl_init', 'dp_ocp_owl_init', 10, 2 );

function dp_ocp_owl_init( $args, $props ) {
    
        $args = array(
            'margin'     => 32,
			'items'		=> 2,
			'loop' => false,
        );
    

    return $args;
}



/* change projects slug 
function my_custom_projects_slug( $slug ) {
    $slug = array( 'slug' => 'event' );

    return $slug;
}
add_filter( 'et_project_posttype_rewrite_args', 'my_custom_projects_slug', 10, 2 );*/




function maybe_filter_project_args()
{
    // Update variables value in code to rename the post type
    $singular_name = 'Event'; // Update this variable to change the singular name of the post type.
    $plural_name = 'Events'; // Update this variable to change the plural name of the post type.
    $slug = 'event'; // Update this variable to change the slug of the post type.
    $menu_icon = 'dashicons-admin-post';

    register_post_type('project', [
        'labels' => [
            'name' => $plural_name,
            'singular_name' => $singular_name,
            'add_new_item' => sprintf('Add New %s', $singular_name),
            'edit_item' => sprintf('Edit %s', $singular_name),
            'new_item' => sprintf('New %s', $singular_name),
            'all_items' => sprintf('All %s', $plural_name),
            'view_item' => sprintf('View %s', $singular_name),
            'search_items' => sprintf('Search %s', $plural_name),
        ],
        'menu_icon'   => $menu_icon,
        'has_archive' => true,
        'hierarchical' => true,
        'public' => true,
        'rewrite' => [
            'slug' => $slug,
        ],
    ]);

    // Rename Project Category Labels and Slug
    $category_singular_name = 'Event Category'; // Update this variable to change the singular name of the category.
    $category_plural_name   = 'Event Categories'; // Update this variable to change the plural name of the category.
    $category_slug          = 'project_category'; // Update this variable to change the slug of the category.

    register_taxonomy('project_category', array('project'), [
        'hierarchical' => true,
        'labels' => [
            'name' => sprintf('%s', $category_plural_name),
            'singular_name' => sprintf('%s', $category_singular_name),
            'search_items' => sprintf('Search %s', $category_plural_name),
            'all_items' => sprintf('All %s', $category_plural_name),
            'parent_item' => sprintf('Parent %s', $category_singular_name),
            'parent_item_colon' => sprintf('Parent %s:', $category_singular_name),
            'edit_item' => sprintf('Edit %s', $category_singular_name),
            'update_item' => sprintf('Update %s', $category_singular_name),
            'add_new_item' => sprintf('Add New %s', $category_singular_name),
            'new_item_name' => sprintf('New %s Name', $category_singular_name),
            'menu_name' => sprintf('%s', $category_plural_name),
            'not_found' => sprintf('You currently don\'t have any %s.', $category_plural_name),
        ],
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'show_in_rest' => true,
        'rewrite' => [
            'slug' => $category_slug,
            'with_front' => true,
        ],
    ]);

    // Rename Project Tag Labels and Slug
    $tag_singular_name = 'Project Tag'; // Update this variable to change the singular name of the tag.
    $tag_plural_name   = 'Project Tags'; // Update this variable to change the plural name of the tag.
    $tag_slug          = 'project_tag'; // Update this variable to change the slug of the tag.

    register_taxonomy('project_tag', array('project'), [
        'hierarchical' => true,
        'labels' => [
            'name' => sprintf('%s', $tag_plural_name),
            'singular_name' => sprintf('%s', $tag_singular_name),
            'search_items' => sprintf('Search %s', $tag_plural_name),
            'all_items' => sprintf('All %s', $tag_plural_name),
            'parent_item' => sprintf('Parent %s', $tag_singular_name),
            'parent_item_colon' => sprintf('Parent %s:', $tag_singular_name),
            'edit_item' => sprintf('Edit %s', $tag_singular_name),
            'update_item' => sprintf('Update %s', $tag_singular_name),
            'add_new_item' => sprintf('Add New %s', $tag_singular_name),
            'new_item_name' => sprintf('New %s Name', $tag_singular_name),
            'menu_name' => sprintf('%s', $tag_plural_name),
            'not_found' => sprintf('You currently don\'t have any  %s.', $tag_plural_name),
        ],
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'show_in_rest' => true,
        'rewrite' => [
            'slug' => $tag_slug,
            'with_front' => true,
        ],
    ]);
}
add_action('init', 'maybe_filter_project_args');



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
