<?php

echo "<?php \r\n";

?>
/**
* Created by JooGii.
* User: <?= JFactory::getUser()->name ?>;
* Date: <?= JHtml::_('date','now','d.m.Y') ?>;
* Time: <?= JHtml::_('date','now','H:i') ?>;
*/
namespace <?= ucfirst($vendor) ?>\<?= ucfirst($component) ?>\<?= ucfirst($place) ?>\Model;

use Joomplace\Library\JooYii\Model;

defined('_JEXEC') or die;

class <?= ucfirst($class) ?> extends Model
{
<?php
foreach ($functions as $function){
    include 'function.php';
}
?>

}