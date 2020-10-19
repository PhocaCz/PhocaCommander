<?php
/*
 * @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @component Phoca Gallery
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die;

JFactory::getDocument()->addScriptDeclaration(

'Joomla.submitbutton = function(task) {
	if (task == "phocacommanderedit.apply" && document.formvalidator.isValid(document.getElementById("adminForm"))) {
	    phSaveEdit(".button-apply");
	    //Joomla.submitform(task, document.getElementById("adminForm"));
	} else if (task == "phocacommanderedit.cancel" || document.formvalidator.isValid(document.getElementById("adminForm"))) {
		Joomla.submitform(task, document.getElementById("adminForm"));
	} else {
        Joomla.renderMessages({"error": ["'. JText::_('JGLOBAL_VALIDATION_FORM_FAILED', true).'"]});
	}
}

jQuery(document).ready(function() {
    jQuery(document).bind("keyup keydown", function(e){
        if(e.ctrlKey && e.which == 83){
            if (jQuery("#ph-ajaxtop:visible").length == 0) {
                Joomla.submitbutton("phocacommanderedit.apply");
            }
            event.preventDefault();
        }
    });
});
'

);
$action = JRoute::_('index.php?option=com_phocacommander&layout=edit&id=1&file='.$this->t['fullfile']);

echo '<div id="phocacommanderedit"><form action="'.$action.'" method="post" name="adminForm" id="adminForm" class="form-validate">';
//."\n"
//.'<div class="row-fluid">'."\n";

// First Column
/*
echo '<div class="span10 form-horizontal">';
$tabs = array (
'general' 		=> JText::_('COM_PHOCACOMMANDER_EDIT_OPTIONS'));
$o = '<ul class="nav nav-tabs">';
$i = 0;
foreach($tabs as $k => $v) {
	$cA = 0;
	if ($i == 0) {
		$cA = 'class="active"';
	}
	$o .= '<li '.$cA.'><a href="#'.$k.'" data-toggle="tab">'. $v.'</a></li>'."\n";
	$i++;
}
$o .= '</ul>';

echo '<div class="tab-content">'. "\n";

echo '<div class="tab-pane active" id="general">'."\n";
*/

if ($this->ftp) { echo $this->loadTemplate('ftp');}

/*echo '<p class="well well-small lead" style="line-height:1;">'.JText::_('COM_PHOCACOMMANDER_EDITING_FILE').' "'.$this->t['file'].'"<br /><span style="font-size:small">('.base64_decode($this->t['fullfile']).')</span></p>';
echo '<p class="label">'.JText::_('COM_PHOCACOMMANDER_TOGGLE_FULL_SCREEN').'</p>';
echo '<div class="clr"></div>';
*/

//echo $this->form->getLabel('source');
//echo '<div class="clr"></div>';
echo '<div class="editor-border" id="ph-editor">';
echo $this->form->getInput('source');
echo '</div>';

//echo '</div></div>'. "\n";

//echo '</div>';

// Second Column
//echo '<div class="span2"></div>';//end span2
echo '<div class="ph-line-info">'.JText::_('COM_PHOCACOMMANDER_EDITING_FILE').' "'.$this->t['file'].'" <span style="font-size:small">('.base64_decode($this->t['fullfile']).')</span></div>';
echo '<input name="task" type="hidden" value="" />'. "\n";
echo '<input name="orderinga" type="hidden" value="'.$this->t['orderinga'].'" />'. "\n";
echo '<input name="orderingb" type="hidden" value="'.$this->t['orderingb'].'" />'. "\n";
echo '<input name="directiona" type="hidden" value="'.$this->t['directiona'].'" />'. "\n";
echo '<input name="directionb" type="hidden" value="'.$this->t['directionb'].'" />'. "\n";
echo '<input name="foldera" type="hidden" value="'.$this->t['foldera'].'" />'. "\n";
echo '<input name="folderb" type="hidden" value="'.$this->t['folderb'].'" />'. "\n";
echo '<input name="panel" type="hidden" value="'.$this->t['panel'].'" />'. "\n";
echo '<input name="activepanel" type="hidden" value="'.$this->t['activepanel'].'" />'. "\n";
echo JHtml::_('form.token'). "\n";
echo  $this->form->getInput('filename');
echo $this->form->getInput('id');
echo '</form></div>';


echo '<div id="ph-ajaxtop"><div id="ph-ajaxtop-message"><div class="ph-loader-top"></div></div></div>';
?>
