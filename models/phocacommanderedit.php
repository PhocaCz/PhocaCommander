<?php
/*
 * @package		Joomla.Framework
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License version 2 or later;
 */
defined( '_JEXEC' ) or die();
jimport('joomla.application.component.modeladmin');

class PhocaCommanderCpModelPhocaCommanderEdit extends JModelAdmin
{
	protected	$option 		= 'com_phocacommander';
	protected 	$text_prefix	= 'com_phocacommander';
	
	function __construct() {
		
		$app	= JFactory::getApplication();
		parent::__construct();
		
	}
	protected function canEditState($record) {
		return parent::canEditState($record);
	}
	
	public function getSource($fileName) {
		$fileName = base64_decode($fileName);
		$item = new stdClass;
		if (JFile::exists(JPATH_ROOT.'/'.$fileName)) {
			$item->source = file_get_contents(JPATH_ROOT.'/'.$fileName);
		} else {
			$this->setError(JText::_('COM_PHOCACOMMANDER_FILE_DOES_NOT_EXIST'));
		}
		return $item;
	}
	
	public function getForm($data = array(), $loadData = true) {
		$app	= JFactory::getApplication();
		$form 	= $this->loadForm('com_phocagallery.phocacommanderedit', 'phocacommanderedit', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}
		return $form;
	}
	
	public function save($data) {
		jimport('joomla.filesystem.file');
		$app = JFactory::getApplication();
		$paramsC 			= JComponentHelper::getParams('com_phocacommander');
		$edit_not_writable 	= $paramsC->get( 'edit_not_writable', 1 );
		
		if ($data['id'] == 1) {
			if ($data['filename'] != '') {
				$fileName = base64_decode($data['filename']);
				$filePath = JPath::clean(JPATH_ROOT . '/' . $fileName);
				
				if (JFile::exists($filePath)) {
					//JClientHelper::setCredentialsFromRequest('ftp');
					//$ftp = JClientHelper::getCredentials('ftp');
					$user = get_current_user();
					chown($filePath, $user);
					JPath::setPermissions($filePath, '0644');
					
					if (!is_writable($filePath) && $edit_not_writable == 0) {
						$app->enqueueMessage(JText::_('COM_PHOCACOMMANDER_ERROR_FILE_NOT_WRITABLE'), 'warning');
						$app->enqueueMessage(JText::_('COM_PHOCACOMMANDER_FILE_PERMISSIONS (' . JPath::getPermissions($filePath) .')'), 'warning');

						if (!JPath::isOwner($filePath))
						{
							$app->enqueueMessage(JText::_('COM_PHOCACOMMANDER_CHECK_FILE_OWNERSHIP'), 'warning');
						}
						return false;
					}

					$return = JFile::write($filePath, $data['source']);

					if (JPath::isOwner($filePath) && !JPath::setPermissions($filePath, '0444')) {
						$app->enqueueMessage(JText::_('COM_PHOCACOMMANDER_ERROR_SOURCE_FILE_NOT_UNWRITABLE'), 'error');
						return false;
					} elseif (!$return){
						$app->enqueueMessage(JText::sprintf('COM_PHOCACOMMANDER_ERROR_FAILED_TO_SAVE_FILENAME', $fileName), 'error');
						return false;
					}
				}
			}
		}
		return true;
	}
}
?>