<?php
$user_role = wp_get_current_user()->roles[0];
$user_human_role = ucwords(str_replace('_', ' ', $user_role));

if(is_user_role('hub')) {
  $hub_id = get_field('hub_user', wp_get_current_user());
  $hub_object = get_term_by('term_id', $hub_id, 'hub'); 
}
?>

<div class="user-details">
  <?php
  if(!is_user_logged_in()) { ?>
    <?php _e('Not logged in', 'tofino'); ?> | <a href="<?php echo parse_post_link(459); ?>"><?php _e('Sign In', 'tofino'); ?></a>
  <?php } else { ?>
    <?php if($user_role) : ?>
      <div class="tag role"><?php _e('Role', 'tofino'); ?>: <?php echo $user_human_role; ?></div>
    <?php endif; ?>

    <?php if($hub_object) : ?>
      <div class="tag hub"><?php echo $hub_object->name; ?></div>
    <?php endif; ?>

    <?php _e('Logged in as', 'tofino'); ?> <?php echo wp_get_current_user()->user_login; ?> | <a href="<?php echo wp_logout_url(home_url()); ?>"><?php _e('Logout', 'tofino'); ?></a>
  <?php } ?>

</div>

<div class="d-flex">
  <?php if(function_exists('pll_the_languages')) { ?>
    <div id="lang-switch" class="mr-2">
      <?php 
      $languages = pll_the_languages(array('raw' => 'true'));
      $languages_available = array();
      foreach($languages as $language) {
        if(!$language['no_translation'] || $language['slug'] == 'en') {
          $languages_available[] = $language;
        }
      }
      
      $current_lang_slug = pll_current_language();
      $current_lang_name = pll_current_language('name');
      $current_flag_url = $languages[$current_lang_slug]['flag'];
      ?>
      
      <?php if($languages_available) { ?>
        <ul class="current">
          <li><a class="selector" href="#" data-toggle="modal" data-target="#langModal"><img src="<?php echo $current_flag_url; ?>"><span style="margin-left: 0.3rem;"><?php echo $current_lang_name; ?></span></a></li>
        </ul>
      <?php } ?>
    </div>


    <?php if($languages_available) { ?>
      <div class="modal fade" id="langModal" tabindex="-1" role="dialog" aria-labelledby="langModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
          <div class="modal-content">
            <div class="modal-body">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
              <ul>
                <?php foreach($languages_available as $language) { ?>
                  <?php if(!$language['no_translation'] || $language['slug'] == 'en') { ?>
                    <?php if($current_lang_slug == 'en' && $language['slug'] == 'en') {
                      // fix url pointing to home when no translations exist for page
                      $language['url'] = the_permalink();
                    } ?>
                    <li><a href="<?php echo $language['url']; ?>"><img src="<?php echo $language['flag']; ?>"><span style="margin-left: 0.3rem;"><?php echo $language['name']; ?></span></a></li>
                  <?php } ?>
                <?php } ?>
              </ul>
            </div>
          </div>
        </div>
      </div>
    <?php } ?>
  <?php } ?>

  <span class="tag beta">beta</span>
</div>
