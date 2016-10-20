<?php

echo "<?php \r\n";

?>
/**
* Created by JooGii.
* User: <?= JFactory::getUser()->name ?>;
* Date: <?= JHtml::_('date','now','d.m.Y') ?>;
* Time: <?= JHtml::_('date','now','H:i') ?>;
*/
namespace <?= ucfirst($vendor) ?>\<?= ucfirst($component) ?>\<?= ucfirst($place) ?>\Controller;

use Joomplace\Library\JooYii\Controller;

defined('_JEXEC') or die;

class <?= ucfirst($class) ?> extends Controller
{
<?php
foreach ($functions as $function){
    include 'function.php';
}
?>

}