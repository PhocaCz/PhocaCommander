<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;

?>

<div id="ph-ajaxtop"><div id="ph-ajaxtop-message"><div class="ph-loader-top"></div></div></div>
<?php /*
<div id="ph-ajaxtop" style="display:block"><div id="ph-ajaxtop-message"><div id="ph-ajaxtop-close">x</div><span class="ph-result-txt ph-error-txt">Some Message</span><div class="ph-progressbar-bottom"></div></div></div>
*/ ?>
<div id="system-message-container"></div>
<div class="ph-commander-box" id="phCommanderBox">
	<div class="row ph-commander-box-in">
        <div class="col-xs-12 col-sm-6 col-md-6"><div class="ph-a" id="ph-a"></div></div>
		<div class="col-xs-12 col-sm-6 col-md-6"><div class="ph-b" id="ph-b"></div></div>
    </div>
	<div class="row row-fluid ph-commander-box-in">
	<div class="span12"style="text-align:right;padding-right: 30px"><?php echo PhocaCommanderHelper::getInfo(); ?><a href="https://www.phoca.cz/version/index.php?phocacommander=<?php echo  $this->t['version']; ?>" target="_blank"><?php echo JText::_('COM_PHOCACOMMANDER_CHECK_FOR_UPDATE') ?></a></div>
	</div>
</div>

<div id="phDialogWarning" class="ph-dialog" title="<?php echo Text::_('COM_PHOCACOMMANDER_WARNING'); ?>"></div>
<div id="phDialogConfirm" class="ph-dialog" title="<?php echo Text::_('COM_PHOCACOMMANDER_CONFIRM'); ?>"></div>
<div id="phDialogPromptNewFolder" class="ph-dialog" title="<?php echo Text::_('COM_PHOCACOMMANDER_SET'); ?>">
	<div><?php echo Text::_('COM_PHOCACOMMANDER_NEW_FOLDER');?></div>
	<div class="ph-prepend-text"></div>
	<div><input type="text" name="phPromptValueNewFolder" id="phPromptValueNewFolder" value="" class="input" /></div>
</div>

<div id="phDialogPromptRename" class="ph-dialog" title="<?php echo Text::_('COM_PHOCACOMMANDER_SET'); ?>">
	<div><?php echo Text::_('COM_PHOCACOMMANDER_RENAME');?></div>
	<div class="ph-prepend-text"></div>
	<div><input type="text" name="phPromptValueRename" id="phPromptValueRename" value="" class="input" /></div>
</div>

<div id="phDialogPromptCopy" class="ph-dialog" title="<?php echo Text::_('COM_PHOCACOMMANDER_SET'); ?>">
	<div><?php echo Text::_('COM_PHOCACOMMANDER_COPY');?></div>
	<div class="ph-prepend-text"></div>
	<div class="ph-input-box-prompt"><label class="ph-input-prompt"><input type="checkbox" name="phPromptValueCopy" id="phPromptValueCopy" value="" class="ph-input-checkbox-prompt" /> <span><?php echo Text::_('COM_PHOCACOMMANDER_OVERWRITE_EXISTING_FOLDERS_FILES'); ?>?</span></label></div>
</div>

<div id="phDialogPromptMove" class="ph-dialog" title="<?php echo Text::_('COM_PHOCACOMMANDER_SET'); ?>">
	<div><?php echo Text::_('COM_PHOCACOMMANDER_MOVE');?></div>
	<div class="ph-prepend-text"></div>
	<div class="ph-input-box-prompt"><label class="ph-input-prompt"><input type="checkbox" name="phPromptValueMove" id="phPromptValueMove" value="" class="ph-input-checkbox-prompt" /> <span><?php echo Text::_('COM_PHOCACOMMANDER_OVERWRITE_EXISTING_FOLDERS_FILES'); ?>?</span></label></div>
</div>

<div id="phDialogPromptUnpack" class="ph-dialog" title="<?php echo Text::_('COM_PHOCACOMMANDER_SET'); ?>">
	<div><?php echo Text::_('COM_PHOCACOMMANDER_UNPACK');?></div>
	<div class="ph-prepend-text"></div>
	<div class="ph-input-box-prompt"><label class="ph-input-prompt"><input type="checkbox" name="phPromptValueUnpack" id="phPromptValueUnpack" value="" class="ph-input-checkbox-prompt" /> <span><?php echo Text::_('COM_PHOCACOMMANDER_OVERWRITE_EXISTING_FOLDERS_FILES'); ?>?</span></label></div>
</div>

<div id="phDialogPromptUnpackOther" class="ph-dialog" title="<?php echo Text::_('COM_PHOCACOMMANDER_SET'); ?>">
	<div><?php echo Text::_('COM_PHOCACOMMANDER_UNPACK');?></div>
	<div class="ph-prepend-text"></div>
	<input type="hidden" name="phPromptValueUnpackOther" id="phPromptValueUnpackOther" value="" />
</div>

<div id="phDialogPromptPack" class="ph-dialog" title="<?php echo Text::_('COM_PHOCACOMMANDER_SET'); ?>">
	<div><?php echo Text::_('COM_PHOCACOMMANDER_SET_PACKAGE_NAME');?></div>
	<div class="ph-prepend-text"></div>
	<div><input type="text" name="phPromptValuePack" id="phPromptValuePack" value=".zip" class="input" /></div>
</div>

<div id="phDialogPromptWelcomeWarning" class="ph-dialog" title="<?php echo Text::_('COM_PHOCACOMMANDER_WARNING'); ?>">
	<div><?php echo Text::_('COM_PHOCACOMMANDER_WELCOME_WARNING');?></div>
	<div class="ph-prepend-text"></div>
	<input type="hidden" name="phPromptValueUnpackOther" id="phPromptValueUnpackOther" value="" />
</div>


<div id="phDialogPromptAttributes" class="ph-dialog" style="width: 400px" title="<?php echo Text::_('COM_PHOCACOMMANDER_SET'); ?>">
<div><?php echo Text::_('COM_PHOCACOMMANDER_ATTRIBUTES');?></div>
<div class="ph-prepend-text"></div>
<table class="ph-table-attribs">
<tr>
	<th><?php echo Text::_('COM_PHOCACOMMANDER_PERMISSION'); ?></th>
	<th><?php echo Text::_('COM_PHOCACOMMANDER_OWNER'); ?></th>
	<th><?php echo Text::_('COM_PHOCACOMMANDER_GROUP'); ?></th>
	<th><?php echo Text::_('COM_PHOCACOMMANDER_OTHER'); ?></th>
</tr>
<tr>
	<td><?php echo Text::_('COM_PHOCACOMMANDER_READ'); ?></td>
	<td><input class="ph-input-checkbox-attr" type="checkbox" name="ro" id="ro" value="" /></td>
	<td><input class="ph-input-checkbox-attr" type="checkbox" name="rg" id="rg" value="" /></td>
	<td><input class="ph-input-checkbox-attr" type="checkbox" name="rt" id="rt" value="" /></td>
</tr>
<tr>
	<td><?php echo Text::_('COM_PHOCACOMMANDER_WRITE'); ?></td>
	<td><input class="ph-input-checkbox-attr" type="checkbox" name="wo" id="wo" value="" /></td>
	<td><input class="ph-input-checkbox-attr" type="checkbox" name="wg" id="wg" value="" /></td>
	<td><input class="ph-input-checkbox-attr" type="checkbox" name="wt" id="wt" value="" /></td>
</tr>
<tr>
	<td><?php echo Text::_('COM_PHOCACOMMANDER_EXECUTE'); ?></td>
	<td><input class="ph-input-checkbox-attr" type="checkbox" name="eo" id="eo" value="" /></td>
	<td><input class="ph-input-checkbox-attr" type="checkbox" name="eg" id="eg" value="" /></td>
	<td><input class="ph-input-checkbox-attr" type="checkbox" name="et" id="et" value="" /></td>
</tr>
<tr>
	<td><?php echo Text::_('COM_PHOCACOMMANDER_VALUE'); ?></td>
	<td colspan="3"><input id="phAttribVal" class="input input-sm input-small ph-input-attribs" type="text" name="phAttribVal" value="" /></td>
</tr>
</table>
</div>

<form id="adminForm" name="adminForm" method="post" action="index.php?option=com_phocacommander">
<input type="hidden" name="task" id="phFormTask" value="" />
<input type="hidden" name="filename" id="phFormFilename" value="" />
<input type="hidden" name="id" value="1" />
<div id="phFormBox" style="display:none;"></div>
<?php echo HTMLHelper::_('form.token'); ?>
</form>

<div id="phDialogPromptUpload" class="ph-dialog" title="<?php echo Text::_('COM_PHOCACOMMANDER_UPLOAD'); ?>">
	<div><span class="ph-prepend-text"></span> <span>(<?php echo  Text::_( 'COM_PHOCACOMMANDER_MAX_UPLOAD_SIZE' ).':&nbsp;'.$this->t['uploadmaxsizeread'] ?>)</span></div>
	<?php echo $this->loadTemplate('multipleupload'); ?>
</div>



