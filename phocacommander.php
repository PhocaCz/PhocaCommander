<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die;
if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
if (!JFactory::getUser()->authorise('core.manage', 'com_phocacommander')) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

require_once( JPATH_COMPONENT.'/controller.php' );
jimport( 'joomla.filesystem.folder' ); 
jimport( 'joomla.filesystem.file' );
require_once( JPATH_COMPONENT.'/helpers/phocacommander.php' );
require_once( JPATH_COMPONENT.'/helpers/phocacommanderresponse.php' );
require_once( JPATH_COMPONENT.'/helpers/fileupload.php' );
require_once( JPATH_COMPONENT.'/helpers/fileuploadmultiple.php' );
require_once( JPATH_COMPONENT.'/helpers/renderadmin.php' );


jimport('joomla.application.component.controller');
$controller	= JControllerLegacy::getInstance('PhocaCommanderCp');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
?>