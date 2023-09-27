<?php if (is_user_role(array('administrator', 'super_hub', 'hub'))) { ?>  <?php if(get_query_var('added_note')) { ?>
    <div class="container">
      <div class="alert top alert-success">
        <?php _e('Your note has been added', 'tofino'); ?>
      </div>
    </div>
  <?php } ?>

  <?php $mail_events = array(
    'failure',
    'delivered',
    'opens',
    'clicks'
  ); ?>

  <?php
  $selected_last_mail_event = get_query_var( 'last_mail_event');
  $selected_hub = get_query_var('hub_name');
  ?>

  <main>
    <form action="<?php echo get_the_permalink(); ?>" method="GET" id="map-filter">
      <div class="container-fluid">
        <div class="panel">
          <div class="row">
            <div class="col-12 col-md-3 filter-col filter-item">
              <label>Last mail event for primary author:</label>
              <select name="last_mail_event" onchange="this.form.submit()">
                <option value="">Any</option>
                <?php foreach($mail_events as $mail_event) { ?>
                  <?php $selected = ($selected_last_mail_event === $mail_event) ? 'selected' : ''; ?>
                  <option value="<?php echo $mail_event; ?>" <?php echo $selected; ?>><?php echo ucwords($mail_event); ?></option>
                <?php } ?>
              </select>
            </div>
            <?php if (!is_user_role('hub'))  { ?>
              <div class="col-12 col-md-3 filter-col filter-item">
                <?php $hubs = get_terms('hub'); ?>
                <label>Hub:</label>
                <select name="hub_name" onchange="this.form.submit()">
                  <option value="">All</option>
                  <?php foreach($hubs as $hub) { ?>
                    <?php $selected = ($selected_hub === $hub->slug) ? 'selected' : ''; ?>
                    <option value="<?php echo $hub->slug; ?>" <?php echo $selected; ?>><?php echo $hub->name; ?></option>
                  <?php } ?>
                </select>
              </div>
            <?php } ?>
          </div>
        </div>
      </div>
    </form>

  <?php 

  $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

  $args = array(
    'post_type' => 'initiatives',
    'posts_per_page' => 50,
    'fields' => 'ids',
    'paged' => $paged,
    'meta_key' => 'last_mail_date',
    'orderby' => 'meta_value',
    'order' => 'DESC'
  );  

  if($selected_last_mail_event) {
    $args['meta_query'] = array(
      array(
        'key' => 'last_mail_event',
        'value' => $selected_last_mail_event
      )
    );
  } else {
    $args['meta_query'] = array(
      array(
        'key' => 'last_mail_event',
        'value' => $mail_events
      )
    );
  }

  if($selected_hub) {
    var_dump($selected_hub);
    $args['tax_query'] = array(
      array (
        'taxonomy' => 'hub',
        'field' => 'slug',
        'terms' => $selected_hub
      )
    );
  } else if (is_user_role('hub')) {
    $user_id = get_current_user_id();
    $hub = get_user_hub_slug($user_id);

    $args['tax_query'] = array(
      array (
        'taxonomy' => 'hub',
        'field' => 'slug',
        'terms' => $hub
      )
    );
  }

  $init_query = new WP_Query($args); ?>

  <div class="container-fluid">
    <?php if($selected_last_mail_event) { ?>
      <h2>Last Email: <?php echo ucwords($selected_last_mail_event); ?></h2>
    <?php } else { ?>
      <h2>Last Email: All Events</h2>
    <?php } ?>
  </div>

  <section>
    <?php
      set_query_var('init_query', $init_query);
      get_template_part('templates/tables/initiatives');
    ?>
  </section>


  </main>
<?php } else {
  wp_redirect(esc_url(add_query_arg('error_code', '1', '/error')));
  exit;
} ?>
