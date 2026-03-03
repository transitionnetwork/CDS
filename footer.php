<?php
use \Tofino\ThemeOptions\Notifications as n;

if (get_theme_mod('footer_sticky') === 'enabled') : ?>
  </div>
<?php endif; ?>

<footer>
  <div class="container">
    <div class="row">
      <div class="col-12 col-md-6">
        &copy <?php echo date('Y'); ?> Transition Network
      </div>
      <div class="col-12 col-md-6 text-md-right">
        <div id="footer-nav">
          <?php wp_nav_menu( array('theme_location' => 'footer_nav') ); ?>
        </div>
      </div>
    </div>
  </div>
</footer>

<?php wp_footer(); ?>

<?php n\notification('bottom'); ?>
</body>
</html>
