<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die;
class PhocaCommanderResponse
{
	function __construct() {}
	
	public function _($status, $message) {
		$o = '';
		if ($status == 1) {
			$response = array(
			'status' => '1',
			'message' => '<span class="ph-result-txt ph-success-txt">' . $message . '</span>');
			return json_encode($response);
		} else {
			$response = array(
			'status' => '0',
			'error' => '<span class="ph-result-txt ph-error-txt">' . $message . '</span>');
			return json_encode($response);
		}
	}
}
?>