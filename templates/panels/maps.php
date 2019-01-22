<section>
  <?php if(is_user_role('administrator') || is_user_role('super_hub')) {
    $terms = get_terms(array(
      'taxonomy' => 'hub'
    ));
  } else {
    $terms = get_the_terms(wp_get_current_user(), 'hub');
  } ?>
  <div class="row">
    <?php foreach($terms as $term) { ?>
      <div class="col-12">
        <h2>Initiative map for <a href="<?php echo get_post_type_archive_link('initiatives') . '?term=' . $term->term_id; ?>"><?php echo $term->name; ?></a></h2>
        <?php $url = add_query_arg(array('hub_id' => $term->term_id), get_the_permalink(438));
        ?>
        <iframe id="iframe_map" src="<?php echo $url; ?>"></iframe>
        <div class="panel">
          <p>To <strong>embed</strong> this map, copy and paste the HTML below:</p>
          <pre>&lt;iframe&nbsp;src&#61;&quot;<?php echo $url; ?>&quot;&nbsp;width&#61;&quot;100%&quot;&nbsp;height&#61;&quot;600px&quot;&gt;</pre>
          <p><a href="<?php echo $url ?>" class="btn btn-primary">View Map</a></p>
        </div>
      </div>
    <?php } ?>
  </div>
</section>
