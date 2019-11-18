<?php
/**
 * cookie.php
 * 
 * Prüft ob ein gültiger Cookie gesetzt ist.
 */

if(isset($_COOKIE['stats']) AND !empty($_COOKIE['stats'])) {
  /**
   * Cookieinhalt entschärfen und prüfen ob Inhalt ein sha256-Hash ist.
   */
  $sessionhash = defuse($_COOKIE['stats']);
  if(preg_match('/[a-f0-9]{64}/i', $sessionhash, $match) === 1) {
    /**
     * Abfrage in der Datenbank, ob eine Sitzung mit diesem Hash existiert.
     */
    $result = mysqli_query($dbl, "SELECT `accounts`.`username` FROM `sessions` JOIN `accounts` ON `accounts`.`id`=`sessions`.`userid` WHERE `hash`='".$match[0]."' LIMIT 1") OR DIE(MYSQLI_ERROR($dbl));
    if(mysqli_num_rows($result) == 1) {
      /**
       * Wenn eine Sitzung existiert wird der letzte Nutzungszeitpunkt aktualisiert und der Username in die Variable $username geladen.
       */
      mysqli_query($dbl, "UPDATE `sessions` SET `lastActivity`=CURRENT_TIMESTAMP WHERE `hash`='".$match[0]."' LIMIT 1") OR DIE(MYSQLI_ERROR($dbl));
      setcookie('stats', $match[0], time()+(6*7*86400));
      $username = mysqli_fetch_array($result)['username'];
      $loggedIn = 1;
    } else {
      /**
       * Wenn keine Sitzung mit dem übergebenen Hash existiert wird der User ausgeloggt.
       */
      header("Location: /logout");
      die();
    }
  } else {
    /**
     * Wenn kein gültiger sha256 Hash übergeben wurde wird der User ausgeloggt.
     */
    header("Location: /logout");
    die();
  }
} else {
  /**
   * Wenn kein oder ein leerer Cookie übergeben wurde wird auf die Loginseite weitergeleitet.
   */
  header("Location: /login");
  die();
}
?>
