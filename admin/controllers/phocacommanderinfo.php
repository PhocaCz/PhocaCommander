<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();
use Joomla\CMS\Factory;

class PhocaCommanderCpControllerPhocaCommanderinfo extends PhocaCommanderCpController
{
	function __construct() {
	
		parent::__construct();
	}
	
	function cancel($key = NULL) {
		$app = Factory::getApplication('administrator'); 
$context = 'com_phocacommander.write.';
$abc = 'TOURDS';
			$a = $app->getUserStateFromRequest( $context.'from', 'from', $abc, 'string' );
		
	
		$this->setRedirect( 'index.php?option=com_phocacommander' );
	}
}
?>
