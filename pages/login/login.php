<?php
/**
 * login.php
 * 
 * Login page
 */
$title = "Login";

/**
 * No cookie set or cookie is empty and form not submitted.
 */
if((!isset($_COOKIE['stats']) OR empty($_COOKIE['stats'])) AND !isset($_POST['submit'])) {
  $content.= "<h1>Login</h1>";
  /**
   * Login form
   */
  $content.= "<form action='/login' method='post'>";
  $content.= "<div class='row hover bordered'>".
  "<div class='col-s-12 col-l-3'>Name</div>".
  "<div class='col-s-12 col-l-9'><input type='text' name='username' placeholder='Name' autofocus tabindex='1'></div>".
  "</div>";
  $content.= "<div class='row hover bordered'>".
  "<div class='col-s-12 col-l-3'>Passwort</div>".
  "<div class='col-s-12 col-l-9'><input type='password' name='password' placeholder='Passwort' tabindex='2'></div>".
  "</div>";
  $content.= "<div class='row hover bordered'>".
  "<div class='col-s-12 col-l-3'>Einloggen</div>".
  "<div class='col-s-12 col-l-9'><input type='submit' name='submit' value='Einloggen' tabindex='3'></div>".
  "</div>";
  $content.= "</form>";
} elseif((!isset($_COOKIE['stats']) OR empty($_COOKIE['stats'])) AND isset($_POST['submit'])) {
  /**
   * Defusing the user input
   */
  $username = defuse($_POST['username']);
  /**
   * Check if there is an account with that username.
   */
  $result = mysqli_query($dbl, "SELECT * FROM `accounts` WHERE `username`='".$username."' LIMIT 1") OR DIE(MYSQLI_ERROR($dbl));
  if(mysqli_num_rows($result) == 1) {
    /**
     * If the user exists, the password has to be verified.
     */
    $row = mysqli_fetch_array($result);
    if(password_verify($_POST['password'].$row['salt'], $row['password'])) {
      /**
       * If the password is correct, the session will be created and the user will be redirected to the stats page.
       */
      $hash = sessions::generateHash();

      if(sessions::createSession($row['id'], $hash)) {
        setcookie('stats', $hash, time()+(8*7*86400), NULL, NULL, TRUE, TRUE);
        header('Location: /stats');
        die();
      } else {
        setcookie('stats', NULL, 0, NULL, NULL, TRUE, TRUE);
        header('Location: /login');
        die();
      }
    } else {
      /**
       * If the password could not be verified HTTP403 is returned and an error message is displayed.
       */
      http_response_code(403);
      $content.= "<h1>Login gescheitert</h1>";
      $content.= "<div class='row'>".
      "<div class='col-s-12 col-l-12 warn bold'>Die Zugangsdaten sind falsch.</div>".
      "</div>";
      $content.= "<div class='row'>".
      "<div class='col-s-12 col-l-12'><a href='/login'>Erneut versuchen</a></div>".
      "</div>";
    }
  } else {
    /**
     * If there is no match, then HTTP403 is returned and an error message is displayed.
     */
    http_response_code(403);
    $content.= "<h1>Login gescheitert</h1>";
    $content.= "<div class='row'>".
    "<div class='col-s-12 col-l-12 warn bold'>Die Zugangsdaten sind falsch.</div>".
    "</div>";
    $content.= "<div class='row'>".
    "<div class='col-s-12 col-l-12'><a href='/login'>Erneut versuchen</a></div>".
    "</div>";
  }
} else {
  /**
   * If a cookie is already set, the user will be redirected to the statistics page.
   */
  header("Location: /stats");
  die();
}
?>
