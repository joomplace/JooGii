<?php
/**
 * Created by PhpStorm.
 * User: Alexandr
 * Date: 17.10.2016
 * Time: 18:08
 */
$chooser = array(
	'name'=>'file',
	'required'=>'true',
	'class'=>'btn-group',
	'options'=> array(
		(object)array(
			'value'=>'Controller',
			'text'=>'Controller',
			'onclick'=>'updateForm()',
		),
		(object)array(
			'value'=>'Model',
			'text'=>'Model',
			'onclick'=>'updateForm()',
			),
		(object)array(
			'value'=>'View',
			'text'=>'View',
			'onclick'=>'updateForm()',
			),
	),
	'value'=>($file)?$file:'Controller',
	'onclick'=>'updateForm()',
	'id'=>'file',
);
$part = array(
	'name'=>'place',
	'required'=>'true',
	'class'=>'btn-group',
	'options'=> array(
		(object)array(
			'value'=>'Site',
			'text'=>'Site',
		),
		(object)array(
			'value'=>'Admin',
			'text'=>'Admin',
			),
	),
	'value'=>($place)?$place:'Site',
	'id'=>'place',
);
JFactory::getDocument()->addScriptDeclaration("
	function updateForm(){
		var logic_part = jQuery('#file > input:checked').val();
		if(logic_part=='Controller' || logic_part=='Model'){
			jQuery('#functions_block').show();
		}
		if(logic_part=='View'){
			jQuery('#functions_block').hide();
		}
	}
	jQuery(document).ready(function($){
		updateForm();
	});
");
?>
<div>
	<form method="POST" action="<?= JRoute::_('index.php?option=com_joogii&task=generate')?>">
		<div class="form-group">
			<label>
				Generate:
			</label>
			<?php echo JLayoutHelper::render('joomla.form.field.radio', $chooser); ?>
		</div>
		<div class="form-group" id="vendor_block">
			<label>
				Vendor:
			</label>
			<input value="<?= $vendor ?>" class="form-control" name="vendor" type="text" placeholder="Joomplace" />
		</div>
		<div class="form-group">
			<label>
				Component:
			</label>
			<div class="input-prepend">
				<span class="add-on">com_</span>
				<input value="<?= $component ?>" class="form-control" name="component" type="text" placeholder="joogii" />
			</div>
		</div>
		<div class="form-group">
			<label>
				Part:
			</label>
			<?php echo JLayoutHelper::render('joomla.form.field.radio', $part); ?>
		</div>
		<div class="form-group">
			<label>
				Name:
			</label>
			<input value="<?= $class ?>" class="form-control" name="class" type="text" placeholder="Dashboard" />
		</div>
		<div class="form-group" id="functions_block">
			<label>
				Implement methods:
			</label>
			<input value="<?= $functions ?>" class="form-control" name="functions" type="text" placeholder="index, add, save" />
		</div>

		<?php if($diff){ ?>
			<div class="accordion" id="accordion2">
				<div class="accordion-group">
					<div class="accordion-heading">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">
							File will be changed, please verify diff
						</a>
					</div>
					<div id="collapseOne" class="accordion-body collapse">
						<div class="accordion-inner">
							<div class="differ">
								<?php
								foreach ($diff as $k => $line){
									if(count($line)==1){
										$string = '&nbsp;';
										$action = $line[0];
									}else{
										$string = $line[0];
										$action = $line[1];
									}
									?>
									<div class="<?= ($action==1)?'deleted':(($action==2)?'added':'not_changed'); ?>">
										<?= str_replace(array("\t",'    '),'&nbsp;&nbsp;&nbsp;&nbsp;',htmlspecialchars($string)) ?>
									</div>
									<?php
								}
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<input id="force" value="0" name="force" type="hidden"/>
		<?php } ?>
		<div class="form-group">
			<button class="btn btn-default" type="submit"><?= ($diff)?'Resubmit':'Submit' ?></button>
			<?php if($diff){
				?>
			<button class="btn btn-warning" onclick="jQuery('input#force').val(1)" type="submit">Proceed with changes</button>
				<?php
				}   ?>
		</div>
	</form>
</div>
<style>
	.form-group{
		margin-bottom: 10px;
	}
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