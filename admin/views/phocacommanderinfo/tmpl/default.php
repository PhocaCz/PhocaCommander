<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

echo '<form action="index.php" method="post" name="adminForm" id="adminForm">';
echo '<div id="j-sidebar-container" class="span2">'.JHtmlSidebar::render().'</div>';
echo '<div id="j-main-container" class="span10">';
echo '<div style="float:right;margin:10px;">'
	. JHTML::_('image', 'media/com_phocacommander/images/administrator/logo-phoca.png', 'Phoca.cz' )
	.'</div>'  ;

echo '<div class="ph-cpanel-logo">'.JHtml::_('image', 'media/com_phocacommander/images/administrator/logo-phoca-commander.png', 'Phoca.cz') . '</div>';
echo '<h3>'.JText::_('COM_PHOCACOMMANDER_PHOCA_COMMANDER').' - '. JText::_('COM_PHOCACOMMANDER_INFORMATION').'</h3>'
	.'<div style="clear:both;"></div>';

echo '<h3>'.  JText::_('COM_PHOCACOMMANDER_HELP').'</h3>';

echo '<p>'
.'<a href="https://www.phoca.cz/phocacommander/" target="_blank">Phoca Commander Main Site</a><br />'
.'<a href="https://www.phoca.cz/documentation/" target="_blank">Phoca Commander User Manual</a><br />'
.'<a href="https://www.phoca.cz/forum/" target="_blank">Phoca Commander Forum</a><br />'
.'</p>';

echo '<h3>'.  JText::_('COM_PHOCACOMMANDER_VERSION').'</h3>'
.'<p>'.  $this->t['version'] .'</p>';

echo '<h3>'.  JText::_('COM_PHOCACOMMANDER_COPYRIGHT').'</h3>'
.'<p>© 2007 - '.  date("Y"). ' Jan Pavelka</p>'
.'<p><a href="https://www.phoca.cz/" target="_blank">www.phoca.cz</a></p>'
.JHTML::_('image', 'media/com_phocacommander/images/administrator/logo.png', 'Phoca.cz');

echo '<h3>'.  JText::_('COM_PHOCACOMMANDER_LICENSE').'</h3>'
.'<p><a href="http://www.gnu.org/licenses/gpl-2.0.html" target="_blank">GPLv2</a></p>';

echo '<h3>'.  JText::_('COM_PHOCACOMMANDER_TRANSLATION').': '. JText::_('COM_PHOCACOMMANDER_TRANSLATION_LANGUAGE_TAG').'</h3>'
        .'<p>© 2007 - '.  date("Y"). ' '. JText::_('COM_PHOCACOMMANDER_TRANSLATER'). '</p>'
        .'<p>'.JText::_('COM_PHOCACOMMANDER_TRANSLATION_SUPPORT_URL').'</p>';

echo '<input type="hidden" name="task" value="" />'
.'<input type="hidden" name="option" value="com_phocacommander" />'
.'<input type="hidden" name="controller" value="phocacommanderinfo" />';

echo '<p>&nbsp;</p>';

echo '<div style="border-top:1px solid #eee"></div><p>&nbsp;</p>';

 echo PhocaCommanderRenderAdmin::getLinks();

echo '<div style="border-top:1px solid #eee"></div><p>&nbsp;</p>'
.'<div class="btn-group">
<a class="btn btn-large btn-primary" href="https://www.phoca.cz/version/index.php?phocacommander='.  $this->t['version'] .'" target="_blank"><i class="icon-loop icon-white"></i>&nbsp;&nbsp;'.  JText::_('COM_PHOCACOMMANDER_CHECK_FOR_UPDATE') .'</a></div>';

echo '<div style="margin-top:30px;height:39px;background: url(\''.JURI::root(true).'/media/com_phocacommander/images/administrator/line.png\') 100% 0 no-repeat;">&nbsp;</div>';


echo '</div>';
//echo '<div class="span1"></div>';

echo '</div>';
echo '</form>';

