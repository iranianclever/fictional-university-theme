<?php get_header();

pageBanner(array(
  'title' => 'Past Events',
  'subtitle' => 'A recap of our past events.'
));
?>

<div class="container container--narrow page-section">
  <?php
    $today = date('Ymd');
    $pastEvents = new wp_Query(array(
        'paged' => get_query_var('paged', 1),
        'posts_per_page' => 1, // -1 For show all posts
        'post_type' => 'event',
        'meta_key' => 'event_date',
        // 'category_name' => 'Awards' // Get specific category post without post type
        'orderby' => 'meta_value_num', // post_date, title, rand (alphabetically)
        'order' => 'ASC', // Descending
        'meta_query' => array(
            array(
                'key' => 'event_date',
                'compare' => '<',
                'value' => $today,
                'type' => 'numeric'
            )
        )
    ));
  while($pastEvents->have_posts()) {
    $pastEvents->the_post();
    get_template_part('/template-parts/content', 'event');
  }

  echo paginate_links(array(
      'total' => $pastEvents->max_num_pages
  ));

   ?>
</div>



<?php get_footer(); ?>
