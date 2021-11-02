<?php

function universityQueryVars($vars) {
    $vars[] = 'skyColor';
    $vars[] = 'grassColor';
    return $vars;
}

add_filter('query_vars', 'universityQueryVars');

require get_theme_file_path('/inc/search-route.php');
require get_theme_file_path('/inc/like-route.php');

function university_custom_rest() {
    register_rest_field('post', 'authorName', array(
        'get_callback' => function () {return get_the_author();}
    ));
    register_rest_field('note', 'userNoteCount', array(
        'get_callback' => function () {return count_user_posts(get_current_user_id(), 'note');}
    ));
}

add_action('rest_api_init', 'university_custom_rest');

// Page banner live function
function pageBanner($args = NULL) {
    // Check title, subtitle, photo
    if (!$args['title']) {
        $args['title'] = get_the_title();
    }
    if (!$args['subtitle']) {
        $args['subtitle'] = get_field('page_banner_subtitle');
    }
    if (!$args['photo']) {
        if (get_field('page_banner_background_image')) {
            $args['photo'] = get_field('page_banner_background_image')['sizes']['pageBanner'];
        } else {
            $args['photo'] = get_theme_file_uri('/images/ocean.jpg');
        }
    }
    ?>

    <div class="page-banner">
      <div class="page-banner__bg-image" style="background-image: url(<?php echo $args['photo']; ?>)"></div>
      <div class="page-banner__content container container--narrow">
        <h1 class="page-banner__title"><?php echo $args['title']; ?></h1>
        <div class="page-banner__intro">
          <p><?php echo $args['subtitle']; ?></p>
        </div>
      </div>
    </div>

    <?php
}

// Adding essential files
function university_files() {
  wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
  wp_enqueue_style('font-awesome', get_theme_file_uri('/css/font-awesome.min.css'));

  wp_enqueue_script('googleMap', '//maps.googleapis.com/maps/api/js?key=yourkeygoeshere', NULL, '1.0', true);
  wp_enqueue_script('axios', get_theme_file_uri('/js/axios.min.js'), NULL, '1.0', true);
  wp_enqueue_script('glidejs', get_theme_file_uri('/js/glide.js'), NULL, '1.0', true);

  wp_enqueue_script('main-university-js', get_theme_file_uri('/scripts.js'), array('jquery'), '1.0', true);
  wp_enqueue_style('university_main_styles', get_stylesheet_uri());

  wp_localize_script('main-university-js', 'universityData', array(
    'root_url' => get_site_url(),
    'nonce' => wp_create_nonce('wp_rest')
  ));
}
// Run above
add_action('wp_enqueue_scripts', 'university_files');


// Add university features
function university_features() {
    // Register nav header
    register_nav_menu('headerMenuLocation', 'Header Menu Location');
    // Register nav footer one
    register_nav_menu('footerMenuOne', 'Footer Menu One');
    // Register nav footer two
    register_nav_menu('footerMenuTwo', 'Footer Menu Two');
    // Add title tag
    add_theme_support('title-tag');
    // Add thumbnail image to posts
    add_theme_support('post-thumbnails');
    // Add image size
    add_image_size('professorLandscape', 400, 260, true);
    add_image_size('professorPortraite', 480, 650, true);
    add_image_size('pageBanner', 1500, 350, true);
}
// Run above
add_action('after_setup_theme', 'university_features');


// Register post types
function university_post_types() {
    // Register post campuses
    register_post_type('campus', array(
        'capability_type' => 'campus',
        'map_meta_cap' => true,
        'show_in_rest' => true,
        'supports' => array('title', 'editor', 'excerpt'), // You can use custom-fields (This is amateur)
        'rewrite' => array(
            'slug' => 'campuses'
        ),
        'has_archive' => true,
        'public' => true,
        'labels' => array(
            'name' => 'Campuses',
            'add_new_item' => 'Add New Campus',
            'edit_item' => 'Edit Campus',
            'all_items' => 'All Campuses',
            'singular_name' => 'Campuses'
        ),
        'menu_icon' => 'dashicons-location-alt'
    ));
    // Register post events
    register_post_type('event', array(
        'capability_type' => 'event',
        'map_meta_cap' => true,
        'show_in_rest' => true,
        'supports' => array('title', 'editor', 'excerpt'), // You can use custom-fields (This is amateur)
        'rewrite' => array(
            'slug' => 'events'
        ),
        'has_archive' => true,
        'public' => true,
        'labels' => array(
            'name' => 'Events',
            'add_new_item' => 'Add New Event',
            'edit_item' => 'Edit Event',
            'all_items' => 'All Events',
            'singular_name' => 'Events'
        ),
        'menu_icon' => 'dashicons-calendar'
    ));
    // Register post programs
    register_post_type('program', array(
        'show_in_rest' => true,
        'supports' => array('title'), // You can use custom-fields (This is amateur)
        'rewrite' => array(
            'slug' => 'programs'
        ),
        'has_archive' => true,
        'public' => true,
        'labels' => array(
            'name' => 'Programs',
            'add_new_item' => 'Add New Program',
            'edit_item' => 'Edit Program',
            'all_items' => 'All Programs',
            'singular_name' => 'Programs'
        ),
        'menu_icon' => 'dashicons-awards'
    ));
    // Register post professors
    register_post_type('professor', array(
        'show_in_rest' => true,
        'supports' => array('title', 'editor', 'thumbnail'), // You can use custom-fields (This is amateur)
        'public' => true,
        'labels' => array(
            'name' => 'Professors',
            'add_new_item' => 'Add New Professor',
            'edit_item' => 'Edit Professor',
            'all_items' => 'All Professors',
            'singular_name' => 'Professors'
        ),
        'menu_icon' => 'dashicons-welcome-learn-more'
    ));
    // Register post notes
    register_post_type('note', array(
        'capability_type' => 'note',
        'map_meta_cap' => true,
        'show_in_rest' => true,
        'supports' => array('title', 'editor'), // You can use custom-fields (This is amateur)
        'public' => false,
        'show_ui' => true,
        'labels' => array(
            'name' => 'Notes',
            'add_new_item' => 'Add New Note',
            'edit_item' => 'Edit Note',
            'all_items' => 'All Notes',
            'singular_name' => 'Notes'
        ),
        'menu_icon' => 'dashicons-welcome-write-blog'
    ));
    // Register post likes
    register_post_type('like', array(
        'supports' => array('title'), // You can use custom-fields (This is amateur)
        'public' => false,
        'show_ui' => true,
        'labels' => array(
            'name' => 'Likes',
            'add_new_item' => 'Add New Like',
            'edit_item' => 'Edit Like',
            'all_items' => 'All Likes',
            'singular_name' => 'Likes'
        ),
        'menu_icon' => 'dashicons-heart'
    ));
}
// Run above
add_action('init', 'university_post_types'); // You can use this function to wordpress/wp-content/mu-plugins test.php for init wordpress in all themes.


// Adjust queries post types
function university_adjust_queries($query) {
    if (!is_admin() && is_post_type_archive('campus') && $query->is_main_query()) {
        $query->set('posts_per_page', -1);
    }

    if (!is_admin() && is_post_type_archive('program') && $query->is_main_query()) {
        $query->set('orderby', 'title');
        $query->set('order', 'ASC');
        $query->set('posts_per_page', -1);
    }

    if (!is_admin() AND is_post_type_archive('event') AND $query->is_main_query()) {
        $today = date('Ymd');
        $query->set('meta_key', 'event_date');
        $query->set('orderby', 'meta_value_num');
        $query->set('order', 'ASC');
        $query->set('meta_query', array(
            array(
              'key' => 'event_date',
              'compare' => '<=',
              'value' => $today,
              'type' => 'numeric'
            )
        ));
    }
}
// Run above
add_action('pre_get_posts', 'university_adjust_queries');


function universityMapKey($api) {
    $api['key'] = 'AIzaSyAbZo58pQy31J81-Spxk_L1hwm_LfZ5yZA';
    return $api;
}
// Run above
add_filter('acf/fields/google_map/api', 'universityMapKey');


// Redirect subscriber user to home after login

add_action('admin_init', 'redirectSubsToFronted');

function redirectSubsToFronted() {
    $currentUser = wp_get_current_user();
    if (count($currentUser->roles) == 1 and $currentUser->roles[0] == 'subscriber') {
        wp_redirect(site_url('/'));
        exit;
    }
}

// Hide admin bar in subscriber user

add_action('wp_loaded', 'noSubsAdminBar');

function noSubsAdminBar() {
    $currentUser = wp_get_current_user();
    if (count($currentUser->roles) == 1 and $currentUser->roles[0] == 'subscriber') {
        show_admin_bar(false);
    }
}


// Customize login screen

add_filter('login_headerurl', 'ourHeaderUrl');

function ourHeaderUrl() {
    return esc_url(site_url('/'));
}

// Manually css to login screen

add_action('login_enqueue_scripts', 'ourLoginCSS');

function ourLoginCSS() {
    wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('university_main_styles', get_stylesheet_uri());
}

// Manually title login screen

add_filter('login_headertitle', 'ourLoginTitle');

function ourLoginTitle() {
    return get_bloginfo('name');
}


// Force post type note to private

add_filter('wp_insert_post_data', 'makeNotePrivate', 10, 2);

function makeNotePrivate($data, $postarr) {
    
    if ($data['post_type'] == 'note') {
        // Limited posts
        if (count_user_posts(get_current_user_id(), 'note') > 4 and !$postarr['ID']) {
            die('You have reached your note limit.');
        }
        // Security notes
        $data['post_title'] = sanitize_textarea_field($data['post_title']);
        $data['post_content'] = sanitize_text_field($data['post_content']);
    }
    if ($data['post_type'] == 'note' and $data['post_status'] != 'trash') {
        $data['post_status'] = 'private';
    }
    return $data;
}


// Ignore folder or file for export and import in-one-wp migration plugin

add_filter('ai1wm_exclude_content_from_export', 'ignoreCertainFiles');

function ignoreCertainFiles($exclude_filters) {
    $exclude_filters[] = 'themes/fictional-university-theme/node-modules';
    return $exclude_filters;
}