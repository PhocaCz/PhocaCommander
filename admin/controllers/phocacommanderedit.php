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
use Joomla\CMS\Factory;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Filesystem\Path;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Uri\Uri;
jimport('joomla.application.component.controllerform');

class PhocaCommanderCpControllerPhocaCommanderEdit extends FormController
{
	protected	$option 		= 'com_phocacommander';


	function __construct($config=array()) {
		parent::__construct($config);

		$app   			= Factory::getApplication();
		$context 		= 'com_phocacommander.phocacommander.';
		$orderinga 		= $app->input->get('orderinga', '', 'string');
		$orderingb 		= $app->input->get('orderingb', '', 'string');
		$directiona 	= $app->input->get('directiona', '', 'string');
		$directionb 	= $app->input->get('directionb', '', 'string');
		$activepanel 	= $app->input->get('activepanel', '', 'string');
		$panel 			= $app->input->get('panel', '', 'string');
		$foldera 		= $app->input->get('foldera', '', 'string');
		$folderb 		= $app->input->get('folderb', '', 'string');

		if(Session::checkToken('request')) {
			$app->input->post->set('orderinga', $orderinga);
			$app->input->post->set('orderingb', $orderingb);
			$app->input->post->set('directiona', $directiona);
			$app->input->post->set('directionb', $directionb);
			$app->input->post->set('foldera', $foldera);
			$app->input->post->set('folderb', $folderb);
			$app->input->post->set('activepanel', $activepanel);
			$app->input->post->set('panel', $panel);


			$app->getUserStateFromRequest($context .'orderinga', 'orderinga', $orderinga, 'string');
			$app->getUserStateFromRequest($context .'orderingb', 'orderingb', $orderingb, 'string');
			$app->getUserStateFromRequest($context .'directiona', 'directiona', $directiona, 'string');
			$app->getUserStateFromRequest($context .'directionb', 'directionb', $directionb, 'string');
			$app->getUserStateFromRequest($context .'panel', 'panel', $panel, 'string');
			$app->getUserStateFromRequest($context .'activepanel', 'activepanel', $activepanel, 'string');
			$app->getUserStateFromRequest($context .'foldera', 'foldera', $foldera, 'string');
			$app->getUserStateFromRequest($context .'folderb', 'folderb', $folderb, 'string');
		}

	}

	protected function allowEdit($data = array(), $key = 'id') {
		$user		= Factory::getUser();
		$allow		= null;
		$allow		= $user->authorise('core.edit', 'com_phocacommander');

		if ($allow === null) {
			return parent::allowEdit($data, $key);
		} else {
			return $allow;
		}
	}

	public function cancel($key = null)
	{
		Session::checkToken('request') or jexit(Text::_('JINVALID_TOKEN'));
		$this->setRedirect(Route::_('index.php?option=com_phocacommander'.$this->getRedirectToListAppend(), false));

		return true;
	}

	public function edit($key = null, $urlVar = null) {

		$app   		= Factory::getApplication();
		$context 	= "$this->option.edit.$this->context";
		$file		= $app->input->get( 'filename', '', 'string'  );
		$recordId 	= 1;
		$key = $urlVar 	= 'id';

		if (!$this->allowEdit(array($key => $recordId), $key))
		{
			$this->setError(Text::_('JLIB_APPLICATION_ERROR_EDIT_NOT_PERMITTED'));
			$this->setMessage($this->getError(), 'error');

			$this->setRedirect(
				Route::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_list
					. $this->getRedirectToListAppend() . '&file='.$file, false
				)
			);

			return false;
		}

		$this->holdEditId($context, $recordId);
		$app->setUserState($context . '.data', null);

		$this->setRedirect(
			Route::_(
				'index.php?option=' . $this->option . '&view=' . $this->view_item
				. $this->getRedirectToItemAppend($recordId, $urlVar) . '&file='.$file, false
			)
		);

		return true;
	}

	public function download() {

		$app   		= Factory::getApplication();
		$context 	= "$this->option.edit.$this->context";
		$file		= $app->input->get( 'filename', '', 'string'  );


		$file 		= base64_decode($file);

		$path		= JPATH_ROOT;

		$pathFolder	= Path::clean($path . '/' .$file);

		$mimeType = '';
		if (File::exists($pathFolder)) {

			if (function_exists('mime_content_type')) {
				$mimeType 	= mime_content_type($pathFolder);

			} else if(class_exists('finfo')){

				$result = new finfo();
				if (is_resource($result) === true) {
					$mimeType = $result->file($pathFolder, FILEINFO_MIME_TYPE);
				}
			}

			if ($mimeType == '') {
				$ext 		= File::getExt($file);
				$mimeType = PhocaCommanderHelper::getMimeType($ext);
			}

			// Clean the output buffer
			ob_end_clean();

			// test for protocol and set the appropriate headers
			jimport( 'joomla.environment.uri' );
			$_tmp_uri 		= Uri::getInstance( Uri::current() );
			$_tmp_protocol 	= $_tmp_uri->getScheme();
			if ($_tmp_protocol == "https") {
				// SSL Support
				header('Cache-Control: private, max-age=0, must-revalidate, no-store');
			} else {
				header("Cache-Control: public, must-revalidate");
				header('Cache-Control: pre-check=0, post-check=0, max-age=0');
				header("Pragma: no-cache");
				header("Expires: 0");
			} /* end if protocol https */
			header("Content-Description: File Transfer");
			header("Expires: Sat, 30 Dec 1990 07:07:07 GMT");
			header("Accept-Ranges: bytes");

			header("Content-Type: " . (string)$mimeType);
			header('Content-Disposition: attachment; filename="'.basename($file).'"');
			header("Content-Transfer-Encoding: binary\n");

			@readfile($pathFolder);
			flush();
			exit;
		} else {

			$recordId 	= 1;
			$key = $urlVar 	= 'id';
			$this->setRedirect(
				Route::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_item
					. $this->getRedirectToItemAppend($recordId, $urlVar) . '&file='.$file, false
				)
			);
			return false;
		}
	}

	public function save($key = null, $urlVar = null)
	{
		Session::checkToken('request') or jexit(Text::_('JINVALID_TOKEN'));

		$app   = Factory::getApplication();
		$lang  = Factory::getLanguage();
		$model = $this->getModel();

		$data  = $this->input->post->get('jform', array(), 'array');

		$context = "$this->option.edit.$this->context";
		$task = $this->getTask();

		$key = $urlVar 	= 'id';

		$recordId = $this->input->getInt($urlVar);

		$data[$key] = $recordId;

		// Access check.
		if (!$this->allowSave($data, $key))
		{
			$this->setError(Text::_('JLIB_APPLICATION_ERROR_SAVE_NOT_PERMITTED'));
			$this->setMessage($this->getError(), 'error');

			$this->setRedirect(
				Route::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_list
					. $this->getRedirectToListAppend(). '&file='.$data['filename'] , false
				)
			);

			return false;
		}

		// Validate the posted data.
		// Sometimes the form needs some posted data, such as for plugins and modules.
		$form = $model->getForm($data, false);

		if (!$form)
		{
			$app->enqueueMessage($model->getError(), 'error');

			return false;
		}

		// Test whether the data is valid.
		$validData = $model->validate($form, $data);

		// Check for validation errors.
		if ($validData === false)
		{
			// Get the validation messages.
			$errors = $model->getErrors();

			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
			{
				if ($errors[$i] instanceof Exception)
				{
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				}
				else
				{
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}

			// Save the data in the session.
			$app->setUserState($context . '.data', $data);

			// Redirect back to the edit screen.
			$this->setRedirect(
				Route::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_item
					. $this->getRedirectToItemAppend($recordId, $urlVar). '&file='.$data['filename'], false
				)
			);

			return false;
		}

		if (!isset($validData['tags']))
		{
			$validData['tags'] = null;
		}

		// Attempt to save the data.
		if (!$model->save($validData))
		{


			// Save the data in the session.
			$app->setUserState($context . '.data', $validData);

			// Redirect back to the edit screen.
			$this->setError(Text::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $model->getError()));
			$this->setMessage($this->getError(), 'error');

			$this->setRedirect(
				Route::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_item
					. $this->getRedirectToItemAppend($recordId, $urlVar). '&file='.$data['filename'], false
				)
			);

			return false;
		}

		$this->setMessage(
			Text::_(
				($lang->hasKey($this->text_prefix . ($recordId == 0 && $app->isSite() ? '_SUBMIT' : '') . '_SAVE_SUCCESS')
					? $this->text_prefix
					: 'JLIB_APPLICATION') . ($recordId == 0 && $app->isSite() ? '_SUBMIT' : '') . '_SAVE_SUCCESS'
			)
		);

		// Redirect the user and adjust session state based on the chosen task.

		switch ($task)
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
		}


		$this->postSaveHook($model, $validData);

		return true;
	}
}
?>
