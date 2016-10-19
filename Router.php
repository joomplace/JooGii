<?php
/**
 * Created by PhpStorm.
 * User: Alexandr
 * Date: 17.10.2016
 * Time: 15:14
 */

namespace Joomplace\Joogii\Site;

use \Joomplace\Library\JooYii\Router as BaseRouter;

defined('_JEXEC') or die;

jimport('JooYii.autoloader',JPATH_LIBRARIES);

class Router extends BaseRouter
{
	protected function setNamespace()
	{
		$this->_namespace = __NAMESPACE__;
	}

}