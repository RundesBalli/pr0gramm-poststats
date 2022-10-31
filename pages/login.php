<?php
/**
 * login.php
 * 
 * Seite zum Einloggen.
 */
$title = "Login";

/**
 * Kein Cookie gesetzt oder Cookie leer und Formular nicht übergeben.
 */
if((!isset($_COOKIE['stats']) OR empty($_COOKIE['stats'])) AND !isset($_POST['submit'])) {
  $content.= "<h1>Login</h1>";
  /**
   * Cookiewarnung
   */
  $content.= "<div class='infobox'>Ab diesem Punkt werden Cookies verwendet! Mit dem Fortfahren stimmst du dem zu!</div>";
  /**
   * Loginformular
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
   * Kein Cookie gesetzt oder Cookie leer und Formular wurde übergeben.
   */
  /**
   * Entschärfen der Usereingaben.
   */
  $username = defuse($_POST['username']);
  /**
   * Abfragen ob eine Übereinstimmung in der Datenbank vorliegt.
   */
  $result = mysqli_query($dbl, "SELECT * FROM `accounts` WHERE `username`='".$username."' LIMIT 1") OR DIE(MYSQLI_ERROR($dbl));
  if(mysqli_num_rows($result) == 1) {
    /**
     * Wenn der User existiert, muss der Passworthash validiert werden.
     */
    $row = mysqli_fetch_array($result);
    if(password_verify($_POST['password'].$row['salt'], $row['password'])) {
      /**
       * Wenn das Passwort verifiziert werden konnte wird eine Sitzung generiert und im Cookie gespeichert.
       * Danach erfolg eine Weiterleitung zur Statistik-Seite.
       */
      $sessionhash = hash('sha256', time().$_SERVER['REMOTE_ADDR'].rand(10000,99999));
      mysqli_query($dbl, "INSERT INTO `sessions` (`userid`, `hash`) VALUES ('".$row['id']."', '".$sessionhash."')") OR DIE(MYSQLI_ERROR($dbl));
      setcookie('stats', $sessionhash, time()+(6*7*86400));
      header("Location: /stats");
      die();
    } else {
      /**
       * Wenn das Passwort nicht verifiziert werden konnte wird HTTP403 zurückgegeben und eine Fehlermeldung wird ausgegeben.
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
     * Wenn keine Übereinstimmung vorliegt, dann wird HTTP403 zurückgegeben und eine Fehlermeldung wird ausgegeben.
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
   * Wenn bereits ein Cookie gesetzt ist wird auf die Statistik Seite weitergeleitet.
   */
  header("Location: /stats");
  die();
}
?>
