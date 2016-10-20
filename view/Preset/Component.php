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

defined('_JEXEC') or die;

class Component extends \Joomplace\Library\JooYii\Component
{
    protected function setNamespace()
    {
        $this->_namespace = __NAMESPACE__;
    }
}