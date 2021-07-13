<?php $terms = get_terms('hub'); ?>
<div id="filter-type">
  <label for="type_name"><?php _e('Show:', 'tofino'); ?></label>
  <select name="type_name">
    <option value="1" <?php echo (get_query_var('type') === "1") ? 'selected' : null; ?>>All</option>
    <option value="2" <?php echo (get_query_var('type') === "2") ? 'selected' : null; ?>>Initiatives</option>
    <option value="3" <?php echo (get_query_var('type') === "3") ? 'selected' : null; ?>>Hubs</option>
  </select>
</div>

<?php $terms = get_terms('hub'); ?>
<div id="filter-hub" class="select-wrap">
  <label for="hub_name"><?php _e('Hub:', 'tofino'); ?></label>
  <select name="hub_name">
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
</div>

<?php $terms = get_terms('country'); ?>
<div id="filter-country" class="select-wrap">
  <label for="country"><?php _e('Country:', 'tofino'); ?></label>
  <select name="country">
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

<div id="training-toggle">
  <input type="checkbox" id="training" name="training">
  <label for="training"><?php _e('Only show hubs that offer training'); ?></label>
</div>

<form action="" method="GET" class="filter">
  <div class="search-wrap">
    <input type="text" name="search" placeholder="Search initiatives..." value="<?php echo get_query_var('search'); ?>">
    <input type="submit" value="Search" class="btn btn-sm btn-primary">
  </div>
</form>
