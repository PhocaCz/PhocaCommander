<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Toolbar\Toolbar;
jimport( 'joomla.application.component.view' );

class PhocaCommanderCpViewPhocaCommanderInfo extends HtmlView
{
	protected $t;
	protected $r;

	function display($tpl = null) {

		$this->t	= PhocaCommanderHelper::setVars('info');
		$this->r = new PhocaCommanderRenderAdminView();
		$this->t['component_head'] 	= 'COM_PHOCACOMMANDER_PHOCA_COMMANDER';
		$this->t['component_links']	= $this->r->getLinks(1);

		$this->t['version'] = PhocaCommanderHelper::getExtensionVersion();
		$this->addToolbar();
		parent::display($tpl);
	}

	protected function addToolbar() {
		require_once JPATH_COMPONENT.'/helpers/phocacommandercp.php';
		$class	= 'PhocaCommanderCpHelper';
		$canDo	= $class::getActions('com_phocacommander');

		ToolbarHelper::title( Text::_('COM_PHOCACOMMANDER_CM_INFO' ), 'info.png' );

		// This button is unnecessary but it is displayed because Joomla! design bug
		$bar = Toolbar::getInstance( 'toolbar' );
		$dhtml = '<a href="index.php?option=com_phocacommander" class="btn btn-small"><i class="icon-home-2" title="'.Text::_('COM_PHOCACOMMANDER').'"></i> '.Text::_('COM_PHOCACOMMANDER').'</a>';
		$bar->appendButton('Custom', $dhtml);

		if ($canDo->get('core.admin')) {
			ToolbarHelper::preferences('com_phocacommander');
		}
		ToolbarHelper::divider();
		ToolbarHelper::help( 'screen.phocacommander', true );
		//JToolbarHelper::cancel('phocacommanderinfo.cancel', 'JTOOLBAR_CLOSE');
	}
}
?>
