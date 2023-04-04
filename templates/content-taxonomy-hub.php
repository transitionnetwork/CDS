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

        <?php if(is_user_role(array('super_hub', 'administrator')) || can_edit_hub($term->term_id)) { ?>
          <p><a class="btn btn-warning btn-sm" href="<?php echo add_query_arg('hub_id', $term->term_id, parse_post_link(5414)); ?>"><?php echo svg('pencil'); ?>Edit Hub</a></p>
        <?php } ?>

        <?php if(is_user_logged_in() && !is_user_role(array('super_hub', 'hub', 'administrator'))) { ?>
          <?php if(!is_hub_access_requested($term->term_id)) { ?>
            <form action="" method="post">
              <button class="btn btn-secondary btn-sm" name="request_access" value="<?php echo $term->term_id; ?>"><?php echo svg('plus'); ?>Request admin access to hub</button>
            </form>
          <?php } else { ?>
            <p><strong>Hub admin access has been requested</strong></p>
          <?php } ?>
        <?php } ?>
      </div>
 
      <div class="col-12 col-lg-4">
        <aside>
          <?php $map = get_field('map', $term); ?>
          <?php set_query_var('map', $map); ?>
          <?php get_template_part('templates/partials/single-map'); ?>
          
          <div class="panel">
            <?php if(get_field('logo', $term)) { ?>
              <img src="<?php echo get_field('logo', $term)['sizes']['large']; ?>">
            <?php } ?>
  
            <?php if (get_field('email', $term)) { ?>
              <h3 class="mt-3"><?php echo get_field_object('email', $term)['label']; ?></h3>
              <a href="mailto:<?php echo get_field('email', $term); ?>"><?php echo get_field('email', $term); ?></a>
            <?php } ?>
  
            <?php if(get_field('website', $term) || get_field('facebook', $term) || get_field('instagram', $term) || get_field('twitter', $term) || get_field('youtube', $term)) { ?>
              <h3 class="mt-3"><?php _e('Links', 'tofino'); ?></h3>
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
          </div>

          <?php $additional = get_field('additional_web_addresses', $term); 
          if($additional) { ?>
            <section>
              <h3><?php _e('More Links', 'tofino'); ?></h3>
              <ul>
                <?php foreach($additional as $item) { ?>
                  <li><a href="<?php echo $item['address']; ?>" target="_blank"><?php echo $item['label']; ?></a></li>
                <?php } ?>
              </ul>
            </section>
          <?php } ?>
        </aside>
      </div>

      <div class="col-12">

        <h2>Groups</h2>
        
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

        $init_query = new WP_Query($args);
        set_query_var('init_query', $init_query);
        get_template_part('templates/tables/initiatives');

        ?>
      </div>
    </div>
  </div>
</main>
