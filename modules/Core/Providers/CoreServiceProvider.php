<?php namespace TB\Core\Providers;

use Illuminate\Support\ServiceProvider;

class CoreServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Boot the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		// $this->registerTranslations();
		$this->registerConfig();
		$this->registerViews();

		// print \Config::get('app.timezone');
		// \Config::set('app.timezone', )

		// require __DIR__.'/../Http/form_helper.php';
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->register(\Laracasts\Flash\FlashServiceProvider::class);
		$this->app->register(\Collective\Html\HtmlServiceProvider::class);
		$this->app->register(\Zizaco\Entrust\EntrustServiceProvider::class);
		$this->app->register(\Snowfire\Beautymail\BeautymailServiceProvider::class);
		$this->app->register(\Intervention\Image\ImageServiceProvider::class);
		$this->app->register(\Laracasts\Utilities\JavaScript\JavaScriptServiceProvider::class);
		// $this->app->register(\Maatwebsite\Excel\ExcelServiceProvider::class);

		$this->registerAlias();
	}

	public function registerAlias()
	{
		$loader = \Illuminate\Foundation\AliasLoader::getInstance();
      	$loader->alias('Form', \Collective\Html\FormFacade::class);
      	$loader->alias('Html', \Collective\Html\HtmlFacade::class);
      	$loader->alias('Flash', \Laracasts\Flash\Flash::class);
      	$loader->alias('Entrust', \Zizaco\Entrust\EntrustFacade::class);
      	$loader->alias('Image', \Intervention\Image\Facades\Image::class);
      	// $loader->alias('Excel', \Maatwebsite\Excel\Facades\Excel::class);
	}

	/**
	 * Register config.
	 *
	 * @return void
	 */
	protected function registerConfig()
	{
		// $this->publishes([
		//     __DIR__.'/../Config/config.php' => config_path('core.php'),
		// ]);
		// $this->mergeConfigFrom(
		//     __DIR__.'/../Config/config.php', 'core'
		// );
	}

	/**
	 * Register views.
	 *
	 * @return void
	 */
	public function registerViews()
	{
		$viewPath = base_path('resources/views/modules/core');

		$sourcePath = __DIR__.'/../Resources/views';

		$this->publishes([
			$sourcePath => $viewPath
		]);

		$this->loadViewsFrom(array_merge(array_map(function ($path) {
			return $path . '/modules/core';
		}, \Config::get('view.paths')), [$sourcePath]), 'core');
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}
