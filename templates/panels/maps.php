<section>
  <?php if(is_user_role('administrator') || is_user_role('super_hub')) {
    $terms = get_terms('hub');
  } else {
    $user_hub_ids = get_user_meta(wp_get_current_user()->ID, 'hub_user');
    foreach($user_hub_ids as $id) {
      $terms[] = get_term_by('id', $id, 'hub');
    }
  } ?>
  <div class="row">
    <?php foreach($terms as $term) { ?>
      <div class="col-12">
        <h2><?php _e('Initiative map', 'tofino'); ?> for <a href="<?php echo add_query_arg('hub_name', $term->slug, get_post_type_archive_link('initiatives')); ?>"><?php echo $term->name; ?></a></h2>
        <?php $url = add_query_arg(array('hub_id' => $term->term_id), get_the_permalink(438));
        ?>
        <iframe id="iframe_map" src="<?php echo $url; ?>"></iframe>
        <div class="panel">
          <p><?php _e('To embed this map, please copy and paste the HTML below', 'tofino'); ?>:</p>
          <pre>&lt;iframe&nbsp;src&#61;&quot;<?php echo $url; ?>&quot;&nbsp;width&#61;&quot;100%&quot;&nbsp;height&#61;&quot;600px&quot;&gt;</pre>
          <p><a href="<?php echo $url ?>" class="btn btn-primary"><?php echo svg('eye'); ?><?php _e('View Map', 'tofino'); ?></a></p>
        </div>
      </div>
    <?php } ?>
  </div>
</section>