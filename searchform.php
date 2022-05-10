<form role="search" method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">
  <div><label class="sr-only" for="s">Search for:</label>
    <input type="text" value="<?php echo get_query_var('s'); ?>" name="s" id="s" placeholder="Group name" /><br/>
    <input type="submit" id="searchsubmit" value="Search groups" />
  </div>
</form>
