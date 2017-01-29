<?php namespace TB\User\Providers;

use Illuminate\Support\ServiceProvider;
use TB\User\Repositories\Eloquent\EloquentUserRepository;
use TB\User\Repositories\UserRepository;
use TB\User\Entities\User;

use TB\User\Repositories\Eloquent\EloquentRoleRepository;
use TB\User\Repositories\RoleRepository;
use TB\User\Entities\Role;

class UserServiceProvider extends ServiceProvider {

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
		$this->registerConfig();
		$this->registerViews();

		$router = $this->app['router'];

		// $router->before(BeforeGlobalFilter::class);
		// $router->after(AfterGlobalFilter::class);

		//also you can register your route level middlewares using the router
		$router->middleware('timezone', \TB\User\Http\Middleware\Timezone::class);
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bind(UserRepository::class, function ($app) {
    		return new EloquentUserRepository(new User());
        });

        $this->app->bind(RoleRepository::class, function ($app) {
    		return new EloquentRoleRepository(new Role());
        });
	}

	/**
	 * Register config.
	 *
	 * @return void
	 */
	protected function registerConfig()
	{
		$this->publishes([
		    __DIR__.'/../Config/config.php' => config_path('user.php'),
		]);
		$this->mergeConfigFrom(
		    __DIR__.'/../Config/config.php', 'user'
		);
	}

	/**
	 * Register views.
	 *
	 * @return void
	 */
	public function registerViews()
	{
		$viewPath = base_path('resources/views/modules/user');

		$sourcePath = __DIR__.'/../Resources/views';

		$this->loadViewsFrom($sourcePath, 'user');

		// $this->publishes([
		// 	$sourcePath => $viewPath
		// ]);

		// $this->loadViewsFrom(array_merge(array_map(function ($path) {
		// 	return $path . '/modules/user';
		// }, \Config::get('view.paths')), [$sourcePath]), 'user');
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
