<form role="search" method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">
  <label class="sr-only" for="s">Search for:</label>
  <div class="flex gap-2">
    <input type="text" value="<?php echo get_query_var('s'); ?>" name="s" id="s" placeholder="Group name"
      class="grow p-2 rounded border border-gray-400" />
    <input type="submit" id="searchsubmit" value="Search" />
  </div>
</form>
