<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die( 'Restricted access' );
jimport('joomla.client.helper');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

class PhocaCommanderCpControllerPhocaCommanderUpload extends PhocaCommanderCpController
{
	function __construct() {
		parent::__construct();
	}

	function multipleupload() {
		$result = PhocaCommanderFileUpload::realMultipleUpload();
		return true;	
	}
	
	function upload() {
		$result = PhocaCommanderFileUpload::realSingleUpload();
		return true;
	}
	
	
}