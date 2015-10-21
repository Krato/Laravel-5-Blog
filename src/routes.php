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
    	Route::get(LaravelLocalization::transRoute('routes.admin')."/".LaravelLocalization::transRoute('routes.blog').'/data_blog', 'ChildrenFriendly\Blog\Controllers\PostController@data_blog');
});

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localize', 'localeSessionRedirect', 'localizationRedirect' ]
    ],
    function()
    {
        //HOTELS
    	Route::get(LaravelLocalization::transRoute('routes.admin/blog'), 'ChildrenFriendly\Blog\Controllers\PostController@index');
        Route::get(LaravelLocalization::transRoute('routes.admin/blog/trash'), 'ChildrenFriendly\Blog\Controllers\PostController@index(true)');
		Route::get(LaravelLocalization::transRoute('routes.admin/blog/new'), 'ChildrenFriendly\Blog\Controllers\PostController@create');
		Route::post(LaravelLocalization::transRoute('routes.admin/blog/store'), 'ChildrenFriendly\Blog\Controllers\PostController@store');
        Route::get(LaravelLocalization::transRoute('routes.admin/blog/edit'), 'ChildrenFriendly\Blog\Controllers\PostController@edit');
        Route::post(LaravelLocalization::transRoute('routes.admin/blog/update'), 'ChildrenFriendly\Blog\Controllers\PostController@update');
        Route::post(LaravelLocalization::transRoute('routes.admin/blog/remove'), 'ChildrenFriendly\Blog\Controllers\PostController@destroy');
        Route::post(LaravelLocalization::transRoute('routes.admin/blog/remove_force'), 'ChildrenFriendly\Blog\Controllers\PostController@forceDestroy');
        Route::post(LaravelLocalization::transRoute('routes.admin/blog/restore'), 'ChildrenFriendly\Blog\Controllers\PostController@restore');
		
		Route::post(LaravelLocalization::transRoute('routes.admin/blog/categories/store'), 'ChildrenFriendly\Blog\Controllers\CategoriesController@store');
        Route::get(LaravelLocalization::transRoute('routes.admin/blog/categories/edit'), 'ChildrenFriendly\Blog\Controllers\CategoriesController@edit');
        Route::post(LaravelLocalization::transRoute('routes.admin/blog/categories/update'), 'ChildrenFriendly\Blog\Controllers\CategoriesController@update');
        Route::post(LaravelLocalization::transRoute('routes.admin/blog/categories/remove'), 'ChildrenFriendly\Blog\Controllers\CategoriesController@destroy');

        //IMAGE TEXT EDITOR
        Route::post(LaravelLocalization::transRoute('routes.admin/blog')."/upload_editor_image", 'ChildrenFriendly\Blog\Controllers\PostController@upload_image_editor');
        Route::post(LaravelLocalization::transRoute('routes.admin/blog')."/remove_editor_image", 'ChildrenFriendly\Blog\Controllers\PostController@remove_editor_images');
        
        Route::get(LaravelLocalization::transRoute('routes.public_blog'), 'ChildrenFriendly\Blog\Controllers\PostController@index_front');
        Route::get(LaravelLocalization::transRoute('routes.public_blog'), 'ChildrenFriendly\Blog\Controllers\PostController@index_front');
        Route::get('blog/{slug}', 'ChildrenFriendly\Blog\Controllers\PostController@show')->where('slug', '[a-z0-9-]+');
        Route::get('blog/categoria/{slug}', 'ChildrenFriendly\Blog\Controllers\PostController@showCategory')->where('slug', '[a-z0-9-]+');
        Route::get('blog/tag/{slug}', 'ChildrenFriendly\Blog\Controllers\PostController@showTag')->where('slug', '[a-z0-9-]+');

});