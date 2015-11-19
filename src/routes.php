<?php


/**
 * Ajax Routes
 */
Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localize', 'localeSessionRedirect', 'localizationRedirect' ]
    ],
    function()
    {
    	Route::get(LaravelLocalization::transRoute('routes.admin/blog').'/data_blog', 'starter\Blog\Controllers\PostController@data_blog');
});

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localize', 'localeSessionRedirect', 'localizationRedirect' ]
    ],
    function()
    {
        //HOTELS


    	Route::get(LaravelLocalization::transRoute('routes.admin/blog'), 'starter\Blog\Controllers\PostController@index');
        Route::get(LaravelLocalization::transRoute('routes.admin/blog/trash'), 'starter\Blog\Controllers\PostController@index(true)');
		Route::get(LaravelLocalization::transRoute('routes.admin/blog/new'), 'starter\Blog\Controllers\PostController@create');
		Route::post(LaravelLocalization::transRoute('routes.admin/blog/store'), 'starter\Blog\Controllers\PostController@store');
        Route::get(LaravelLocalization::transRoute('routes.admin/blog/edit'), 'starter\Blog\Controllers\PostController@edit');
        Route::post(LaravelLocalization::transRoute('routes.admin/blog/update'), 'starter\Blog\Controllers\PostController@update');
        Route::post(LaravelLocalization::transRoute('routes.admin/blog/remove'), 'starter\Blog\Controllers\PostController@destroy');
        Route::post(LaravelLocalization::transRoute('routes.admin/blog/remove_force'), 'starter\Blog\Controllers\PostController@forceDestroy');
        Route::post(LaravelLocalization::transRoute('routes.admin/blog/restore'), 'starter\Blog\Controllers\PostController@restore');
		
		Route::post(LaravelLocalization::transRoute('routes.admin/blog/categories/store'), 'starter\Blog\Controllers\CategoriesController@store');
        Route::get(LaravelLocalization::transRoute('routes.admin/blog/categories/edit'), 'starter\Blog\Controllers\CategoriesController@edit');
        Route::post(LaravelLocalization::transRoute('routes.admin/blog/categories/update'), 'starter\Blog\Controllers\CategoriesController@update');
        Route::post(LaravelLocalization::transRoute('routes.admin/blog/categories/remove'), 'starter\Blog\Controllers\CategoriesController@destroy');

        //IMAGE TEXT EDITOR
        Route::post('admin/blog/upload_editor_image', 'starter\Blog\Controllers\PostController@upload_image_editor');
        Route::post('admin/blog/remove_editor_image', 'starter\Blog\Controllers\PostController@remove_editor_images');
        
        Route::get(LaravelLocalization::transRoute('routes.public_blog'), 'starter\Blog\Controllers\PostController@index_front');
        Route::get(LaravelLocalization::transRoute('routes.public_blog'), 'starter\Blog\Controllers\PostController@index_front');

        Route::get('blog/{slug}', 'starter\Blog\Controllers\PostController@show')->where('slug', '[a-z0-9-]+');
        Route::get('blog/categoria/{slug}', 'starter\Blog\Controllers\PostController@showCategory')->where('slug', '[a-z0-9-]+');
        Route::get('blog/tag/{slug}', 'starter\Blog\Controllers\PostController@showTag')->where('slug', '[a-z0-9-]+');

});