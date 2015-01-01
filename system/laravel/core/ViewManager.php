<?php namespace App\View;

use Illuminate\View\FileViewFinder;
use Illuminate\Filesystem\Filesystem as Filesystem;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container as Container;
use Illuminate\View\Factory;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\View as View;

class ViewManager
{
	public static function loadBlade($view, $viewPath = false, $data = array() ) {

		$viewPath = DIR_SYSTEM.'laravel/views/'.$viewPath;

		$FileViewFinder = new FileViewFinder(
			new Filesystem,
			array($viewPath)
		);

		$compiler = new BladeCompiler(new Filesystem(), DIR_SYSTEM.'laravel/storage/views');
		$BladeEngine = new CompilerEngine($compiler);

		$dispatcher = new Dispatcher(new Container);

		$factory = new Factory(
			new EngineResolver,
			$FileViewFinder,
			$dispatcher
		);

		$viewObj = new View(
			$factory,
			$BladeEngine,
			$view,
			$viewPath,
			$data
		);

		return $viewObj;
	}
}