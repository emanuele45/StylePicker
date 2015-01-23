<?php
/**
 * @name      OpenImporter
 * @copyright OpenImporter contributors
 * @license   BSD http://opensource.org/licenses/BSD-3-Clause
 *
 * @version 1.0 Alpha
 */

/**
 * Just define ELK and require the files
 */
if (!defined('ELK'))
	define('ELK', 1);
if (!defined('BASEDIR'))
	define('BASEDIR', __DIR__ . '/../src');

require_once(__DIR__ . '/../src/StylePicker.class.php');

/**
 * Dummy function
 */
function loadLanguage($lang)
{
	require_once(BASEDIR . '/StylePicker.english.php');
}

function loadTemplate($file)
{
	require_once(BASEDIR . '/StylePicker.template.php');
}