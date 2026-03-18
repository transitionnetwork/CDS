<?php
/**
 * Xinc Login — Custom authentication flow
 *
 * Replaces wp-login.php with custom themed pages for login, registration,
 * password reset, and account editing.
 *
 * Migrated from the xinc-login plugin into the theme for tighter integration
 * and Tailwind CSS conversion support.
 *
 * @package Tofino
 */

namespace Tofino\XincLogin;

class Controller
{
  /** @var string Path to the views directory */
  private $views_path;

  public function __construct()
  {
    $this->views_path = __DIR__ . '/views';

    // Login
    add_shortcode('custom-login-form', [$this, 'render_login_form']);
    add_action('login_form_login', [$this, 'redirect_to_custom_login']);
    add_filter('authenticate', [$this, 'maybe_redirect_at_authenticate'], 101, 3);
    add_action('wp_logout', [$this, 'redirect_after_logout']);
    add_filter('wp_nav_menu_items', [$this, 'build_nav'], 10, 2);
    add_filter('login_redirect', [$this, 'redirect_after_login'], 10, 3);

    // Registration
    add_shortcode('custom-register-form', [$this, 'render_register_form']);
    add_action('login_form_register', [$this, 'redirect_to_custom_register']);
    add_action('login_form_register', [$this, 'do_register_user']);
    add_action('wp_print_footer_scripts', [$this, 'add_captcha_js_to_footer']);

    // Registration (super — admin-created users, no CAPTCHA)
    add_shortcode('custom-register-form-super', [$this, 'render_register_form_super']);

    // Password reset
    add_shortcode('custom-password-lost-form', [$this, 'render_password_lost_form']);
    add_action('login_form_lostpassword', [$this, 'redirect_to_custom_lostpassword']);
    add_action('login_form_lostpassword', [$this, 'do_password_lost']);
    add_filter('retrieve_password_message', [$this, 'replace_retrieve_password_message'], 10, 4);

    // Pick a new password
    add_shortcode('custom-password-reset-form', [$this, 'render_password_reset_form']);
    add_action('login_form_rp', [$this, 'redirect_to_custom_password_reset']);
    add_action('login_form_resetpass', [$this, 'redirect_to_custom_password_reset']);
    add_action('login_form_rp', [$this, 'do_password_reset']);
    add_action('login_form_resetpass', [$this, 'do_password_reset']);

    // Edit account
    add_shortcode('custom-edit-account', [$this, 'render_edit_account']);
    add_action('template_redirect', [$this, 'do_edit_account']);
  }


  // ─── Login ───────────────────────────────────────────────────────────

  public function render_login_form($attributes, $content = null)
  {
    $default_attributes = ['show_title' => false];
    $attributes = shortcode_atts($default_attributes, $attributes);

    if (is_user_logged_in()) {
      return __('You are already signed in.', 'tofino');
    }

    $attributes['redirect'] = '';
    if (isset($_REQUEST['redirect_to'])) {
      $attributes['redirect'] = wp_validate_redirect($_REQUEST['redirect_to'], $attributes['redirect']);
    }

    $attributes['lost_password_sent'] = isset($_REQUEST['checkemail']) && $_REQUEST['checkemail'];
    $attributes['password_updated'] = isset($_REQUEST['password']) && $_REQUEST['password'] == 'changed';

    $errors = [];
    if (isset($_REQUEST['login'])) {
      $error_codes = explode(',', $_REQUEST['login']);
      foreach ($error_codes as $code) {
        $errors[] = $this->get_error_message($code);
      }
    }
    $attributes['errors'] = $errors;
    $attributes['logged_out'] = isset($_REQUEST['logged_out']) && $_REQUEST['logged_out'] == true;

    return $this->render_view('login-form', $attributes);
  }

  public function redirect_to_custom_login()
  {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
      $redirect_to = isset($_REQUEST['redirect_to']) ? $_REQUEST['redirect_to'] : null;

      if (is_user_logged_in()) {
        $this->redirect_logged_in_user($redirect_to);
        exit;
      }

      $login_url = home_url('member-login');
      if (!empty($redirect_to)) {
        $login_url = add_query_arg('redirect_to', $redirect_to, $login_url);
      }

      wp_redirect($login_url);
      exit;
    }
  }

  private function redirect_logged_in_user($redirect_to = null)
  {
    $user = wp_get_current_user();

    if (user_can($user, 'manage_options')) {
      if ($redirect_to) {
        wp_safe_redirect($redirect_to);
      } else {
        wp_redirect(admin_url());
      }
    } else {
      wp_redirect(home_url('dashboard'));
    }
  }

  public function maybe_redirect_at_authenticate($user, $username, $password)
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (is_wp_error($user)) {
        $error_codes = join(',', $user->get_error_codes());

        $login_url = home_url('member-login');
        $login_url = add_query_arg('login', $error_codes, $login_url);

        wp_redirect($login_url);
        exit;
      }
    }

    return $user;
  }

  public function redirect_after_login($redirect_to, $requested_redirect_to, $user)
  {
    $redirect_url = home_url();

    if (!isset($user->ID)) {
      return $redirect_url;
    }

    $user_roles = $user->roles;

    if (get_user_meta($user->ID, 'gdpr_accepted', true)) {
      if ($requested_redirect_to !== '') {
        $redirect_url = $requested_redirect_to;
      } else {
        if (in_array('administrator', $user_roles)) {
          $redirect_url = admin_url();
        } elseif (in_array('hub', $user_roles) || in_array('super_hub', $user_roles)) {
          $redirect_url = home_url('account#nav-initiative-admin');
        } else {
          $redirect_url = home_url('account');
        }
      }
    } else {
      $redirect_url = home_url('gdpr-terms-and-conditions');
    }

    return wp_validate_redirect($redirect_url, home_url());
  }

  public function redirect_after_logout()
  {
    $redirect_url = home_url('member-login?logged_out=true');
    wp_safe_redirect($redirect_url);
    exit;
  }

  public function build_nav($nav, $args)
  {
    if ($args->theme_location == 'account_menu') {
      if (is_user_logged_in()) {
        $user_nav = '<li><a href="/dashboard">Dashboard</a></li>';
        $user_nav .= '<li><a href="' . wp_logout_url(home_url()) . '">Logout</a></li>';
      } else {
        $user_nav = '<li><a href="/member-login">Sign In</a></li>';
      }

      return $nav . $user_nav;
    }

    return $nav;
  }


  // ─── Registration ────────────────────────────────────────────────────

  public function render_register_form($attributes, $content = null)
  {
    $default_attributes = ['show_title' => false];
    $attributes = shortcode_atts($default_attributes, $attributes);

    $attributes['errors'] = [];
    if (isset($_REQUEST['register-errors'])) {
      $error_codes = explode(',', $_REQUEST['register-errors']);
      foreach ($error_codes as $error_code) {
        $attributes['errors'][] = $this->get_error_message($error_code);
      }
    }

    if (isset($_REQUEST['form-data'])) {
      $attributes['form-data'] = $_REQUEST['form-data'];
    }

    $attributes['recaptcha_site_key'] = get_theme_mod('captcha_site_key', '');
    $attributes['registered'] = isset($_REQUEST['registered']);

    if (is_user_logged_in()) {
      wp_redirect(home_url());
    } else {
      return $this->render_view('register-form', $attributes);
    }
  }

  public function render_register_form_super($attributes, $content = null)
  {
    $default_attributes = ['show_title' => false];
    $attributes = shortcode_atts($default_attributes, $attributes);

    $attributes['errors'] = [];
    if (isset($_REQUEST['register-errors'])) {
      $error_codes = explode(',', $_REQUEST['register-errors']);
      foreach ($error_codes as $error_code) {
        $attributes['errors'][] = $this->get_error_message($error_code);
      }
    }

    if (isset($_REQUEST['form-data'])) {
      $attributes['form-data'] = $_REQUEST['form-data'];
    }

    if (isset($_REQUEST['registered'])) {
      $attributes['registered'] = $_REQUEST['registered'];
    }

    if (!is_user_logged_in()) {
      wp_redirect(home_url());
    } else {
      return $this->render_view('register-form-super', $attributes);
    }
  }

  public function redirect_to_custom_register()
  {
    if ('GET' == $_SERVER['REQUEST_METHOD']) {
      wp_redirect(home_url());
      exit;
    }
  }

  public function do_register_user()
  {
    if ('POST' == $_SERVER['REQUEST_METHOD']) {
      $redirect_url = $_POST['source_url'];

      if (!get_option('users_can_register')) {
        $redirect_url = add_query_arg('register-errors', 'closed', $redirect_url);
      } elseif (!$this->verify_recaptcha() && $_POST['recaptcha_required'] === 'true') {
        $redirect_url = add_query_arg('register-errors', 'captcha', $redirect_url);
      } else {
        $email = $_POST['email'];
        $first_name = sanitize_text_field($_POST['first_name']);
        $last_name = sanitize_text_field($_POST['last_name']);

        $result = $this->register_user($email, $first_name, $last_name);

        if (is_wp_error($result)) {
          $form_data = [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
          ];
          $errors = join(',', $result->get_error_codes());
          $redirect_url = add_query_arg(['register-errors' => $errors, 'form-data' => $form_data], $redirect_url);
        } else {
          $redirect_url = add_query_arg('registered', $email, $redirect_url);
        }
      }

      wp_redirect($redirect_url);
      exit;
    }
  }

  private function register_user($email, $first_name, $last_name)
  {
    $errors = new \WP_Error();

    if (!is_email($email)) {
      $errors->add('email', $this->get_error_message('email'));
    }

    if (username_exists($email) || email_exists($email)) {
      $errors->add('email_exists', $this->get_error_message('email_exists'));
    }

    if ($errors->errors) {
      return $errors;
    }

    $password = wp_generate_password(12, false);

    $user_data = [
      'user_login' => $email,
      'user_email' => $email,
      'user_pass' => $password,
      'first_name' => $first_name,
      'last_name' => $last_name,
      'nickname' => $first_name,
    ];

    $user_id = wp_insert_user($user_data);

    if (is_user_logged_in()) {
      update_user_meta($user_id, 'parent_id', get_current_user_id());
    }

    wp_new_user_notification($user_id, null, 'user');

    return $user_id;
  }

  private function verify_recaptcha()
  {
    if (!isset($_POST['g-recaptcha-response'])) {
      return false;
    }

    $captcha_response = $_POST['g-recaptcha-response'];
    $secret = get_theme_mod('captcha_secret', '');

    if (empty($secret)) {
      return false;
    }

    $response = wp_remote_post(
      'https://www.google.com/recaptcha/api/siteverify',
      [
        'body' => [
          'secret' => $secret,
          'response' => $captcha_response,
        ],
      ]
    );

    $success = false;
    if ($response && is_array($response)) {
      $decoded_response = json_decode($response['body']);
      $success = $decoded_response->success;
    }

    return $success;
  }


  // ─── Password Reset ──────────────────────────────────────────────────

  public function render_password_lost_form($attributes, $content = null)
  {
    $default_attributes = ['show_title' => false];
    $attributes = shortcode_atts($default_attributes, $attributes);

    $attributes['errors'] = [];
    if (isset($_REQUEST['errors'])) {
      $error_codes = explode(',', $_REQUEST['errors']);
      foreach ($error_codes as $error_code) {
        $attributes['errors'][] = $this->get_error_message($error_code);
      }
    }

    if (is_user_logged_in()) {
      return __('You are already signed in.', 'tofino');
    } else {
      return $this->render_view('password-lost-form', $attributes);
    }
  }

  public function redirect_to_custom_lostpassword()
  {
    if ('GET' == $_SERVER['REQUEST_METHOD']) {
      if (is_user_logged_in()) {
        $this->redirect_logged_in_user();
        exit;
      }

      wp_redirect(home_url('member-password-lost'));
      exit;
    }
  }

  public function do_password_lost()
  {
    if ('POST' == $_SERVER['REQUEST_METHOD']) {
      $errors = retrieve_password();
      if (is_wp_error($errors)) {
        $redirect_url = home_url('member-password-lost');
        $redirect_url = add_query_arg('errors', join(',', $errors->get_error_codes()), $redirect_url);
      } else {
        $redirect_url = home_url('member-login');
        $redirect_url = add_query_arg('checkemail', 'confirm', $redirect_url);
      }

      wp_redirect($redirect_url);
      exit;
    }
  }

  public function replace_retrieve_password_message($message, $key, $user_login, $user_data)
  {
    $msg = __('Hello!', 'tofino') . "\r\n\r\n";
    $msg .= sprintf(__('You asked us to reset your password for your account using the email address %s.', 'tofino'), $user_login) . "\r\n\r\n";
    $msg .= __("If this was a mistake, or you didn't ask for a password reset, just ignore this email and nothing will happen.", 'tofino') . "\r\n\r\n";
    $msg .= __('To reset your password, visit the following address:', 'tofino') . "\r\n\r\n";
    $msg .= site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login') . "\r\n\r\n";
    $msg .= __('Thanks!', 'tofino') . "\r\n";

    return $msg;
  }


  // ─── Pick a New Password ─────────────────────────────────────────────

  public function render_password_reset_form($attributes, $content = null)
  {
    $default_attributes = ['show_title' => false];
    $attributes = shortcode_atts($default_attributes, $attributes);

    if (is_user_logged_in()) {
      return __('You are already signed in.', 'tofino');
    } else {
      if (isset($_REQUEST['login']) && isset($_REQUEST['key'])) {
        $attributes['login'] = $_REQUEST['login'];
        $attributes['key'] = $_REQUEST['key'];

        $errors = [];
        if (isset($_REQUEST['error'])) {
          $error_codes = explode(',', $_REQUEST['error']);
          foreach ($error_codes as $code) {
            $errors[] = $this->get_error_message($code);
          }
        }
        $attributes['errors'] = $errors;

        return $this->render_view('password-reset-form', $attributes);
      } else {
        return __('Invalid password reset link.', 'tofino');
      }
    }
  }

  public function redirect_to_custom_password_reset()
  {
    if ('GET' == $_SERVER['REQUEST_METHOD']) {
      $user = check_password_reset_key($_REQUEST['key'], $_REQUEST['login']);
      if (!$user || is_wp_error($user)) {
        if ($user && $user->get_error_code() === 'expired_key') {
          wp_redirect(home_url('member-login?login=expiredkey'));
        } else {
          wp_redirect(home_url('member-login?login=invalidkey'));
        }
        exit;
      }

      $redirect_url = home_url('member-password-reset');
      $redirect_url = add_query_arg('login', esc_attr($_REQUEST['login']), $redirect_url);
      $redirect_url = add_query_arg('key', esc_attr($_REQUEST['key']), $redirect_url);

      wp_redirect($redirect_url);
      exit;
    }
  }

  public function do_password_reset()
  {
    if ('POST' == $_SERVER['REQUEST_METHOD']) {
      $rp_key = $_REQUEST['rp_key'];
      $rp_login = $_REQUEST['rp_login'];

      $user = check_password_reset_key($rp_key, $rp_login);

      if (!$user || is_wp_error($user)) {
        if ($user && $user->get_error_code() === 'expired_key') {
          wp_redirect(home_url('member-login?login=expiredkey'));
        } else {
          wp_redirect(home_url('member-login?login=invalidkey'));
        }
        exit;
      }

      if (isset($_POST['pass1'])) {
        if ($_POST['pass1'] != $_POST['pass2']) {
          $redirect_url = home_url('member-password-reset');
          $redirect_url = add_query_arg('key', $rp_key, $redirect_url);
          $redirect_url = add_query_arg('login', $rp_login, $redirect_url);
          $redirect_url = add_query_arg('error', 'password_reset_mismatch', $redirect_url);

          wp_redirect($redirect_url);
          exit;
        }

        if (empty($_POST['pass1'])) {
          $redirect_url = home_url('member-password-reset');
          $redirect_url = add_query_arg('key', $rp_key, $redirect_url);
          $redirect_url = add_query_arg('login', $rp_login, $redirect_url);
          $redirect_url = add_query_arg('error', 'password_reset_empty', $redirect_url);

          wp_redirect($redirect_url);
          exit;
        }

        reset_password($user, $_POST['pass1']);

        $credentials = [
          'user_login' => $rp_login,
          'user_password' => $_POST['pass1'],
        ];
        wp_signon($credentials);
        wp_redirect(home_url());
      } else {
        echo 'Invalid request.';
      }

      exit;
    }
  }


  // ─── Edit Account ────────────────────────────────────────────────────

  public function render_edit_account($attributes, $content = null)
  {
    $default_attributes = ['show_title' => false];
    $attributes = shortcode_atts($default_attributes, $attributes);

    $user = wp_get_current_user();
    $attributes['form-data'] = [
      'user_id' => $user->ID,
      'first_name' => $user->first_name,
      'last_name' => $user->last_name,
      'email' => $user->user_email,
    ];

    $attributes['redirect'] = '';
    if (isset($_REQUEST['redirect_to'])) {
      $attributes['redirect'] = wp_validate_redirect($_REQUEST['redirect_to'], $attributes['redirect']);
    }

    if (isset($_REQUEST['form-data'])) {
      $attributes['form-data'] = $_REQUEST['form-data'];
    }

    if (isset($_REQUEST['updated'])) {
      $attributes['updated'] = true;
    }

    $errors = [];
    if (isset($_REQUEST['register-errors'])) {
      $error_codes = explode(',', $_REQUEST['register-errors']);
      foreach ($error_codes as $code) {
        $errors[] = $this->get_error_message($code);
      }
    }
    $attributes['errors'] = $errors;

    return $this->render_view('edit-account', $attributes);
  }

  public function do_edit_account()
  {
    global $post;
    if ('POST' == $_SERVER['REQUEST_METHOD'] && $post && $post->post_name == 'member-edit-account') {

      $data = [
        'email' => $_POST['email'],
        'pass1' => $_POST['pass1'],
        'pass2' => $_POST['pass2'],
        'first_name' => sanitize_text_field($_POST['first_name']),
        'last_name' => sanitize_text_field($_POST['last_name']),
      ];

      $result = $this->update_account($data);

      if (is_wp_error($result)) {
        $errors = join(',', $result->get_error_codes());
        $redirect_url = add_query_arg([
          'register-errors' => $errors,
          'form-data' => $data,
        ]);
      } else {
        $redirect_url = add_query_arg('updated', true);
      }
      wp_redirect($redirect_url);
      exit;
    }
  }

  private function update_account($data)
  {
    $errors = new \WP_Error();

    $user_id = wp_get_current_user()->ID;

    if (email_exists($data['email']) && (email_exists($data['email']) != $user_id)) {
      $errors->add('email_exists', $this->get_error_message('email_exists'));
    }

    if (!is_email($data['email'])) {
      $errors->add('email', $this->get_error_message('email'));
    }

    if (!empty($errors->errors)) {
      return $errors;
    }

    $args = [
      'ID' => $user_id,
      'email' => $data['email'],
      'first_name' => $data['first_name'],
      'last_name' => $data['last_name'],
      'user_email' => $data['email'],
    ];

    if ($data['pass1'] || $data['pass2']) {
      if ($data['pass1'] != $data['pass2']) {
        $errors->add('password_reset_mismatch', $this->get_error_message('password_reset_mismatch'));
      }
      if ($data['pass1'] == $data['pass2']) {
        $args['user_pass'] = $data['pass1'];
      }
    }

    if (!empty($errors->errors)) {
      return $errors;
    }

    wp_update_user($args);
  }


  // ─── Shared Helpers ──────────────────────────────────────────────────

  private function get_error_message($error_code)
  {
    switch ($error_code) {
      case 'empty_username':
        return __('Please enter your email address.', 'tofino');

      case 'empty_password':
        return __('Please enter your password to login.', 'tofino');

      case 'invalid_username':
      case 'invalid_email':
        return __(
          'We don\'t have any users with that email address. Please enter your registered email address.',
          'tofino'
        );

      case 'incorrect_password':
        $err = __(
          'Incorrect password. <a href=\'%s\'>Did you forget your password</a>?',
          'tofino'
        );
        return sprintf($err, wp_lostpassword_url());

      case 'email':
        return __('The email address you entered is not valid.', 'tofino');

      case 'email_exists':
        return __('An account exists with this email address.', 'tofino');

      case 'captcha':
        return __('Please complete the reCaptcha', 'tofino');

      case 'expiredkey':
      case 'invalidkey':
        return __('The password reset link you used is not valid anymore.', 'tofino');

      case 'password_reset_mismatch':
        return __("The two passwords you entered don't match.", 'tofino');

      case 'password_reset_empty':
        return __("Sorry, we don't accept empty passwords.", 'tofino');

      default:
        return __('An unknown error occurred. Please try again later.', 'tofino');
    }
  }

  public function add_captcha_js_to_footer()
  {
    echo "<script src='https://www.google.com/recaptcha/api.js'></script>";
  }

  /**
   * Render a view file and return the HTML.
   *
   * @param string $view_name  The view filename without .php (e.g. 'login-form')
   * @param array  $attributes Template variables
   * @return string
   */
  private function render_view($view_name, $attributes = [])
  {
    ob_start();

    do_action('xinc_login_before_' . $view_name);

    require $this->views_path . '/' . $view_name . '.php';

    do_action('xinc_login_after_' . $view_name);

    $html = ob_get_contents();
    ob_end_clean();

    return $html;
  }
}

// Boot
new Controller();
