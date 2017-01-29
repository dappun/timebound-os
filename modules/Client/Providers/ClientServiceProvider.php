<?php namespace TB\Client\Providers;

use Illuminate\Support\ServiceProvider;

use TB\Client\Repositories\Eloquent\EloquentClientRepository;
use TB\Client\Repositories\ClientRepository;
use TB\Client\Entities\Client;

class ClientServiceProvider extends ServiceProvider {

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
		$this->app->bind(ClientRepository::class, function ($app) {
    		return new EloquentClientRepository(new Client());
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
		//     __DIR__.'/../Config/config.php' => config_path('client.php'),
		// ]);
		// $this->mergeConfigFrom(
		//     __DIR__.'/../Config/config.php', 'client'
		// );
	}

	/**
	 * Register views.
	 *
	 * @return void
	 */
	public function registerViews()
	{
		$viewPath = base_path('resources/views/modules/client');
		$sourcePath = __DIR__.'/../Resources/views';
		$this->loadViewsFrom($sourcePath, 'client');
	}

	/**
	 * Register translations.
	 *
	 * @return void
	 */
	public function registerTranslations()
	{
		$langPath = base_path('resources/lang/modules/client');

		if (is_dir($langPath)) {
			$this->loadTranslationsFrom($langPath, 'client');
		} else {
			$this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'client');
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
