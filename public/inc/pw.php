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
$content.= "<h1>Passwort ändern</h1>".PHP_EOL;

$content.= "<form action='/pw' method='post'>".PHP_EOL;
$content.= "<div class='row'>".PHP_EOL.
"<div class='col-x-12 col-s-12 col-m-4 col-l-3 col-xl-2'>Neues Passwort</div>".PHP_EOL.
"<div class='col-x-12 col-s-12 col-m-4 col-l-4 col-xl-4'><input type='password' name='password' placeholder='Passwort' autofocus tabindex='1'></div>".PHP_EOL.
"<div class='col-x-12 col-s-12 col-m-4 col-l-5 col-xl-6'>Mindestens 12 Zeichen.</div>".PHP_EOL.
"</div>".PHP_EOL;
$content.= "<div class='row'>".PHP_EOL.
"<div class='col-x-12 col-s-12 col-m-4 col-l-3 col-xl-2'>Ändern</div>".PHP_EOL.
"<div class='col-x-12 col-s-12 col-m-4 col-l-4 col-xl-4'><input type='submit' name='submit' value='Ändern' tabindex='2'></div>".PHP_EOL.
"</div>".PHP_EOL;
$content.= "</form>".PHP_EOL;

if(isset($_POST['submit'])) {
  if(preg_match('/^.{12,}$/', $_POST['password'], $match) === 1) {
    $salt = hash('sha256', random_bytes(4096));
    $password = password_hash($match[0].$salt, PASSWORD_DEFAULT);
    mysqli_query($dbl, "UPDATE `accounts` SET `password`='".$password."', `salt`='".$salt."' WHERE `username`='".$username."' LIMIT 1") OR DIE(MYSQLI_ERROR($dbl));
    if(mysqli_affected_rows($dbl) == 1) {
      $content.= "<div class='successbox'>Passwort geändert.</div>".PHP_EOL;
    } else {
      $content.= "<div class='warnbox'>Es trat ein unbekannter Fehler auf.</div>".PHP_EOL;
    }
  } else {
    $content.= "<div class='warnbox'>Das Passwort ist zu kurz.</div>".PHP_EOL;
  }
}
?>
