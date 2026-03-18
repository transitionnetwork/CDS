<?php
use \Tofino\ThemeOptions\Notifications as n;

if (get_theme_mod('footer_sticky') === 'enabled') : ?>
  </div>
<?php endif; ?>

<footer>
  <div class="container">
    <div class="flex flex-col md:flex-row md:justify-between gap-4">
      <div>
        &copy <?php echo date('Y'); ?> Transition Network
      </div>
      <div>
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
