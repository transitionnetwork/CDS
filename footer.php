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

  </div><!-- /.drawer-content -->

  <!-- Mobile sidebar -->
  <div class="drawer-side z-1100">
    <label for="mobile-nav-drawer" aria-label="close sidebar" class="drawer-overlay"></label>
    <?php
    if (has_nav_menu('primary_nav')) :
      wp_nav_menu([
        'menu'            => 'nav_menu',
        'theme_location'  => 'primary_nav',
        'depth'           => 2,
        'container'       => '',
        'container_class' => '',
        'container_id'    => '',
        'menu_class'      => 'menu bg-base-200 min-h-full w-72 p-4 pt-20 text-base',
        'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
        'walker'          => new Tofino\Nav\NavWalker()
      ]);
    endif; ?>
  </div>
</div><!-- /.drawer -->

<?php wp_footer(); ?>

<?php n\notification('bottom'); ?>
</body>
</html>
