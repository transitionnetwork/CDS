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
  
  <?php $gen_info = get_field('general_information'); ?>

  <main>
    <div class="container">
      <?php $post_author = get_the_author_meta('ID'); ?>
      <div class="flex flex-col lg:flex-row gap-6 lg:items-start">
        <div class="w-full lg:w-7/12">
          <h1><?php echo \Tofino\Helpers\title(); ?></h1>
          <?php $languages = get_list_terms('trainer_language'); ?>
          <?php $topics = get_list_terms('trainer_topic'); ?>
          <?php $countries = get_list_terms('country'); ?>
          <?php $regions = get_field('additional_information_trainer_regions'); ?>

          <div class="panel">
            <ul class="space-y-2">
              <?php if($languages) { ?>
                <li><strong>Languages:</strong>&nbsp;<?php echo $languages; ?></li>
              <?php } ?>
              <?php if($topics) { ?>
                <li><strong>Topics:</strong>&nbsp;<?php echo $topics; ?></li>
              <?php } ?>
              <?php if($countries) { ?>
                <li><strong>Countries:</strong>&nbsp;<?php echo $countries; ?></li>
              <?php } ?>
              <?php if($regions) { ?>
                <li><strong>Regions:</strong>&nbsp;<?php echo $regions; ?></li>
              <?php } ?>
            </ul>
          </div>

          <?php $field_names = array(
            'general_information_trainer_bio',
          ); ?>

          <?php foreach($field_names as $field_name) {
            if(get_field($field_name)) { ?>
            <div class="mt-6">
              <h3><?php echo get_field_object($field_name)['label']; ?></h3>
              <div class="mt-1 rich-text">
                <?php echo get_field($field_name); ?>
              </div>
            </div>
            <?php }
          } ?>
          
          <?php if(is_user_trainer_admin()) { ?>
            <div class="mt-12">
              <p><a href="<?php echo add_query_arg('edit_post', get_the_ID(), get_the_permalink(6741)); ?>" class="btn btn-dark btn-sm"><?php echo svg('pencil'); ?><?php _e('Edit', 'tofino'); ?></a></p>
          
              <?php get_template_part('templates/partials/form-toggle-trainer-state'); ?>
            </div>
          <?php } ?>

          <div class="mt-12">
            <h3>Contact <?php the_title(); ?></h3>
            <div id="trainer-name" class="hidden" data-name="<?php the_title(); ?>"></div>
            <div id="trainer-email" class="hidden" data-email="<?php echo get_field('general_information_email'); ?>"></div>
            <?php echo do_shortcode('[contact-form-7 id="8688" title="Trainer Contact Form"]'); ?>
          </div>

          <?php if(is_user_logged_in() && is_user_role(array('super_hub', 'administrator'))) { ?>
            <?php get_template_part('templates/panels/email-history'); ?>
          <?php } ?>
        </div>

        <div class="w-full lg:w-5/12">
          <aside>
            <?php $training_photo = get_field('general_information_trainer_photo'); ?>
            <div>
              <?php if($training_photo) { ?>
                <img src="<?php echo $training_photo['sizes']['large']; ?>">
              <?php } ?>
            </div>

            <?php $map = get_field('general_information_location')['map']; ?>
            <?php set_query_var('map', $map); ?>
            <?php if(!empty($map)) { ?>
              <div class="mt-6">
                <?php get_template_part('templates/partials/single-map'); ?>
              </div>
            <?php } ?>

            <?php $website = get_field('general_information_your_website'); ?>
            <?php if($website) { ?>
              <div class="panel">
                <div class="mt-4">
                  <h3><?php _e('Website', 'tofino'); ?></h3>
                  <div><a href="<?php echo $website; ?>" target="_blank"><?php echo $website; ?></a></div>
                </div>
              </div>
            <?php } ?>

          </aside>
        </div>
      </div>
      
    </div>
  </main>
<?php endwhile; ?>
