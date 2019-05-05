<?php

namespace App\Providers;

use App\Models\AdminMessage;
use App\Models\AppMessage;
use App\Models\AppVersion;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\Attachment;
use App\Models\Carousel;
use App\Models\Log;
use App\Models\Permission;
use App\Models\Role;
use App\Models\StatusMap;
use App\Models\SystemConfig;
use App\Models\Table;
use App\Models\Tag;
use App\Models\User;
use App\Models\UserAgreement;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Route::bind('permission', function ($value) {
            return Permission::findOrFail($value);
        });
        Route::bind('role', function ($value) {
            return Role::findOrFail($value);
        });
        Route::bind('user', function ($value) {
            return User::findOrFail($value);
        });
        Route::bind('table', function ($value) {
            return Table::findOrFail($value);
        });
        Route::bind('status_map', function ($value) {
            return StatusMap::findOrFail($value);
        });
        Route::bind('attachment', function ($value) {
            return Attachment::findOrFail($value);
        });
        Route::bind('system_config', function ($value) {
            return SystemConfig::findOrFail($value);
        });
        Route::bind('article_category', function ($value) {
            return ArticleCategory::findOrFail($value);
        });
        Route::bind('tag', function ($value) {
            return Tag::findOrFail($value);
        });
        Route::bind('article', function ($value) {
            return Article::findOrFail($value);
        });
        Route::bind('app_message', function ($value) {
            return AppMessage::findOrFail($value);
        });
        Route::bind('admin_message', function ($value) {
            return AdminMessage::findOrFail($value);
        });
        Route::bind('carousel', function ($value) {
            return Carousel::findOrFail($value);
        });
        Route::bind('log', function ($value) {
            return Log::findOrFail($value);
        });
        Route::bind('app_version', function ($value) {
            return AppVersion::findOrFail($value);
        });
        Route::bind('user_agreement', function ($value) {
            return UserAgreement::findOrFail($value);
        });
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }
}
