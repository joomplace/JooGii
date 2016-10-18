<?php

echo "<?php \r\n";

?>
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