<?php
echo "<?php ";
?>

namespace <?= ucfirst($vendor) ?>\<?= ucfirst($component) ?>\<?= ucfirst($place) ?>;

defined('_JEXEC') or die;

jimport('JooYii.autoloader',JPATH_LIBRARIES);

$component = new Component();
$component->execute();