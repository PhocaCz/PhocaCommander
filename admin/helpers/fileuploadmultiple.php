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
defined( '_JEXEC' ) or die( 'Restricted access' );
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

class PhocaCommanderFileUploadMultiple
{
	public $method 		= 4;
	public $url			= '';
	public $reload		= '';
	public $maxFileSize	= '';
	public $chunkSize	= '';
	public $imageHeight	= '';
	public $imageWidth	= '';
	public $imageQuality= '';
	public $frontEnd	= 0;

	public function __construct() {}

	public static function renderMultipleUploadLibraries() {


		$paramsC 		= ComponentHelper::getParams('com_phocacommander');
		$chunkMethod 	= $paramsC->get( 'multiple_upload_chunk', 0 );
		$uploadMethod 	= $paramsC->get( 'multiple_upload_method', 4 );
		$app				= Factory::getApplication();
		$wa 				= $app->getDocument()->getWebAssetManager();

		//JHtml::_('behavior.framework', true);// Load it here to be sure, it is loaded before jquery
		HtmlHelper::_('jquery.framework', false);// Load it here because of own nonConflict method (nonconflict is set below)
		$document			= Factory::getDocument();
		// No more used  - - - - -
		//$document->addScript(JUri::root(true).'/components/com_phocadownload/assets/jquery/jquery-1.6.4.min.js');//USE SYSTEM
		//$nC = 'var pgJQ =  jQuery.noConflict();';//SET BELOW
		//$document->addScriptDeclaration($nC);//SET BELOW
		// - - - - - - - - - - - -

		$document->addScript(Uri::root(true).'/media/com_phocacommander/js/administrator/plupload/plupload.js');
		$document->addScript(Uri::root(true).'/media/com_phocacommander/js/administrator/plupload/jquery.plupload.queue/jquery.plupload.queue.js');

		$document->addScript(Uri::root(true).'/media/com_phocacommander/js/administrator/plupload/plupload.html5.js');
		//$document->addScript(JUri::root(true).'/media/com_phocacommander/js/administrator/plupload/moxie.js');
		//$document->addScript(JUri::root(true).'/media/com_phocacommander/js/administrator/plupload/jquery.ui.plupload/jquery.ui.plupload.js');
		//HTMLHelper::stylesheet( 'media/com_phocacommander/js/administrator/plupload/jquery.plupload.queue/css/jquery.plupload.queue.css' );
		$wa->registerAndUseStyle('com_phocacommander.plupload', 'media/com_phocacommander/js/administrator/plupload/jquery.plupload.queue/css/jquery.plupload.queue.css', array('version' => 'auto'));
	}

	public static function getMultipleUploadSizeFormat($size) {
		$readableSize = PhocaCommanderHelper::getFileSizeReadable($size, '%01.0f %s', 1);
		$readableSize 	= str_replace(' ', '', $readableSize);
		$readableSize 	= strtolower($readableSize);
		return $readableSize;
	}

	public function renderMultipleUploadJS($frontEnd = 0, $chunkMethod = 0) {

		$document	= Factory::getDocument();
		switch ($this->method) {
			/*case 2:
				$name		= 'gears_uploader';
				$runtime	= 'gears';
			break;
			case 3:
				$name		= 'silverlight_uploader';
				$runtime	= 'silverlight';
			break;*/
			default:
			case 4:
				$name		= 'html5_uploader';
				$runtime	= 'html5';
			break;

			/*case 5:
				$name		= 'browserplus_uploader';
				$runtime	= 'browserplus';
			break;

			case 6:
				$name		= 'html4_uploader';
				$runtime	= 'html4';
			break;

			case 1:
			default:
				$name		= 'flash_uploader';
				$runtime	= 'flash';
			break;*/
		}

		$chunkEnabled = 0;
		// Chunk only if is enabled and only if flash is enabled
		if (($chunkMethod == 1 && $this->method == 1) || ($this->frontEnd == 0 && $chunkMethod == 0 && $this->method == 1)) {
			$chunkEnabled = 1;
		}


        $this->url      = PhocaCommanderHelper::filterValue($this->url, 'text');
        $this->reload 	= PhocaCommanderHelper::filterValue($this->reload, 'text');
        $this->url 		= str_replace('&amp;', '&', $this->url);
        $this->reload 	= str_replace('&amp;', '&', $this->reload);

		//$js = 'var pgJQ = jQuery.noConflict();';
		$js  = 'jQuery(document).ready(function() {'."\n";
		$js .= '   '."\n";
		$js .= '})'."\n";

		$js.= ' '. "\n";

		$js.='
		var phFolderUpload = \'\';
		function phSetFolderUpload(folderUpload) {
			phFolderUpload = folderUpload;
		}
		function phPlupload(phFolderUpload) {'."\n";

		$js.= '  phSetFolderUpload(phFolderUpload)'. "\n";

		$js.='   plupload.addI18n({'."\n";
		$js.='	   \'Select files\' : \''.addslashes(Text::_('COM_PHOCACOMMANDER_SELECT_FILES')).'\','."\n";
		$js.='	   \'Add files to the upload queue and click the start button.\' : \''.addslashes(Text::_('COM_PHOCACOMMANDER_ADD_FILES_TO_UPLOAD_QUEUE_AND_CLICK_START_BUTTON')).'\','."\n";
		$js.='	   \'Filename\' : \''.addslashes(Text::_('COM_PHOCACOMMANDER_FILENAME')).'\','."\n";
		$js.='	   \'Status\' : \''.addslashes(Text::_('COM_PHOCACOMMANDER_STATUS')).'\','."\n";
		$js.='	   \'Size\' : \''.addslashes(Text::_('COM_PHOCACOMMANDER_SIZE')).'\','."\n";
		$js.='	   \'Add files\' : \''.addslashes(Text::_('COM_PHOCACOMMANDER_ADD_FILES')).'\','."\n";
		$js.='	   \'Start upload\':\''.addslashes(Text::_('COM_PHOCACOMMANDER_START_UPLOAD')).'\','."\n";
		$js.='	   \'Start Upload\':\''.addslashes(Text::_('COM_PHOCACOMMANDER_START_UPLOAD')).'\','."\n";
		$js.='	   \'Stop Upload\' : \''.addslashes(Text::_('COM_PHOCACOMMANDER_STOP_CURRENT_UPLOAD')).'\','."\n";
		$js.='	   \'Stop current upload\' : \''.addslashes(Text::_('COM_PHOCACOMMANDER_STOP_CURRENT_UPLOAD')).'\','."\n";
		$js.='	   \'Start uploading queue\' : \''.addslashes(Text::_('COM_PHOCACOMMANDER_START_UPLOADING_QUEUE')).'\','."\n";
		$js.='	   \'Drag files here.\' : \''.addslashes(Text::_('COM_PHOCACOMMANDER_DRAG_FILES_HERE')).'\''."\n";
		$js.='   });';

		$js.=' '."\n";

		$js.='	jQuery("#'.$name.'").pluploadQueue({'."\n";
		$js.='		runtimes : \''.$runtime.'\','."\n";
		$js.='		url : \''.$this->url.'\','."\n";
		$js.='		max_file_size : \''.$this->maxFileSize.'\','."\n";

		if ($chunkEnabled == 1) {
			$js.='		chunk_size : \'1mb\','."\n";
		}
		$js.='      preinit: phAttachErrors, '."\n";
		//$js.='      setup: phAttachCallbacks, '."\n";
		$js.='		unique_names : false,'."\n";
		//$js.='       multiple_queues: true,'."\n";
		$js.='		multipart: true,'."\n";

		$js.= 'multipart_params : {
				"folder" : phFolderUpload
			},'."\n";

		$js.='		filters : ['."\n";
		//$js.='			{title : "'.JText::_('COM_PHOCACOMMANDER_IMAGE_FILES').'", extensions : "jpg,gif,png"}'."\n";
		//$js.='			{title : "Zip files", extensions : "zip"}'."\n";
		$js.='		],'."\n";
		$js.=''."\n";
		/*if ($this->method != 6) {
			if ((int)$this->imageWidth > 0 || (int)$this->imageWidth > 0) {
				$js.='		resize : {width : '.$this->imageWidth.', height : '.$this->imageHeight.', quality : '.$this->imageQuality.'},'."\n";
				$js.=''."\n";
			}
		}*/
		if ($this->method == 1) {
			//$js.='		flash_swf_url : \''.Uri::root(false).'media/com_phocacommander/js/administrator/plupload/moxie.js\''."\n";
		} else if ($this->method == 3) {
			//$js.='		silverlight_xap_url : \''.Uri::root(true).'/media/com_phocacommander/js/administrator/plupload/Moxie.xap\''."\n";
		}
		$js.='	});'."\n";

		$js.='}'."\n";// End phPlupload();

		$js.=' '."\n";

		$js.= '
		

		
var phAllFiles = 0;
var phUploadedFiles = 0;
var phNotUploadedFiles = 0;
var phAllErrorMessages = \'\';
function phAttachErrors(uploader) {
	uploader.bind(\'FileUploaded\', function(up, file, response) {
		var obj = JSON.parse(response.response);
		if (obj.result && obj.result == \'error\') {
			phNotUploadedFiles = phNotUploadedFiles + 1;
		} else {
			phUploadedFiles = phUploadedFiles + 1;
		}
		phAllFiles = phUploadedFiles + phNotUploadedFiles;
		
		
		if (obj.result && obj.result == \'error\') {
			
			jQuery(\'#\' + file.id).append(\'<span class="alert alert-error">\'+ obj.message + obj.details +\'</span><div style="clear:both">&nbsp;</div>\');
			
			
			phAllErrorMessages = phAllErrorMessages + "\n"  + "• " + file.name + \': \' + obj.message + obj.details;

			up.trigger("Error", {message : obj.message, code : obj.code, details : obj.details, file: File});
			
			file.status = plupload.FAILED;
			if (phAllFiles == uploader.files.length ) { 
				alert(\''.Text::_('COM_PHOCACOMMANDER_LIST_OF_UPLOAD_ERRORS').': \' + "\n" + phAllErrorMessages);
			} else { return false;}
		}
		
		if (phAllFiles == uploader.files.length ) {
			
			jQuery(\'.plupload_filelist_footer\').append(\'<span class="ph-upload-finished alert alert-success">'.Text::_('COM_PHOCACOMMANDER_UPLOADING_FINISHED').'</span>\'); 
			setTimeout(function(){
				phAllFiles = 0;
				phUploadedFiles = 0;
				phNotUploadedFiles = 0;
				uploader.splice();
				uploader.refresh();
				phLFA[\'panel\'] = \'A\'; phLoadFiles(phLFA);
				phLFA[\'panel\'] = \'B\'; phLoadFiles(phLFA);
				phPlupload(phFolderUpload);
			}, 2500);
		}
	});

}';

/* $js .='
function phAttachCallbacks(uploader) {
    uploader.bind(\'Init\', function(up) {
        up.settings.multipart_params = {
            \'upload_type\' : jQuery("#uploadQueue").attr(\'data-uploadType\'),
            \'xref_id\' : jQuery("#uploadQueue").attr(\'data-xrefID\'),
            \'image_category\' : jQuery("#uploadQueue").attr(\'data-imageCategory\')
        };
    });

    uploader.bind(\'UploadComplete\', function(up, files) {

        if(jQuery().tipTip) {
            jQuery(\'.plupload_failed\').tipTip();
        }
        jQuery("#image_list").html(\'\', {xref_id : $("#uploadQueue").attr(\'data-xrefID\'), image_category : jQuery("#uploadQueue").attr(\'data-imageCategory\')});
    });


	/*uploader.bind(\'Error\', function(Up, ErrorObj) {

		uploader.refresh();
	})
}'; */

	/*	$js.='function phAttachCallbacks(uploader) {'."\n";
		$js.='	uploader.bind(\'FileUploaded\', function(Up, File, Response) { ;'."\n";
		$js.='
		var obj = eval(\'(\' + Response.response + \')\');'."\n";
		//if ($this->method == 6) {
			$js.='		var queueFiles = uploader.total.failed + uploader.total.uploaded;'."\n";
			//$js.='		var uploaded0 = uploader.total.uploaded;'."\n";
		//} else {
		//	$js.='		var queueFiles = uploader.total.failed + uploader.total.uploaded + 1;'."\n";
		//	$js.='		var uploaded0 = uploader.total.uploaded + 1;'."\n";
		//}

		$js.=' alert(queueFiles);alert(uploader.total.failed);alert(uploader.total.uploaded);'."\n";

		$js.='		if (obj.result && obj.result == \'error\') {'."\n";
		$js.='			Up.trigger("Error", {message : obj.message, code : obj.code, details : obj.details, file: File});'."\n";
		$js.='			if( queueFiles == uploader.files.length) {'."\n";
		$js.='			   //var uploaded0 = uploader.total.uploaded;'."\n";
		$js.='			}'."\n";
		$js.='			//alert("not");'."\n";
		$js.='		} else {'."\n";
		$js.='			if( queueFiles == uploader.files.length) {'."\n";
		//if ($this->method == 6) {
			$js.='			var uploaded = uploader.total.uploaded;'."\n";
		//} else {
		//	$js.='			var uploaded = uploader.total.uploaded + 1;'."\n";
		//}
		$js.='	    	};'."\n";
		$js.='		};'."\n";
		$js.='	});'."\n";

		$js.='	'."\n";

		$js.='	uploader.bind(\'Error\', function(Up, ErrorObj) {'."\n";
		$js.='		if (ErrorObj.file) {'."\n";
		$js.='	  		jQuery(\'#\' + ErrorObj.file.id).append(\'<div class="alert alert-error">\'+ ErrorObj.message + ErrorObj.details +\'</div>\');'."\n";
		$js.='		}'."\n";
		$js.='  });	'."\n";

		$js.='	'."\n";

		$js.='}';*/

		$document->addScriptDeclaration($js);
	}

	public function getMultipleUploadHTML($width = '', $height = '330', $mootools = 1) {


		switch ($this->method) {
			/*case 2:
				$name		= 'gears_uploader';
				$msg		= Text::_('COM_PHOCACOMMANDER_NOT_INSTALLED_GEARS');
			break;
			case 3:
				$name		= 'silverlight_uploader';
				$msg		= Text::_('COM_PHOCACOMMANDER_NOT_INSTALLED_SILVERLIGHT');
			break;*/
			case 4:
				$name		= 'html5_uploader';
				$msg		= Text::_('COM_PHOCACOMMANDER_NOT_SUPPORTED_HTML5');
			break;
/*
			case 5:
				$name		= 'browserplus_uploader';
				$msg		= Text::_('COM_PHOCACOMMANDER_NOT_INSTALLED_BROWSERPLUS');
			break;

			case 6:
				$name		= 'html4_uploader';
				$msg		= Text::_('COM_PHOCACOMMANDER_NOT_SUPPORTED_HTML4');
			break;

			case 1:
			default:
				$name		= 'flash_uploader';
				$msg		= Text::_('COM_PHOCACOMMANDER_NOT_INSTALLED_FLASH');
			break;*/
		}

		$style				= '';
		if ($width != '') {
			$style	.= 'width: '.(int)$width.'px;';
		}
		if ($height != '') {
			$style	.= 'height: '.(int)$height.'px;';
		}

		return '<div id="'.$name.'" style="'.$style.'">'.$msg.'</div>';

	}
}
?>
