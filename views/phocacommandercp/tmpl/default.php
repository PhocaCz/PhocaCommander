<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();


$this->t['url'] = 'index.php?option=com_phocacommander&view=phocacommanderfilesa&format=json&tmpl=component&'. JSession::getFormToken().'=1';
$this->t['urlaction'] = 'index.php?option=com_phocacommander&view=phocacommanderactiona&format=json&tmpl=component&'. JSession::getFormToken().'=1';
$this->t['urledit'] = 'index.php?option=com_phocacommander&task=phocacommanderedit.edit&'. JSession::getFormToken().'=1';
$this->t['urladmin'] = 'index.php';



$session = JFactory::getSession();
$w = $session->get('ww', false, 'com_phocacommander.phocacommander');
?>
<script type="text/javascript">

<?php if(!$w){
	echo 'var phWelcomeWarning = 0;'."\n";
	$session->set('ww', true, 'com_phocacommander.phocacommander');
} else {
	echo 'var phWelcomeWarning = 1;'."\n";
}?>
var phActivePanel		= '<?php echo $this->t['activepanel']; ?>';
var phPanel				= '<?php echo $this->t['panel']; ?>';
var phFolderA			= '<?php echo $this->t['foldera']; ?>';
var phFolderB			= '<?php echo $this->t['folderb']; ?>';
var phOrderingA			= '<?php echo $this->t['orderinga']; ?>';
var phOrderingB			= '<?php echo $this->t['orderingb']; ?>';
var phDirectionA		= '<?php echo $this->t['directiona']; ?>';
var phDirectionB		= '<?php echo $this->t['directiona']; ?>';


function phSetForm() {
	var phFormContent = 
	  '<input type="hidden" name="activepanel" value="' + phActivePanel +'" />' + "\n"
	+ '<input type="hidden" name="panel" value="' + phPanel +'" />' + "\n" 
	+ '<input type="hidden" name="directiona" value="' + phDirectionA +'" />' + "\n" 
	+ '<input type="hidden" name="directionb" value="' + phDirectionB +'" />' + "\n"
	+ '<input type="hidden" name="orderinga" value="' + phOrderingA +'" />' + "\n" 
	+ '<input type="hidden" name="orderingb" value="' + phOrderingB +'" />' + "\n" 
	+ '<input type="hidden" name="foldera" value="' + phFolderA +'" />' + "\n" 
	+ '<input type="hidden" name="folderb" value="' + phFolderB +'" />' + "\n";	
	jQuery("#phFormBox").html(phFormContent);
}

function phSetVariables(phLFA) {
	
	if (phLFA['orderinga'] && phLFA['orderinga'] != '')			{ phOrderingA = phLFA['orderinga'];}
	if (phLFA['orderingb'] && phLFA['orderingb'] != '')			{ phOrderingB = phLFA['orderingb'];}
	if (phLFA['directiona'] && phLFA['directiona'] != '')		{ phDirectionA = phLFA['directiona'];}
	if (phLFA['directionb'] && phLFA['directionb'] != '')		{ phDirectionB = phLFA['directionb'];}
	if (phLFA['panel'] && phLFA['panel'] != '')					{ phPanel = phLFA['panel'];}
	if (phLFA['activepanel'] && phLFA['activepanel'] != '')		{ phActivePanel = phLFA['activepanel'];}

	if (phLFA['foldera'] && phLFA['foldera'] != '*') {phFolderA = phLFA['foldera'];}
	if (phLFA['foldera'] && phLFA['foldera'] == '|') {phFolderA = '';}
	if (!phLFA['foldera'] && !phFolderA ) { phFolderA = '';}
	
	if (phLFA['folderb'] && phLFA['folderb'] != '*') {phFolderB = phLFA['folderb'];}
	if (phLFA['folderb'] && phLFA['folderb'] == '|') {phFolderB = '';}
	if (!phLFA['folderb'] && !phFolderB ) { phFolderB = '';}
}


function phLoadFiles(phLFA) {
	
	phSetVariables(phLFA);
	phSetForm();
	var url 				= '<?php echo $this->t['url']; ?>';	
	var dataPost 			= {};
	var panelId				= getPanelID(phPanel);
	dataPost['panel'] 		= phPanel;
	dataPost['activepanel']	= phActivePanel;
	dataPost['foldera']		= phFolderA;
	dataPost['folderb']		= phFolderB;
	dataPost['orderinga']	= phOrderingA;
	dataPost['orderingb']	= phOrderingB;
	dataPost['directiona']	= phDirectionA;
	dataPost['directionb']	= phDirectionB;
	
	jQuery.ajax({
	   url: url,
	   type:'POST',
	   data:dataPost,
	   dataType:'JSON',
	   success:function(data){
			if ( data.status == 1 ){
				jQuery(panelId).html(data.message);
			} else {
				jQuery("#ph-ajaxtop").show();
				jQuery("#ph-ajaxtop-message").html(data.error); 
			}
		}
	});
}

function phChangeActive() {
	if (phActivePanel == 'B') {
		jQuery('#phStatusA').addClass('ph-status-active');
		jQuery('#phStatusB').removeClass('ph-status-active');
		phActivePanel = 'A';
	} else if (phActivePanel == 'A') {
		jQuery('#phStatusB').addClass('ph-status-active');
		jQuery('#phStatusA').removeClass('ph-status-active');
		phActivePanel = 'B';
	}
}

function phSearchIDs(panel) {
	var searchIDs = jQuery("#ph-table-" + panel + " tr input:checkbox:checked").map(function(){
      return jQuery(this).val();
    }).get();
	return searchIDs;
}

function phGetPassivePanel(panel) {
	if (panel == 'A') { return 'B';}
	else if (panel == 'B') { return 'A';}
}
function getPanelID(panel) {
	if(panel == 'A') {return '#ph-a';}
	if(panel == 'B') {return '#ph-b';}
}


function phRequest(dataPost) {
	var url = '<?php echo $this->t['urlaction']; ?>';
	var phAjaxTop = '<div id="ph-ajaxtop-message">'
		+ '<?php echo JHtml::_( 'image', 'media/com_phocacommander/images/administrator/icon-loading5.gif', ''); ?>'
		+ '&nbsp; ' + '<?php echo JText::_('COM_PHOCACOMMANDER_UPDATING'); ?>' + '</div>';
	jQuery("#ph-ajaxtop").html(phAjaxTop);	
	jQuery("#ph-ajaxtop").show();
	
	var phRequestActive = jQuery.ajax({
	   url: url,
	   type:'POST',
	   data:dataPost,
	   dataType:'JSON',
	   success:function(data){
			if ( data.status == 1 ){
				//jQuery("#ph-ajaxtop").show();
				jQuery("#ph-ajaxtop-message").html(data.message);
				phRequestActive = null;
				setTimeout(function(){
					jQuery("#ph-ajaxtop").hide(600);
					jQuery(".ph-result-txt").remove();
				}, 2500);
				
				phLFA = {};
				phLFA['activepanel'] = phActivePanel;
				if (phActivePanel == 'A') {
					phLFA['foldera'] = dataPost['pathfrom'];
					phLFA['folderb'] = dataPost['pathwhere'];
				} else {
					phLFA['folderb'] = dataPost['pathfrom'];
					phLFA['foldera'] = dataPost['pathwhere'];
				}

				phLFA['panel'] = 'A'; jQuery(document).ready(phLoadFiles(phLFA));
				phLFA['panel'] = 'B'; jQuery(document).ready(phLoadFiles(phLFA));
				
			} else {
				//jQuery("#ph-ajaxtop").show();
				jQuery("#ph-ajaxtop-message").html(data.error);
				phRequestActive = null;
				setTimeout(function(){
					jQuery("#ph-ajaxtop").hide(600);
					jQuery(".ph-result-txt").remove();
				}, 3500);
				
				phLFA = {};
				phLFA['activepanel'] = phActivePanel;
				if (phActivePanel == 'A') {
					phLFA['foldera'] = dataPost['pathfrom'];
					phLFA['folderb'] = dataPost['pathwhere'];
				} else {
					phLFA['folderb'] = dataPost['pathfrom'];
					phLFA['foldera'] = dataPost['pathwhere'];
				}

				phLFA['panel'] = 'A'; jQuery(document).ready(phLoadFiles(phLFA));
				phLFA['panel'] = 'B'; jQuery(document).ready(phLoadFiles(phLFA));
			}
		},
		error: function(request, textStatus, errorThrown){
			var errorMsg = '<?php echo JText::_('COM_PHOCACOMMANDER_SERVER_ERROR'); ?>';
			errorMsg = errorMsg + ': ' + request.getResponseHeader('Status') + ' ('+ errorThrown +')';
			jQuery("#ph-ajaxtop-message").html(errorMsg);
				phRequestActive = null;
				setTimeout(function(){
					jQuery("#ph-ajaxtop").hide(600);
					jQuery(".ph-result-txt").remove();
				}, 3500);
				
				phLFA = {};
				phLFA['activepanel'] = phActivePanel;
				if (phActivePanel == 'A') {
					phLFA['foldera'] = dataPost['pathfrom'];
					phLFA['folderb'] = dataPost['pathwhere'];
				} else {
					phLFA['folderb'] = dataPost['pathfrom'];
					phLFA['foldera'] = dataPost['pathwhere'];
				}

				phLFA['panel'] = 'A'; jQuery(document).ready(phLoadFiles(phLFA));
				phLFA['panel'] = 'B'; jQuery(document).ready(phLoadFiles(phLFA));
		}
	});
}


/* Action */
function phDoAction(task) {
	
	var dataPost 			= {};
	var txtFileFolder 		= '';
	var renameItem			= '';
	var newItem				= '';
	var newAttrib			= '';
	var pathFrom			= '';
	var pathWhere			= '';
	var searchIDsA			= null;
	var searchIDsB			= null;
	var searchIDs			= null;
	var searchIDsNoPrefix	= '';
	var passivePanel 		= phGetPassivePanel(phActivePanel);
	searchIDs 				= phSearchIDs(phActivePanel);
	if (task == 'new' || task == 'upload') {
		pathFrom 	= jQuery("#phPanel" + phActivePanel).val();
		pathWhere 	= jQuery("#phPanel" + passivePanel).val();
	} else {
		if (searchIDs[0] != null) {
			pathFrom 	= jQuery("#phPanel" + phActivePanel).val();
			pathWhere 	= jQuery("#phPanel" + passivePanel).val();
		} else {
			searchIDs 			= phSearchIDs(passivePanel);
			if (searchIDs[0] != null) {
				pathFrom 	= jQuery("#phPanel" + passivePanel).val();
				pathWhere 	= jQuery("#phPanel" + phActivePanel).val();
			}
		}
	}

	dataPost['pathfrom'] 	= pathFrom;	
	dataPost['pathwhere'] 	= pathWhere;
	dataPost['task'] 		= task;
	dataPost['selfiles'] 	= searchIDs;
	
	//dataPost['newitem'] 	= newItem;
	//dataPost['renameitem'] 	= renameItem;
	//dataPost['newattrib']	= newAttrib;

	
	if (task == 'new') {
		jQuery('#phPromptValueNewFolder').val( '' );
		phPrompt(dataPost, 'NewFolder', '<?php echo JText::_('COM_PHOCACOMMANDER_CREATE');?>', '', "#phPromptValueNewFolder");
		return true;		
	} else if (task == 'upload') {
		var txtPathFrom = jQuery.base64.atob(pathFrom, true);
		if (!txtPathFrom) {
			txtPathFrom = '<?php echo JText::_('COM_PHOCACOMMANDER_JOOMLA_ROOT_FOLDER');?>';
		}
		phPlupload(dataPost['pathfrom']);
		phFrame(dataPost, 'Upload', '<?php echo JText::_('COM_PHOCACOMMANDER_FOLDER');?>: ' + '<span class="ph-file-folder">' + txtPathFrom + '</span>' );
		return true;
	} else if (searchIDs[0] == null) {
		phAlert('<?php echo JText::_('COM_PHOCACOMMANDER_NO_FILE_NO_FOLDER_SELECTED') ?>');
		return false;
	} else {
		var count 			= searchIDs.filter(function(value) { return value !== undefined }).length;
		
		if (count == 1) {
			if (searchIDs[0].indexOf("folder|") >= 0) {
				searchIDsNoPrefix = searchIDs[0].replace('folder|', '');
				txtFileFolder = jQuery.base64.atob(searchIDsNoPrefix, true);
				txtFileFolder = '<span class="ph-file-folder">' + txtFileFolder + '</span> ' + '<?php echo JText::_('COM_PHOCACOMMANDER_FOLDER_SM'); ?>';
				dataPost['type'] = 'folder';
				
				if (task == 'view' || task == 'edit') {
					phAlert('<?php echo JText::_('COM_PHOCACOMMANDER_FOLDER_CANNOT_BE_PREVIEWED_OR_EDITED'); ?>');
					return false;
				}
				
			} else if (searchIDs[0].indexOf("file|") >= 0) {
				searchIDsNoPrefix = searchIDs[0].replace('file|', '');
				txtFileFolder = '<span class="ph-file-folder">' + jQuery.base64.atob(searchIDsNoPrefix, true) + '</span>';
				dataPost['type'] = 'file';
				
			}
		} else {
			if (task == 'rename') {
				phAlert('<?php echo JText::_('COM_PHOCACOMMANDER_ONLY_ONE_FILE_OR_FOLDER_NEEDS_TO_BE_SELECTED'); ?>');
				return false;
			} else if (task == 'view' || task == 'edit' || task == 'unpack') {
				phAlert('<?php echo JText::_('COM_PHOCACOMMANDER_ONLY_ONE_FILE_NEEDS_TO_BE_SELECTED'); ?>');
				return false;
			}
			txtFileFolder = count + ' ' + '<?php echo JText::_('COM_PHOCACOMMANDER_FILES_FOLDERS_SM'); ?>';
		}
		//return;
		
		var txtTo = '<?php echo JText::_('COM_PHOCACOMMANDER_TO'); ?>';
		var txtPathWhere = jQuery.base64.atob(pathWhere, true);
		if (!txtPathWhere) {
			txtPathWhere = '<?php echo JText::_('COM_PHOCACOMMANDER_JOOMLA_ROOT_FOLDER');?>';
		} else {
			txtPathWhere = '<span class="ph-file-folder">' + txtPathWhere + '</span> ' + '<?php echo JText::_('COM_PHOCACOMMANDER_FOLDER_SM'); ?>';
		}
		

		if (task == 'copy') {
			phPrompt(dataPost, 'Copy', '<?php echo JText::_('COM_PHOCACOMMANDER_OK');?>', '<?php echo JText::_('COM_PHOCACOMMANDER_ARE_YOU_SURE_COPY');?>' + "<br>" + txtFileFolder + "<br>" + txtTo + "<br>" + txtPathWhere, "#phPromptValueCopy", 1 );
			return true;
		} else if (task == 'move') {
			phPrompt(dataPost, 'Move', '<?php echo JText::_('COM_PHOCACOMMANDER_OK');?>', '<?php echo JText::_('COM_PHOCACOMMANDER_ARE_YOU_SURE_MOVE');?>' + "<br>" + txtFileFolder + "<br>" + txtTo + "<br>" + txtPathWhere, "#phPromptValueMove", 1 );
			return true;
		} else if (task == 'delete') {
			phConfirm(dataPost, '<?php echo JText::_('COM_PHOCACOMMANDER_ARE_YOU_SURE_DELETE');?>' + "<br>" + txtFileFolder + "<br>" + '<?php echo '<span class="ph-warning-text">'.JText::_('COM_PHOCACOMMANDER_PERMANENTLY_REMOVE_WARNING').'</span>'; ?>' );
			return true;
		} else if (task == 'rename') {
			jQuery("#phPromptValueRename").val(jQuery.base64.atob(searchIDsNoPrefix, true));
			jQuery("#phPromptValueRename").on("focus", function () {
				jQuery(this).select();
			});
			phPrompt(dataPost, 'Rename', '<?php echo JText::_('COM_PHOCACOMMANDER_RENAME');?>', '', '#phPromptValueRename', 0);
			return true;
		} else if (task == 'attributes') {
			clearAttribVal();
			phPrompt(dataPost, 'Attributes', '<?php echo JText::_('COM_PHOCACOMMANDER_NEW_ATTRIBUTE');?>', '<?php echo JText::_('COM_PHOCACOMMANDER_SET_NEW_ATTRIBUTE_FOR');?>' + "<br>" + txtFileFolder + '', "#phAttribVal");
			return true;
		} else if (task == 'edit') {
			if (jQuery.base64.atob(pathFrom, true)) {
				var fullFile = jQuery.base64.atob(pathFrom, true) + '/' + jQuery.base64.atob(searchIDsNoPrefix, true);
			} else {
				var fullFile = jQuery.base64.atob(searchIDsNoPrefix, true);
			}
			var fullFileEncoded = jQuery.base64.btoa(fullFile);
			jQuery("#phFormTask").val('phocacommanderedit.edit');
			jQuery("#phFormFilename").val(fullFileEncoded);
			jQuery( "#adminForm" ).submit();
			return true;
		} else if (task == 'view') {
			var phFile = jQuery.base64.atob(searchIDsNoPrefix, true);
			var phExt = phFile.substr( (phFile.lastIndexOf('.') +1) );
			var phExt = phExt.toLowerCase();
			if (phExt == 'jpg' || phExt == 'jpeg' || phExt == 'png' || phExt == 'gif') {
				jQuery().prettyPhoto({social_tools: false, horizontal_padding: 17});
				var ppImage	= '<?php echo JURI::root();?>' + jQuery.base64.atob(pathFrom, true) + '/' + jQuery.base64.atob(searchIDsNoPrefix, true);
				var ppTitle	= jQuery.base64.atob(searchIDsNoPrefix, true);
				var ppDesc	= ' ';
				jQuery.prettyPhoto.open(ppImage,ppTitle,ppDesc);
			} else {
				phAlert('<?php echo JText::_('COM_PHOCACOMMANDER_ONLY_IMAGES_CAN_BE_PREVIEWED') ;?>');
				return false;
			}
			return true;
		} else if (task == 'unpack') {
			var phFile = jQuery.base64.atob(searchIDsNoPrefix, true);
			var phExt = phFile.substr( (phFile.lastIndexOf('.') +1) );
			var phExt = phExt.toLowerCase();
			if (phExt == 'zip' || phExt == 'tar' || phExt == 'gz' || phExt == 'gzip' || phExt == 'bz2' || phExt == 'bzip2' ) {
				if (phExt == 'zip') {
					phPrompt(dataPost, 'Unpack', '<?php echo JText::_('COM_PHOCACOMMANDER_OK');?>', '<?php echo JText::_('COM_PHOCACOMMANDER_ARE_YOU_SURE_UNPACK');?>' + "<br>" + txtFileFolder + "<br>" + txtTo + "<br>" + txtPathWhere, "#phPromptValueUnpack", 1 );
				} else {
					var warnUnpack = '<?php echo '<span class="ph-warning-text">'.JText::_('COM_PHOCACOMMANDER_EXTRACTED_FILES_OVERWRITE_EXISTING_FILES_WARNING').'</span>'; ?>';
					phPrompt(dataPost, 'UnpackOther', '<?php echo JText::_('COM_PHOCACOMMANDER_OK');?>', '<?php echo JText::_('COM_PHOCACOMMANDER_ARE_YOU_SURE_UNPACK');?>' + "<br>" + txtFileFolder + "<br>" + txtTo + "<br>" + txtPathWhere + "<br>" + warnUnpack, "#phPromptValueUnpackOther", 1 );
				}
				
			} else {
				phAlert('<?php echo JText::_('COM_PHOCACOMMANDER_ONLY_ARCHIVE_FILE_CAN_BE_UNPACKED') ;?>');
			}
			return true;
		} else if (task == 'pack') {
			phPrompt(dataPost, 'Pack', '<?php echo JText::_('COM_PHOCACOMMANDER_PACK');?>', '', '#phPromptValuePack', 0);
			return true;
		} else {
			return true;
		}
	}
	return true;
}

/* DIALOG */
function phSetCloseButton() {
	jQuery('.ui-dialog-titlebar-close').html('<span class="ph-close-button ui-button-icon-primary ui-icon ui-icon-closethick"></span>');
}

function phAlert(txt) {
	jQuery("#phDialogWarning").dialog({
        autoOpen: false,
		modal: true,
		buttons: {
            "<?php echo JText::_('COM_PHOCACOMMANDER_OK'); ?>": function() {
                jQuery(this).dialog("close");
				return false;
            }
        }
    });
	jQuery( "#phDialogWarning" ).html( txt );
	jQuery( "#phDialogWarning" ).dialog( "open" );
	/* Correct class */
	phSetCloseButton();
	jQuery('button').addClass('btn btn-default');

}

function phConfirm(dataPost, txt) {
	jQuery("#phDialogConfirm" ).html( txt );
	jQuery("#phDialogConfirm").dialog({
        autoOpen: false,
		modal: true,
		buttons: {
            "<?php echo JText::_('COM_PHOCACOMMANDER_OK'); ?>": function() {
                jQuery(this).dialog("close");
				phRequest(dataPost);
				return true;
            },
            "<?php echo JText::_('COM_PHOCACOMMANDER_CANCEL'); ?>": function() {
				jQuery(this).dialog("close");
				return false;
            }
        }
    });
	jQuery( "#phDialogConfirm" ).dialog( "open" );
	/* Correct class */
	phSetCloseButton();
	jQuery('button').addClass('btn btn-default');
}

function phPrompt(dataPost, type, txtButton, txt, promptValue, checkBox) {
	var phButtons = {};
	phButtons[txtButton] = function() {
		if(checkBox) {
			var phPromptValue = jQuery( promptValue );
			dataPost['newvalue'] = phPromptValue.prop('checked') ;
			phRequest(dataPost);
			phPromptValue.prop('checked', false);
		} else {
			var phPromptValue = jQuery( promptValue );
			if (phPromptValue.val() != '') {
				dataPost['newvalue'] = phPromptValue.val();
				phRequest(dataPost);
			}
		}
		jQuery(this).dialog("close");
		return false;
	};
	
	phButtons["<?php echo JText::_('COM_PHOCACOMMANDER_CANCEL'); ?>"] = function() {
		jQuery(this).dialog("close");
		return false;
	};
	
	jQuery("#phDialogPrompt" + type).dialog({
		autoOpen: false,
		modal: true,
		buttons: phButtons
	});
	
	jQuery("#phDialogPrompt" + type).dialog( "open" );
	jQuery("#phDialogPrompt" + type + ' .ph-prepend-text').show();
	jQuery("#phDialogPrompt" + type + ' .ph-prepend-text').html( txt );
	/* Correct class */
	phSetCloseButton();
	jQuery('button').addClass('btn btn-default');
}

function phPromtWarning(type, txtButton, returnValue, w, h) {
	var phButtons = {};
	phButtons[txtButton] = function() {
		jQuery(this).dialog("close");
		return false;
	};
	
	phButtons["<?php echo JText::_('COM_PHOCACOMMANDER_CANCEL'); ?>"] = function() {
		if (returnValue) {
			window.location = returnValue;
			return false;
		}
		jQuery(this).dialog("close");
		return false;
	};
	var windowHeight = jQuery(window).height();
	var windowWidth = jQuery(window).width();
	windowWidth = windowWidth * w;
	windowHeight = windowHeight * h;
	jQuery("#phDialogPrompt" + type).dialog({
		autoOpen: false,
		modal: true,
		width: windowWidth,
		height: windowHeight,
		buttons: phButtons
	});
	
	jQuery("#phDialogPrompt" + type).dialog( "open" );
	/* Correct class */
	phSetCloseButton();
	jQuery('button').addClass('btn btn-default');
}

function phFrame(dataPost, type, txt) {
	
	var windowHeight = jQuery(window).height();
	var windowWidth = jQuery(window).width();
	windowWidth = windowWidth * 0.8;
	windowHeight = windowHeight * 0.8;
	
	
	jQuery("#phDialogPrompt" + type).dialog({
        autoOpen: false,
		resizable: true,
		width: windowWidth,
		height: windowHeight,
		modal: true,
		draggable: false,
        resizable: false
    });
	
	jQuery("#phDialogPrompt" + type).dialog( "open" );
	jQuery("#phDialogPrompt" + type + ' .ph-prepend-text').show();
	jQuery("#phDialogPrompt" + type + ' .ph-prepend-text').html( txt );
	/* Correct class */
	phSetCloseButton();
	jQuery('button').addClass('btn btn-default');
}



/* Attributes */
var phO = 0;
var phG = 0;
var phT = 0;
var phAttribVal = '';

function clearAttribVal() {
	phO = phG = phT = 0;
	phAttribVal = '';
	jQuery('#phDialogPromptAttributes').find(':checked').each(function() {
		jQuery(this).removeAttr('checked');
		jQuery(this).parent().toggleClass('ph-active', false);
	});
	jQuery('#phAttribVal').val( phAttribVal );
}

function setAttribVal(phO, phG, phT) { 
	phAttribVal = phO.toString() + '' + phG.toString() + '' + phT.toString();
	jQuery('#phAttribVal').val( phAttribVal );
}

/* Document Ready */
jQuery(document).ready(function() {
	jQuery('#selectAllA').live( "click", function() {
		phActivePanel = 'B';
		var checkedStatusA = this.checked;
		jQuery('#ph-table-A tr').find('td:first :checkbox').each(function () {
			jQuery(this).prop('checked', checkedStatusA);
			jQuery(this).parent().toggleClass('ph-active', this.checked);
			
		 });
	});
	
	jQuery('#selectAllB').live( "click", function() {
		phActivePanel = 'B';
		var checkedStatusB = this.checked;
		jQuery('#ph-table-B tr').find('td:first :checkbox').each(function () {
			jQuery(this).prop('checked', checkedStatusB);
			jQuery(this).parent().toggleClass('ph-active', this.checked);
			
		 });
	});
	
	/* Start */
	if(phWelcomeWarning == 0) {
		phPromtWarning('WelcomeWarning', '<?php echo JText::_('COM_PHOCACOMMANDER_OK');?>', '<?php echo $this->t['urladmin']; ?>', 0.6, 0.6 );
	}
	phLFA = {};
	phLFA['panel'] = 'A'; jQuery(document).ready(phLoadFiles(phLFA));
	phLFA['panel'] = 'B'; jQuery(document).ready(phLoadFiles(phLFA));

	
	/* Make selected file or folder active - color */
	jQuery('td input:checkbox').live("change", function(){ 
		jQuery(this).parent().toggleClass('ph-active', this.checked);
	});
	
	/* Make panel active - color */
	/* Start or change the form*/
	
	/* Change */
	jQuery('#ph-a').live( "click", function() {
		phActivePanel = 'A';
	});
	jQuery('#ph-b').live( "click", function() {
		phActivePanel = 'B';
	});
	
	jQuery('#phCommanderBox').live("click", function(){ 
		if (phActivePanel == 'A') {
			jQuery('#phStatusA').addClass('ph-status-active');
			jQuery('#phStatusB').removeClass('ph-status-active');
		} else if (phActivePanel == 'B') {
			jQuery('#phStatusB').addClass('ph-status-active');
			jQuery('#phStatusA').removeClass('ph-status-active');
		}
	})

	
	
	/* Keys */
	jQuery( document ).live( "keydown", function( e ) {
		//alert(e.which);
		
		if (e.keyCode == 90 && e.ctrlKey) {
			e.preventDefault();
			phDoAction('pack');
		} 
		
		if ((e.which || e.keyCode) == 112) {
			e.preventDefault();
			phDoAction('attributes');
		} 
		if ((e.which || e.keyCode) == 113) {
			e.preventDefault();
			phDoAction('rename');
		} 
		if ((e.which || e.keyCode) == 114) {
			e.preventDefault();
			phDoAction('view');
		} 
		if ((e.which || e.keyCode) == 115) {
			e.preventDefault();
			phDoAction('edit');
		} 
		
		if ((e.which || e.keyCode) == 116) {
			e.preventDefault();
			phDoAction('copy');
		} 
		if ((e.which || e.keyCode) == 117) {
			e.preventDefault();
			phDoAction('move');
		} 
		if ((e.which || e.keyCode) == 118) {
			e.preventDefault();
			phDoAction('new');
		} 
		if ((e.which || e.keyCode) == 119) {
			e.preventDefault();
			phDoAction('delete');
		} 
		
		if ((e.which || e.keyCode) == 120) {
			e.preventDefault();
			phDoAction('unpack');
		} 
		if ((e.which || e.keyCode) == 121) {
			e.preventDefault();
			phDoAction('upload');
		} 
		if ((e.which || e.keyCode) == 9) {
			e.preventDefault();
			phChangeActive();
		} 
	});
	
/*	var map = {17: false, 120: false};
$(document).keydown(function(e) {
    if (e.keyCode in map) {
        map[e.keyCode] = true;
        if (map[17] && map[120]) {
            alert("ano");
        }
    }
}).keyup(function(e) {
    if (e.keyCode in map) {
        map[e.keyCode] = false;
    }
}); */
		
	/* Attributes */
	jQuery('#ro').live("change", function(){ if(this.checked) { phO = phO + 4;} else {phO = phO - 4;} setAttribVal(phO, phG, phT);});
	jQuery('#wo').live("change", function(){ if(this.checked) { phO = phO + 2;} else {phO = phO - 2;} setAttribVal(phO, phG, phT);});
	jQuery('#eo').live("change", function(){ if(this.checked) { phO = phO + 1;} else {phO = phO - 1;} setAttribVal(phO, phG, phT);});
	
	jQuery('#rg').live("change", function(){ if(this.checked) { phG = phG + 4;} else {phG = phG - 4;} setAttribVal(phO, phG, phT);});
	jQuery('#wg').live("change", function(){ if(this.checked) { phG = phG + 2;} else {phG = phG - 2;} setAttribVal(phO, phG, phT);});
	jQuery('#eg').live("change", function(){ if(this.checked) { phG = phG + 1;} else {phG = phG - 1;} setAttribVal(phO, phG, phT);});
	
	jQuery('#rt').live("change", function(){ if(this.checked) { phT = phT + 4;} else {phT = phT - 4;} setAttribVal(phO, phG, phT);});
	jQuery('#wt').live("change", function(){ if(this.checked) { phT = phT + 2;} else {phT = phT - 2;} setAttribVal(phO, phG, phT);});
	jQuery('#et').live("change", function(){ if(this.checked) { phT = phT + 1;} else {phT = phT - 1;} setAttribVal(phO, phG, phT);});

	
})
</script>

<?php
	
?>

<div id="ph-ajaxtop"></div>
<div id="system-message-container"></div>
<div class="ph-commander-box" id="phCommanderBox">
	<div class="row row-fluid ph-commander-box-in">
		<div class="span6 ph-a" id="ph-a"></div>
		<div class="span6 ph-b" id="ph-b"></div>
    </div>
	<div class="row row-fluid ph-commander-box-in">
	<div class="span12"style="text-align:right;padding-right: 30px">Powered by <a href="http://www.phoca.cz/phocacommander" target="_blank">Phoca Commander</a> | <a href="http://www.phoca.cz/version/index.php?phocacommander=<?php echo  $this->t['version']; ?>" target="_blank"><?php echo JText::_('COM_PHOCACOMMANDER_CHECK_FOR_UPDATE') ?></a></div>
	</div>
</div>

<div id="phDialogWarning" class="ph-dialog" title="<?php echo JText::_('COM_PHOCACOMMANDER_WARNING'); ?>"></div>
<div id="phDialogConfirm" class="ph-dialog" title="<?php echo JText::_('COM_PHOCACOMMANDER_CONFIRM'); ?>"></div>
<div id="phDialogPromptNewFolder" class="ph-dialog" title="<?php echo JText::_('COM_PHOCACOMMANDER_SET'); ?>">
	<div><?php echo JText::_('COM_PHOCACOMMANDER_NEW_FOLDER');?></div>
	<div class="ph-prepend-text"></div>
	<div><input type="text" name="phPromptValueNewFolder" id="phPromptValueNewFolder" value="" class="input" /></div>
</div>

<div id="phDialogPromptRename" class="ph-dialog" title="<?php echo JText::_('COM_PHOCACOMMANDER_SET'); ?>">
	<div><?php echo JText::_('COM_PHOCACOMMANDER_RENAME');?></div>
	<div class="ph-prepend-text"></div>
	<div><input type="text" name="phPromptValueRename" id="phPromptValueRename" value="" class="input" /></div>
</div>

<div id="phDialogPromptCopy" class="ph-dialog" title="<?php echo JText::_('COM_PHOCACOMMANDER_SET'); ?>">
	<div><?php echo JText::_('COM_PHOCACOMMANDER_COPY');?></div>
	<div class="ph-prepend-text"></div>
	<div class="ph-input-box-prompt"><label class="ph-input-prompt"><input type="checkbox" name="phPromptValueCopy" id="phPromptValueCopy" value="" class="ph-input-checkbox-prompt" /> <span><?php echo JText::_('COM_PHOCACOMMANDER_OVERWRITE_EXISTING_FOLDERS_FILES'); ?>?</span></label></div>
</div>

<div id="phDialogPromptMove" class="ph-dialog" title="<?php echo JText::_('COM_PHOCACOMMANDER_SET'); ?>">
	<div><?php echo JText::_('COM_PHOCACOMMANDER_MOVE');?></div>
	<div class="ph-prepend-text"></div>
	<div class="ph-input-box-prompt"><label class="ph-input-prompt"><input type="checkbox" name="phPromptValueMove" id="phPromptValueMove" value="" class="ph-input-checkbox-prompt" /> <span><?php echo JText::_('COM_PHOCACOMMANDER_OVERWRITE_EXISTING_FOLDERS_FILES'); ?>?</span></label></div>
</div>

<div id="phDialogPromptUnpack" class="ph-dialog" title="<?php echo JText::_('COM_PHOCACOMMANDER_SET'); ?>">
	<div><?php echo JText::_('COM_PHOCACOMMANDER_UNPACK');?></div>
	<div class="ph-prepend-text"></div>
	<div class="ph-input-box-prompt"><label class="ph-input-prompt"><input type="checkbox" name="phPromptValueUnpack" id="phPromptValueUnpack" value="" class="ph-input-checkbox-prompt" /> <span><?php echo JText::_('COM_PHOCACOMMANDER_OVERWRITE_EXISTING_FOLDERS_FILES'); ?>?</span></label></div>
</div>

<div id="phDialogPromptUnpackOther" class="ph-dialog" title="<?php echo JText::_('COM_PHOCACOMMANDER_SET'); ?>">
	<div><?php echo JText::_('COM_PHOCACOMMANDER_UNPACK');?></div>
	<div class="ph-prepend-text"></div>
	<input type="hidden" name="phPromptValueUnpackOther" id="phPromptValueUnpackOther" value="" />
</div>

<div id="phDialogPromptPack" class="ph-dialog" title="<?php echo JText::_('COM_PHOCACOMMANDER_SET'); ?>">
	<div><?php echo JText::_('COM_PHOCACOMMANDER_SET_PACKAGE_NAME');?></div>
	<div class="ph-prepend-text"></div>
	<div><input type="text" name="phPromptValuePack" id="phPromptValuePack" value=".zip" class="input" /></div>
</div>

<div id="phDialogPromptWelcomeWarning" class="ph-dialog" title="<?php echo JText::_('COM_PHOCACOMMANDER_WARNING'); ?>">
	<div><?php echo JText::_('COM_PHOCACOMMANDER_WELCOME_WARNING');?></div>
	<div class="ph-prepend-text"></div>
	<input type="hidden" name="phPromptValueUnpackOther" id="phPromptValueUnpackOther" value="" />
</div>


<div id="phDialogPromptAttributes" class="ph-dialog" style="width: 400px" title="<?php echo JText::_('COM_PHOCACOMMANDER_SET'); ?>">
<div><?php echo JText::_('COM_PHOCACOMMANDER_ATTRIBUTES');?></div>
<div class="ph-prepend-text"></div>
<table class="ph-table-attribs">
<tr>
	<th><?php echo JText::_('COM_PHOCACOMMANDER_PERMISSION'); ?></th>
	<th><?php echo JText::_('COM_PHOCACOMMANDER_OWNER'); ?></th>
	<th><?php echo JText::_('COM_PHOCACOMMANDER_GROUP'); ?></th>
	<th><?php echo JText::_('COM_PHOCACOMMANDER_OTHER'); ?></th>
</tr>
<tr>
	<td><?php echo JText::_('COM_PHOCACOMMANDER_READ'); ?></td>
	<td><input class="ph-input-checkbox-attr" type="checkbox" name="ro" id="ro" value="" /></td>
	<td><input class="ph-input-checkbox-attr" type="checkbox" name="rg" id="rg" value="" /></td>
	<td><input class="ph-input-checkbox-attr" type="checkbox" name="rt" id="rt" value="" /></td>
</tr>
<tr>
	<td><?php echo JText::_('COM_PHOCACOMMANDER_WRITE'); ?></td>
	<td><input class="ph-input-checkbox-attr" type="checkbox" name="wo" id="wo" value="" /></td>
	<td><input class="ph-input-checkbox-attr" type="checkbox" name="wg" id="wg" value="" /></td>
	<td><input class="ph-input-checkbox-attr" type="checkbox" name="wt" id="wt" value="" /></td>
</tr>
<tr>
	<td><?php echo JText::_('COM_PHOCACOMMANDER_EXECUTE'); ?></td>
	<td><input class="ph-input-checkbox-attr" type="checkbox" name="eo" id="eo" value="" /></td>
	<td><input class="ph-input-checkbox-attr" type="checkbox" name="eg" id="eg" value="" /></td>
	<td><input class="ph-input-checkbox-attr" type="checkbox" name="et" id="et" value="" /></td>
</tr>
<tr>
	<td><?php echo JText::_('COM_PHOCACOMMANDER_VALUE'); ?></td>
	<td colspan="3"><input id="phAttribVal" class="input input-sm input-small ph-input-attribs" type="text" name="phAttribVal" value="" /></td>
</tr>
</table>
</div>

<form id="adminForm" name="adminForm" method="post" action="index.php?option=com_phocacommander">
<input type="hidden" name="task" id="phFormTask" value="" />
<input type="hidden" name="filename" id="phFormFilename" value="" />
<input type="hidden" name="id" value="1" />
<div id="phFormBox" style="display:none;"></div>
<?php echo JHtml::_('form.token'); ?>
</form>

<div id="phDialogPromptUpload" class="ph-dialog" title="<?php echo JText::_('COM_PHOCACOMMANDER_UPLOAD'); ?>">
	<div><span class="ph-prepend-text"></span> <span>(<?php echo  JText::_( 'COM_PHOCACOMMANDER_MAX_UPLOAD_SIZE' ).':&nbsp;'.$this->t['uploadmaxsizeread'] ?>)</span></div>
	<?php echo $this->loadTemplate('multipleupload'); ?>
</div>

	
