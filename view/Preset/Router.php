<?php

echo "<?php \r\n";

?>
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