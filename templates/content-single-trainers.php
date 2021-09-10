<?php acf_form_head(); ?>

<?php if(get_query_var('updated')) { ?>
  <div class="container">
    <div class="alert top alert-success">
      <?php _e('This trainer has been updated', 'tofino'); ?>
    </div>
  </div>
<?php } ?>

<?php if(get_query_var('added_post')) { ?>
  <div class="container">
    <div class="alert top alert-success">
      <?php _e('This trainer has been added', 'tofino'); ?>
    </div>
  </div>
<?php } ?>

<?php while (have_posts()) : the_post(); ?>
  <?php if(get_post_status() === 'pending') { ?>
    <div class="container">
      <div class="alert top alert-warning">
        <?php _e('This trainer is awaiting approval by a member of the training admin team.', 'tofino'); ?>
      </div>
    </div>
  <?php } ?>
  
  <main>
    <div class="container">
      <?php $post_author = get_the_author_meta('ID'); ?>
      <div class="row justify-content-between">
        <div class="col-12 col-lg-7">
          <h1><?php echo \Tofino\Helpers\title(); ?></h1>
          <?php $formats = get_list_terms('trainer_type'); ?>
          <?php $languages = get_list_terms('trainer_language'); ?>
          <?php $topics = get_list_terms('trainer_topic'); ?>
          <?php $countries = get_list_terms('country'); ?>

          <ul class="meta">
            <?php if($formats) { ?>
              <li><strong>Traing Formats: </strong> <?php echo $formats; ?></li>
            <?php } ?>
            <?php if($languages) { ?>
              <li><strong>Languages: </strong> <?php echo $languages; ?></li>
            <?php } ?>
            <?php if($topics) { ?>
              <li><strong>Topics: </strong> <?php echo $topics; ?></li>
            <?php } ?>
            <?php if($countries) { ?>
              <li><strong>Countries: </strong> <?php echo $countries; ?></li>
            <?php } ?>
          </ul>

          <?php $field_names = array(
            'training_information_training_format_detail' => 'Detail of coaching formats offered',
            'training_information_training_regions' => 'Regional Focus',
            'training_information_training_other_organisations' => 'Training undertaken by trainer',
            'your_website_listing_training_bio' => 'Biography'
          ); ?>

          <?php foreach($field_names as $field_name => $field_label) {
            if(get_field($field_name)) { ?>
            <div class="mt-4">
              <?php if($field_label) { ?>
                <h3 class="normal-size"><strong><?php echo $field_label; ?></strong></h3>
              <?php } ?>
              <div class="mt-1">
                <?php echo get_field($field_name); ?>
              </div>
            </div>
            <?php }
          } ?>
          
          <?php if(is_user_trainer_admin()) { ?>
            <div class="mt-3">
              <p><a href="<?php echo add_query_arg('edit_post', get_the_ID(), parse_post_link(6741)); ?>" class="btn btn-dark btn-sm"><?php echo svg('pencil'); ?><?php _e('Edit', 'tofino'); ?></a></p>
          
              <?php get_template_part('templates/partials/form-toggle-trainer-state'); ?>
            </div>
          <?php } ?>
        </div>

        <div class="col-12 col-lg-4">
          <aside>
            <?php $training_photo = get_field('your_website_listing_training_photo'); ?>
            <div>
              <?php if($training_photo) { ?>
                <img src="<?php echo $training_photo['sizes']['large']; ?>">
              <?php } ?>
            </div>
            
            <?php $map = get_field('map'); ?>
            <?php set_query_var('map', $map); ?>
            <?php if($map) { ?>
              <div class="mt-4">
                <?php get_template_part('templates/partials/single-map'); ?>
              </div>
            <?php } ?>
            
  
            <?php if (get_field('email')) { ?>
              <label><?php echo get_field_object('email')['label']; ?></label>
              <a href="mailto:<?php echo get_field('email'); ?>"><?php echo get_field('email'); ?></a>
            <?php } ?>

            <?php if(get_field('website') || get_field('facebook') || get_field('instagram') || get_field('twitter') || get_field('youtube')) { ?>
              <label><?php _e('Links', 'tofino'); ?></label>
              <ul class="links">
                <?php if (get_field('website')) { ?>
                  <li><a href="<?php echo get_field('website'); ?>" target="_blank">Web</a></li>
                <?php } ?>
                <?php if (get_field('twitter')) { ?>
                  <li><a href="<?php echo get_field('twitter'); ?>" target="_blank"><?php echo svg('twitter'); ?></a></li>
                <?php } ?>
                <?php if (get_field('facebook')) { ?>
                  <li><a href="<?php echo get_field('facebook'); ?>" target="_blank"><?php echo svg('facebook'); ?></a></li>
                <?php } ?>
                <?php if (get_field('instagram')) { ?>
                  <li><a href="<?php echo get_field('instagram'); ?>" target="_blank"><?php echo svg('instagram'); ?></a></li>
                <?php } ?>
                <?php if (get_field('youtube')) { ?>
                  <li><a href="<?php echo get_field('youtube'); ?>" target="_blank"><?php echo svg('youtube'); ?></a></li>
                <?php } ?>
              </ul>
            <?php } ?>

            <?php $additional = get_field('additional_web_addresses'); 
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
<?php endwhile; ?>
