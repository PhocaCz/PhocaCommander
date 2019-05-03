<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();
echo '<div id="com_phocacommander-multipleupload" class="ph-in">';
echo $this->t['mu_response_msg'] ;
echo '<form action="'. JURI::base().'index.php?option=com_phocacommander" >';
if ($this->t['ftp']) {echo PhocaDownloadFileUpload::renderFTPaccess();}
echo '<small>'.JText::_('COM_PHOCACOMMANDER_SELECT_FILES').'. '.JText::_('COM_PHOCACOMMANDER_ADD_FILES_TO_UPLOAD_QUEUE_AND_CLICK_START_BUTTON').'</small>';
echo $this->t['mu_output'];
echo '</form>';
echo '</div>';
?>