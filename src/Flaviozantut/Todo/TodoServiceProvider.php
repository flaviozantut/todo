<?php namespace Flaviozantut\Todo;

use Illuminate\Support\ServiceProvider;

class TodoServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('flaviozantut/todo');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->registerTodo();
		$this->registerCommands();
	}

	/**
	 * Register todo
	 *
	 * @return void
	 */
	public function registerTodo()
	{
		$this->app->singleton('todo', function($app)
		{
			//return new Todo('Markdown', array(__DIR__. '/fixtures/todo.md')
			return new Todo('Markdown', array(app_path(). '/../todo.md'));

		});
	}

	/**
	 * Register the artisan commands.
	 *
	 * @return void
	 */
	private function registerCommands()
	{
		$this->app['command.todo.add'] = $this->app->share(function($app)
		{
			return new TodoAddCommand($app);
		});
		$this->app['command.todo.ls'] = $this->app->share(function($app)
		{
			return new TodoLsCommand($app);
		});
		$this->commands(
			'command.todo.add',
			'command.todo.ls'
		);
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