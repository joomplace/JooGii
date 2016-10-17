<?php
namespace Joomplace\JooGii\Site;

defined('_JEXEC') or die;
if(!defined('DS')){
	define('DS',DIRECTORY_SEPARATOR);
}

jimport('jooyii.autoloader',JPATH_LIBRARIES.DS);

$component = new Component();
$component->execute();