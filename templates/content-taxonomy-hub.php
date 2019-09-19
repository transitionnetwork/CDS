<?php $term = (get_queried_object()); ?>

<main>
  <div class="container">
    <?php $post_author = get_the_author_meta('ID'); ?>
    <div class="row justify-content-between">
      <div class="col-12 col-lg-7">
        <h1><?php echo \Tofino\Helpers\title(); ?></h1>
        <ul class="meta">
          <li><strong>Status:</strong> <?php echo ucwords(get_field('status', $term)); ?>
        </ul>
        
        <?php echo get_field('hub_description', $term); ?>

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
        
        <h2>Initiatives</h2>
        <?php list_initiatives($initiatives); ?>

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
      </div>
 
      <div class="col-12 col-lg-4">
        <aside>
          <?php $map = get_field('map', $term); ?>
          <?php if($map) { ?>
            <div id="initiative-map" data-lat="<?php echo $map['lat']; ?>" data-lng="<?php echo $map['lng']; ?>" data-zoom="<?php echo $map['zoom']; ?>"></div>
          <?php } ?>
          
          <?php if (get_field('address_line_1', $term)) { ?>
            <label><?php _e('Location', 'tofino'); ?></label>
            <?php echo get_field('address_line_1', $term); ?><br/>
            <?php echo get_field('city', $term); ?><br/>
            <?php echo get_field('province', $term); ?><br/>
            <?php echo get_field('postal_code', $term); ?><br/>
            <?php echo get_term_by('id', get_field('country', $term), 'country')->name; ?><br/>
          <?php } else if($map) { ?>
            <?php foreach($map['markers'] as $marker) { ?>
              <label><?php _e('Location', 'tofino'); ?></label>
              <div id="marker-address" data-address="<?php echo $marker['default_label']; ?>"></div>
              <?php echo $marker['default_label']; ?>
            <?php } ?>
          <?php } ?>
          
          <?php if(get_field('logo', $term)) { ?>
            <p><img src="<?php echo get_field('logo', $term)['sizes']['large']; ?>"></p>
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
        </aside>
      </div>
    </div>
  </div>
</main>
