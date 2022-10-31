<?php
/**
 * includes/configCheck.php
 * 
 * Check if the config version is sufficient for the operation of the site.
 */
if($configVersion < MIN_CONFIG_VERSION) {
  die('Die vorhandene Konfigurationsdatei ist nicht ausreichend. Bitte lege eine neue Konfigurationsdatei mithilfe der im Repository vorhandenen config.template.php Datei an.');
}
?>
