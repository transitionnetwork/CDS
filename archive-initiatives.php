<?php use \Tofino\Helpers as h;
get_header(); ?>

<ul id="dom-target" style="display:none;">
  <?php foreach ($posts as $post) :
    generate_map($post);
  endforeach; ?>
</ul>
<div id="iframe_map"></div>
<div class="container">
  <?php render_hub_filter(); ?>
</div>
<main>
  <div class="container">
    <h1><?php echo $page_title ?></h1>
    <?php list_initiatives($posts); ?>
    <ul class="button-group">
      <li><a class="btn btn-primary" href="<?php echo get_permalink(13); ?>">Add new initiative</a></li>
    </ul>
  </div>
</main>

<?php get_footer(); ?>
