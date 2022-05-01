<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_JEXEC' ) or die();
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Factory;
jimport( 'joomla.application.component.modellist' );
jimport( 'joomla.filesystem.folder' );
jimport( 'joomla.filesystem.file' );

class PhocaCommanderCpModelPhocaCommanderCp extends ListModel
{
	protected	$option 		= 'com_phocacommander';

	public function checkState() {
		$app = Factory::getApplication('administrator');

	/*	$ab = $app->getUserState('cv');
		$panelA = $this->state->get('cv');

		if ($panelA == '') {
			$panelA = $app->getUserStateFromRequest('cv', 'panelg','acd', 'string');
		}


$this->setState('cv', $panelA);
//$panelc = $app->getUserStateFromRequest('cv', 'panelg');

		*/
		$app->getUserStateFromRequest( 'com_phocacommander.cv', 'panelg', 'baf', 'string' );

	}




}
?>
