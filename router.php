<?php
/**
 * Created by PhpStorm.
 * User: Alexandr
 * Date: 17.10.2016
 * Time: 15:14
 */

namespace Joomplace\JooGii\Site;

defined('_JEXEC') or die;
if(!defined('DS')){
	define('DS',DIRECTORY_SEPARATOR);
}

jimport('jooyii.autoloader',JPATH_LIBRARIES.DS);

class Router extends \Joomplace\Library\JooYii\Router
{
	protected function setNamespace()
	{
		$this->_namespace = __NAMESPACE__;
	}

}