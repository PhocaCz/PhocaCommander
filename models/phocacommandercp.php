<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_JEXEC' ) or die();
jimport( 'joomla.application.component.modellist' );
jimport( 'joomla.filesystem.folder' );
jimport( 'joomla.filesystem.file' );

class PhocaCommanderCpModelPhocaCommanderCp extends JModelList
{
	protected	$option 		= 'com_phocacommander';
	
	public function checkState() {
		$app = JFactory::getApplication('administrator');
		
	/*	$ab = $app->getUserState('cv');
		$panelA = $this->state->get('cv');
		krumo($ab, $panelA);
		if ($panelA == '') {
			$panelA = $app->getUserStateFromRequest('cv', 'panelg','acd', 'string');
		}
		
		//krumo($panelA);
$this->setState('cv', $panelA);
//$panelc = $app->getUserStateFromRequest('cv', 'panelg');

		print_r($this->state);*/
		$app->getUserStateFromRequest( 'com_phocacommander.cv', 'panelg', 'baf', 'string' );
		
	}
	

	
	
}
?>