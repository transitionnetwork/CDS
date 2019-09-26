<main>
  <div class="container">
    <?php while (have_posts()) : the_post(); ?>
      <h1><?php echo \Tofino\Helpers\title(); ?></h1>
    </div>
    <div class="container">
      <div class="row">
        <div class="col-12 col-md-10 col-lg-6">
          <?php $hubs = get_terms('hub'); ?>
          <table class="item-list">
            <tr>
              <th>Hub Name</th>
              <th></th>
            </tr>
            <?php foreach ($hubs as $hub) { ?>
              <tr>
                <td>
                   <a href="<?php echo get_term_link($hub); ?>"><?php echo $hub->name; ?></a>
                </td>
                <td class="text-right">
                  <a class="btn btn-primary btn-sm" href="<?php echo get_term_link($hub); ?>"><?php echo svg('eye'); ?>View</a>
                </td>
              </tr>
            <?php } ?>
            </table>
          </div>
        <?php endwhile; ?>
      </div>
    </div>
  </div>
</main>
