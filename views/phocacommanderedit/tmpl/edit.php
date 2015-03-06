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

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');

?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		
		if (task == 'phocacommanderedit.cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
			Joomla.submitform(task, document.getElementById('adminForm'));
		}
		else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<?php

echo '<div id="phocacommanderedit"><form action="'.JRoute::_('index.php?option=com_phocacommander&layout=edit&id=1&file='.$this->t['fullfile']).'" method="post" name="adminForm" id="adminForm" class="form-validate">'."\n"
.'<div class="row-fluid">'."\n";

// First Column
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

if ($this->ftp) { echo $this->loadTemplate('ftp');}

echo '<p class="well well-small lead" style="line-height:1;">'.JText::_('COM_PHOCACOMMANDER_EDITING_FILE').' "'.$this->t['file'].'"<br /><span style="font-size:small">('.base64_decode($this->t['fullfile']).')</span></p>';
echo '<p class="label">'.JText::_('COM_PHOCACOMMANDER_TOGGLE_FULL_SCREEN').'</p>';
echo '<div class="clr"></div>';


//echo $this->form->getLabel('source');
//echo '<div class="clr"></div>';
echo '<div class="editor-border" id="ph-editor">';
echo $this->form->getInput('source');
echo '</div>';

echo '</div></div>'. "\n";

echo '</div>';

// Second Column
echo '<div class="span2"></div>';//end span2
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
echo '</div></form></div>';
?>