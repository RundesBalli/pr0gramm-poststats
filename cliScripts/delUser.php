<?php
/**
 * cliScripts/delUser.php
 * 
 * Remove an account.
 * 
 * @param string $argv[1] Username
 */

/**
 * Including the configuration and function loader.
 */
require_once(__DIR__.DIRECTORY_SEPARATOR.'loader.php');

/**
 * Check if the script is running in the CLI.
 */
if(php_sapi_name() != 'cli') {
  die("Das Script kann nur per Konsole ausgeführt werden.\n\n");
}

/**
 * Read and process the user name.
 */
if(isset($argv[1]) AND preg_match('/^[0-9a-zA-Z]{3,32}$/', defuse($argv[1]), $match) === 1) {
  $username = $match[0];
} else {
  die("Der Name ist ungültig. Er muss zwischen 3 und 32 Zeichen lang sein und darf keine Sonderzeichen enthalten (0-9a-zA-Z).\nBeispielaufruf:\nphp ".$argv[0]." Hans\nLöscht den Nutzer \"Hans\".\n\n");
}

/**
 * Removing the account.
 */
mysqli_query($dbl, "DELETE FROM `users` WHERE `username`='".$username."' LIMIT 1") OR DIE(MYSQLI_ERROR($dbl));
if(mysqli_affected_rows($dbl) == 1) {
  die("Account erfolgreich entfernt.\n\n");
} else {
  die("Dieser Account existiert nicht.\n\n");
}
?>
