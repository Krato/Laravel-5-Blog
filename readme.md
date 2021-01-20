# Blog Package for Laravel 5

Custom Blog Package for Laravel 5

## Requirements


   **Dimsav: Translator**: https://github.com/dimsav/laravel-translatable
   
   **Mcamara Laravel Localization**: https://github.com/mcamara/laravel-localization
   
   **Cviebrock Eloquent-Sluggable**: https://github.com/cviebrock/eloquent-sluggable
   
   **Yajra Laravel Datatables**: http://yajra.github.io/laravel-datatables/


All the packages will be installed automatically if you don't have it.


## Instructions

Create a folder inside app called **Blog** and the create inside the folders: **Controllers** and **Models**.
Folder Structure:
```
   -app
    -Blog (You can change this name on configuration file blog.php)
        -Controllers
        -Models
```

## Publish configuration and Public Files

First, you need to publish the configuration file. This, will create a *blog.php* in your config folder. Publish the configuration with next command:
`php artisan vendor:publish`


## Creating Migrations

You will be able to create the migration file. Just call his command:
`php artisan newbranding:blog migrate`. This will call for create your custom migration file on *database/migrations/* with your options. And ask you to migrate this file.


## Creating Models

You should create the models with the following command:
`php artisan newbranding:blog models`. This will generate all your Models in the folders with your custom names.

### User Model

**Important!** You must modify your User model and copy the function created by the previus command.


## Creating Controllers

You should create the controllers with the following command:
`php artisan newbranding:blog controllers`. This will generate all the  Controllers in the folders with your custom names. We can use it inside the pacakage because you can create dynamic modules

## Creating Requests

You should create the requests files with the following command:
`php artisan newbranding:blog requests`. This will create all Requests needed for the package.


##ToDo
*Add a better readme

    
