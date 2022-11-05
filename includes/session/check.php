<?php
/**
 * includes/session/cookieCheck.php
 * 
 * Checks if a valid cookie is set.
 */

if(!empty($_COOKIE['stats'])) {
  /**
   * Defuse cookie content and check if content is a sha256 hash.
   */
  $hash = defuse($_COOKIE['stats']);
  if(preg_match('/[a-f0-9]{64}/i', $hash, $match) === 1) {
    /**
     * Check if a session with the hash exists.
     */
    if($userRow = sessions::getSessionData($match[0])) {
      /**
       * There exists a session with the hash. Update the lastActivity timestamp in the session.
       */
      if(!sessions::updateLastActivity($match[0])) {
        /**
         * If the session timestamp couldn't be updated, the user will be logged out.
         */
        setcookie('stats', NULL, 0, NULL, NULL, TRUE, TRUE);
        header("Location: /login");
        die();
      }
      /**
       * Reload the userData via the session.
       */
      $userRow = sessions::getSessionData($match[0]);
    } else {
      /**
       * No session exists with the hash. The user will be logged out by removing the cookie and will be 
       * redirected to the login page.
       */
      setcookie('stats', NULL, 0, NULL, NULL, TRUE, TRUE);
      header("Location: /login");
      die();
    }
  } else {
    /**
     * If no valid sha256 hash is provided, the user is logged out by removing the cookie and redirecting to the login page.
     */
    setcookie('stats', NULL, 0, NULL, NULL, TRUE, TRUE);
    header("Location: /login");
    die();
  }
} else {
  /**
   * If no cookie or an empty cookie is provided, the user will be redirected to the login page.
   */
  header("Location: /login");
  die();
}
?>
