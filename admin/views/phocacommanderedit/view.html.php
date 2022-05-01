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
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Factory;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Client\ClientHelper;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;
jimport('joomla.application.component.view');
use Joomla\CMS\HTML\HTMLHelper;

class PhocaCommanderCpViewPhocaCommanderEdit extends HtmlView
{
	protected $item;
	protected $form;
	protected $state;
	protected $t;
	protected $r;
	protected $source;

	public function display($tpl = null)
	{
		$document 			= Factory::getDocument();
		$paramsC 			= ComponentHelper::getParams('com_phocacommander');

		$this->r = new PhocaCommanderRenderAdminView();


		$oVars   = array();
        $oLang   = array();
        $oParams = array();
        $oLang   = array('COM_PHOCACOMMANDER_ERROR_SAVING_FILE' => Text::_('COM_PHOCACOMMANDER_ERROR_SAVING_FILE'));


        //$oVars['token']         = JSession::getFormToken();
        $oVars['urleditsave'] = Uri::base(true) . '/index.php?option=com_phocacommander&task=phocacommanderedit.save&format=json&' . Session::getFormToken() . '=1';
		$document->addScriptOptions('phLangCM', $oLang);
		$document->addScriptOptions('phVarsCM', $oVars);

		$app   					= Factory::getApplication();
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
		$this->ftp		= ClientHelper::setCredentialsFromRequest('ftp');
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
			throw new Exception(Text::_('COM_PHOCACOMMANDER_FILE_DOES_NOT_EXIST'), 500);
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
		Factory::getApplication()->input->set('hidemainmenu', true);
		$bar 		= Toolbar::getInstance('toolbar');
		$user		= Factory::getUser();
		$canDo		= PhocaCommanderEditHelper::getActions();

		$text = Text::_('COM_PHOCACOMMANDER_EDIT');
		ToolbarHelper::title(   Text::_( 'COM_PHOCACOMMANDER_FILE' ).': <small><small>[ ' . $text.' ]</small></small>' , 'file');

		// If not checked out, can save the item.
		if ($canDo->get('core.edit')){
			ToolbarHelper::apply('phocacommanderedit.apply', 'JTOOLBAR_APPLY');
			ToolbarHelper::save('phocacommanderedit.save', 'JTOOLBAR_SAVE');
		}

		ToolbarHelper::cancel('phocacommanderedit.cancel', 'JTOOLBAR_CLOSE');
		ToolbarHelper::divider();
		ToolbarHelper::help( 'screen.phocacommander', true );
	}

}
?>
