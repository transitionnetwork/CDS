<?php
use \Tofino\ThemeOptions\Notifications as n;

if (get_theme_mod('footer_sticky') === 'enabled') : ?>
  </div>
<?php endif; ?>

<footer>
  <div class="container">
    <div class="row">
      <div class="col-12 col-md-6">
        <p>&copy <?php echo date('Y'); ?> Transition Network</p>
      </div>
      <div class="col-12 col-md-6 text-md-right">
        <div id="footer-nav">
          <?php wp_nav_menu( array('theme_location' => 'footer_menu') ); ?>
        </div>
      </div>
    </div>
  </div>
</footer>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<?php wp_footer(); ?>

<?php n\notification('bottom'); ?>
</body>
</html>
