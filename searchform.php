<form role="search" method="get" id="searchform" action="<?php echo home_url( '/' ); ?>" class="flex items-center gap-2">
  <label class="sr-only" for="s">Search for:</label>
  <input type="text" value="<?php echo get_query_var('s'); ?>" name="s" id="s" placeholder="Group name" class="input input-bordered flex-1 m-0" />
  <input type="submit" id="searchsubmit" value="Search groups" class="btn btn-primary" />
</form>
