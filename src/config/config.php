<?php

/**
 * This file is part of NewBranding Blog for Laravel 5.
 *
 * @license MIT
 * @package NewBranding\Blog
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Blog Folder
    |--------------------------------------------------------------------------
    |
    | Path of your Blog Resources Folder. Inside this folder you will need to create the Folders:
    |       - Controllers
    |       - Models
    |       - Requests
    |
    */
    'blog_path'   => 'Blog',


    /*
    |--------------------------------------------------------------------------
    | Locale Model Route
    |--------------------------------------------------------------------------
    |
    | Set the Locale Model for translations
    |
    */
    'locale_model' => 'app\Locales\Locale',


    /*
    |--------------------------------------------------------------------------
    | Blog Post Table
    |--------------------------------------------------------------------------
    |
    | This is the post table used by NewBranding Blog to save posts to the database.
    |
    */
    'posts_table' => 'post',


    /*
    |--------------------------------------------------------------------------
    | Blog User -> Post Table
    |--------------------------------------------------------------------------
    |
    | This is the table used by NewBranding Blog to save the relation between users and posts
    |
    */
    'user_posts' =>  'user_posts',

    /*
    |--------------------------------------------------------------------------
    | Blog Comments
    |--------------------------------------------------------------------------
    |
    | This is the table used by NewBranding Blog to save comments to the database
    |
    */
    'comments_table' =>  'comment',


    /*
    |--------------------------------------------------------------------------
    | Blog Tags Table
    |--------------------------------------------------------------------------
    |
    | This is the tag table used by NewBranding Blog to save tags to the database.
    |
    */
    'tags_table' => 'tag',

    /*
    |--------------------------------------------------------------------------
    | Blog Post_Tag Pivot Table
    |--------------------------------------------------------------------------
    |
    | This is the pivot table for posts and tags
    |
    */
    'posts_tags_pivot_table' => 'post_tag',


    /*
    |--------------------------------------------------------------------------
    | Blog Categories Table
    |--------------------------------------------------------------------------
    |
    | This is the category table used by NewBranding Blog to save categories to the database.
    |
    */
    'blog_categories_table' =>  'categories',


    /*
    |--------------------------------------------------------------------------
    | Blog Categories_Post Pivot Table
    |--------------------------------------------------------------------------
    |
    | This is the pivot table for posts and categories
    |
    */
    'posts_categories_pivot_table' => 'post_blog_categories',


     /*
    |--------------------------------------------------------------------------
    | Images Formats to make thumbnails
    |--------------------------------------------------------------------------
    |
    | This is an array to create images 
    |
    */
    'image_formats' => [
            'large' => ['w'=>1024,'h'=>null,'q'=>96, 'a' => 0],
            'thumbnail' => ['w'=>300,'h'=>null,'q'=>80, 'a' => 0],
            'square' => ['w'=>150,'h'=>150, 'q'=>99, 'a' => 1]    
        ],


    /*
    |--------------------------------------------------------------------------
    | Default Thumbnail Folder
    |--------------------------------------------------------------------------
    |
    | This is the thumbnail default folder
    |
    */
   
   'thumbnail_folder'    => '300',
];
