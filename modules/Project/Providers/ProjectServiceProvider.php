<?php namespace TB\Project\Providers;

use Illuminate\Support\ServiceProvider;

use TB\Project\Repositories\Eloquent\EloquentProjectRepository;
use TB\Project\Repositories\ProjectRepository;
use TB\Project\Entities\Project;

class ProjectServiceProvider extends ServiceProvider {

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
		$this->registerTranslations();
		$this->registerConfig();
		$this->registerViews();
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bind(ProjectRepository::class, function ($app) {
    		return new EloquentProjectRepository(new Project());
        });
	}

	/**
	 * Register config.
	 *
	 * @return void
	 */
	protected function registerConfig()
	{
		// $this->publishes([
		//     __DIR__.'/../Config/config.php' => config_path('project.php'),
		// ]);
		// $this->mergeConfigFrom(
		//     __DIR__.'/../Config/config.php', 'project'
		// );
	}

	/**
	 * Register views.
	 *
	 * @return void
	 */
	public function registerViews()
	{
		$viewPath = base_path('resources/views/modules/project');
		$sourcePath = __DIR__.'/../Resources/views';
		$this->loadViewsFrom($sourcePath, 'project');
	}

	/**
	 * Register translations.
	 *
	 * @return void
	 */
	public function registerTranslations()
	{
		$langPath = base_path('resources/lang/modules/project');

		if (is_dir($langPath)) {
			$this->loadTranslationsFrom($langPath, 'project');
		} else {
			$this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'project');
		}
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
