<?php
$days_since_author_login = (get_field('inactive_authors_days_since_author_login', 'options')) ? get_field('inactive_authors_days_since_author_login', 'options') . ' days' : '1 year';
$last_login = new DateTime('-' . $days_since_author_login);

$days_email_frequency = (get_field('inactive_authors_days_resend', 'options')) ? get_field('inactive_authors_days_resend', 'options') . ' days' : '8 days';
$last_email_sent = new DateTime('-' . $days_email_frequency);

$selected_hubs = get_field('reminder_email_hubs', 'options');
?>

<?php if($selected_hubs) { ?>
  <main>
    <div class="container">
      <?php while (have_posts()) : the_post(); ?>
        <h1><?php echo \Tofino\Helpers\title(); ?></h1>

        <label><strong>Selected hubs:</strong></label>
        <ul>
          <?php foreach($selected_hubs as $hub_id) { ?>
            <li><?php echo get_term_by('id', $hub_id, 'hub')->name; ?></li>
          <?php } ?>
        </ul>
        <p>Group authors who haven't logged in for <strong><?php echo $days_since_author_login; ?></strong> since <strong><?php echo $last_login->format('Y-m-d H:i:s'); ?></strong> are to be emailed.</p>
        <p>The script will email them every <strong><?php echo $days_email_frequency; ?></strong>.</p>
        <p><strong>These settings can be amended on the <a href="<?php echo home_url('wp-admin/admin.php?page=acf-options-general-options'); ?>" target="_blank">General settings screen</a> in the dashboard.</strong></p>
        <p>Below are the groups being currently sent reminder emails by the cron script:</p>

        <?php 
        $args = array(
          'post_type' => 'initiatives',
          'posts_per_page' => -1,
          'orderby' => array(
            'last_mail_exists' => 'ASC',
            'last_mail_date' => 'ASC'
          ),
          'tax_query' => array(
            array(
              'taxonomy' => 'hub',
              'term_id' => 'term_id',
              'terms' => $selected_hubs
            )
          ),
          'meta_query' => array(
            'relation' => 'AND',
            array(
              'relation' => 'OR',
              'last_logged_in' => array(
                'key' => 'author_last_logged_in',
                'value' => $last_login->format('Y-m-d H:i:s'),
                'compare' => '<',
                'type' => 'DATE'
              ),
              array(
                'key' => 'author_last_logged_in',
                'compare' => 'NOT EXISTS'
              ),
            ),
            array(
              'relation' => 'OR',
              'last_mail_date' => array(
                'key' => 'last_mail_date',
                'compare' => 'EXISTS'
              ),
              'last_mail_exists' => array(
                'key' => 'last_mail_date',
                'compare' => 'NOT EXISTS'
              )
            )
          )
        );

        $posts = get_posts($args); ?>

        <table class="item-list">
          <tr>
            <th>initaitive_name</th>
            <th>hub</th>
            <th>author_email</th>
            <th>author_last_logged_in</th>
            <th>last_mail_date</th>
          </tr>
          <?php foreach($posts as $post) { ?>
            <?php setup_postdata( $post ); ?>
            <tr>
              <td><a href="<?php echo get_the_permalink(); ?>"><?php echo get_the_title(); ?></a></td>
              <td><?php echo endpoint_get_taxonomy_terms($post, 'hub'); ?></td>
              <td><?php echo get_the_author_meta( 'user_email' ); ?></td>
              <td>
                <?php $last_login = get_post_meta( get_the_ID(), 'author_last_logged_in', true); ?>
                <?php echo ($last_login) ? $last_login : 'Never'; ?>
              </td>
              <td>
                <?php $last_mail_date = get_post_meta( get_the_ID(), 'last_mail_date', true); ?>
                <?php echo ($last_mail_date) ? $last_mail_date : 'Never'; ?>
              </td>
            </tr>
          <?php } ?>
          <?php wp_reset_postdata(  ); ?>
        </table>
      <?php endwhile; ?>
    </div>
  </main>
<?php } ?>

