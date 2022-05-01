<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Filesystem\Path;
use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Filesystem\File;
jimport( 'joomla.application.component.view');

class PhocaCommanderCpViewPhocaCommanderFilesA extends HtmlView
{
	protected $t;
	protected $p;
	protected $state;

	function display($tpl = null){

		if (!Session::checkToken('request')) {
			$response = array(
				'status' => '0',
				'error' => '<button type="button" class="close" data-dismiss="alert">×</button><div class="alert alert-danger">' . Text::_('JINVALID_TOKEN') . '</div>');
			echo json_encode($response);
			return;
		}

		$app					= Factory::getApplication();
		$this->t['panel']		= $app->input->get( 'panel', '', 'string'  );
		$this->t['activepanel']	= $app->input->get( 'activepanel', '', 'string'  );
		$this->t['orderinga']	= $app->input->get( 'orderinga', '', 'string'  );
		$this->t['directiona'] 	= $app->input->get( 'directiona', '', 'string'  );
		$this->t['foldera']		= $app->input->get( 'foldera', '', 'string'  );
		$this->t['orderingb']	= $app->input->get( 'orderingb', '', 'string'  );
		$this->t['directionb'] 	= $app->input->get( 'directionb', '', 'string'  );
		$this->t['folderb']		= $app->input->get( 'folderb', '', 'string'  );

		$app   			= Factory::getApplication();
		$context 		= 'com_phocacommander.phocacommander.';
		$app->getUserStateFromRequest($context .'orderinga', 'orderinga', $this->t['orderinga'], 'string');
		$app->getUserStateFromRequest($context .'orderingb', 'orderingb', $this->t['orderingb'], 'string');
		$app->getUserStateFromRequest($context .'directiona', 'directiona', $this->t['directiona'], 'string');
		$app->getUserStateFromRequest($context .'directionb', 'directionb', $this->t['directionb'], 'string');
		$app->getUserStateFromRequest($context .'panel', 'panel', $this->t['panel'], 'string');
		$app->getUserStateFromRequest($context .'activepanel', 'activepanel', $this->t['activepanel'], 'string');
		$app->getUserStateFromRequest($context .'foldera', 'foldera', $this->t['foldera'], 'string');
		$app->getUserStateFromRequest($context .'folderb', 'folderb', $this->t['folderb'], 'string');


		if ($this->t['panel'] == 'A') {
			$this->t['folder'] 		= $this->t['foldera'];
			$this->t['ordering'] 	= $this->t['orderinga'];
			$this->t['direction'] 	= $this->t['directiona'];
		} else if ($this->t['panel'] == 'B') {
			$this->t['folder'] 		= $this->t['folderb'];
			$this->t['ordering'] 	= $this->t['orderingb'];
			$this->t['direction'] 	= $this->t['directionb'];
		}

		$model 						= $this->getModel();
		$path 						= JPATH_ROOT;
		$searchPath					= JPATH_ROOT;
		$searchPathRel				= Uri::root();
		$parent						= '';
		$this->t['folderdecoded'] 	= '';
		if ($this->t['folder'] != '') {
			$this->t['folderdecoded'] 	= base64_decode($this->t['folder']);

			if (Folder::exists(Path::clean($path . '/' .$this->t['folderdecoded']))) {
				$searchPath					= $path . '/' . $this->t['folderdecoded'];
				$searchPathRel				= $searchPathRel . '/' . $this->t['folderdecoded'];
				$parent 					= str_replace("\\", "/", dirname($this->t['folderdecoded']));
				$parent 					= ($parent == '.') ? null : $parent;
			} else {
				$searchPath					= $path;
				$searchPathRel				= $searchPathRel;
				//$parent 					= null;
				$parent						= null;
				$this->t['folder'] 			= '';
				$this->t['folderdecoded']	= '';
				if ($this->t['panel'] == 'A') {
					$this->t['foldera']		= '';
					$app->getUserStateFromRequest($context .'foldera', 'foldera', '|', 'string');
				}
				if ($this->t['panel'] == 'B') {
					$this->t['folderb']		= '';
					$app->getUserStateFromRequest($context .'folderb', 'folderb', '|', 'string');
				}

			}

		}

		$paramsC 					= ComponentHelper::getParams('com_phocacommander');


		$this->p['display_inline_view'] 		= $paramsC->get( 'display_inline_view', 0);
		$this->p['display_inline_edit'] 		= $paramsC->get( 'display_inline_edit', 0);
		$this->p['display_inline_download'] 	= $paramsC->get( 'display_inline_download', 0);

		$this->p['box_height'] 		= $paramsC->get( 'box_height', '60vh');
		$this->t['urlimage'] 		= Uri::root().'media/com_phocacommander/images/administrator/';
		$this->t['urlimagemime'] 	= Uri::root().'media/com_phocacommander/images/administrator/mime/16';
		$this->t['url'] 			= 'index.php?option=com_phocacommander&view=phocacommanderfilesa&format=json&tmpl=component&'. Session::getFormToken().'=1';

		// Active panel
		$activePanelClass = '';
		if ($this->t['panel'] == $this->t['activepanel']) {
			$activePanelClass = 'ph-status-active';
		}


		// Direction Ouput of arrow
		if ($this->t['direction'] == 'ASC') {
			//$arrow = '&uarr;';
			$arrow = '<img src="'.$this->t['urlimage'].'/arrow-up.png" alt="" />';
			$reverse = 'DESC';
			$searchSorting = 0;
		} else {
			//$arrow = '&darr;';
			$arrow = '<img src="'.$this->t['urlimage'].'/arrow-down.png" alt="" />';
			$reverse = 'ASC';
			$searchSorting = 1;
		}

		$reorder = 0;
		$arrowName = $arrowSize = $arrowDate = '';
		if ($this->t['ordering'] == 'name') {
			$reorder = 0;
			$arrowName = '<span class="ph-arrow">'.$arrow.'</span>';
		}
		if ($this->t['ordering'] == 'size') {
			$reorder = 1;
			$searchSorting = 0;
			$arrowSize = '<span class="ph-arrow">'.$arrow.'</span>';
		}
		if ($this->t['ordering'] == 'date') {
			$reorder = 2;
			$searchSorting = 0;
			$arrowDate = '<span class="ph-arrow">'.$arrow.'</span>';
		}


		$lFF = PhocaCommanderHelper::createLoadFilesFunction($this->t, $this->t['folder'], 'name', $reverse);
		$name = '<a href="javascript: void(0)" onclick="'.$lFF.'">'.Text::_('COM_PHOCACOMMANDER_NAME').'</a> '.$arrowName;
		$lFF = PhocaCommanderHelper::createLoadFilesFunction($this->t, $this->t['folder'],  'size', $reverse);
		$size = '<a href="javascript: void(0)" onclick="'.$lFF.'">'.Text::_('COM_PHOCACOMMANDER_SIZE').'</a> '.$arrowSize;
		$lFF = PhocaCommanderHelper::createLoadFilesFunction($this->t, $this->t['folder'], 'date', $reverse);
		$date = '<a href="javascript: void(0)" onclick="'.$lFF.'">'.Text::_('COM_PHOCACOMMANDER_DATE').'</a> '.$arrowDate;


// Files Folders
$items 		= scandir($searchPath, $searchSorting);
$folders 	= array();
$files 		= array();
$i 			= 0;
foreach ($items as $k => $v) {
    if ($v === '.' or $v === '..') {
		continue;
	}

	// ------
	// FOLDER
	// ------
    if (is_dir($searchPath . '/' . $v)) {
		$folders[$i]['name'] = $v;
		$statF = stat($searchPath . '/' . $v);
		$folders[$i]['date'] = $statF['mtime'];
		$folders[$i]['size'] = $statF['size'];
		$folders[$i]['uid'] = $statF['uid'];
		$folders[$i]['gid'] = $statF['gid'];
		//$folders[$i]['chmod'] = fileperms($searchPath . '/' . $v);
		$folders[$i]['chmod'] = substr(sprintf('%o', fileperms($searchPath . '/' . $v)), -4);

		$folderLink = $v;
		if ($this->t['folderdecoded'] != '') {
			$folderLink = $this->t['folderdecoded']. '/'.$v;
		}

		$lFF = PhocaCommanderHelper::createLoadFilesFunction($this->t, base64_encode($folderLink), $this->t['ordering'], $this->t['direction']);
		$folders[$i]['fullname'] = '<div><label class="ph-checkbox"><input type="checkbox" name="'.base64_encode($v).'" value="folder|'.base64_encode($v).'" /> <img src="'.$this->t['urlimagemime'].'/icon-folder.png" alt="" /> [<a href="javascript: void(0)" onclick="'.$lFF.'" >'.utf8_encode($v).'</a>]</label></div>';
    }

	// ----
	// FILE
	// ----
	if (is_file($searchPath . '/' . $v)) {
		$files[$i]['name'] = $v;
		$statF = stat($searchPath . '/' . $v);
		$files[$i]['date'] = $statF['mtime'];
		$files[$i]['size'] = $statF['size'];
		$files[$i]['uid'] = $statF['uid'];
		$files[$i]['gid'] = $statF['gid'];
		//$files[$i]['chmod'] = fileperms($searchPath . '/' . $v);
		$files[$i]['chmod'] = substr(sprintf('%o', fileperms($searchPath . '/' . $v)), -4);

		$ext 		= File::getExt($v);
		$attribImg	= '';
		if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' | $ext == 'gif') {
			$attribImg = ' class="phLightBox" data-src="'.$searchPathRel.''.utf8_encode($v).'" ';
		}

		$image = PhocaCommanderHelper::getMimeTypeIcon($v);

		// Inline Actions
		$iA = '';


		// View
		if ($this->p['display_inline_view'] == 1) {
			if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' | $ext == 'gif') {
				$iA .= ' <a href="javascript: void(0)" onclick="phDoActionInline(\'view\', \''.$files[$i]['name'].'\', \''.$this->t['folder'].'\');return false;"><i class="glyphicon glyphicon-search icon-search" title="'.Text::_('COM_PHOCACOMMANDER_VIEW').'"></i></a>';
			}
		}

		// Edit
		if ($this->p['display_inline_edit'] == 1) {
			$iA .= ' <a href="javascript: void(0)" onclick="phDoActionInline(\'edit\', \''.$files[$i]['name'].'\', \''.$this->t['folder'].'\');return false;"><i class="glyphicon glyphicon-edit icon-edit" title="'.Text::_('COM_PHOCACOMMANDER_EDIT').'"></i></a>';
		}

		// Download
		if ($this->p['display_inline_download'] == 1) {
			$iA .= ' <a href="javascript: void(0)" onclick="phDoActionInline(\'download\', \''.$files[$i]['name'].'\', \''.$this->t['folder'].'\');return false;"><i class="glyphicon glyphicon-download icon-download" title="'.Text::_('COM_PHOCACOMMANDER_DOWNLOAD').'"></i></a>';
		}



		//$files[$i]['fullname'] = '<div><label class="ph-checkbox"><input type="checkbox" name="'.base64_encode($v).'" /> <img src="'.$this->t['urlimagemime'].'/icon-empty.png" alt="" /> <a href="" >'.utf8_encode($v).'</a></label></div>';
		$files[$i]['fullname'] = '<div><label class="ph-checkbox"><input class="input-checkbox" type="checkbox" name="'.base64_encode($v).'" value="file|'.base64_encode($v).'" /> '.$image. ' <span '.$attribImg.'>'.utf8_encode($v).'</span> '.$iA.'</label></div>';
	}
	$i++;
}

// Folders
$o = '<div class="ph-box-s">';



$o .= '<div class="ph-status '.$activePanelClass.'" id="phStatus'.$this->t['panel'].'"></div>';
$o .= '<table class="ph-table">';
$o .= '<tr><th class="ph-check"><input type="checkbox" name="selectAll" id="selectAll'.$this->t['panel'].'" /></th>';
$o .= '<th class="ph-name">'.$name.'</th>';
$o .= '<th class="ph-size">'.$size.'</th>';
$o .= '<th class="ph-date">'.$date.'</th>';
$o .= '<th class="ph-attributes">'.Text::_('COM_PHOCACOMMANDER_ATTR').'</th>';
$o .= '<th class="ph-owner">'.Text::_('COM_PHOCACOMMANDER_OWNER').'</th></tr></table></div>';
$o .= '<div class="ph-box-o" style="height:'.htmlspecialchars(strip_tags($this->p['box_height'])).';"><table class="ph-table" id="ph-table-'.$this->t['panel'].'">';

// UP
if ($parent == '' && $path == $searchPath) {

} else {
	$lFF = PhocaCommanderHelper::createLoadFilesFunction($this->t, base64_encode($parent), $this->t['ordering'], $this->t['direction']);
	$o .= '<tr><td class="ph-name">&nbsp; &nbsp; <a href="javascript: void(0)" onclick="'.$lFF.'" ><img src="'.$this->t['urlimage'].'/up.png" alt="" /></a> &nbsp; <a href="javascript: void(0)" onclick="'.$lFF.'" >..</a></td>';
	$o .= '<td class="ph-size"></td>';
	$o .= '<td class="ph-date"></td>';
	$o .= '<td class="ph-attributes"></td>';
	$o .= '<td class="ph-owner"></td></tr>';
}

// Folders
if ($reorder == 2) {
	$model->sortItems($folders, "date", $this->t['direction']);
}

foreach ($folders as $k => $v) {
	//$o .= '<tr><td class="ph-name">'.$v['fullname'].'('.$this->t['folderdecoded'].')</td>';
	$o .= '<tr><td class="ph-name">'.$v['fullname'].'</td>';
	$o .= '<td class="ph-size"></td>';
	$o .= '<td class="ph-date">'.date('Y-m-d H:i', $v['date']).'</td>';
	$o .= '<td class="ph-attributes" >'.$v['chmod'].'</td>';
	//$o .= '<td class="ph-owner">'.$v['uid'].' ('.$v['gid'].')</td></tr>';
	$o .= '<td class="ph-owner">'.$v['uid'].'</td></tr>';
}

// Files
if ($reorder == 1 ) {
	$model->sortItems($files, "size", $this->t['direction']);
}
if ($reorder == 2) {
	$model->sortItems($files, "date", $this->t['direction']);
}

foreach ($files as $k => $v) {
	$o .= '<tr><td class="ph-name">'.$v['fullname'].'</td>';
	$o .= '<td class="ph-size">'.PhocaCommanderHelper::getFileSizeReadable($v['size']).'</td>';
	$o .= '<td class="ph-date">'.date('Y-m-d H:i', $v['date']).'</td>';
	$o .= '<td class="ph-attributes">'.$v['chmod'].'</td>';
	//$o .= '<td class="ph-owner">'.$v['uid'].' ('.$v['gid'].')</td></tr>';
	$o .= '<td class="ph-owner">'.$v['uid'].'</td></tr>';
}

$o .='</table></div>';

$o .= '<div class="ph-box-b"><table class="ph-table">';
$o .= '<tr><td class="ph-path" colspan="5">'.Path::clean($searchPath).'</td></table></div>';

$o .= '<form style="display:none;">';
$o .= '<input type="hidden" value="'.$this->t['folder'].'" name="ph-panel'.$this->t['panel'].'" id="phPanel'.$this->t['panel'].'" />';
$o .= '</form>';




		$response = array(
		'status' => '1',
		'message' => $o);
		echo json_encode($response);
		return;
		exit;
	}
}
?>
