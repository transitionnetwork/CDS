<section>
      <?php if (!is_user_role('administrator')) { ?>
        <h2>Initiative map for <?php echo get_the_terms(wp_get_current_user(), 'hub')[0]->name; ?></h2>
        <?php $term_id = get_the_terms(wp_get_current_user(), 'hub')[0]->term_id; ?>
        <?php $url = add_query_arg(array('hub_id' => $term_id), get_the_permalink(438));
        ?>
        <iframe id="iframe_map" src="<?php echo $url; ?>"></iframe>
        <div class="panel">
          <p>To <strong>embed</strong> this map, copy and paste the HTML below:</p>
          <pre>&lt;iframe&nbsp;src&#61;&quot;<?php echo $url; ?>&quot;&nbsp;width&#61;&quot;100%&quot;&nbsp;height&#61;&quot;600px&quot;&gt;</pre>
          <p><a href="<?php echo $url ?>" class="btn btn-primary">View Map</a></p>
        </div>
      <?php } else { ?>
        <h2>No hub map is associated with admin accounts</h2>
        <p>Please log in with a non-admin account to use maps. A map must be      associated with a hub.</p>
      <?php } ?>
    </section>
