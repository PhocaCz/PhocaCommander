<?php
/*
 * @package		Joomla.Framework
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License version 2 or later;
 */
defined('_JEXEC') or die();
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;
jimport('joomla.application.component.controllerform');

class PhocaCommanderCpControllerPhocaCommanderEdit extends FormController
{
	protected	$option 		= 'com_phocacommander';


	public function save($key = null, $urlVar = null)
	{

		if (!Session::checkToken('request')) {
			$response = array(
				'status' => '0',
				'error' => '<span class="ph-result-txt ph-error-txt">' . Text::_('JINVALID_TOKEN') . '</span>');
			echo json_encode($response);
			return;
		}


		$app   = Factory::getApplication();
		$lang  = Factory::getLanguage();
		$model = $this->getModel();
		$data  = $this->input->post->get('jform', array(), 'array');

		$context = "$this->option.edit.$this->context";
		$task = $this->getTask();
		$key = $urlVar 	= 'id';
		//$recordId = $this->input->getInt($urlVar);
		//$data[$key] = $recordId;

		// Access check.
		if (!$this->allowSave($data, $key)) {
			$response = array(
				'status' => '0',
				'error' => '<span class="ph-result-txt ph-error-txt">' . Text::_('JLIB_APPLICATION_ERROR_SAVE_NOT_PERMITTED') . '</span>');
			echo json_encode($response);
			return;
		}

		// Validate the posted data.
		// Sometimes the form needs some posted data, such as for plugins and modules.
		$form = $model->getForm($data, false);

		if (!$form) {

			$response = array(
				'status' => '0',
				'error' => '<span class="ph-result-txt ph-error-txt">' . $model->getError(). '</span>');
			echo json_encode($response);
			return;
		}


		// Test whether the data is valid.
		$validData = $model->validate($form, $data);

		// Check for validation errors.
		if ($validData === false)
		{
			// Get the validation messages.
			$errorMsg = '';
			$errors = $model->getErrors();

			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
			{
				if ($errors[$i] instanceof Exception)
				{
					//$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
					$errorMsg .= $errors[$i]->getMessage() . "\n";
				}
				else
				{
					//$app->enqueueMessage($errors[$i], 'warning');
					$errorMsg .= $errors[$i]. "\n";
				}
			}

			// Save the data in the session.
			/*$app->setUserState($context . '.data', $data);

			// Redirect back to the edit screen.
			$this->setRedirect(
				Route::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_item
					. $this->getRedirectToItemAppend($recordId, $urlVar). '&file='.$data['filename'], false
				)
			);*/

			$response = array(
				'status' => '0',
				'error' => '<span class="ph-result-txt ph-error-txt">' . Text::_('COM_PHOCACOMMANDER_ERROR') . ': ' . $errorMsg. '</span>');
			echo json_encode($response);
			return;
		}

		if (!isset($validData['tags']))
		{
			$validData['tags'] = null;
		}



		// Attempt to save the data.
		if (!$model->save($validData))
		{


			// Save the data in the session.
			//$app->setUserState($context . '.data', $validData);

			// Redirect back to the edit screen.
			/*$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $model->getError()));
			$this->setMessage($this->getError(), 'error');

			$this->setRedirect(
				Route::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_item
					. $this->getRedirectToItemAppend($recordId, $urlVar). '&file='.$data['filename'], false
				)
			);
			return false;*/

			$response = array(
				'status' => '0',
				'error' => '<span class="ph-result-txt ph-error-txt">' . Text::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $model->getError()). '</span>');
			echo json_encode($response);
			return;


		}

		/*$this->setMessage(
			Text::_(
				($lang->hasKey($this->text_prefix . ($recordId == 0 && $app->isSite() ? '_SUBMIT' : '') . '_SAVE_SUCCESS')
					? $this->text_prefix
					: 'JLIB_APPLICATION') . ($recordId == 0 && $app->isSite() ? '_SUBMIT' : '') . '_SAVE_SUCCESS'
			)
		);*/

		/*$success =  JText::_(
				($lang->hasKey($this->text_prefix . ($recordId == 0 && $app->isSite() ? '_SUBMIT' : '') . '_SAVE_SUCCESS')
					? $this->text_prefix
					: 'JLIB_APPLICATION') . ($recordId == 0 && $app->isSite() ? '_SUBMIT' : '') . '_SAVE_SUCCESS'
			);*/

		// Redirect the user and adjust session state based on the chosen task.

		/*switch ($task)
		{
			case 'apply':
				// Set the record data in the session.
				//$recordId = $model->getState($this->context . '.id');

				$this->holdEditId($context, $recordId);
				$app->setUserState($context . '.data', null);
				//$model->checkout($recordId);

				// Redirect back to the edit screen.
				$this->setRedirect(
					Route::_(
						'index.php?option=' . $this->option . '&view=' . $this->view_item
						. $this->getRedirectToItemAppend($recordId, $urlVar). '&file='.$data['filename'], false
					)
				);
				break;



			default:
				// Clear the record id and data from the session.
				$this->releaseEditId($context, $recordId);
				$app->setUserState($context . '.data', null);

				// Redirect to the list screen.
				$this->setRedirect(
					Route::_(
						'index.php?option=' . $this->option , false
					)
				);
				break;
		}*/

		$response = array(
				'status' => '1',
				'message' => '<span class="ph-result-txt ph-success-txt">' .Text::_('COM_PHOCACOMMANDER_SUCCESS_SAVING_FILE'). '</span>');
			echo json_encode($response);
			return;


		//$this->postSaveHook($model, $validData);

		//return true;
	}
}
?>
