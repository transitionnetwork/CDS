<section>
  <?php if(is_user_role('administrator') || is_user_role('super_hub')) {
    $terms = get_terms('hub');
  } else {
    $user_hub_ids = get_user_meta(wp_get_current_user()->ID, 'hub_user');
    foreach($user_hub_ids as $id) {
      $terms[] = get_term_by('id', $id, 'hub');
    }
  } ?>
  <h2>Global Map</h2>
  <div class="panel">
    <?php $url = get_the_permalink(438); ?>
    <iframe src="<?php echo $url; ?>" frameborder="0" width="100%" height="550px;" style="display:block;"></iframe>
    <p><?php _e('To embed this map, please copy and paste the HTML below', 'tofino'); ?>:</p>
    <pre>&lt;iframe src=&quot;<?php echo $url; ?>&quot; width="100%&quot; height=&quot;550px&quot; frameBorder=&quot;0&quot; style=&quot;display: block;&quot; allow=&quot;geolocation &apos;src&apos;&quot;&gt;&lt;/iframe&gt;</pre>
    <p><a href="<?php echo $url ?>" class="btn btn-primary btn-sm" target="_blank"><?php echo svg('eye'); ?><?php _e('Preview Map', 'tofino'); ?></a></p>
  </div>
  
  <?php foreach($terms as $term) { ?>
    <h2><?php _e('Group map', 'tofino'); ?> for <a href="<?php echo add_query_arg('hub_name', $term->slug, get_the_permalink(5901)); ?>"><?php echo $term->name; ?></a></h2>
    
    <?php $url = add_query_arg(array('hub_name' => $term->slug), get_the_permalink(438));
    ?>
    <div class="panel">
      <p><?php _e('To embed this map, please copy and paste the HTML below', 'tofino'); ?>:</p>
      <pre>&lt;iframe src=&quot;<?php echo $url; ?>&quot; width="100%&quot; height=&quot;550px&quot; frameBorder=&quot;0&quot; style=&quot;display: block;&quot; allow=&quot;geolocation &apos;src&apos;&quot;&gt;&lt;/iframe&gt;</pre>
      <p><a href="<?php echo $url ?>" class="btn btn-primary btn-sm" target="_blank"><?php echo svg('eye'); ?><?php _e('Preview Map', 'tofino'); ?></a></p>
    </div>
  <?php } ?>
</section>


<iframe style="display: block; max-width: 100%; max-height: 6270px;" src="https://maps.transitionnetwork.org/map/?hub_name=croatia" width="100%" height="550px" frameborder="0" scrolling="yes" class="iframe-class" allow="geolocation 'src'"></iframe>
  