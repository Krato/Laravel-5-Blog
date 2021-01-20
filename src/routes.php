<?php


/**
 * Ajax Routes
 */
Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localize', 'localeSessionRedirect', 'localizationRedirect' ]
    ],
    function () {
        Route::get(LaravelLocalization::transRoute('routes.admin/blog').'/data_blog', 'App\Blog\Controllers\PostController@data_blog');
    }
);

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localize', 'localeSessionRedirect', 'localizationRedirect' ]
    ],
    function () {
        //HOTELS


        Route::get(LaravelLocalization::transRoute('routes.admin/blog'), 'App\Blog\Controllers\PostController@index');
        Route::get(LaravelLocalization::transRoute('routes.admin/blog/trash'), 'App\Blog\Controllers\PostController@index(true)');
        Route::get(LaravelLocalization::transRoute('routes.admin/blog/new'), 'App\Blog\Controllers\PostController@create');
        Route::post(LaravelLocalization::transRoute('routes.admin/blog/store'), 'App\Blog\Controllers\PostController@store');
        Route::get(LaravelLocalization::transRoute('routes.admin/blog/edit'), 'App\Blog\Controllers\PostController@edit');
        Route::post(LaravelLocalization::transRoute('routes.admin/blog/update'), 'App\Blog\Controllers\PostController@update');
        Route::post(LaravelLocalization::transRoute('routes.admin/blog/remove'), 'App\Blog\Controllers\PostController@destroy');
        Route::post(LaravelLocalization::transRoute('routes.admin/blog/remove_force'), 'App\Blog\Controllers\PostController@forceDestroy');
        Route::post(LaravelLocalization::transRoute('routes.admin/blog/restore'), 'App\Blog\Controllers\PostController@restore');
        
        Route::post(LaravelLocalization::transRoute('routes.admin/blog/categories/store'), 'App\Blog\Controllers\CategoriesController@store');
        Route::get(LaravelLocalization::transRoute('routes.admin/blog/categories/edit'), 'App\Blog\Controllers\CategoriesController@edit');
        Route::post(LaravelLocalization::transRoute('routes.admin/blog/categories/update'), 'App\Blog\Controllers\CategoriesController@update');
        Route::post(LaravelLocalization::transRoute('routes.admin/blog/categories/remove'), 'App\Blog\Controllers\CategoriesController@destroy');

        //IMAGE TEXT EDITOR
        Route::post('admin/blog/upload_editor_image', 'App\Blog\Controllers\PostController@upload_image_editor');
        Route::post('admin/blog/remove_editor_image', 'App\Blog\Controllers\PostController@remove_editor_images');
        
        Route::get(LaravelLocalization::transRoute('routes.menu.public_blog'), 'App\Blog\Controllers\PostController@index_front');

        Route::get(LaravelLocalization::transRoute('routes.menu.public_blog_item'), 'App\Blog\Controllers\PostController@show')->where('slug', '[a-z0-9-]+');
        Route::get(LaravelLocalization::transRoute('routes.menu.public_blog_category'), 'App\Blog\Controllers\PostController@showCategory')->where('slug', '[a-z0-9-]+');
        Route::get(LaravelLocalization::transRoute('routes.menu.public_blog_tag'), 'App\Blog\Controllers\PostController@showTag')->where('slug', '[a-z0-9-]+');
    }
);
