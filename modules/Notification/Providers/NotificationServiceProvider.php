<?php namespace TB\Notification\Providers;

use Illuminate\Support\ServiceProvider;
use TB\Notification\Repositories\Eloquent\EloquentNotificationRepository;
use TB\Notification\Repositories\NotificationRepository;
use TB\Notification\Entities\Notification;

class NotificationServiceProvider extends ServiceProvider {

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
		$this->app->bind(NotificationRepository::class, function ($app) {
    		return new EloquentNotificationRepository(new Notification());
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
		//     __DIR__.'/../Config/config.php' => config_path('notification.php'),
		// ]);
		// $this->mergeConfigFrom(
		//     __DIR__.'/../Config/config.php', 'notification'
		// );
	}

	/**
	 * Register views.
	 *
	 * @return void
	 */
	public function registerViews()
	{
		$viewPath = base_path('resources/views/modules/notification');
		$sourcePath = __DIR__.'/../Resources/views';
		$this->loadViewsFrom($sourcePath, 'notification');
	}

	/**
	 * Register translations.
	 *
	 * @return void
	 */
	public function registerTranslations()
	{
		$langPath = base_path('resources/lang/modules/notification');

		if (is_dir($langPath)) {
			$this->loadTranslationsFrom($langPath, 'notification');
		} else {
			$this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'notification');
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
