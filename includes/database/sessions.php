<?php
/**
 * includes/database/sessions.php
 * 
 * Session Handling
 */
class sessions {
  /**
   * Generate a hash to use in sessions
   * 
   * @return string The hash.
   */
  public static function generateHash() {
    return hash("sha256", random_bytes(4096));
  }

  /**
   * Create a session
   * 
   * @param int $userId The userId of the user the session should be created for.
   * @param string $hash The session hash previously generated via generateHash().
   * 
   * @return bool True if success, false otherwise
   */
  public static function createSession(int $userId, string $hash) {
    global $dbl;
    /**
     * Check if the userId and the hash are provided.
     */
    if(empty($userId) OR empty($hash)) {
      return FALSE;
    }

    if(mysqli_query($dbl, "INSERT INTO `sessions` (`userId`, `hash`) VALUES ('".defuse($userId)."', '".defuse($hash)."')")) {
      return TRUE;
    } else {
      return FALSE;
    }
  }

  /**
   * Delete all sessions
   * 
   * @param int $userId The userId of the user whose sessions should be terminated.
   */
  public static function deleteSessionsByUserId(int $userId) {
    global $dbl;
    /**
     * Check if the userId is provided.
     */
    if(empty($userId)) {
      return FALSE;
    }

    if(mysqli_query($dbl, "DELETE FROM `sessions` WHERE `userId`='".defuse($userId)."'")) {
      return TRUE;
    } else {
      return FALSE;
    }
  }

  /**
   * Get session data
   * 
   * @param string $hash The hash of the session from which to load the session data.
   * 
   * @return mixed If the session exists, an array with the session data is returned, otherwise false.
   */
  public static function getSessionData(string $hash) {
    global $dbl;
    /**
     * Check if a valid hash is provided.
     */
    if(empty($hash) OR preg_match('/[a-f0-9]{64}/i', $hash, $match) !== 1) {
      return FALSE;
    }

    if($result = mysqli_query($dbl, "SELECT `users`.`id` as `userId`, `users`.`username` as `username`, `users`.`lastApiSync` as `lastApiSync`, `users`.`lastPrinted` as `lastPrinted`, `sessions`.`id` as `sessionId`, `sessions`.`hash` as `hash` FROM `sessions` JOIN `users` ON `users`.`id`=`sessions`.`userId` WHERE `hash`='".defuse($match[0])."' LIMIT 1")) {
      if(mysqli_num_rows($result) == 1) {
        return mysqli_fetch_assoc($result);
      } else {
        return FALSE;
      }
    } else {
      return FALSE;
    }
  }

  /**
   * Update lastActivity timestamp
   * 
   * @param string $hash The hash of the session whose lastActivity timestamp should be updated.
   * 
   * @return bool True on success, false otherwise.
   */
  public static function updateLastActivity(string $hash) {
    global $dbl;
    /**
     * Check if a valid hash is provided.
     */
    if(empty($hash) OR preg_match('/[a-f0-9]{64}/i', $hash, $match) !== 1) {
      return FALSE;
    }

    /**
     * Update the session.
     */
    if(mysqli_query($dbl, "UPDATE `sessions` SET `lastActivity`=CURRENT_TIMESTAMP WHERE `hash`='".defuse($match[0])."' LIMIT 1")) {
      return TRUE;
    } else {
      return FALSE;
    }
  }
}
?>
