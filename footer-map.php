<?php
use \Tofino\ThemeOptions\Notifications as n;

if (get_theme_mod('footer_sticky') === 'enabled') : ?>
  </div>
<?php endif; ?>

<?php wp_footer(); ?>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/iframe/maps.js"></script>

<?php n\notification('bottom'); ?>
</body>
</html>
