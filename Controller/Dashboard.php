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

class Dashboard extends Controller
{
	public function index($limit = false, $limitstart = 0, $view = false)
	{
	}

	public function createView($name, $layout = 'default', $component, $place, $vendor, $ext = 'php')
	{
		$ns   = $vendor . '\\' . $component . '\\' . $place;
		list($path) = Loader::extractPaths($ns . '\\View\\' . $name, '/');
		$path .= '/' . $layout . '.' . $ext;
		if (!is_file($path))
		{
			$vars        = array();
			$new_content = $this->render('preset.view', $vars);
			jimport('joomla.filesystem.file');
			\JFile::write($path, $new_content);
		}
	}

	public function generate($file, $class, $component, $place, $vendor, $functions)
	{

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
				), explode(',', str_replace(array(' ', ',,'), ',', $functions))
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
		if (is_file($path))
		{
			// diff and confirm
			$old_content = file_get_contents($path);
			$diff        = Diff::compare($old_content, $new_content);

			$vars['diff'] = $diff;
			echo $this->render('form', $vars);
		}
		else
		{
			jimport('joomla.filesystem.file');
			\JFile::write($path, $new_content);
		}
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