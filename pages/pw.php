<?php
/**
 * pw.php
 * 
 * Statistikseite
 */

/**
 * Einbinden der Cookieüberprüfung.
 */
require_once('cookie.php');

/**
 * Titel und Überschrift
 */
$title = "Passwort ändern";
$content.= "<h1>Passwort ändern</h1>";

$content.= "<form action='/pw' method='post'>";
$content.= "<div class='row'>".
"<div class='col-s-12 col-l-3'>Neues Passwort</div>".
"<div class='col-s-12 col-l-4'><input type='password' name='password' placeholder='Passwort' autofocus tabindex='1'></div>".
"<div class='col-s-12 col-l-5'>Mindestens 12 Zeichen.</div>".
"</div>";
$content.= "<div class='row'>".
"<div class='col-s-12 col-l-3'>Ändern</div>".
"<div class='col-s-12 col-l-4'><input type='submit' name='submit' value='Ändern' tabindex='2'></div>".
"</div>";
$content.= "</form>";

if(isset($_POST['submit'])) {
  if(preg_match('/^.{12,}$/', $_POST['password'], $match) === 1) {
    $salt = hash('sha256', random_bytes(4096));
    $password = password_hash($match[0].$salt, PASSWORD_DEFAULT);
    mysqli_query($dbl, "UPDATE `accounts` SET `password`='".$password."', `salt`='".$salt."' WHERE `username`='".$username."' LIMIT 1") OR DIE(MYSQLI_ERROR($dbl));
    if(mysqli_affected_rows($dbl) == 1) {
      $content.= "<div class='successbox'>Passwort geändert.</div>";
    } else {
      $content.= "<div class='warnbox'>Es trat ein unbekannter Fehler auf.</div>";
    }
  } else {
    $content.= "<div class='warnbox'>Das Passwort ist zu kurz.</div>";
  }
}
?>
