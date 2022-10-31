<?php
/**
 * logout.php
 * 
 * Seite zum Löschen der Sitzung und um den Cookie zu leeren.
 */

if(isset($_COOKIE['stats']) AND !empty($_COOKIE['stats'])) {
  /**
   * Cookieinhalt entschärfen und prüfen ob Inhalt ein sha256-Hash ist.
   */
  $sessionhash = defuse($_COOKIE['stats']);
  if(preg_match('/[a-f0-9]{64}/i', $sessionhash, $match) === 1) {
    /**
     * Löschen der Sitzung, sofern existent.
     */
    mysqli_query($dbl, "DELETE FROM `sessions` WHERE `hash`='".$match[0]."'") OR DIE(MYSQLI_ERROR($dbl));
  }
  /**
   * Entfernen des Cookies und Umleitung zur Loginseite.
   */
  setcookie('stats', NULL, 0);
  header("Location: /login");
  die();
} else {
  /**
   * Wenn kein oder ein leerer Cookie übergeben wurde wird auf die Loginseite weitergeleitet.
   */
  header("Location: /login");
  die();
}
?>
