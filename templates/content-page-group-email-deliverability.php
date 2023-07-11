<?php
$last_mail_event = get_query_var( 'last_mail_event');
if(!$last_mail_event) {
  $last_mail_event = 'delivered';
}
?>

<?php $mail_events = array(
  'failure',
  'delivered',
  'opens',
  'clicks'
); ?>

<?php 
$args = array(
  'post_type' => 'initiatives',
  'posts_per_page' => -1,
  'fields' => 'ids',
  'meta_query' => array(
    array(
      'key' => 'last_mail_event',
      'value' => $last_mail_event
    )
  )
);

$init_query = new WP_Query($args); ?>

<section>
  <ul>
    <?php foreach($mail_events as $event) { ?>
      <li><a href="<?php echo add_query_arg( 'last_mail_event', $event, the_permalink() ); ?>"><?php echo $event; ?></a></li>
    <?php } ?>
  </ul>
  
  <h2>Last mail event: <?php echo $last_mail_event; ?></h2>

  <?php
    set_query_var('init_query', $init_query);
    get_template_part('templates/tables/initiatives');
  ?>

</section>

