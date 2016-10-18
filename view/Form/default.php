<?php
/**
 * Created by PhpStorm.
 * User: Alexandr
 * Date: 17.10.2016
 * Time: 18:08
 */
?>

<style>
	.differ{
		background: #eeeeee;
		border: 2px solid #dddddd;
	}
	.differ > div{
		min-height: 1em;
		border-bottom: #eee 1px solid;
		padding: 2px;
	}
	.differ > div.added{
		background: #7dbe7d;
	}
	.differ > div.deleted{
		background: #ff8282;
	}
</style>
<div class="differ">
<?php
foreach ($diff as $line){
	if(count($line)==1){
		$string = '&nbsp;';
		$action = $line[0];
	}else{
		$string = $line[0];
		$action = $line[1];
	}
	?>
		<div class="<?= ($action==1)?'deleted':(($action==2)?'added':''); ?>">
			<?= str_replace(array("\t",'    '),'&nbsp;&nbsp;&nbsp;&nbsp;',htmlspecialchars($string)) ?>
		</div>
	<?php
}
?>
</div>
