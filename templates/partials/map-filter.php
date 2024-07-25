<div id="map-filter">
  <div class="container-fluid">
    <div class="row map-filter-row">
      <div class="filter-col">
        <div id="filter-type" class="filter-item">
          <label for="type_name"><?php _e('Show', 'tofino'); ?></label>
          <select name="type_name" id="type_name">
            <option value="1" <?php echo (get_query_var('type') === "1") ? 'selected' : null; ?>>All</option>
            <option value="2" <?php echo (get_query_var('type') === "2") ? 'selected' : null; ?>>Groups</option>
            <option value="3" <?php echo (get_query_var('type') === "3") ? 'selected' : null; ?>>Hubs</option>
            <option value="4" <?php echo (get_query_var('type') === "4") ? 'selected' : null; ?>>Trainers</option>
          </select>
        </div>
      </div>
      <div class="filter-col">
        <?php $terms = get_terms('hub'); ?>
        <?php $hub_name = get_query_var('hub_name'); ?>
        <?php // deal with legacy embeds and check 
        if(get_query_var('hub_id')) {
          $hub_ids = explode(',', get_query_var('hub_id'));

          //dont set select for multiple values
          if(count($hub_ids) === 1) {
            $hub_name = get_term_by('id', get_query_var('hub_id'), 'hub')->slug;
          }
        } ?>

        <div id="filter-hub" class="filter-item">
          <label for="hub_name"><?php _e('Hub', 'tofino'); ?></label>
          <select name="hub_name" id="hub_name">
            <option value="">All</option>
            
            <?php foreach ($terms as $term) { ?>
              <?php if ($hub_name === $term->slug) {
                $selected = 'selected';
              } else {
                $selected = '';
              } ?>
              <option value="<?php echo $term->slug; ?>" <?php echo $selected; ?>><?php echo $term->name . '&nbsp;(' . $term->count. ')'; ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
      <div class="filter-col">
        <?php $terms = get_terms('country'); ?>
        <div id="filter-country" class="filter-item">
          <label for="country"><?php _e('Country', 'tofino'); ?></label>
          <select name="country" id="country">
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
      </div>
      <div class="filter-col">
        <div id="recent-toggle" class="filter-item">
          <input type="checkbox" id="show_recent" name="show_recent" <?php echo (get_query_var('show_recent')) ? "checked" : null; ?>>
          <label for="show_recent"><?php _e('Only show recently active groups'); ?></label>
        </div>
      </div>
      <div class="filter-col">
        <div id="training-toggle" class="filter-item">
          <input type="checkbox" id="training" name="training" <?php echo (get_query_var('training')) ? "checked" : null; ?>>
          <label for="training"><?php _e('Only show hubs that offer training'); ?></label>
        </div>
      </div>

    </div>
  </div>
</div>
