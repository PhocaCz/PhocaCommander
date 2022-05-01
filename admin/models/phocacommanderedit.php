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
use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Filesystem\Path;
jimport('joomla.application.component.modeladmin');

class PhocaCommanderCpModelPhocaCommanderEdit extends AdminModel
{
	protected	$option 		= 'com_phocacommander';
	protected 	$text_prefix	= 'com_phocacommander';
	public $typeAlias 			= 'com_phocacommander.phocacommanderedit';


	function __construct() {

		$app	= Factory::getApplication();
		parent::__construct();

	}
	protected function canEditState($record) {
		return parent::canEditState($record);
	}

	public function getSource($fileName) {
		$fileName = base64_decode($fileName);
		$item = new stdClass;
		if (File::exists(JPATH_ROOT.'/'.$fileName)) {
			$item->source = file_get_contents(JPATH_ROOT.'/'.$fileName);
		} else {
			$this->setError(Text::_('COM_PHOCACOMMANDER_FILE_DOES_NOT_EXIST'));
		}
		return $item;
	}

	public function getForm($data = array(), $loadData = true) {
		$app	= Factory::getApplication();
		$form 	= $this->loadForm('com_phocacommander.phocacommanderedit', 'phocacommanderedit', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}
		return $form;
	}

	public function save($data) {
		jimport('joomla.filesystem.file');
		$app = Factory::getApplication();
		$paramsC 			= ComponentHelper::getParams('com_phocacommander');
		$edit_not_writable 	= $paramsC->get( 'edit_not_writable', 1 );

		if ($data['id'] == 1) {
			if ($data['filename'] != '') {
				$fileName = base64_decode($data['filename']);
				$filePath = Path::clean(JPATH_ROOT . '/' . $fileName);

				if (File::exists($filePath)) {
					//JClientHelper::setCredentialsFromRequest('ftp');
					//$ftp = JClientHelper::getCredentials('ftp');
					$user = get_current_user();
					//$basePermissions = JPath::getPermissions($filePath);
					//$basePermissions = fileperms($filePath);
					$basePermissions = substr(sprintf('%o', fileperms($filePath)), -4);
					chown($filePath, $user);
					Path::setPermissions($filePath, '0644');



					if (!is_writable($filePath) && $edit_not_writable == 0) {
						$app->enqueueMessage(Text::_('COM_PHOCACOMMANDER_ERROR_FILE_NOT_WRITABLE'), 'warning');
						$app->enqueueMessage(Text::_('COM_PHOCACOMMANDER_FILE_PERMISSIONS (' . Path::getPermissions($filePath) .')'), 'warning');

						if (!Path::isOwner($filePath))
						{
							$app->enqueueMessage(Text::_('COM_PHOCACOMMANDER_CHECK_FILE_OWNERSHIP'), 'warning');
						}
						return false;
					}

					$return = File::write($filePath, $data['source']);

					/*
					// Test solution, if problems it can be set to:
					if (!$return) {
						$this->setError(Text::sprintf('COM_PHOCADOWNLOAD_ERROR_FAILED_TO_SAVE_FILENAME', $fileName));
						return false;
					}
					*/

					if (Path::isOwner($filePath) && !Path::setPermissions($filePath, $basePermissions)) {

						$app->enqueueMessage(Text::_('COM_PHOCACOMMANDER_ERROR_SOURCE_FILE_NOT_UNWRITABLE'), 'error');
						return false;
					} elseif (!$return){
						$app->enqueueMessage(Text::sprintf('COM_PHOCACOMMANDER_ERROR_FAILED_TO_SAVE_FILENAME', $fileName), 'error');
						return false;
					}


				}
			}
		}
		return true;
	}
}
?>
