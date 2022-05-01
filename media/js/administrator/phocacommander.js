/*
 * @package   Phoca Commander
 * @author    Jan Pavelka - https://www.phoca.cz
 * @copyright Copyright (C) Jan Pavelka https://www.phoca.cz
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 and later
 * @cms       Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */

function phGetMsg(msg, defaultMsg) {

	if (defaultMsg == 1) {
		return '<div id="ph-ajaxtop-message">'
		+ '<div id="ph-ajaxtop-close">x</div>'
		+ '<div class="ph-result-txt ph-info-txt">' + msg + '</div>'
		+ '<div class="ph-progressbar-bottom"></div>'
		+ '</div>';
	} else {
		return '<div id="ph-ajaxtop-close">x</div>'  + msg + '<div class="ph-progressbar-bottom"></div>';
	}

}

function phCloseMsgBoxSuccess(button) {

    if (typeof button === 'undefined') {button = '';}
    if (button != '') {jQuery(button).attr('disabled', true);}

	var animateId =  jQuery(".ph-progressbar-bottom").animate({
		width: "0%"
    }, 2500, function() {
        jQuery("#ph-ajaxtop").hide();
        jQuery(".ph-result-txt").remove();
        if (button != '') {jQuery(button).attr('disabled', false);}
      }
    );

    jQuery("#ph-ajaxtop-message").on("click", function(e){
        animateId.finish();
        animateId.remove();
        jQuery("#ph-ajaxtop").hide();
        jQuery(".ph-result-txt").remove();
        if (button != '') {jQuery(button).attr('disabled', false);}
    })
    jQuery("#ph-ajaxtop-message").on("mouseenter", function(e){
        animateId.pause();
    })
    jQuery("#ph-ajaxtop-message").on("mouseleave", function(e){
        animateId.resume();
    })
}

function phCloseMsgBoxError(button) {

    if (typeof button === 'undefined') {button = '';}
    if (button != '') {jQuery(button).attr('disabled', true);}

	var animateId =  jQuery(".ph-progressbar-bottom").animate({
		width: "0%"
    }, 3500, function() {
        jQuery("#ph-ajaxtop").hide();
        jQuery(".ph-result-txt").remove();
        if (button != '') {jQuery(button).attr('disabled', false);}
      }
    );

    jQuery("#ph-ajaxtop-message").on("click", function(e){
        animateId.finish();
        animateId.remove();
        jQuery("#ph-ajaxtop").hide();
        jQuery(".ph-result-txt").remove();
        if (button != '') {jQuery(button).attr('disabled', false);}
    })
    jQuery("#ph-ajaxtop-message").on("mouseenter", function(e){
        animateId.pause();
    })
    jQuery("#ph-ajaxtop-message").on("mouseleave", function(e){
        animateId.resume();
    })
}

function phSaveEdit(button) {

    if (typeof button === 'undefined') {button = '';}

    var phVars 	= Joomla.getOptions('phVarsCM');
    var phLang  = Joomla.getOptions('phLangCM');
    var phUrl = phVars['urleditsave'];


    var source = Joomla.editors.instances['jform_source'].getValue();
    jQuery('#adminForm input[name ="task"]').val('phocacommanderedit.save');
    jQuery('#adminForm #jform_source').val(source);
    var formdata = jQuery("#adminForm").serialize();


    jQuery.ajax({
        type: "POST",
        dataType: "JSON",
        url: phUrl,
        data: formdata,
        async: "false",
        cache: "false",
        success: function(data) {
            if (data.status == 1){
                jQuery("#ph-ajaxtop").show();
                jQuery("#ph-ajaxtop-message").html(phGetMsg(data.message, 0));
                phCloseMsgBoxSuccess(button);
            } else {
                jQuery("#ph-ajaxtop").show();
                jQuery("#ph-ajaxtop-message").html(phGetMsg(data.error, 0));
                phCloseMsgBoxError(button);
            }
        },
        error: function(data){
            jQuery("#ph-ajaxtop").show();
            jQuery("#ph-ajaxtop-message").html(phGetMsg('<span class="ph-result-txt ph-error-txt">' + phLang['COM_PHOCACOMMANDER_ERROR_SAVING_FILE'] + '</span>', 0));
            phCloseMsgBoxError(button);
        }
    })
}


/*
 * Main
 */
var phVars 	= Joomla.getOptions('phVarsCM');
var phLang  = Joomla.getOptions('phLangCM');

var phWelcomeWarning    = phVars['welcomewarning'];
var phActivePanel		= phVars['activepanel'];
var phPanel				= phVars['panel'];
var phFolderA			= phVars['foldera'];
var phFolderB			= phVars['folderb'];
var phOrderingA			= phVars['orderinga'];
var phOrderingB			= phVars['orderingb'];
var phDirectionA		= phVars['directiona'];
var phDirectionB		= phVars['directiona'];


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

    var phVars 	= Joomla.getOptions('phVarsCM');
    var phUrl = phVars['url'];

	phSetVariables(phLFA);
	phSetForm();

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
	   url: phUrl,
	   type:'POST',
	   data:dataPost,
	   dataType:'JSON',
	   success:function(data){
			if ( data.status == 1 ){
				jQuery(panelId).html(data.message);
			} else {
                jQuery("#ph-ajaxtop").show();
                jQuery("#ph-ajaxtop-message").html(phGetMsg(data.error, 0));
                phCloseMsgBoxError();
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

    var phVars 	= Joomla.getOptions('phVarsCM');
    var phLang  = Joomla.getOptions('phLangCM');
    var phUrl = phVars['urlaction'];

	jQuery("#ph-ajaxtop-message").html(phGetMsg('<span class="ph-result-txt ph-info-txt">' + phLang['COM_PHOCACOMMANDER_UPDATING'] + '</span>', 0));
	jQuery("#ph-ajaxtop").show();

	var phRequestActive = jQuery.ajax({
	   url: phUrl,
	   type:'POST',
	   data:dataPost,
	   dataType:'JSON',
	   success:function(data){
			if ( data.status == 1 ){
				jQuery("#ph-ajaxtop-message").html(phGetMsg(data.message, 0));
				phCloseMsgBoxSuccess();

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


				jQuery("#ph-ajaxtop-message").html(phGetMsg(data.error, 0));
				phRequestActive = null;
				phCloseMsgBoxError();

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
			var errorMsg = phLang['COM_PHOCACOMMANDER_SERVER_ERROR'];
            errorMsg = '<span class="ph-result-txt ph-error-txt">' + errorMsg + ': ' + request.getResponseHeader('Status') + ' ('+ errorThrown +')' + '</span>';
            jQuery("#ph-ajaxtop-message").html(phGetMsg(errorMsg, 0));
			phCloseMsgBoxError();

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

    var phVars 	= Joomla.getOptions('phVarsCM');
	var phLang  = Joomla.getOptions('phLangCM');

	var phUrlRoot = phVars['urlroot'];

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
		phPrompt(dataPost, 'NewFolder', phLang['COM_PHOCACOMMANDER_CREATE'], '', "#phPromptValueNewFolder");
		return true;
	} else if (task == 'upload') {
		var txtPathFrom = jQuery.base64.atob(pathFrom, true);
		if (!txtPathFrom) {
			txtPathFrom = phLang['COM_PHOCACOMMANDER_JOOMLA_ROOT_FOLDER'];
		}
		phPlupload(dataPost['pathfrom']);
		phFrame(dataPost, 'Upload', phLang['COM_PHOCACOMMANDER_FOLDER'] + ': ' + '<span class="ph-file-folder">' + txtPathFrom + '</span>' );
		return true;
	} else if (searchIDs[0] == null) {
		phAlert(phLang['COM_PHOCACOMMANDER_NO_FILE_NO_FOLDER_SELECTED']);
		return false;
	} else {
		var count 			= searchIDs.filter(function(value) { return value !== undefined }).length;

		if (count == 1) {
			if (searchIDs[0].indexOf("folder|") >= 0) {
				searchIDsNoPrefix = searchIDs[0].replace('folder|', '');
				txtFileFolder = jQuery.base64.atob(searchIDsNoPrefix, true);
				txtFileFolder = '<span class="ph-file-folder">' + txtFileFolder + '</span> ' + phLang['COM_PHOCACOMMANDER_FOLDER_SM'];
				dataPost['type'] = 'folder';

				if (task == 'view' || task == 'edit') {
					phAlert(phLang['COM_PHOCACOMMANDER_FOLDER_CANNOT_BE_PREVIEWED_OR_EDITED']);
					return false;
				} else if (task == 'download') {
					phAlert(phLang['COM_PHOCACOMMANDER_FOLDER_CANNOT_BE_DOWNLOADED']);
					return false;
				}

			} else if (searchIDs[0].indexOf("file|") >= 0) {
				searchIDsNoPrefix = searchIDs[0].replace('file|', '');
				txtFileFolder = '<span class="ph-file-folder">' + jQuery.base64.atob(searchIDsNoPrefix, true) + '</span>';
				dataPost['type'] = 'file';

			}
		} else {
			if (task == 'rename') {
				phAlert(phLang['COM_PHOCACOMMANDER_ONLY_ONE_FILE_OR_FOLDER_NEEDS_TO_BE_SELECTED']);
				return false;
			} else if (task == 'view' || task == 'edit' || task == 'unpack' || task == 'download') {
				phAlert(phLang['COM_PHOCACOMMANDER_ONLY_ONE_FILE_NEEDS_TO_BE_SELECTED']);
				return false;
			}
			txtFileFolder = count + ' ' + phLang['COM_PHOCACOMMANDER_FILES_FOLDERS_SM'];
		}
		//return;

		var txtTo = phLang['COM_PHOCACOMMANDER_TO'];
		var txtPathWhere = jQuery.base64.atob(pathWhere, true);
		if (!txtPathWhere) {
			txtPathWhere = phLang['COM_PHOCACOMMANDER_JOOMLA_ROOT_FOLDER'];
		} else {
			txtPathWhere = '<span class="ph-file-folder">' + txtPathWhere + '</span> ' + phLang['COM_PHOCACOMMANDER_FOLDER_SM'];
		}


		if (task == 'copy') {
			phPrompt(dataPost, 'Copy', phLang['COM_PHOCACOMMANDER_OK'], phLang['COM_PHOCACOMMANDER_ARE_YOU_SURE_COPY'] + "<br>" + txtFileFolder + "<br>" + txtTo + "<br>" + txtPathWhere, "#phPromptValueCopy", 1 );
			return true;
		} else if (task == 'move') {
			phPrompt(dataPost, 'Move', phLang['COM_PHOCACOMMANDER_OK'], phLang['COM_PHOCACOMMANDER_ARE_YOU_SURE_MOVE'] + "<br>" + txtFileFolder + "<br>" + txtTo + "<br>" + txtPathWhere, "#phPromptValueMove", 1 );
			return true;
		} else if (task == 'delete') {
			phConfirm(dataPost, phLang['COM_PHOCACOMMANDER_ARE_YOU_SURE_DELETE'] + "<br>" + txtFileFolder + "<br>" + '<span class="ph-warning-text">' + phLang['COM_PHOCACOMMANDER_PERMANENTLY_REMOVE_WARNING'] + '</span>');
			return true;
		} else if (task == 'rename') {
			jQuery("#phPromptValueRename").val(jQuery.base64.atob(searchIDsNoPrefix, true));
			jQuery("#phPromptValueRename").on("focus", function () {
				jQuery(this).select();
			});
			phPrompt(dataPost, 'Rename', phLang['COM_PHOCACOMMANDER_RENAME'], '', '#phPromptValueRename', 0);
			return true;
		} else if (task == 'attributes') {
			clearAttribVal();
			phPrompt(dataPost, 'Attributes', phLang['COM_PHOCACOMMANDER_NEW_ATTRIBUTE'], phLang['COM_PHOCACOMMANDER_SET_NEW_ATTRIBUTE_FOR'] + "<br>" + txtFileFolder + '', "#phAttribVal");
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
			jQuery("#adminForm").submit();
			return true;
		} else if (task == 'download') {

			if (jQuery.base64.atob(pathFrom, true)) {
				var fullFile = jQuery.base64.atob(pathFrom, true) + '/' + jQuery.base64.atob(searchIDsNoPrefix, true);
			} else {
				var fullFile = jQuery.base64.atob(searchIDsNoPrefix, true);
			}

			var fullFileEncoded = jQuery.base64.btoa(fullFile);

			jQuery("#phFormTask").val('phocacommanderedit.download');
			jQuery("#phFormFilename").val(fullFileEncoded);
			jQuery("#adminForm").submit();
			return true;


		} else if (task == 'view') {
			var phFile = jQuery.base64.atob(searchIDsNoPrefix, true);
			var phExt = phFile.substr( (phFile.lastIndexOf('.') +1) );
			var phExt = phExt.toLowerCase();
			if (phExt == 'jpg' || phExt == 'jpeg' || phExt == 'png' || phExt == 'gif') {
				jQuery().prettyPhoto({social_tools: false, horizontal_padding: 17, theme: 'pp_default'});
				var ppImage	= phUrlRoot + jQuery.base64.atob(pathFrom, true) + '/' + jQuery.base64.atob(searchIDsNoPrefix, true);
				var ppTitle	= jQuery.base64.atob(searchIDsNoPrefix, true);
				var ppDesc	= ' ';
				jQuery.prettyPhoto.open(ppImage,ppTitle,ppDesc);
			} else {
				phAlert(phLang['COM_PHOCACOMMANDER_ONLY_IMAGES_CAN_BE_PREVIEWED']);
				return false;
			}
			return true;
		} else if (task == 'unpack') {
			var phFile = jQuery.base64.atob(searchIDsNoPrefix, true);
			var phExt = phFile.substr( (phFile.lastIndexOf('.') +1) );
			var phExt = phExt.toLowerCase();
			if (phExt == 'zip' || phExt == 'tar' || phExt == 'gz' || phExt == 'gzip' || phExt == 'bz2' || phExt == 'bzip2' ) {
				if (phExt == 'zip') {

					phPrompt(dataPost, 'Unpack', phLang['COM_PHOCACOMMANDER_OK'], phLang['COM_PHOCACOMMANDER_ARE_YOU_SURE_UNPACK'] + "<br>" + txtFileFolder + "<br>" + txtTo + "<br>" + txtPathWhere, "#phPromptValueUnpack", 1 );
				} else {
					var warnUnpack = '<span class="ph-warning-text">' + phLang['COM_PHOCACOMMANDER_EXTRACTED_FILES_OVERWRITE_EXISTING_FILES_WARNING'] + '</span>';
					phPrompt(dataPost, 'UnpackOther', phLang['COM_PHOCACOMMANDER_OK'], phLang['COM_PHOCACOMMANDER_ARE_YOU_SURE_UNPACK'] + "<br>" + txtFileFolder + "<br>" + txtTo + "<br>" + txtPathWhere + "<br>" + warnUnpack, "#phPromptValueUnpackOther", 1 );
				}

			} else {
				phAlert(phLang['COM_PHOCACOMMANDER_ONLY_ARCHIVE_FILE_CAN_BE_UNPACKED']);
			}
			return true;
		} else if (task == 'pack') {
			phPrompt(dataPost, 'Pack', phLang['COM_PHOCACOMMANDER_PACK'], '', '#phPromptValuePack', 0);
			return true;
		} else {
			return true;
		}
	}
	return true;
}

function phDoActionInline(task, fileName, pathFrom) {

    var phVars 	= Joomla.getOptions('phVarsCM');
    var phLang  = Joomla.getOptions('phLangCM');

    var phUrl = phVars['urlroot'];

	if (task == 'edit') {
		if (jQuery.base64.atob(pathFrom, true)) {
			var fullFile = jQuery.base64.atob(pathFrom, true) + '/' + fileName;
		} else {
			var fullFile = fileName;
		}

		var fullFileEncoded = jQuery.base64.btoa(fullFile);
		jQuery("#phFormTask").val('phocacommanderedit.edit');
		jQuery("#phFormFilename").val(fullFileEncoded);
		jQuery("#adminForm").submit();
		return true;

	} else if (task == 'view') {

		var phExt = fileName.substr( (fileName.lastIndexOf('.') +1) );
		var phExt = phExt.toLowerCase();
		if (phExt == 'jpg' || phExt == 'jpeg' || phExt == 'png' || phExt == 'gif') {
			jQuery().prettyPhoto({social_tools: false, horizontal_padding: 17, theme: 'pp_default'});
			var ppImage	= phUrl + jQuery.base64.atob(pathFrom, true) + '/' + fileName;
			var ppTitle	= fileName;
			var ppDesc	= ' ';
			jQuery.prettyPhoto.open(ppImage,ppTitle,ppDesc);
		} else {
			phAlert(phLang['COM_PHOCACOMMANDER_ONLY_IMAGES_CAN_BE_PREVIEWED']);
			return false;
		}
		return true;
	} else if (task == 'download') {

		if (jQuery.base64.atob(pathFrom, true)) {
			var fullFile = jQuery.base64.atob(pathFrom, true) + '/' + fileName;
		} else {
			var fullFile = fileName;
		}

		var fullFileEncoded = jQuery.base64.btoa(fullFile);
		jQuery("#phFormTask").val('phocacommanderedit.download');
		jQuery("#phFormFilename").val(fullFileEncoded);
		jQuery("#adminForm").submit();
		return true;
	}
}

/* DIALOG */
function phSetCloseButton() {
	jQuery('.ui-dialog-titlebar-close').html('<span class="ph-close-button ui-button-icon-primary ui-icon ui-icon-closethick"></span>');
}

function phAlert(txt) {

    var phLang  = Joomla.getOptions('phLangCM');
    var phLangOk = phLang['COM_PHOCACOMMANDER_OK'];
    var phLangCancel = phLang['COM_PHOCACOMMANDER_CANCEL'];

    var phButtons = {};
	phButtons[phLangOk] = function() {
        jQuery(this).dialog("close");
        return false;
    };

	jQuery("#phDialogWarning").dialog({
        autoOpen: false,
		modal: true,
		buttons: phButtons
    });
	jQuery( "#phDialogWarning" ).html( txt );
	jQuery( "#phDialogWarning" ).dialog( "open" );
	/* Correct class */
	phSetCloseButton();
	jQuery('button').addClass('btn btn-default');

}

function phConfirm(dataPost, txt) {

    var phLang  = Joomla.getOptions('phLangCM');
    var phLangOk = phLang['COM_PHOCACOMMANDER_OK'];
    var phLangCancel = phLang['COM_PHOCACOMMANDER_CANCEL'];

	jQuery("#phDialogConfirm" ).html( txt );

    var phButtons = {};
	phButtons[phLangOk] = function() {
        jQuery(this).dialog("close");
        phRequest(dataPost);
        return true;
    };
    phButtons[phLangCancel] = function() {
        jQuery(this).dialog("close");
		return false;
    };

    jQuery("#phDialogConfirm").dialog({
        autoOpen: false,
		modal: true,
		buttons: phButtons
    });
	jQuery( "#phDialogConfirm" ).dialog( "open" );
	/* Correct class */
	phSetCloseButton();
	jQuery('button').addClass('btn btn-default');
}

function phPrompt(dataPost, type, txtButton, txt, promptValue, checkBox) {

    var phLang  = Joomla.getOptions('phLangCM');
    var phLangOk = phLang['COM_PHOCACOMMANDER_OK'];
    var phLangCancel = phLang['COM_PHOCACOMMANDER_CANCEL'];

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

	phButtons[phLangCancel] = function() {
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

    var phLang  = Joomla.getOptions('phLangCM');
    var phLangOk = phLang['COM_PHOCACOMMANDER_OK'];
    var phLangCancel = phLang['COM_PHOCACOMMANDER_CANCEL'];

	var phButtons = {};
	phButtons[txtButton] = function() {
		jQuery(this).dialog("close");
		return false;
	};

	phButtons[phLangCancel] = function() {
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

    var phLang  = Joomla.getOptions('phLangCM');
    var phVars  = Joomla.getOptions('phVarsCM');

	jQuery('#selectAllA').on( "click", function() {
		phActivePanel = 'B';
		var checkedStatusA = this.checked;
		jQuery('#ph-table-A tr').find('td:first :checkbox').each(function () {
			jQuery(this).prop('checked', checkedStatusA);
			jQuery(this).parent().toggleClass('ph-active', this.checked);

		 });
	});

	jQuery('#selectAllB').on( "click", function() {
		phActivePanel = 'B';
		var checkedStatusB = this.checked;
		jQuery('#ph-table-B tr').find('td:first :checkbox').each(function () {
			jQuery(this).prop('checked', checkedStatusB);
			jQuery(this).parent().toggleClass('ph-active', this.checked);

		 });
	});

	/* Start */
	if(phWelcomeWarning == 0) {
		phPromtWarning('WelcomeWarning', phLang['COM_PHOCACOMMANDER_OK'], phVars['urladmin'], 0.6, 0.6 );
	}
	phLFA = {};
	phLFA['panel'] = 'A'; jQuery(document).ready(phLoadFiles(phLFA));
	phLFA['panel'] = 'B'; jQuery(document).ready(phLoadFiles(phLFA));


	/* Make selected file or folder active - color */
	jQuery('td input:checkbox').on("change", function(){
		jQuery(this).parent().toggleClass('ph-active', this.checked);
	});

	/* Make panel active - color */
	/* Start or change the form*/

	/* Change */
	jQuery('#ph-a').on( "click", function() {
		phActivePanel = 'A';
	});
	jQuery('#ph-b').on( "click", function() {
		phActivePanel = 'B';
	});

	jQuery('#phCommanderBox').on("click", function(){
		if (phActivePanel == 'A') {
			jQuery('#phStatusA').addClass('ph-status-active');
			jQuery('#phStatusB').removeClass('ph-status-active');
		} else if (phActivePanel == 'B') {
			jQuery('#phStatusB').addClass('ph-status-active');
			jQuery('#phStatusA').removeClass('ph-status-active');
		}
	})



	/* Keys */
	jQuery( document ).on( "keydown", function( e ) {
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

        }
    }
}).keyup(function(e) {
    if (e.keyCode in map) {
        map[e.keyCode] = false;
    }
}); */

	/* Attributes */
	jQuery('#ro').on("change", function(){ if(this.checked) { phO = phO + 4;} else {phO = phO - 4;} setAttribVal(phO, phG, phT);});
	jQuery('#wo').on("change", function(){ if(this.checked) { phO = phO + 2;} else {phO = phO - 2;} setAttribVal(phO, phG, phT);});
	jQuery('#eo').on("change", function(){ if(this.checked) { phO = phO + 1;} else {phO = phO - 1;} setAttribVal(phO, phG, phT);});

	jQuery('#rg').on("change", function(){ if(this.checked) { phG = phG + 4;} else {phG = phG - 4;} setAttribVal(phO, phG, phT);});
	jQuery('#wg').on("change", function(){ if(this.checked) { phG = phG + 2;} else {phG = phG - 2;} setAttribVal(phO, phG, phT);});
	jQuery('#eg').on("change", function(){ if(this.checked) { phG = phG + 1;} else {phG = phG - 1;} setAttribVal(phO, phG, phT);});

	jQuery('#rt').on("change", function(){ if(this.checked) { phT = phT + 4;} else {phT = phT - 4;} setAttribVal(phO, phG, phT);});
	jQuery('#wt').on("change", function(){ if(this.checked) { phT = phT + 2;} else {phT = phT - 2;} setAttribVal(phO, phG, phT);});
	jQuery('#et').on("change", function(){ if(this.checked) { phT = phT + 1;} else {phT = phT - 1;} setAttribVal(phO, phG, phT);});
})
