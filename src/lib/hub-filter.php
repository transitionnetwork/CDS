<?php
function render_hub_filter() {
  $terms = get_terms('hub'); ?>
  <form action="<?php echo home_url('list-initiatives'); ?>" method="GET" id="hub-filter">
    <?php _e('Filter by hub:'); ?>
    <select name="hub_name" id="term" onchange="this.form.submit();">
      <option value="">All</option>
      <?php foreach ($terms as $term) { ?>
        <?php if (get_query_var('hub_name') == $term->slug) {
          $selected = 'selected';
        } else {
          $selected = '';
        } ?>
        <option value="<?php echo $term->slug; ?>" <?php echo $selected; ?>><?php echo $term->name . '&nbsp;(' . $term->count. ')'; ?></option>
      } ?>
      <?php 
    } ?>
    </select>
  </form>
<?php } ?>
