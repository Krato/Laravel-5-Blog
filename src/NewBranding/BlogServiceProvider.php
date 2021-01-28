<?php namespace NewBranding\Blog;

use Illuminate\Support\ServiceProvider;

class BlogServiceProvider extends ServiceProvider {


	protected $defer = false;

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
        include __DIR__.'/../routes.php';

		$this->publishes([
            __DIR__.'/../config/config.php' => config_path('blog.php'),
        ]);

        $this->publishes([
            __DIR__.'/../public' => public_path('blog_assets'),
        ], 'public');

        $this->loadViewsFrom(__DIR__.'/../views', 'blog');

        $this->loadTranslationsFrom(__DIR__.'/../lang', 'blog');

        $this->commands('command.newbranding.blog');
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->registerNewBranding();

		$this->registerCommands();

		$this->mergeConfig();
	}

	private function registerNewBranding()
    {
        $this->app->bind('blog', function ($app) {
            return new Blog($app);
        });
	}

	private function registerCommands()
    {
        $this->app->singleton('command.newbranding.blog', function ($app) {
            return new BlogMigrate();
        });
	}

	 /**
     * Merges user's and Blog configs.
     *
     * @return void
     */
    private function mergeConfig()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/config.php', 'blog'
        );
    }

    /**
     * Get the services provided.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'command.newbranding.blog'
        ];
    }

}
