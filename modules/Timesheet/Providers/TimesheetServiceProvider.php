<?php namespace TB\Timesheet\Providers;

use Illuminate\Support\ServiceProvider;

use TB\Timesheet\Repositories\Eloquent\EloquentTimesheetRepository;
use TB\Timesheet\Repositories\TimesheetRepository;
use TB\Timesheet\Entities\Timesheet;

class TimesheetServiceProvider extends ServiceProvider 
{

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	protected $commands = [
		'ReportCommand',
		'WatcherCommand'
	];

	protected $namespace = '\TB\Timesheet\\';

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

		require __DIR__.'/../Http/time_display.php';
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bind(TimesheetRepository::class, function ($app) {
    		return new EloquentTimesheetRepository(new Timesheet());
        });

		// Register console commands
		foreach ($this->commands as $key => $name) {
			$this->commands($this->namespace . 'Console\\' . $name);
		}
	}

	/**
	 * Register config.
	 *
	 * @return void
	 */
	protected function registerConfig()
	{
		$this->publishes([
		    __DIR__.'/../Config/config.php' => config_path('timesheet.php'),
		]);
		$this->mergeConfigFrom(
		    __DIR__.'/../Config/config.php', 'timesheet'
		);
	}

	/**
	 * Register views.
	 *
	 * @return void
	 */
	public function registerViews()
	{
		$viewPath = base_path('resources/views/modules/timesheet');
		$sourcePath = __DIR__.'/../Resources/views';
		$this->loadViewsFrom($sourcePath, 'timesheet');
	}

	/**
	 * Register translations.
	 *
	 * @return void
	 */
	public function registerTranslations()
	{
		$langPath = base_path('resources/lang/modules/timesheet');

		if (is_dir($langPath)) {
			$this->loadTranslationsFrom($langPath, 'timesheet');
		} else {
			$this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'timesheet');
		}
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		$provides = [];

		// Register console commands
		foreach ($this->commands as $key => $name) {
			$provides[] = $this->namespace . 'Console\\' . $name;
		}
        
        return $provides;
	}

}
