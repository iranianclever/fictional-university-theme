<?php get_header();

pageBanner();

?>

    <div class="container container--narrow page-section">

      <?php
        $theParent = wp_get_post_parent_ID(get_the_ID());
        if ($theParent) {
      ?>
      <div class="metabox metabox--position-up metabox--with-home-link">
        <p>
          <a class="metabox__blog-home-link" href="<?php echo get_the_permalink($theParent); ?>"><i class="fa fa-home" aria-hidden="true"></i> Back to <?php echo get_the_title($theParent); ?></a> <span class="metabox__main"><?php echo get_the_title(); ?></span>
        </p>
      </div>
      <?php
      }
      ?>

      <?php
        $testArray = get_pages(array(
          'child_of' => get_the_ID()
        ));
        if ($theParent or $testArray) {
      ?>
      <div class="page-links">
        <h2 class="page-links__title"><a href="<?php echo get_the_permalink($theParent); ?>"><?php echo get_the_title($theParent); ?></a></h2>
        <ul class="min-list">
          <?php
            if ($theParent) {
              $findChildrenOf = $theParent;
            } else {
              $findChildrenOf = get_the_ID();
            }
            wp_list_pages(array(
              'title_li' => NULL,
              'child_of' => $findChildrenOf,
              'sort_column' => 'menu_order'
            ));
          ?>
        </ul>
      </div>
      <?php } ?>

      <div class="generic-content">
        <?php the_content();

        $skyColorValue = sanitize_text_field(get_query_var('skyColor'));
        $grassColorValue = sanitize_text_field(get_query_var('grassColor'));
        
        if ($skyColorValue == 'blue' && $grassColorValue == 'green') {
          echo '<p>The sky is blue today and grass is green. Life is good.</p>';
        }
        
        ?>
        <!-- Get or Post -->
        <form method="get">
          <input name="skyColor" placeholder="Sky Color">
          <input name="grassColor" placeholder="Grass Color">
          <button>Submit</button>
        </form>
      </div>
    </div>

<?php get_footer(); ?>