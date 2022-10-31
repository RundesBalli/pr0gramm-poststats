<?php
/**
 * Post-Statistiken
 * 
 * Ein Script zum Schnellauswerten eines Posts auf pr0gramm.com
 * 
 * @author    RundesBalli <webspam@rundesballi.com>
 * @copyright 2022 RundesBalli
 * @version   2.0
 * @license   MIT-License
 * @see       https://github.com/RundesBalli/pr0gramm-poststats
 */

/**
 * Initialize the output and the default title
 */
$content = "";
$title = "";

/**
 * Including the configuration and function loader, the page generation elements, the router and the output generation.
 */
require_once(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'loader.php');

/**
 * Output the generated and tidied output.
 */
echo $output;
?>
