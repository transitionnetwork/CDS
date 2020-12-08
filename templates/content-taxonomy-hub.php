<?php $term = (get_queried_object()); ?>

<main>
  <div class="container">
    <?php $post_author = get_the_author_meta('ID'); ?>
    <div class="row justify-content-between">
      <div class="col-12 col-lg-7">
        <h1><?php echo \Tofino\Helpers\title(); ?></h1>
        
        <?php $status = get_field('status', $term); ?>
        <?php $status_color = get_status_tag($status); ?>
        <p><span class="btn-<?php echo $status_color; ?> btn-sm"><?php echo $status['label']; ?></span></p>
        
        <div class="mt-4"><?php echo get_field('hub_description', $term); ?></div>

        <?php if(is_user_role('administrator') || is_user_role('super_hub') || can_edit_hub($term->term_id)) { ?>
          <p><a class="btn btn-warning btn-sm" href="<?php echo add_query_arg('hub_id', $term->term_id, parse_post_link(5414)); ?>"><?php echo svg('pencil'); ?>Edit Hub</a></p>
        <?php } ?>

        <?php $args = array(
          'post_type' => 'initiatives',
          'fields' => 'ids',
          'posts_per_page' => -1,
          'tax_query' => array(
            array(
              'taxonomy' => 'hub',
              'field' => 'term_id',
              'terms' => $term->term_id
            )
          )
        ); 

        $initiatives = get_posts($args); ?>
        
        <?php if($initiatives) { ?>
          <div class="mt-5">
            <h2>Initiatives</h2>
            <?php list_initiatives($initiatives); ?>
          </div>
        <?php } ?>

      </div>
 
      <div class="col-12 col-lg-4">
        <aside>
          <?php $map = get_field('map'); ?>
          <?php set_query_var('map', $map); ?>
          <?php get_template_part('templates/partials/single-map'); ?>
          
          <?php if(get_field('logo', $term)) { ?>
            <p class="mt-4"><img src="<?php echo get_field('logo', $term)['sizes']['large']; ?>"></p>
          <?php } ?>

          <?php if (get_field('email', $term)) { ?>
            <label><?php echo get_field_object('email', $term)['label']; ?></label>
            <a href="mailto:<?php echo get_field('email', $term); ?>"><?php echo get_field('email', $term); ?></a>
          <?php } ?>

          <?php if(get_field('website', $term) || get_field('facebook', $term) || get_field('instagram', $term) || get_field('twitter', $term) || get_field('youtube', $term)) { ?>
            <label><?php _e('Links', 'tofino'); ?></label>
            <ul class="links">
              <?php if (get_field('website', $term)) { ?>
                <li><a href="<?php echo get_field('website', $term); ?>" target="_blank">Web</a></li>
              <?php } ?>
              <?php if (get_field('twitter', $term)) { ?>
                <li><a href="<?php echo get_field('twitter', $term); ?>" target="_blank"><?php echo svg('twitter'); ?></a></li>
              <?php } ?>
              <?php if (get_field('facebook', $term)) { ?>
                <li><a href="<?php echo get_field('facebook', $term); ?>" target="_blank"><?php echo svg('facebook'); ?></a></li>
              <?php } ?>
              <?php if (get_field('instagram', $term)) { ?>
                <li><a href="<?php echo get_field('instagram', $term); ?>" target="_blank"><?php echo svg('instagram'); ?></a></li>
              <?php } ?>
              <?php if (get_field('youtube', $term)) { ?>
                <li><a href="<?php echo get_field('youtube', $term); ?>" target="_blank"><?php echo svg('youtube'); ?></a></li>
              <?php } ?>
            </ul>
          <?php } ?>

          <?php $additional = get_field('additional_web_addresses', $term); 
          if($additional) { ?>
            <section>
              <h4><?php _e('More Links', 'tofino'); ?></h4>
              <ul>
                <?php foreach($additional as $item) { ?>
                  <li><a href="<?php echo $item['address']; ?>" target="_blank"><?php echo $item['label']; ?></a></li>
                <?php } ?>
              </ul>
            </section>
          <?php } ?>
          
        </aside>
      </div>
    </div>
  </div>
</main>
