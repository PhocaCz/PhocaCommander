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
use Joomla\CMS\Factory;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Client\ClientHelper;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Toolbar\Toolbar;
jimport( 'joomla.application.component.view' );
jimport( 'joomla.html.pane' );
use Joomla\CMS\HTML\HTMLHelper;

class PhocaCommanderCpViewPhocaCommanderCp extends HtmlView
{
	protected $t;
	protected $r;
	protected $views;

	function display($tpl = null) {

		$app			= Factory::getApplication();
		$document		= Factory::getDocument();
		$paramsC 		= ComponentHelper::getParams('com_phocacommander');

		$this->t['experimental_zip']		= $paramsC->get( 'experimental_zip', 0 );
		$this->t['display_upload_button']	= $paramsC->get( 'display_upload_button', 1 );
		$this->t['display_download_button']	= $paramsC->get( 'display_download_button', 0 );



		$this->t['version'] = PhocaCommanderHelper::getExtensionVersion();

		$this->r = new PhocaCommanderRenderAdminView();







		$model = $this->getModel();
		$model->checkState();

		$app   			= Factory::getApplication();
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
		$this->session		= Factory::getSession();
		PhocaCommanderFileUploadMultiple::renderMultipleUploadLibraries();
		$this->manager 		= $app->input->get( 'manager', '', '', 'file' );
		$this->field	= $app->input->get('field');
		$this->currentFolder = '';

		/*if ($muUploaded > 0) {
			$muUploadedMsg = Text::_('COM_PHOCADOWNLOAD_COUNT_UPLOADED_FILE'). ': ' . $muUploaded;
		}
		if ($muFailed > 0) {
			$muFailedMsg = Text::_('COM_PHOCADOWNLOAD_COUNT_NOT_UPLOADED_FILE'). ': ' . $muFailed;
		}
		if ($muFailed > 0 && $muUploaded > 0) {
			$this->t['mu_response_msg'] = '<div class="alert alert-info">'
			.'<button type="button" class="close" data-dismiss="alert">&times;</button>'
			.Text::_('COM_PHOCADOWNLOAD_COUNT_UPLOADED_FILE'). ': ' . $muUploaded .'<br />'
			.Text::_('COM_PHOCADOWNLOAD_COUNT_NOT_UPLOADED_FILE'). ': ' . $muFailed.'</div>';
		} else if ($muFailed > 0 && $muUploaded == 0) {
			$this->t['mu_response_msg'] = '<div class="alert alert-error">'
			.'<button type="button" class="close" data-dismiss="alert">&times;</button>'
			.Text::_('COM_PHOCADOWNLOAD_COUNT_NOT_UPLOADED_FILE'). ': ' . $muFailed.'</div>';
		} else if ($muFailed == 0 && $muUploaded > 0){
			$this->t['mu_response_msg'] = '<div class="alert alert-success">'
			.'<button type="button" class="close" data-dismiss="alert">&times;</button>'
			.Text::_('COM_PHOCADOWNLOAD_COUNT_UPLOADED_FILE'). ': ' . $muUploaded.'</div>';
		} else {
			$this->t['mu_response_msg'] = '';
		}*/


		$mU						= new PhocaCommanderFileUploadMultiple();
		$mU->frontEnd			= 0;
		$mU->method				= $this->t['multipleuploadmethod'];
		$mU->url				= Uri::base().'index.php?option=com_phocacommander&task=phocacommanderupload.multipleupload&amp;'
								 .$this->session->getName().'='.$this->session->getId().'&'
								 . Session::getFormToken().'=1';
		$mU->maxFileSize		= PhocaCommanderFileUploadMultiple::getMultipleUploadSizeFormat($this->t['uploadmaxsize']);
		$mU->chunkSize			= '1mb';

		$mU->renderMultipleUploadJS(0, $this->t['multipleuploadchunk']);
		$this->t['mu_output']= $mU->getMultipleUploadHTML();



		$this->t['ftp'] 			= !ClientHelper::hasCredentials('ftp');
		$this->t['path']			= JPATH_ROOT;




		/* JS */
		$this->t['url'] = 'index.php?option=com_phocacommander&view=phocacommanderfilesa&format=json&tmpl=component&'. Session::getFormToken().'=1';
		$this->t['urlaction'] = 'index.php?option=com_phocacommander&view=phocacommanderactiona&format=json&tmpl=component&'. Session::getFormToken().'=1';
		$this->t['urledit'] = 'index.php?option=com_phocacommander&task=phocacommanderedit.edit&'. Session::getFormToken().'=1';
		$this->t['urladmin'] = 'index.php';



		$oVars   = array();
        $oLang   = array();
        $oParams = array();
        $oLang   = array(
        	'COM_PHOCACOMMANDER_ERROR_SAVING_FILE' => Text::_('COM_PHOCACOMMANDER_ERROR_SAVING_FILE'),
			'COM_PHOCACOMMANDER_UPDATING' => Text::_('COM_PHOCACOMMANDER_UPDATING'),
			'COM_PHOCACOMMANDER_SERVER_ERROR' => Text::_('COM_PHOCACOMMANDER_SERVER_ERROR'),
			'COM_PHOCACOMMANDER_CREATE' => Text::_('COM_PHOCACOMMANDER_CREATE'),
			'COM_PHOCACOMMANDER_JOOMLA_ROOT_FOLDER' => Text::_('COM_PHOCACOMMANDER_JOOMLA_ROOT_FOLDER'),
			'COM_PHOCACOMMANDER_FOLDER' => Text::_('COM_PHOCACOMMANDER_FOLDER'),
			'COM_PHOCACOMMANDER_NO_FILE_NO_FOLDER_SELECTED' => Text::_('COM_PHOCACOMMANDER_NO_FILE_NO_FOLDER_SELECTED'),
			'COM_PHOCACOMMANDER_FOLDER_CANNOT_BE_PREVIEWED_OR_EDITED' => Text::_('COM_PHOCACOMMANDER_FOLDER_CANNOT_BE_PREVIEWED_OR_EDITED'),
			'COM_PHOCACOMMANDER_FOLDER_CANNOT_BE_DOWNLOADED' => Text::_('COM_PHOCACOMMANDER_FOLDER_CANNOT_BE_DOWNLOADED'),
			'COM_PHOCACOMMANDER_ONLY_ONE_FILE_OR_FOLDER_NEEDS_TO_BE_SELECTED' => Text::_('COM_PHOCACOMMANDER_ONLY_ONE_FILE_OR_FOLDER_NEEDS_TO_BE_SELECTED'),
			'COM_PHOCACOMMANDER_ONLY_ONE_FILE_NEEDS_TO_BE_SELECTED' => Text::_('COM_PHOCACOMMANDER_ONLY_ONE_FILE_NEEDS_TO_BE_SELECTED'),
			'COM_PHOCACOMMANDER_FILES_FOLDERS_SM' => Text::_('COM_PHOCACOMMANDER_FILES_FOLDERS_SM'),
			'COM_PHOCACOMMANDER_FOLDER_SM' => Text::_('COM_PHOCACOMMANDER_FOLDER_SM'),
			'COM_PHOCACOMMANDER_TO' => Text::_('COM_PHOCACOMMANDER_TO'),
			'COM_PHOCACOMMANDER_OK' => Text::_('COM_PHOCACOMMANDER_OK'),
			'COM_PHOCACOMMANDER_ARE_YOU_SURE_COPY' => Text::_('COM_PHOCACOMMANDER_ARE_YOU_SURE_COPY'),
			'COM_PHOCACOMMANDER_ARE_YOU_SURE_MOVE' => Text::_('COM_PHOCACOMMANDER_ARE_YOU_SURE_MOVE'),
			'COM_PHOCACOMMANDER_ARE_YOU_SURE_DELETE' => Text::_('COM_PHOCACOMMANDER_ARE_YOU_SURE_DELETE'),
			'COM_PHOCACOMMANDER_PERMANENTLY_REMOVE_WARNING' => Text::_('COM_PHOCACOMMANDER_PERMANENTLY_REMOVE_WARNING'),
			'COM_PHOCACOMMANDER_RENAME' => Text::_('COM_PHOCACOMMANDER_RENAME'),
			'COM_PHOCACOMMANDER_NEW_ATTRIBUTE' => Text::_('COM_PHOCACOMMANDER_NEW_ATTRIBUTE'),
			'COM_PHOCACOMMANDER_SET_NEW_ATTRIBUTE_FOR' => Text::_('COM_PHOCACOMMANDER_SET_NEW_ATTRIBUTE_FOR'),
			'COM_PHOCACOMMANDER_ARE_YOU_SURE_UNPACK' => Text::_('COM_PHOCACOMMANDER_ARE_YOU_SURE_UNPACK'),
			'COM_PHOCACOMMANDER_EXTRACTED_FILES_OVERWRITE_EXISTING_FILES_WARNING' => Text::_('COM_PHOCACOMMANDER_EXTRACTED_FILES_OVERWRITE_EXISTING_FILES_WARNING'),
			'COM_PHOCACOMMANDER_ONLY_ARCHIVE_FILE_CAN_BE_UNPACKED' => Text::_('COM_PHOCACOMMANDER_ONLY_ARCHIVE_FILE_CAN_BE_UNPACKED'),
			'COM_PHOCACOMMANDER_PACK' => Text::_('COM_PHOCACOMMANDER_PACK'),
			'COM_PHOCACOMMANDER_ONLY_IMAGES_CAN_BE_PREVIEWED' => Text::_('COM_PHOCACOMMANDER_ONLY_IMAGES_CAN_BE_PREVIEWED'),
			'COM_PHOCACOMMANDER_CANCEL' => Text::_('COM_PHOCACOMMANDER_CANCEL')
		);

		$session = Factory::getSession();
		$w = $session->get('ww', false, 'com_phocacommander.phocacommander');
		if(!$w){
			$oVars['welcomewarning'] = 0;
			$session->set('ww', true, 'com_phocacommander.phocacommander');
		} else {
			$oVars['welcomewarning'] = 1;
		}

		$oVars['activepanel']		= $this->t['activepanel'];
		$oVars['panel']				= $this->t['panel'];
		$oVars['foldera']			= $this->t['foldera'];
		$oVars['folderb']			= $this->t['folderb'];
		$oVars['orderinga']			= $this->t['orderinga'];
		$oVars['orderingb']			= $this->t['orderingb'];
		$oVars['directiona']		= $this->t['directiona'];
		$oVars['directiona']		= $this->t['directiona'];

		$oVars['url']			= $this->t['url'];
		$oVars['urlaction']		= $this->t['urlaction'];
		$oVars['urledit']		= $this->t['urledit'];
		$oVars['urladmin']		= $this->t['urladmin'];
		$oVars['urlroot']		= Uri::root();




        //$oVars['token']         = JSession::getFormToken();
        $oVars['urleditsave'] = Uri::base(true) . '/index.php?option=com_phocacommander&task=phocacommanderedit.save&format=json&' . Session::getFormToken() . '=1';
		$document->addScriptOptions('phLangCM', $oLang);
		$document->addScriptOptions('phVarsCM', $oVars);


		$this->addToolbar();
		parent::display($tpl);
	}

	protected function addToolbar() {

		//Factory::getApplication()->input->set('hidemainmenu', true);

		$paramsC 		= ComponentHelper::getParams('com_phocacommander');
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
		ToolbarHelper::title( Text::_( 'COM_PHOCACOMMANDER' ), 'home-2 cpanel' );

		// This button is unnecessary but it is displayed because Joomla! design bug
		$bar = Toolbar::getInstance( 'toolbar' );
		$dhtml = '<a href="index.php?option=com_phocacommander" class="btn btn-small"><i class="icon-home-2" title="'.Text::_('COM_PHOCACOMMANDER').'"></i> '. ' '.'</a>';
		$bar->appendButton('Custom', $dhtml);

		ToolbarHelper::divider();

		$dhtml = '<a href="javascript: void(0)" onclick="phDoAction(\'attributes\')" class="btn btn-small"><i class="icon-list" title="'.Text::_('COM_PHOCACOMMANDER_F1_ATTRIBUTES').'"></i> '.Text::_($f[1]).'</a>';
		$bar->appendButton('Custom', $dhtml);

		$dhtml = '<a href="javascript: void(0)" onclick="phDoAction(\'rename\')" class="btn btn-small"><i class="icon-pencil-2" title="'.Text::_('COM_PHOCACOMMANDER_F2_RENAME').'"></i> '.Text::_($f[2]).'</a>';
		$bar->appendButton('Custom', $dhtml);

		$dhtml = '<a href="javascript: void(0)" onclick="phDoAction(\'view\')" class="btn btn-small"><i class="icon-search" title="'.Text::_('COM_PHOCACOMMANDER_F3_VIEW').'"></i> '.Text::_($f[3]).'</a>';
		$bar->appendButton('Custom', $dhtml);

		$dhtml = '<a href="javascript: void(0)" onclick="phDoAction(\'edit\')" class="btn btn-small"><i class="icon-edit" title="'.Text::_('COM_PHOCACOMMANDER_F4_EDIT').'"></i> '.Text::_($f[4]).'</a>';
		$bar->appendButton('Custom', $dhtml);

		$dhtml = '<a href="javascript: void(0)" onclick="phDoAction(\'copy\')" class="btn btn-small" id="btn-copy"><i class="icon-copy" title="'.Text::_('COM_PHOCACOMMANDER_F5_COPY').'"></i> '.Text::_($f[5]).'</a>';
		$bar->appendButton('Custom', $dhtml);

		$dhtml = '<a href="javascript: void(0)" onclick="phDoAction(\'move\')" class="btn btn-small" id="btn-move"><i class="icon-move" title="'.Text::_('COM_PHOCACOMMANDER_F6_MOVE').'"></i> '.Text::_($f[6]).'</a>';
		$bar->appendButton('Custom', $dhtml);

		$dhtml = '<a href="javascript: void(0)" onclick="phDoAction(\'new\')" class="btn btn-small" id="btn-new"><i class="icon-folder-close" title="'.Text::_('COM_PHOCACOMMANDER_F7_NEW_FOLDER').'"></i> '.Text::_($f[7]).'</a>';
		$bar->appendButton('Custom', $dhtml);

		$dhtml = '<a href="javascript: void(0)" onclick="phDoAction(\'delete\')" class="btn btn-small" id="btn-delete"><i class="icon-delete" title="'.Text::_('COM_PHOCACOMMANDER_F8_DELETE').'"></i> '.Text::_($f[8]).'</a>';
		$bar->appendButton('Custom', $dhtml);

		$dhtml = '<a href="javascript: void(0)" onclick="phDoAction(\'unpack\')" class="btn btn-small btn-default"><i class="icon-box-remove" title="'.Text::_('COM_PHOCACOMMANDER_F9_UNPACK').'"></i> '.Text::_($f[9]).'</a>';
		$bar->appendButton('Custom', $dhtml);

		if ($this->t['display_upload_button'] == 1) {
			$dhtml = '<a href="javascript: void(0)" onclick="phDoAction(\'upload\')" class="btn btn-small btn-default"><i class="icon-upload" title="'.Text::_('COM_PHOCACOMMANDER_F10_UPLOAD').'"></i> '.Text::_($f[10]).'</a>';
			$bar->appendButton('Custom', $dhtml);
		}
		if ($this->t['display_download_button'] == 1) {
			$dhtml = '<a href="javascript: void(0)" onclick="phDoAction(\'download\')" class="btn btn-small"><i class="icon-download" title="'.Text::_('COM_PHOCACOMMANDER_DOWNLOAD').'"></i> '.Text::_($f[12]).'</a>';
			$bar->appendButton('Custom', $dhtml);
		}
		if ($this->t['experimental_zip'] == 1) {
			$dhtml = '<a href="javascript: void(0)" onclick="phDoAction(\'pack\')" class="btn btn-small btn-default"><i class="icon-box-add" title="'.Text::_('COM_PHOCACOMMANDER_PACK').'"></i> '.Text::_($f[11]).'</a>';
			$bar->appendButton('Custom', $dhtml);

		}


		ToolbarHelper::divider();

		if ($canDo->get('core.admin')) {
			ToolbarHelper::preferences('com_phocacommander');
			ToolbarHelper::divider();
		}
		ToolbarHelper::help( 'screen.phocacommander', true );

		$dhtml = '<a href="index.php?option=com_phocacommander&view=phocacommanderinfo" class="btn btn-small btn-primary"><i class="icon-info" title="'.Text::_('COM_PHOCACOMMANDER_INFO').'"></i> '.Text::_('COM_PHOCACOMMANDER_INFO').'</a>';
		$bar->appendButton('Custom', $dhtml);
	}
}
?>
