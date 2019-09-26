<?php function render_hub_filter() { ?>
  
  <div class="row">
    <div class="col-12 col-lg-6">
      <form action="<?php echo parse_post_link(5901); ?>" method="GET" class="filter">
        <div class="search-wrap">
          <input type="text" name="search" placeholder="Search initiatives..." value="<?php echo get_query_var('search'); ?>">
          <input type="submit" value="Search" class="btn btn-sm btn-primary">
        </div>
      </form>
    </div>
    <div class="col-12 col-lg-6 d-lg-flex flex-lg-column align-items-lg-end">
      <form action="<?php echo parse_post_link(5901); ?>" method="GET" class="filter">
        <?php $terms = get_terms('hub'); ?>
        <label for="hub_name"><?php _e('Hub:', 'tofino'); ?></label>
        <select name="hub_name" onchange="this.form.submit();">
          <option value="">All</option>
          <?php foreach ($terms as $term) { ?>
            <?php if (get_query_var('hub_name') == $term->slug) {
              $selected = 'selected';
            } else {
              $selected = '';
            } ?>
            <option value="<?php echo $term->slug; ?>" <?php echo $selected; ?>><?php echo $term->name . '&nbsp;(' . $term->count. ')'; ?></option>
          <?php } ?>
        </select>
      </form>
      <form action="<?php echo parse_post_link(5901); ?>" method="GET" class="filter">
        <?php $terms = get_terms('country'); ?>
        <div class="select-wrap">
          <label for="country"><?php _e('Country:', 'tofino'); ?></label>
          <select name="country" onchange="this.form.submit();">
            <option value="">All</option>
            <?php foreach ($terms as $term) { ?>
              <?php if (get_query_var('country') == $term->slug) {
                $selected = 'selected';
              } else {
                $selected = '';
              } ?>
              <option value="<?php echo $term->slug; ?>" <?php echo $selected; ?>><?php echo $term->name . '&nbsp;(' . $term->count. ')'; ?></option>
            <?php } ?>
          </select>
        </div>
      </form>
    </div>
  </div>
<?php } ?>
