<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();
jimport( 'joomla.application.component.view' );
jimport( 'joomla.html.pane' );

class PhocaCommanderCpViewPhocaCommanderCp extends JViewLegacy
{
	protected $t;
	protected $views;

	function display($tpl = null) {

		$app			= JFactory::getApplication();
		$document		= JFactory::getDocument();
		$paramsC 		= JComponentHelper::getParams('com_phocacommander');

		$this->t['experimental_zip']		= $paramsC->get( 'experimental_zip', 0 );
		$this->t['display_upload_button']	= $paramsC->get( 'display_upload_button', 1 );
		$this->t['display_download_button']	= $paramsC->get( 'display_download_button', 0 );
		//JHtml::_('bootstrap.loadCss');
		JHtml::_('jquery.framework', false);

		$this->t['version'] = PhocaCommanderHelper::getExtensionVersion();

		JHTML::stylesheet( 'media/com_phocacommander/css/administrator/phocacommander.css' );
		JHTML::stylesheet( 'media/com_phocacommander/css/administrator/jquery-ui.css' );
		JHTML::stylesheet( 'media/com_phocacommander/css/administrator/phoca-ui.css' );
		JHTML::stylesheet( 'media/com_phocacommander/js/administrator/prettyphoto/css/prettyPhoto.css' );

		$document->addScript(JURI::root(true).'/media/com_phocacommander/js/administrator/jquery.base64.js');
		$document->addScript(JURI::root(true).'/media/com_phocacommander/js/administrator/jquery-ui.min.js');
		$document->addScript(JURI::root(true).'/media/com_phocacommander/js/administrator/prettyphoto/js/jquery.prettyPhoto.js');

		$model = $this->getModel();
		$model->checkState();

		$app   			= JFactory::getApplication();
		$context 				= 'com_phocacommander.phocacommander.';
		$this->t['orderinga'] 	= $app->getUserStateFromRequest($context .'orderinga', 'orderinga', '', 'string');
		$this->t['orderingb'] 	= $app->getUserStateFromRequest($context .'orderingb', 'orderingb', '', 'string');
		$this->t['directiona'] 	= $app->getUserStateFromRequest($context .'directiona', 'directiona', '', 'string');
		$this->t['directionb'] 	= $app->getUserStateFromRequest($context .'directionb', 'directionb', '', 'string');
		$this->t['foldera'] 	= $app->getUserStateFromRequest($context .'foldera', 'foldera', '', 'string');
		$this->t['folderb'] 	= $app->getUserStateFromRequest($context .'folderb', 'folderb', '', 'string');
		$this->t['panel'] 		= $app->getUserStateFromRequest($context .'panel', 'panel', '', 'string');
		$this->t['activepanel'] = $app->getUserStateFromRequest($context .'activepanel', 'activepanel', '', 'string');
		if($this->t['activepanel'] == '')	{$this->t['activepanel'] = 'A';}
		if($this->t['orderinga'] == '') 	{$this->t['orderinga'] = 'name';}
		if($this->t['orderingb'] == '') 	{$this->t['orderingb'] = 'name';}
		if($this->t['directiona'] == '') 	{$this->t['directiona'] = 'ASC';}
		if($this->t['directionb'] == '') 	{$this->t['directionb'] = 'ASC';}


		// - - - - - - - - - - -
		// Multiple Upload
		// - - - - - - - - - - -
		// Get infos from multiple upload

		$this->t['mu_response_msg']			= $muUploadedMsg 	= '';
		$this->t['multipleuploadchunk']		= $paramsC->get( 'multiple_upload_chunk', 0 );
		$this->t['uploadmaxsize'] 			= $paramsC->get( 'upload_maxsize', 3145728 );
		$this->t['uploadmaxsizeread'] 		= PhocaCommanderHelper::getFileSizeReadable($this->t['uploadmaxsize']);
		$this->t['enablemultiple'] 			= 1;
		$this->t['multipleuploadmethod'] 	= $paramsC->get( 'multiple_upload_method', 4 );
		$this->session		= JFactory::getSession();
		PhocaCommanderFileUploadMultiple::renderMultipleUploadLibraries();
		$this->manager 		= $app->input->get( 'manager', '', '', 'file' );
		$this->field	= $app->input->get('field');
		$this->currentFolder = '';

		/*if ($muUploaded > 0) {
			$muUploadedMsg = JText::_('COM_PHOCADOWNLOAD_COUNT_UPLOADED_FILE'). ': ' . $muUploaded;
		}
		if ($muFailed > 0) {
			$muFailedMsg = JText::_('COM_PHOCADOWNLOAD_COUNT_NOT_UPLOADED_FILE'). ': ' . $muFailed;
		}
		if ($muFailed > 0 && $muUploaded > 0) {
			$this->t['mu_response_msg'] = '<div class="alert alert-info">'
			.'<button type="button" class="close" data-dismiss="alert">&times;</button>'
			.JText::_('COM_PHOCADOWNLOAD_COUNT_UPLOADED_FILE'). ': ' . $muUploaded .'<br />'
			.JText::_('COM_PHOCADOWNLOAD_COUNT_NOT_UPLOADED_FILE'). ': ' . $muFailed.'</div>';
		} else if ($muFailed > 0 && $muUploaded == 0) {
			$this->t['mu_response_msg'] = '<div class="alert alert-error">'
			.'<button type="button" class="close" data-dismiss="alert">&times;</button>'
			.JText::_('COM_PHOCADOWNLOAD_COUNT_NOT_UPLOADED_FILE'). ': ' . $muFailed.'</div>';
		} else if ($muFailed == 0 && $muUploaded > 0){
			$this->t['mu_response_msg'] = '<div class="alert alert-success">'
			.'<button type="button" class="close" data-dismiss="alert">&times;</button>'
			.JText::_('COM_PHOCADOWNLOAD_COUNT_UPLOADED_FILE'). ': ' . $muUploaded.'</div>';
		} else {
			$this->t['mu_response_msg'] = '';
		}*/


		$mU						= new PhocaCommanderFileUploadMultiple();
		$mU->frontEnd			= 0;
		$mU->method				= $this->t['multipleuploadmethod'];
		$mU->url				= JURI::base().'index.php?option=com_phocacommander&task=phocacommanderupload.multipleupload&amp;'
								 .$this->session->getName().'='.$this->session->getId().'&'
								 . JSession::getFormToken().'=1';
		$mU->maxFileSize		= PhocaCommanderFileUploadMultiple::getMultipleUploadSizeFormat($this->t['uploadmaxsize']);
		$mU->chunkSize			= '1mb';

		$mU->renderMultipleUploadJS(0, $this->t['multipleuploadchunk']);
		$this->t['mu_output']= $mU->getMultipleUploadHTML();



		$this->t['ftp'] 			= !JClientHelper::hasCredentials('ftp');
		$this->t['path']			= JPATH_ROOT;


		$this->addToolbar();
		parent::display($tpl);
	}

	protected function addToolbar() {

		$paramsC 		= JComponentHelper::getParams('com_phocacommander');
		$fKeys 	= $paramsC->get( 'f_keys', 1 );
		if ($fKeys) {
			$f[1] = 'COM_PHOCACOMMANDER_F1_ATTRIBUTES';
			$f[2] = 'COM_PHOCACOMMANDER_F2_RENAME';
			$f[3] = 'COM_PHOCACOMMANDER_F3_VIEW';
			$f[4] = 'COM_PHOCACOMMANDER_F4_EDIT';
			$f[5] = 'COM_PHOCACOMMANDER_F5_COPY';
			$f[6] = 'COM_PHOCACOMMANDER_F6_MOVE';
			$f[7] = 'COM_PHOCACOMMANDER_F7_NEW_FOLDER';
			$f[8] = 'COM_PHOCACOMMANDER_F8_DELETE';
			$f[9] = 'COM_PHOCACOMMANDER_F9_UNPACK';
			$f[10] = 'COM_PHOCACOMMANDER_F10_UPLOAD';
			$f[11] = 'COM_PHOCACOMMANDER_PACK';
			$f[12] = 'COM_PHOCACOMMANDER_DOWNLOAD';
		} else {
			$f[1] = 'COM_PHOCACOMMANDER_F1';
			$f[2] = 'COM_PHOCACOMMANDER_F2';
			$f[3] = 'COM_PHOCACOMMANDER_F3';
			$f[4] = 'COM_PHOCACOMMANDER_F4';
			$f[5] = 'COM_PHOCACOMMANDER_F5';
			$f[6] = 'COM_PHOCACOMMANDER_F6';
			$f[7] = 'COM_PHOCACOMMANDER_F7';
			$f[8] = 'COM_PHOCACOMMANDER_F8';
			$f[9] = 'COM_PHOCACOMMANDER_F9';
			$f[10] = 'COM_PHOCACOMMANDER_F10';
			$f[11] = 'COM_PHOCACOMMANDER_PACK';
			$f[12] = 'COM_PHOCACOMMANDER_DOWNLOAD';
		}
		require_once JPATH_COMPONENT.'/helpers/phocacommandercp.php';
		$class	= 'PhocaCommanderCpHelper';
		$canDo	= $class::getActions('com_phocacommander');
		JToolbarHelper::title( JText::_( 'COM_PHOCACOMMANDER' ), 'home-2 cpanel' );

		// This button is unnecessary but it is displayed because Joomla! design bug
		$bar = JToolbar::getInstance( 'toolbar' );
		$dhtml = '<a href="index.php?option=com_phocacommander" class="btn btn-small"><i class="icon-home-2" title="'.JText::_('COM_PHOCACOMMANDER').'"></i> '. ' '.'</a>';
		$bar->appendButton('Custom', $dhtml);

		JToolbarHelper::divider();

		$dhtml = '<a href="javascript: void(0)" onclick="phDoAction(\'attributes\')" class="btn btn-small"><i class="icon-list" title="'.JText::_('COM_PHOCACOMMANDER_F1_ATTRIBUTES').'"></i> '.JText::_($f[1]).'</a>';
		$bar->appendButton('Custom', $dhtml);

		$dhtml = '<a href="javascript: void(0)" onclick="phDoAction(\'rename\')" class="btn btn-small"><i class="icon-pencil-2" title="'.JText::_('COM_PHOCACOMMANDER_F2_RENAME').'"></i> '.JText::_($f[2]).'</a>';
		$bar->appendButton('Custom', $dhtml);

		$dhtml = '<a href="javascript: void(0)" onclick="phDoAction(\'view\')" class="btn btn-small"><i class="icon-search" title="'.JText::_('COM_PHOCACOMMANDER_F3_VIEW').'"></i> '.JText::_($f[3]).'</a>';
		$bar->appendButton('Custom', $dhtml);

		$dhtml = '<a href="javascript: void(0)" onclick="phDoAction(\'edit\')" class="btn btn-small"><i class="icon-edit" title="'.JText::_('COM_PHOCACOMMANDER_F4_EDIT').'"></i> '.JText::_($f[4]).'</a>';
		$bar->appendButton('Custom', $dhtml);

		$dhtml = '<a href="javascript: void(0)" onclick="phDoAction(\'copy\')" class="btn btn-small" id="btn-copy"><i class="icon-copy" title="'.JText::_('COM_PHOCACOMMANDER_F5_COPY').'"></i> '.JText::_($f[5]).'</a>';
		$bar->appendButton('Custom', $dhtml);

		$dhtml = '<a href="javascript: void(0)" onclick="phDoAction(\'move\')" class="btn btn-small" id="btn-move"><i class="icon-move" title="'.JText::_('COM_PHOCACOMMANDER_F6_MOVE').'"></i> '.JText::_($f[6]).'</a>';
		$bar->appendButton('Custom', $dhtml);

		$dhtml = '<a href="javascript: void(0)" onclick="phDoAction(\'new\')" class="btn btn-small" id="btn-new"><i class="icon-folder-close" title="'.JText::_('COM_PHOCACOMMANDER_F7_NEW_FOLDER').'"></i> '.JText::_($f[7]).'</a>';
		$bar->appendButton('Custom', $dhtml);

		$dhtml = '<a href="javascript: void(0)" onclick="phDoAction(\'delete\')" class="btn btn-small" id="btn-delete"><i class="icon-delete" title="'.JText::_('COM_PHOCACOMMANDER_F8_DELETE').'"></i> '.JText::_($f[8]).'</a>';
		$bar->appendButton('Custom', $dhtml);

		$dhtml = '<a href="javascript: void(0)" onclick="phDoAction(\'unpack\')" class="btn btn-small btn-default"><i class="icon-box-remove" title="'.JText::_('COM_PHOCACOMMANDER_F9_UNZIP').'"></i> '.JText::_($f[9]).'</a>';
		$bar->appendButton('Custom', $dhtml);

		if ($this->t['display_upload_button'] == 1) {
			$dhtml = '<a href="javascript: void(0)" onclick="phDoAction(\'upload\')" class="btn btn-small btn-default"><i class="icon-upload" title="'.JText::_('COM_PHOCACOMMANDER_F10_UPLOAD').'"></i> '.JText::_($f[10]).'</a>';
			$bar->appendButton('Custom', $dhtml);
		}
		if ($this->t['display_download_button'] == 1) {
			$dhtml = '<a href="javascript: void(0)" onclick="phDoAction(\'download\')" class="btn btn-small"><i class="icon-download" title="'.JText::_('COM_PHOCACOMMANDER_DOWNLOAD').'"></i> '.JText::_($f[12]).'</a>';
			$bar->appendButton('Custom', $dhtml);
		}
		if ($this->t['experimental_zip'] == 1) {
			$dhtml = '<a href="javascript: void(0)" onclick="phDoAction(\'pack\')" class="btn btn-small btn-default"><i class="icon-box-add" title="'.JText::_('COM_PHOCACOMMANDER_PACK').'"></i> '.JText::_($f[11]).'</a>';
			$bar->appendButton('Custom', $dhtml);

		}


		JToolbarHelper::divider();

		if ($canDo->get('core.admin')) {
			JToolbarHelper::preferences('com_phocacommander');
			JToolbarHelper::divider();
		}
		JToolbarHelper::help( 'screen.phocacommander', true );

		$dhtml = '<a href="index.php?option=com_phocacommander&view=phocacommanderinfo" class="btn btn-small btn-primary"><i class="icon-info" title="'.JText::_('COM_PHOCACOMMANDER_INFO').'"></i> '.JText::_('COM_PHOCACOMMANDER_INFO').'</a>';
		$bar->appendButton('Custom', $dhtml);
	}
}
?>
