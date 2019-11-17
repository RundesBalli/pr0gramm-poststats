<?php
/**
 * config.php
 * 
 * Konfigurationsdatei
 */

/**
 * Variablen
 */
$mysql_host = "localhost";
$mysql_user = "";
$mysql_pass = "";
$mysql_db =   "";

/**
 * Datenbankverbindung
*/
$dbl = mysqli_connect($mysql_host, $mysql_user, $mysql_pass, $mysql_db) OR DIE(MYSQLI_ERROR($dbl));
mysqli_set_charset($dbl, "utf8") OR DIE(MYSQLI_ERROR($dbl));

/**
 * Einbinden des apiCalls.
 * Download: https://github.com/RundesBalli/pr0gramm-apiCall
 * 
 * Beispielwert: /home/user/apiCall/apiCall.php
 * 
 * @param string
 */
require_once("");
?>
