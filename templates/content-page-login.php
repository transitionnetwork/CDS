<?php
$args = array(
  'redirect' => $_SERVER['HTTP_HOST']
); ?>

<main>
  <div class="container">
    <?php wp_login_form($args); ?>
    <p>Or <a href="register">Register</a> if you don't have an account</p>
  </div>
</main>
