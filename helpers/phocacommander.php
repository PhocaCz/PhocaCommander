<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die;
class PhocaCommanderHelper
{
	/*
	 * http://aidanlister.com/repos/v/function.size_readable.php
	 */
	public static function getFileSizeReadable ($size, $retstring = null, $onlyMB = false) {
	
		if ($onlyMB) {
			$sizes = array('B', 'kB', 'MB');
		} else {
			$sizes = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        }
		

		if ($retstring === null) { $retstring = '%01.2f %s'; }
        $lastsizestring = end($sizes);
		
        foreach ($sizes as $sizestring) {
                if ($size < 1024) { break; }
                if ($sizestring != $lastsizestring) { $size /= 1024; }
        }
		
        if ($sizestring == $sizes[0]) { $retstring = '%01d %s'; } // Bytes aren't normally fractional
        return sprintf($retstring, $size, $sizestring);
	}
	
	public static function getExtensionVersion($c = 'phocacommander') {
		$folder = JPATH_ADMINISTRATOR .DS. 'components'.DS.'com_'.$c;
		if (JFolder::exists($folder)) {
			$xmlFilesInDir = JFolder::files($folder, '.xml$');
		} else {
			$folder = JPATH_SITE .DS. 'components'.DS.'com_'.$c;
			if (JFolder::exists($folder)) {
				$xmlFilesInDir = JFolder::files($folder, '.xml$');
			} else {
				$xmlFilesInDir = null;
			}
		}

		$xml_items = array();
		if (count($xmlFilesInDir))
		{
			foreach ($xmlFilesInDir as $xmlfile)
			{
				if ($data = JApplicationHelper::parseXMLInstallFile($folder.DS.$xmlfile)) {
					foreach($data as $key => $value) {
						$xml_items[$key] = $value;
					}
				}
			}
		}
		
		if (isset($xml_items['version']) && $xml_items['version'] != '' ) {
			return $xml_items['version'];
		} else {
			return '';
		}
	}
	

	public static function setChmod($path, $mode)
	{
		
		if (is_string($mode))
		{
			$mode = octdec($mode);
			if ( ($mode < 0600) || ($mode > 0777) )
			{
				$mode = 0755;
			}
		}

		$ftpOptions = JClientHelper::getCredentials('ftp');

		// Check to make sure the path valid and clean
		$path = JPath::clean($path);

		if ($ftpOptions['enabled'] == 1)
		{
			// Connect the FTP client
			//jimport('joomla.client.ftp');
			//jimport('joomla.client.helper');
			$ftp = JClientFtp::getInstance(
				$ftpOptions['host'], $ftpOptions['port'], array(),
				$ftpOptions['user'], $ftpOptions['pass']
			);
			
		}

		
		if ($ftpOptions['enabled'] == 1)
		{
			// Translate path and delete
			$path = JPath::clean(str_replace(JPATH_ROOT, $ftpOptions['root'], $path), '/');

			// FTP connector throws an error
			$ret = $ftp->chmod($path, $mode);
		} else if (@chmod($path, $mode))
		{
			$ret = true;
		} else
		{
			return false;
		}
		return $ret;
	}
	
	
	public static function renderAjaxTopHtml($text) {
		$o = '<div id="ph-ajaxtop">'
		.'<div id="ph-ajaxtop-message">'. JHtml::_( 'image', 'media/com_phocacommander/images/administrator/icon-loading5.gif', '')
		.'&nbsp; '. htmlspecialchars($text) . '</div>'
		.'</div>';
		return $o;
	}
	
	public static function getMimeTypeIcon($filename, $size = 16, $outcome = 0) {
		$ext = JFile::getExt($filename);		
		switch(strtolower($ext)) {
			
			
			
			case 'html':
			case 'htm':
				$icon = 'html';
			break;
			
			case 'c':
			case 'js':
			case 'py':
			case 'rp':
				$icon = 'source';
			break;
			
			case 'xml':
				$icon = 'xml';
			break;
			
			case 'odp':
			case 'ppt':
			case 'pps':
			case 'ppsx':
			case 'pptx':
			case 'pptm':
			case 'ppsm':
				$icon = 'presentation';
			break;
			
			case 'ods':
			case 'xls':
			case 'xlsx':
				$icon = 'spreadsheet';
			break;
			
			case 'odt':
			case 'doc':
			case 'docx':
				$icon = 'document';
			break;
			
			case 'php':
				$icon = 'php';
			break;
			
			case 'png':
			case 'jpg':
			case 'jpeg':
			case 'gif':
			case 'bmp':
				$icon = 'img';
			break;
			
			case 'jar':
				$icon = 'jar';
			break;
			
			case 'pdf':
				$icon = 'pdf';
			break;
			
			case 'sql':
				$icon = 'sql';
			break;
			
			case 'svg':
			case 'ai':
			case 'cdr':
				$icon = 'drawing';
			break;
			
			case 'txt':
			case 'ini':
				$icon = 'txt';
			break;
			
			
			case '7z':
				$icon = '7zip';
			break;
			case 'gz':
				$icon = 'gzip';
			break;
			case 'rar':
				$icon = 'rar';
			break;
			case 'tar':
				$icon = 'tar';
			break;
			case 'zip':
			case 'bzip':
				$icon = 'zip';
			break;
			
			case 'flv':
			case 'avi':
			case 'mp4':
			case 'mpeg':
			case 'ogv':
				$icon = 'video';
			break;
			
			case 'ogg':
			case 'mp3':
			case 'wav':
				$icon = 'audio';
			break;
			
			default:
				$icon = 'empty';
			break;
		}
		
		if ($outcome == 1) {
			return 'style="background: url(\''.JURI::root(). 'media/com_phocacommander/images/administrator/mime/'.(int)$size.'/icon-'. htmlspecialchars($icon).'.png\') 0 center no-repeat;"';
		} else {
			return '<img src="'.JURI::root(). 'media/com_phocacommander/images/administrator/mime/'.(int)$size.'/icon-'. htmlspecialchars($icon). '.png'.'" alt="" />';
		}
		
		return $mime;
	}

	public static function createLoadFilesFunction($var, $folder, $ordering, $dir) {
		
		$o = '';
		$o .= 'var phLFAc = {};';
		$o .= 'phLFAc[\'panel\'] = \''.$var['panel'].'\';';
		
		
		// Active Panel always changes when click (click on order, click on folder will make active the clicked panel)
		//$o .= 'phLFAc[\'activepanel\'] = \''.$var['activepanel'].'\';';
		$o .= 'phLFAc[\'activepanel\'] = \''.$var['panel'].'\';';
		
		
		// FOLDER
		// | used for root
		// * don't change
		// 
		
		if ($folder == '') {
			$folder = '|';
		}
		
		if ($var['panel'] == 'A') {
			$o .= 'phLFAc[\'foldera\'] = \''.$folder.'\';';
			$o .= 'phLFAc[\'folderb\'] = \'*\';';
		} else {
			$o .= 'phLFAc[\'folderb\'] = \''.$folder.'\';';
			$o .= 'phLFAc[\'foldera\'] = \'*\';';
		}
		
		if ($var['panel'] == 'A') {
			$o .= 'phLFAc[\'orderinga\'] = \''.$ordering.'\';';
			$o .= 'phLFAc[\'orderingb\'] = \'\';';
		} else {
			$o .= 'phLFAc[\'orderingb\'] = \''.$ordering.'\';';
			$o .= 'phLFAc[\'orderinga\'] = \'\';';
		}
		
		if ($var['panel'] == 'A') {
			$o .= 'phLFAc[\'directiona\'] = \''.$dir.'\';';
			$o .= 'phLFAc[\'directionb\'] = \'\';';
		} else {
			$o .= 'phLFAc[\'directionb\'] = \''.$dir.'\';';
			$o .= 'phLFAc[\'directiona\'] = \'\';';
		}

		$o .= 'phLoadFiles(phLFAc);';
		return $o;
	
	}
	
	public static function getDefaultAllowedMimeTypesUpload() {
		return '{csv=text/x-comma-separated-values}{lha=application/octet-stream}{lzh=application/octet-stream}{class=application/octet-stream}{psd=application/x-photoshop}{oda=application/oda}{pdf=application/pdf}{ai=application/postscript}{eps=application/postscript}{ps=application/postscript}{xls=application/vnd.ms-excel}{ppt=application/powerpoint}{wbxml=application/wbxml}{wmlc=application/wmlc}{dvi=application/x-dvi}{gtar=application/x-gtar}{gz=application/x-gzip}{tar=application/x-tar}{tgz=application/x-tar}{xhtml=application/xhtml+xml}{xht=application/xhtml+xml}{zip=application/x-zip}{mid=audio/midi}{midi=audio/midi}{mpga=audio/mpeg}{mp2=audio/mpeg}{mp3=audio/mpeg}{aif=audio/x-aiff}{aiff=audio/x-aiff}{aifc=audio/x-aiff}{ram=audio/x-pn-realaudio}{rm=audio/x-pn-realaudio}{rpm=audio/x-pn-realaudio-plugin}{ra=audio/x-realaudio}{rv=video/vnd.rn-realvideo}{wav=audio/x-wav}{bmp=image/bmp}{gif=image/gif}{jpeg=image/jpeg}{jpg=image/jpeg}{jpe=image/jpeg}{png=image/png}{tiff=image/tiff}{tif=image/tiff}{css=text/css}{html=text/html}{htm=text/html}{shtml=text/html}{txt=text/plain}{text=text/plain}{log=text/plain}{rtx=text/richtext}{rtf=text/rtf}{xml=text/xml}{xsl=text/xml}{mpeg=video/mpeg}{mpg=video/mpeg}{mpe=video/mpeg}{qt=video/quicktime}{mov=video/quicktime}{avi=video/x-msvideo}{flv=video/x-flv}{movie=video/x-sgi-movie}{doc=application/msword}{xl=application/excel}{eml=message/rfc822}{pptx=application/vnd.openxmlformats-officedocument.presentationml.presentation}{xlsx=application/vnd.openxmlformats-officedocument.spreadsheetml.sheet}{docx=application/vnd.openxmlformats-officedocument.wordprocessingml.document}{rar=application/x-rar-compressed}{odb=application/vnd.oasis.opendocument.database}{odc=application/vnd.oasis.opendocument.chart}{odf=application/vnd.oasis.opendocument.formula}{odg=application/vnd.oasis.opendocument.graphics}{odi=application/vnd.oasis.opendocument.image}{odm=application/vnd.oasis.opendocument.text-master}{odp=application/vnd.oasis.opendocument.presentation}{ods=application/vnd.oasis.opendocument.spreadsheet}{odt=application/vnd.oasis.opendocument.text}{sxc=application/vnd.sun.xml.calc}{sxd=application/vnd.sun.xml.draw}{sxg=application/vnd.sun.xml.writer.global}{sxi=application/vnd.sun.xml.impress}{sxm=application/vnd.sun.xml.math}{sxw=application/vnd.sun.xml.writer}{ogv=video/ogg}{ogg=audio/ogg}';
	}
	
	public static function getHTMLTagsUpload() {
		return array('abbr','acronym','address','applet','area','audioscope','base','basefont','bdo','bgsound','big','blackface','blink','blockquote','body','bq','br','button','caption','center','cite','code','col','colgroup','comment','custom','dd','del','dfn','dir','div','dl','dt','em','embed','fieldset','fn','font','form','frame','frameset','h1','h2','h3','h4','h5','h6','head','hr','html','iframe','ilayer','img','input','ins','isindex','keygen','kbd','label','layer','legend','li','limittext','link','listing','map','marquee','menu','meta','multicol','nobr','noembed','noframes','noscript','nosmartquotes','object','ol','optgroup','option','param','plaintext','pre','rt','ruby','s','samp','script','select','server','shadow','sidebar','small','spacer','span','strike','strong','style','sub','sup','table','tbody','td','textarea','tfoot','th','thead','title','tr','tt','ul','var','wbr','xml','xmp','!DOCTYPE', '!--');
	}
	
	public static function getMimeTypeString($params) {
		
		$regex_one		= '/({\s*)(.*?)(})/si';
		$regex_all		= '/{\s*.*?}/si';
		$matches 		= array();
		$count_matches	= preg_match_all($regex_all,$params,$matches,PREG_OFFSET_CAPTURE | PREG_PATTERN_ORDER);

		$extString 	= '';
		$mimeString	= '';
		
		for($i = 0; $i < $count_matches; $i++) {
			
			$phocaDownload	= $matches[0][$i][0];
			preg_match($regex_one,$phocaDownload,$phocaDownloadParts);
			$values_replace = array ("/^'/", "/'$/", "/^&#39;/", "/&#39;$/", "/<br \/>/");
			$values = explode("=", $phocaDownloadParts[2], 2);	
			
			foreach ($values_replace as $key2 => $values2) {
				$values = preg_replace($values2, '', $values);
			}
				
			// Create strings
			$extString .= $values[0];
			$mimeString .= $values[1];
			
			$j = $i + 1;
			if ($j < $count_matches) {
				$extString .=',';
				$mimeString .=',';
			}
		}
		
		$string 		= array();
		$string['mime']	= $mimeString;
		$string['ext']	= $extString;
		
		return $string;
	}
}
?>
