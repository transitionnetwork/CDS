<div class="panel">
  <h3>Primary Editor</h3>
  <?php $post_author_id = get_the_author_meta('ID'); ?>
  <?php echo get_the_author_meta('display_name'); ?> <strong>[<?php echo $post_author_id; ?>]</strong><br/>
  <a href="mailto:<?php echo get_the_author_meta('user_email'); ?>"><?php echo get_the_author_meta('user_email'); ?></a>

  <div class="autologin-panel">
    <?php if (is_user_role(array('administrator') && get_user_meta($post_author_id, PKG_AUTOLOGIN_USER_META_KEY))) { ?>
      <h3>Autologin link</h3>
      <?php
      $link = 'https://' . $_SERVER['SERVER_NAME'] . '/account/?autologin_code=' . get_user_meta($post_author_id, PKG_AUTOLOGIN_USER_META_KEY, true) . '#nav-initiative-admin';
      ?>
      <span id="token-login-link"><?php echo $link; ?></span>
      <button id="token-login-button" alt="copy link"><?php echo svg('copy'); ?></button>
    <?php } else { ?>
      <form action="<?php the_permalink(); ?>" method="POST">
        <input type="hidden" name="generate_autologin_link" value="<?php echo $post_author_id; ?>">
        <button type="submit" class="btn btn-primary btn-sm">Generate autologin token</button>
      </form>
    <?php } ?>
  </div>
</div>

