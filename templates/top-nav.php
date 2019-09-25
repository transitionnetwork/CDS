<?php
$user_role = wp_get_current_user()->roles[0];
$user_human_role = ucwords(str_replace('_', ' ', $user_role));

if(is_user_role('hub')) {
  $hub_id = get_field('hub_user', wp_get_current_user());
  $hub = get_term_by('term_id', $hub_id, 'hub'); 
}
?>

<div class="user-details">
  <?php
  if(is_user_logged_in()) : ?>
    <?php _e('Logged in as', 'tofino'); ?> <?php echo wp_get_current_user()->user_login; ?>
  <?php else : ?>
    <?php _e('Not logged in', 'tofino'); ?> | <a href="<?php echo get_the_permalink(459); ?>"><?php _e('Login', 'tofino'); ?></a>
  <?php endif; ?>

  <?php if($user_role) : ?>
    <div class="tag role"><?php _e('Role', 'tofino'); ?>: <?php echo $user_human_role; ?></div>
  <?php endif; ?>

  <?php if($hub) : ?>
    <div class="tag hub"><?php echo $hub->name; ?></div>
  <?php endif; ?>

  <span class="tag beta">beta</span>
</div>

<?php if(function_exists('pll_the_languages')) { ?>
  <div id="lang-switch">
    <?php $languages = pll_the_languages(array('raw' => 'true')); ?>
    <?php $current_lang_slug = pll_current_language(); ?>
    <?php $current_lang_name = pll_current_language('name'); ?>
    <?php $current_flag_url = $languages[$current_lang_slug]['flag']; ?>
    <ul class="current">
      <li><a class="selector" href="#" data-toggle="modal" data-target="#langModal"><img src="<?php echo $current_flag_url; ?>"><span style="margin-left: 0.3rem;"><?php echo $current_lang_name; ?></span></a></li>
    </ul>
  </div>
  
  <div class="modal fade" id="langModal" tabindex="-1" role="dialog" aria-labelledby="langModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-body">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <ul>
            <?php pll_the_languages(array('show_flags' => true, 'hide_if_no_translation' => true)); ?>
          </ul>
        </div>
      </div>
    </div>
  </div>
<?php } ?>
