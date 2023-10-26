<?php function export_author_emails_by_hub($hub_id) {
  $args = array(
    'post_type' => 'initiatives',
    'order' => 'DESC',
    'post_status' => array('publish', 'pending'),
    'posts_per_page' => -1,
    'tax_query' => array(
      array(
        'taxonomy' => 'hub',
        'terms' => $hub_id,
      )
    )
  );
  
  $posts = get_posts($args);
  
  $author_emails = array();
  $records = array();

  $author_emails[] = 'Email Address,Group,TAGS';
  
  
  foreach($posts as $post) {
    $title = str_replace(array(','), '-', get_the_title($post));
    
    $primary_author = get_userdata($post->post_author);
    $author_emails[] = $primary_author->user_email . ',' . $title . ',T-Groups-org-import-oct23';
  
    $co_authors = ma_get_co_authors($post->ID);
    if($co_authors) {
      foreach($co_authors as $co_author_id) {
        $co_author = get_userdata($co_author_id);
        $author_emails[] = $co_author->user_email . ',' . $title .  ',T-Groups-org-import-oct23';
      }
    }
  }

  echo implode('<br/>', $author_emails);
  // echo implode('<br>', implode(',', $author_emails));
}

?>
