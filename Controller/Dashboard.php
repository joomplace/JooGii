<?php
/**
 * Created by PhpStorm.
 * User: Alexandr
 * Date: 16.10.2016
 * Time: 21:55
 */

namespace Joomplace\JooGii\Site\Controller;


use Joomplace\Library\JooYii\Controller;
use Joomplace\Library\JooYii\Helper;
use Joomplace\Library\JooYii\Loader;
use ReflectionMethod;
use Stephenmorley\JooGii\Site\Library\Diff;

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

class Dashboard extends Controller
{
	public function createView($class, $layout = 'default', $component, $place, $vendor, $ext = 'php')
	{
		$ns   = $vendor . '\\' . $component . '\\' . $place;
		list($path) = Loader::extractPaths($ns . '\\View\\' . $class, '/');
		$path .= '/' . $layout . '.' . $ext;
		if (!is_file($path))
		{
			$vars        = array();
			$new_content = $this->render('preset.view', $vars);
			jimport('joomla.filesystem.file');
			\JFile::write($path, $new_content);
		}
		$this->success();
	}

	public function regenerateXML($vendor, $component, $place){
		$vars = array(
			'component' => $component,
			'place'     => $place,
			'vendor'    => $vendor,
		);
		list($path) = Loader::extractPaths($vendor . '\\' . ucfirst($component) . '\\Site\\','/');
		list($apath) = Loader::extractPaths($vendor . '\\' . ucfirst($component) . '\\Admin\\','/');
		if(is_dir($path)){
			$vars['folders'] = array(\JFolder::folders($path));
			$vars['folders'] = $vars['folders'][0];
			$vars['files'] = array(\JFolder::files($path));
			$vars['files'] = array_filter($vars['files'][0],function($item)use($component){
				if($item==lcfirst($component).'.xml'){
					return false;
				}else{
					return $item;
				}
			});
		}else{
			$vars['folders'] = $vars['files'] = array();
		}
		if(is_dir($apath)){
			$vars['admin_folders'] = array(\JFolder::folders($apath));
			$vars['admin_folders'] = $vars['admin_folders'][0];
			$vars['admin_files'] = array(\JFolder::files($apath));
			$vars['admin_files'] = array_filter($vars['admin_files'][0],function($item)use($component){
				if($item==lcfirst($component).'.xml'){
					return false;
				}else{
					return $item;
				}
			});
		}else{
			$vars['admin_folders'] = $vars['admin_files'] = array();
		}
		$new_content = $this->render('preset.manifest', $vars);

		list($root_file) = Loader::extractPaths($vendor . '\\' . $component . '\\' . $place . '\\'.lcfirst($component));
		$root_xml = str_replace('.php','.xml',$root_file);
		return \JFile::write($root_xml, $new_content);
	}

	public function createComponent($vendor, $component, $place, $router = true){
		$newClass = $vendor . '\\' . $component . '\\' . $place . '\\Component';
		if(!class_exists($newClass)){
			$vars = array(
				'component' => $component,
				'place'     => $place,
				'vendor'    => $vendor,
			);
			$classes = array(
				'Component',
			);
			if($router){
				$classes[] = 'Router';
			}

			jimport('joomla.filesystem.folder');
			jimport('joomla.filesystem.file');
			$results = array_map(function($class)use($vars,$newClass){
				$vars['class'] = $class;
				$new_content = $this->render('preset.' . $class, $vars);

				list($path) = Loader::extractPaths($newClass);
				return \JFile::write($path, $new_content);
			},$classes);

			list($root_file) = Loader::extractPaths($vendor . '\\' . $component . '\\' . $place . '\\'.lcfirst($component));
			$new_content = $this->render('preset.manifest', $vars);
			$results[] = \JFile::write($root_file, $new_content);

			if(in_array(false,$results)){
				// issues
			}else{
				$this->success();
			}
		}
	}

	public function generate($file, $class, $layout = 'default', $component, $place, $vendor, $functions, $force = 0)
	{
		if(!class_exists($vendor . '\\' . $component . '\\' . $place . '\\Component')){
			$this->createComponent($vendor, $component, $place);
		}

		if($file=='View'){
			$this->createView($class,$layout,$component,$place,$vendor,'php');
		}else{
			$functions_data = $functions;
			$parentClass = 'Joomplace\\Library\\JooYii\\' . ucfirst($file);
			if (in_array($file, array('component', 'router')))
			{
				$folder = '';
			}
			else
			{
				$folder = '\\' . $file;
			}
			$newClass = $vendor . '\\' . $component . '\\' . $place . $folder . '\\' . ucfirst($class);

			$functions = explode(',', str_replace(array(' ', ',,'), ',', $functions));
			if (class_exists($newClass))
			{
				$functions = array_merge(
					array_filter(
						get_class_methods($newClass),
						function ($func) use ($newClass)
						{
							if (Helper::methodExists($newClass, $func, true))
							{
								return $func;
							}
							else
							{
								return false;
							}
						}
					), $functions
				);
				$functions = array_unique($functions);
			}

			$methods = array();
			foreach ($functions as $func)
			{
				$item = array(0 => $func, 1 => '');
				if (class_exists($parentClass))
				{
					if (Helper::methodExists($newClass, $func) || Helper::methodExists($parentClass, $func))
					{
						$names    = array();
						$inTarget = Helper::methodExists($newClass, $func, true);
						if ($inTarget)
						{
							$args = Helper::getMethodArgs($newClass, $func);
						}
						else
						{
							$args = Helper::getMethodArgs($parentClass, $func);
						}
						foreach ($args as $arg)
						{
							$names[] = '$' . $arg->name;
						}
						if ($inTarget)
						{
							preg_match_all('/(.*?)function.*?\((.*?)\)/', $this->getMethodDeclarationLine($newClass, $func), $matches);
							$item[1] = trim(trim($this->getMethodBody($newClass, $func)), '{}');
						}
						else
						{
							preg_match_all('/(.*?)function.*?\((.*?)\)/', $this->getMethodDeclarationLine($parentClass, $func), $matches);
							$item[1] = "return parent::$func(" . implode(', ', $names) . ");";
						}
						$item[2] = isset($matches[2][0]) ? $matches[2][0] : '';
						$item[3] = isset($matches[1][0]) ? trim($matches[1][0]) : '';
						if (!$item[3])
						{
							$item[3] = 'public';
						}
					}
					else
					{
						$item[2] = $item[1] = '';
						$item[3] = 'public';
					}
				}
				$methods[] = $item;
			}

			$vars = array(
				'class'     => $class,
				'component' => $component,
				'place'     => $place,
				'vendor'    => $vendor,
				'functions' => $methods,
			);

			$new_content = $this->render('preset.' . $file, $vars);

			list($path) = Loader::extractPaths($newClass);
			if (is_file($path) && !$force)
			{
				// diff and confirm
				$old_content = file_get_contents($path);
				$diff        = Diff::compare($old_content, $new_content);

				$vars['diff'] = $diff;
				$vars['functions'] = $functions_data;
				echo $this->render('form', $vars);
			}
			else
			{
				jimport('joomla.filesystem.file');
				\JFile::write($path, $new_content);
				$this->success();
			}
		}
		$this->regenerateXML($vendor,$component,$place);
	}

	protected function success(){
		echo 'Success';
	}

	/**
	 * Method to get Class::method source code
	 *
	 * @param $class
	 * @param $method
	 *
	 * @return string
	 */
	public function getMethodDeclarationLine($class, $method)
	{
		if (Helper::methodExists($class, $method))
		{
			$ref        = new ReflectionMethod($class, $method);
			$start_line = $ref->getStartLine() - 1;
			$source     = file($ref->getFileName());
			$body       = implode("", array_slice($source, $start_line, 1));
		}
		else
		{
			$body = '';
		}

		return $body;
	}

	/**
	 * Method to get Class::method source code
	 *
	 * @param $class
	 * @param $method
	 *
	 * @return string
	 */
	public function getMethodBody($class, $method)
	{
		$ref = new ReflectionMethod($class, $method);

		$start_line = $ref->getStartLine();
		$end_line   = $ref->getEndLine();
		$length     = $end_line - 1 - $start_line;

		$source = file($ref->getFileName());
		$body   = implode("", array_slice($source, $start_line, $length));

		return $body;
	}
}