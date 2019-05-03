<?php
/*
 * @package		Joomla.Framework
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 *
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License version 2 or later;
 */
defined('_JEXEC') or die;
jimport('joomla.application.component.view');


class PhocaCommanderCpViewPhocaCommanderEdit extends JViewLegacy
{
	protected $item;
	protected $form;
	protected $state;
	protected $t;
	protected $source;

	public function display($tpl = null)
	{
		JHTML::stylesheet('media/com_phocacommander/css/administrator/phocacommander.css' );

		$app   					= JFactory::getApplication();
		$this->t['fullfile']	= $app->input->get( 'file', '', 'string'  );

		$context 				= 'com_phocacommander.phocacommander.';
		$this->t['orderinga'] 	= $app->getUserStateFromRequest($context .'orderinga', 'orderinga', '', 'string');
		$this->t['orderingb'] 	= $app->getUserStateFromRequest($context .'orderingb', 'orderingb', '', 'string');
		$this->t['directiona'] 	= $app->getUserStateFromRequest($context .'directiona', 'directiona', '', 'string');
		$this->t['directionb'] 	= $app->getUserStateFromRequest($context .'directionb', 'directionb', '', 'string');
		$this->t['foldera'] 	= $app->getUserStateFromRequest($context .'foldera', 'foldera', '', 'string');
		$this->t['folderb'] 	= $app->getUserStateFromRequest($context .'folderb', 'folderb', '', 'string');
		$this->t['panel'] 		= $app->getUserStateFromRequest($context .'panel', 'panel', '', 'string');
		$this->t['activepanel'] = $app->getUserStateFromRequest($context .'activepanel', 'activepanel', '', 'string');

		$this->form		= $this->get('Form');
		$this->ftp		= JClientHelper::setCredentialsFromRequest('ftp');
		$model 			= $this->getModel();

		$fileWithPath	= base64_decode($this->t['fullfile']);

		$file			= explode('/', $fileWithPath);
		$this->t['file']= '';
		if(is_array($file)) {
			$c = count($file);
			$c--;
			$this->t['file'] = $file[$c];
		}

		// Set CSS for codemirror
		//JFactory::getApplication()->setUserState('editor.source.syntax', '');

		$this->form->setValue('id', null, 1);
		$this->form->setValue('filename', null, base64_encode($fileWithPath));

		$this->source	= $model->getSource($this->form->getValue('filename'));

		if (!isset($this->source->source)) {
			throw new Exception(JText::_('COM_PHOCACOMMANDER_FILE_DOES_NOT_EXIST'), 500);
			return false;
		}

		$this->form->setValue('source', null, $this->source->source);

		if (count($errors = $this->get('Errors'))) {
			throw new Exception(implode("\n", $errors), 500);
			return false;
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	protected function addToolbar() {

		require_once JPATH_COMPONENT.'/helpers/phocacommanderedit.php';
		JFactory::getApplication()->input->set('hidemainmenu', true);
		$bar 		= JToolbar::getInstance('toolbar');
		$user		= JFactory::getUser();
		$canDo		= PhocaCommanderEditHelper::getActions();

		$text = JText::_('COM_PHOCACOMMANDER_EDIT');
		JToolbarHelper::title(   JText::_( 'COM_PHOCACOMMANDER_FILE' ).': <small><small>[ ' . $text.' ]</small></small>' , 'file');

		// If not checked out, can save the item.
		if ($canDo->get('core.edit')){
			JToolbarHelper::apply('phocacommanderedit.apply', 'JTOOLBAR_APPLY');
			JToolbarHelper::save('phocacommanderedit.save', 'JTOOLBAR_SAVE');
		}

		JToolbarHelper::cancel('phocacommanderedit.cancel', 'JTOOLBAR_CLOSE');
		JToolbarHelper::divider();
		JToolbarHelper::help( 'screen.phocacommander', true );
	}

}
?>
