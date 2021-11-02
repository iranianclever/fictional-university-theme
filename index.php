<?php get_header();

pageBanner(array(
  'title' => 'Welcome to our Blog!',
  'subtitle' => 'Display latest blog post in here.'
));
 ?>


<div class="container container--narrow page-section">
  <?php while(have_posts()) {
    the_post();
    ?>
    <div class="post-item">
      <h2 class="headline headline--medium headline--post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
      <div class="metabox">
        <p>Posted By <?php the_author_posts_link(); ?> on <?php the_time('d.m.Y'); ?> in <?php echo get_the_category_list(', '); ?></p>
      </div>
      <div class="generic-content">
        <?php the_excerpt(); ?>
        <p><a href="<?php the_permalink(); ?>" class="btn btn--blue">Continue Reading &raquo;</a></p>
      </div>
    </div>
    <?php
  }

  echo paginate_links();

   ?>
</div>



<?php get_footer(); ?>
