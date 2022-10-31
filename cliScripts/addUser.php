<?php
/**
 * cliScripts/addUser.php
 * 
 * Create an user account.
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
  die("Der Name ist ungültig. Er muss zwischen 3 und 32 Zeichen lang sein und darf keine Sonderzeichen enthalten (0-9a-zA-Z).\nBeispielaufruf:\nphp ".$argv[0]." Hans\nErstellt einen Nutzer \"Hans\".\n\n");
}

/**
 * Generate a random password.
 */
$passwordClear = hash('md5', random_bytes(4096));
$salt = hash('sha256', random_bytes(4096));
$password = password_hash($passwordClear.$salt, PASSWORD_DEFAULT);

/**
 * Insert the new user.
 */
if(mysqli_query($dbl, "INSERT INTO `accounts` (`username`, `password`, `salt`) VALUES ('".$username."', '".$password."', '".$salt."')")) {
  die("Account erfolgreich angelegt.\n\nUser: ".$username."\nPass: ".$passwordClear."\n\n");
} elseif(mysqli_errno($dbl) == 1062) {
  die("Es existiert bereits ein Account mit diesem Namen.\n\n");
} else {
  die("Unbekannter Fehler: ".mysqli_error($dbl)."\n\n");
}
?>
