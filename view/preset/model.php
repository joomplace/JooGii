<?php

echo "<?php \r\n";

?>
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