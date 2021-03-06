<?php

echo "<?php \r\n";

?>
/**
* Created by JooGii.
* User: <?= JFactory::getUser()->name ?>;
* Date: <?= JHtml::_('date','now','d.m.Y') ?>;
* Time: <?= JHtml::_('date','now','H:i') ?>;
*/
namespace <?= ucfirst($vendor) ?>\<?= ucfirst($component) ?>\<?= ucfirst($place) ?>;

use Joomplace\Library\JooYii\Router as BaseRouter;

defined('_JEXEC') or die;

jimport('JooYii.autoloader',JPATH_LIBRARIES);

class Router extends BaseRouter
{

    protected function setNamespace()
    {
        $this->_namespace = __NAMESPACE__;
    }

}