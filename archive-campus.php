<?php get_header();

pageBanner(array(
  'title' => 'All Campuses',
  'subtitle' => 'The beautiful campus for location map in google map.'
));
 ?>

<div class="container container--narrow page-section">
<div class="acf-map">
  <?php while(have_posts()) {
    the_post();
    $locationMap = get_field('map_location');
    ?>
    <div class="marker" data-lat="<?php echo $locationMap['lat']; ?>" data-lng="<?php echo $locationMap['lng']; ?>">
      <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
      <?php echo $locationMap['address']; ?>
    </div>
    <?php
  }

   ?>
</div>

</div>



<?php get_footer(); ?>
