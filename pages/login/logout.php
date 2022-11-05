<?php
/**
 * pages/login/logout.php
 * 
 * Logout page.
 */

/**
 * Inclusion of the cookieCheck.
 */
require_once(INCLUDE_DIR.'session'.DIRECTORY_SEPARATOR.'check.php');

/**
 * Title
 */
$title = 'Logout';
$content.= "<h1>Logout</h1>";

/**
 * Display the form if it has not been submitted yet.
 */
if(!isset($_POST['submit'])) {
  $content.= "<form action='/logout' method='post'>";
  /**
   * Session hash
   */
  $content.= "<input type='hidden' name='token' value='".output($userRow['hash'])."'>";
  /**
   * Choice
   */
  $content.= "<div class='row'>".
    "<div class='col-s-12 col-l-12'>Möchtest du dich ausloggen?</div>".
    "<div class='col-s-12 col-l-12'><input type='submit' name='submit' value='Ja, ausloggen'></div>".
    "<div class='col-s-12 col-l-12'><a href='/stats'>Nein, zurück</a></div>".
  "</div>";
  $content.= "</form>";
  return;
}

/**
 * Form has been submitted
 */

/**
 * Session hash
 */
if($_POST['token'] != $userRow['hash']) {
  http_response_code(403);
  $content.= "<div class='warnbox'>Ungültige Anfrage</div>";
  $content.= "<div class='row'>".
    "<div class='col-s-12 col-l-12'><a href='/stats'>Zurück zur Übersicht</a></div>".
  "</div>";
  return;
}

/**
 * Deletion of all sessions of the user.
 */
sessions::deleteSessionsByUserId($userRow['userId']);

/**
 * Removing the cookie and redirecting to the home page.
 */
setcookie($cookieName, NULL, 0, NULL, NULL, TRUE, TRUE);
header("Location: /login");
die();
?>
