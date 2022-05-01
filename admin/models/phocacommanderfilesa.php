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

class PhocaCommanderCpModelPhocaCommanderFilesA extends ListModel
{
	protected	$option 		= 'com_phocacommander';
	/*
	public function checkState($panel, $ordering, $direction, $panelA, $panelB) {
		$app = Factory::getApplication('administrator');

		if ($direction == '') {
			$direction = $app->getUserStateFromRequest( $this->context.$panel.'direction', 'direction' );
		} else {
			$direction = $app->getUserStateFromRequest( $this->context.$panel.'direction', 'direction', $direction, 'string' );
		}

		$this->setState('direction', $direction);

		if ($ordering == '') {
			$ordering = $app->getUserStateFromRequest($this->context.$panel.'.ordering', 'ordering');
		} else {
			$ordering = $app->getUserStateFromRequest($this->context.$panel.'.ordering', 'ordering', $ordering, 'string');
		}

		$this->setState('ordering', $ordering);

		if ($panelA == '') {
			$panelA = $app->getUserStateFromRequest($this->context.$panel.'.panela', 'panela');
		} else {
			$panelA = $app->getUserStateFromRequest($this->context.$panel.'.panela', 'panela', $panelA, 'string');
		}

		$this->setState('panela', $panelA);

		if ($panelB == '') {
			$panelB = $app->getUserStateFromRequest($this->context.$panel.'.panelb', 'panelb');
		} else {
			$panelB = $app->getUserStateFromRequest($this->context.$panel.'.panelb', 'panelb', $panelB, 'string');
		}

		$this->setState('panelb', $panelB);
	}*/

	public function sortItems (&$array, $key, $dir = 'ASC') {
		$sorter=array();
		$ret=array();
		reset($array);
		foreach ($array as $ii => $va) {
			$sorter[$ii]=$va[$key];
		}
		if ($dir == 'ASC') {
			asort($sorter);
		} else {
			arsort($sorter);
		}

		foreach ($sorter as $ii => $va) {
			$ret[$ii]=$array[$ii];
		}
		$array=$ret;
	}


}
?>
