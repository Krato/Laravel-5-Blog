<?php namespace NewBranding\Blog;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Facades\Config;
use Illuminate\Container\Container;
use DB;
use View;


class BlogMigrate extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'newbranding:blog';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Commands Tools for Blog';




    /**
     * Array for locales
     * @var array
     */
    protected $locales;


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $argument =  $this->argument();

        $no_argument = true;


        $blog_path              = Config::get('blog.blog_path');
        $locale_model           = Config::get('blog.locale_model');
        $postsTable             = Config::get('blog.posts_table');
        $userPostsTable         = Config::get('blog.user_posts');
        $commentsTable          = Config::get('blog.comments_table');
        $tagsTable              = Config::get('blog.tags_table');
        $categories             = Config::get('blog.blog_categories_table');
        $pivotTagsTable         = Config::get('blog.posts_tags_pivot_table');
        $pivotCategoriesTable   = Config::get('blog.posts_categories_pivot_table');

        if(array_search("migrate", $argument)){

            $no_argument = false;

            $this->info("Migrating DB tables");

            $message = "A migration that creates '$postsTable', '$userPostsTable', '$commentsTable',  '$tagsTable', '$categories', '$pivotTagsTable', '$pivotCategoriesTable' tables will be created in database/migrations directory";

            $this->comment($message);
            $this->line('');

            /*
            foreach ($this->paths as $path) {
                $this->comment($path);
                $this->call('migrate', [$path]);
            }
            */
           
            if ($this->confirm("Proceed with the migration creation? [Yes|no]", "Yes")) {

                $this->line('');

                $this->info("Creating migration...");
                if ($this->createMigration($postsTable, $userPostsTable, $commentsTable, $tagsTable, $categories, $pivotTagsTable, $pivotCategoriesTable)) {

                    $this->info("Blog Migration successfully created!");

                     if ($this->confirm("Would you like to migrate now? [Yes|no]", "Yes")) {
                        $this->call('migrate');

                        if ($this->confirm("Would you like to seed now? [Yes|no]", "Yes")) {

                            if ($this->createSeed($postsTable, $userPostsTable, $commentsTable, $tagsTable, $categories, $pivotTagsTable, $pivotCategoriesTable)) {
                                $this->call('db:seed --class=BlogTableSeeder');
                            } else {
                                $this->error(
                                    "Couldn't create seed file.\n Check the write permissions".
                                    " within the database/seeds directory."
                                );
                            }

                        }
                     } 
                } else {
                    $this->error(
                        "Couldn't create migration file.\n Check the write permissions".
                        " within the database/migrations directory."
                    );
                }

                $this->line('');

            }

            //$this->comment('Tables generated!');
        }


        if(array_search("seed", $argument)){

            $no_argument = false;

            if ($this->createSeed($postsTable, $userPostsTable, $commentsTable, $tagsTable, $categories, $pivotTagsTable, $pivotCategoriesTable)) {
                $this->call('db:seed --class=BlogTableSeeder');
            } else {
                $this->error(
                    "Couldn't create seed file.\n Check the write permissions".
                    " within the database/seeds directory."
                );
            }
        }

        if(array_search("models", $argument)){

            $no_argument = false;



            $this->info("Creating models");
            $this->info("");
            if ($this->createModels($blog_path, $postsTable, $userPostsTable, $commentsTable, $tagsTable, $categories, $pivotTagsTable, $pivotCategoriesTable)) {

                $this->info("Models successfully created!");
                $this->info("");
                $this->info("Plase copy this lines into User Model");


                $app_name = $this->getNamespaceForPath(app_path());

                $message = '
    /**
     * Get all posts from user [NewBranding - Blog Package]
     * @return Eloquent
     */
    public function posts(){
        return $this->hasMany("'.$app_name.$blog_path.'\Models\\'.$postsTable.'");
    }';
                $this->question($message);

                } else {
                    $this->error(
                        "Couldn't create models.\n Check the write permissions".
                        " within the app directory."
                    );
                }

        }

        if(array_search("requests", $argument)){

            $no_argument = false;

            $locales = DB::table('locales')->lists('language');

            $this->info("Creating Requests");
            $this->info("");

            if ($this->createRequests($blog_path, $postsTable, $userPostsTable, $commentsTable, $tagsTable, $categories, $pivotTagsTable, $pivotCategoriesTable, $locales, $locale_model)) {
                $this->info("Requests successfully created!");
            } else {
                $this->error(
                    "Couldn't create requests.\n Check the write permissions".
                    " within the app directory."
                );
            }
        }


        if(array_search("controllers", $argument)){

            $no_argument = false;

            $locales = DB::table('locales')->lists('language');

            if ($this->createControllers($blog_path, $postsTable, $userPostsTable, $commentsTable, $tagsTable, $categories, $pivotTagsTable, $pivotCategoriesTable, $locale_model)) {
                $this->info("Controllers successfully created!");
            } else {
                $this->error(
                    "Couldn't create controllers.\n Check the write permissions".
                    " within the app directory."
                );
            };

        }

        if(array_search("create-paths", $argument)){

            $no_argument = false;

            $blog_path = Config::get('blog.blog_path');

            if (! file_exists(base_path('app/'.$blog_path."/Models"))) {
                mkdir(base_path($blog_path."/Models"), 775, true);
            }

            if (! file_exists(base_path('app/'.$blog_path."/Controllers"))) {
                mkdir(base_path($blog_path."/Controllers"), 775, true);
            };
        }

        if($no_argument == true){
            $this->info('Please indicate an argument');
            $this->info('');
            $this->info('List of arguments:');
            $this->info('   create-paths');
            $this->info('   migrate');
            $this->info('   models');
            $this->info('   requests');
            $this->info('   controllers');
            return false;
        }

    }

    /**
     * Create the migration.
     *
     * @param string $name
     *
     * @return bool
     */
    protected function createMigration($postsTable, $userPostsTable, $commentsTable,  $tagsTable, $categories, $pivotTagsTable, $pivotCategoriesTable)
    {
        $migrationFile = base_path("/database/migrations")."/".date('Y_m_d_His')."_blog_setup_tables.php";
        
        $usersTable  = Config::get('auth.table');
       
        $data = compact('postsTable', 'userPostsTable', 'commentsTable', 'tagsTable', 'categories', 'pivotTagsTable', 'pivotCategoriesTable', 'usersTable');
        

        $this->laravel->view->addNamespace('blog', __DIR__.'/../views/');
        $output = $this->laravel->view->make('blog::blog.migration')->with($data)->render();
       

        if (!file_exists($migrationFile) && $fs = fopen($migrationFile, 'x')) {
            fwrite($fs, $output);
            fclose($fs);
            return true;
        }

        return false;
    }

    /**
     * Create the seed file.
     *
     * @param string $name
     *
     * @return bool
     */
    protected function createSeed($postsTable, $userPostsTable, $commentsTable,  $tagsTable, $categories, $pivotTagsTable, $pivotCategoriesTable)
    {
        $seedFile = base_path("/database/seeds")."/BlogTableSeeder.php";
       
        $data = compact('postsTable', 'userPostsTable', 'commentsTable', 'tagsTable', 'categories', 'pivotTagsTable', 'pivotCategoriesTable');

        $this->laravel->view->addNamespace('blog', __DIR__.'/../views/');
        $output = $this->laravel->view->make('blog::blog.seed')->with($data)->render();
       

        if (!file_exists($seedFile) && $fs = fopen($seedFile, 'x')) {
            fwrite($fs, $output);
            fclose($fs);
            return true;
        }

        return false;
    } 

    /**
     * Create the models.
     *
     * @param string $name
     *
     * @return bool
     */
    protected function createModels($blog_path, $postsTable, $userPostsTable, $commentsTable,  $tagsTable, $categories, $pivotTagsTable, $pivotCategoriesTable)
    {
        $error = array();

        //Get the Package Views Path
        $this->laravel->view->addNamespace('blog', __DIR__.'/../views/');

        
        $path = base_path('app/'.$blog_path);
        $modelFile = $path."/Models/".ucfirst($postsTable).".php";
        $modelTranslationFile = $path."/Models/".ucfirst($postsTable)."Translation.php";
        $modelcommentsFile = $path."/Models/".ucfirst($commentsTable).".php";
        $modelTagsFile = $path."/Models/".ucfirst($tagsTable).".php";
        $modelCategoriesFile = $path."/Models/".ucfirst($categories).".php";
        $modelCategoriesTranslationFile = $path."/Models/".ucfirst($categories)."Translation.php";

        $app_name = $this->getNamespaceForPath(app_path());
        $usersModel = Config::get('auth.model');


        //Create the Post Model
        $data = compact('blog_path', 'postsTable', 'usersModel', 'userPostsTable', 'commentsTable', 'tagsTable', 'categories', 'pivotTagsTable', 'pivotCategoriesTable', 'usersTable', 'app_name');
        $output = $this->laravel->view->make('blog::blog.postmodel')->with($data)->render();
        if (!file_exists($modelFile) && $fs = fopen($modelFile, 'x')) {
            fwrite($fs, $output);
            fclose($fs);
            $this->info("Model ".$postsTable." created!");
        } else {
            array_push($error, 'Error creating post model');
        }

        //Create the Post Translation Model
        $data = array('translationTable' => $postsTable, 'blog_path' => $blog_path, 'app_name' => $app_name);
        $output = $this->laravel->view->make('blog::blog.translationpostmodel')->with($data)->render();
        if (!file_exists($modelTranslationFile) && $fs = fopen($modelTranslationFile, 'x')) {
            fwrite($fs, $output);
            fclose($fs);
             $this->info("Model ".$postsTable."Translation created!");      
        } else {
            array_push($error, 'Error creating Post translation model');
        }

        //Create the Comments Model
        $data = compact('blog_path','postsTable', 'usersModel', 'commentsTable',  'app_name');
        $output = $this->laravel->view->make('blog::blog.commentsmodel')->with($data)->render();
        if (!file_exists($modelcommentsFile) && $fs = fopen($modelcommentsFile, 'x')) {
            fwrite($fs, $output);
            fclose($fs);  
            $this->info("Model ".$commentsTable." created!");          
        } else {
            array_push($error, 'Error creating Comments model');
        }

        //Create the Tags Model
        $data = compact('blog_path','postsTable', 'tagsTable', 'pivotTagsTable',  'app_name');
        $output = $this->laravel->view->make('blog::blog.tagsmodel')->with($data)->render();
        if (!file_exists($modelTagsFile) && $fs = fopen($modelTagsFile, 'x')) {
            fwrite($fs, $output);
            fclose($fs);
            $this->info("Model ".$tagsTable." created!");
        } else {
            array_push($error, 'Error creating Tags model');
        }

        //Create the Categories Model
        $data = compact('blog_path','postsTable', 'categories', 'pivotCategoriesTable',  'app_name');
        $output = $this->laravel->view->make('blog::blog.categoriesmodel')->with($data)->render();
        if (!file_exists($modelCategoriesFile) && $fs = fopen($modelCategoriesFile, 'x')) {
            fwrite($fs, $output);
            fclose($fs);
            $this->info("Model ".$categories." created!");
        } else {
            array_push($error, 'Error creating Tags model');
        }

        //Create the Categories Translation Model
        $data = array('translationTable' => $categories, 'blog_path' => $blog_path, 'app_name' => $app_name);
        $output = $this->laravel->view->make('blog::blog.translationmodel')->with($data)->render();
        if (!file_exists($modelCategoriesTranslationFile) && $fs = fopen($modelCategoriesTranslationFile, 'x')) {
            fwrite($fs, $output);
            fclose($fs);
            $this->info("Model ".$categories."Translation created!");
        } else {
            array_push($error, 'Error creating Tags model');
        }

        if(count($error) > 0){
            foreach ($error as $key => $message) {
                $this->error($message);
            }
            return false;
        } else {
            return true;
        }
    }

    /**
     * Create the Requests.
     *
     * @param string $name
     *
     * @return bool
     */
    protected function createRequests($blog_path, $postsTable, $userPostsTable, $commentsTable,  $tagsTable, $categories, $pivotTagsTable, $pivotCategoriesTable, $locales,  $locale_model)
    {

        $error = array();

        //Get the Package Views Path
        $this->laravel->view->addNamespace('blog', __DIR__.'/../views/');

        $app_name = $this->getNamespaceForPath(app_path());
         
        $path = base_path('app/'.$blog_path);
       
        $postRequestFile = $path."/Requests/".ucfirst($postsTable)."Request.php";
        $postUpdateRequestFile = $path."/Requests/".ucfirst($postsTable)."UpdateRequest.php";
        $commentsRequestFile = $path."/Requests/".ucfirst($commentsTable)."Request.php";
        $tagsRequestFile = $path."/Requests/".ucfirst($tagsTable)."Request.php";
        $categoriesRequestFile = $path."/Requests/".ucfirst($categories)."Request.php";

        

        //Create the Post Requests
        $data = compact('blog_path', 'postsTable', 'app_name', 'locales');

        $output = $this->laravel->view->make('blog::blog.postRequest')->with($data)->render();
        if (!file_exists($postRequestFile) && $fs = fopen($postRequestFile, 'x')) {
            fwrite($fs, $output);
            fclose($fs);
            $this->info("Request ".$postsTable." created!");
        } else {
            array_push($error, 'Error creating '.$postsTable.' Request');
        }

        //Create the Post Update Requests
        $data = compact('blog_path', 'postsTable', 'app_name', 'locales');

        $output = $this->laravel->view->make('blog::blog.postUpdateRequest')->with($data)->render();
        if (!file_exists($postUpdateRequestFile) && $fs = fopen($postUpdateRequestFile, 'x')) {
            fwrite($fs, $output);
            fclose($fs);
            $this->info("Request ".$postsTable."Update created!");
        } else {
            array_push($error, 'Error creating '.$postsTable.'Update Request');
        }

        //Create the Comment Requests
        $data = compact('blog_path', 'commentsTable', 'app_name');
        $output = $this->laravel->view->make('blog::blog.commentsRequest')->with($data)->render();
        if (!file_exists($commentsRequestFile) && $fs = fopen($commentsRequestFile, 'x')) {
            fwrite($fs, $output);
            fclose($fs);
            $this->info("Request ".$postsTable." created!");
        } else {
            array_push($error, 'Error creating '.$commentsTable.' Request');
        }

        //Create the Tag Requests
        $data = compact('blog_path', 'tagsTable', 'app_name');
        $output = $this->laravel->view->make('blog::blog.tagsRequest')->with($data)->render();
        if (!file_exists($tagsRequestFile) && $fs = fopen($tagsRequestFile, 'x')) {
            fwrite($fs, $output);
            fclose($fs);
            $this->info("Request ".$postsTable." created!");
        } else {
            array_push($error, 'Error creating '.$tagsRequestFile.' Request');
        }

        //Create the Categories Requests
        $data = compact('blog_path', 'categories', 'app_name', 'locales', 'locale_model');
        $output = $this->laravel->view->make('blog::blog.categoriesRequest')->with($data)->render();
        if (!file_exists($categoriesRequestFile) && $fs = fopen($categoriesRequestFile, 'x')) {
            fwrite($fs, $output);
            fclose($fs);
            $this->info("Request ".$postsTable." created!");
        } else {
            array_push($error, 'Error creating '.$categoriesRequestFile.' Request');
        }

        if(count($error) > 0){
            foreach ($error as $key => $message) {
                $this->error($message);
            }
            return false;
        } else {
            return true;
        }


    }

    protected function createControllers($blog_path, $postsTable, $userPostsTable, $commentsTable,  $tagsTable, $categories, $pivotTagsTable, $pivotCategoriesTable, $locale_model)
    {

        $error = array();

        //Get the Package Views Path
        $this->laravel->view->addNamespace('blog', __DIR__.'/../views/');

        $app_name = $this->getNamespaceForPath(app_path());
        $usersModel = Config::get('auth.model');
        $usersTable  = Config::get('auth.table');
         
        $path = base_path('app/'.$blog_path);
        $postControllerFile = $path."/Controllers/".ucfirst($postsTable)."Controller.php";
        $categoriesControllerFile = $path."/Controllers/".ucfirst($categories)."Controller.php";
        //Create the Post Requests
        $data = compact('blog_path', 'postsTable', 'usersModel', 'usersTable', 'userPostsTable', 'commentsTable', 'tagsTable', 'categories', 'pivotTagsTable', 'pivotCategoriesTable', 'usersTable', 'app_name', 'locale_model');

        $output = $this->laravel->view->make('blog::blog.postController')->with($data)->render();
        if (!file_exists($postControllerFile) && $fs = fopen($postControllerFile, 'x')) {
            fwrite($fs, $output);
            fclose($fs);
            $this->info("Controller ".$postsTable." created!");
        } else {
            array_push($error, 'Error creating '.$postsTable.' Controller');
        }


        $output = $this->laravel->view->make('blog::blog.categoriesController')->with($data)->render();
        if (!file_exists($categoriesControllerFile) && $fs = fopen($categoriesControllerFile, 'x')) {
            fwrite($fs, $output);
            fclose($fs);
            $this->info("Controller ".$categories." created!");
        } else {
            array_push($error, 'Error creating '.$categories.' Controller');
        }

        if(count($error) > 0){
            foreach ($error as $key => $message) {
                $this->error($message);
            }
            return false;
        } else {
            return true;
        }

    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['migrate', null, InputOption::VALUE_OPTIONAL, 'Migrate the database tables.', null],
        ];
    }

    /**
     * Function to get App Name
     * @return text
     */
    private function getNamespaceForPath($searchForPath)
    {
        $composer = json_decode(file_get_contents(base_path().'/composer.json'), true);
        foreach ((array) data_get($composer, 'autoload.psr-4') as $namespace => $path)
        {
            foreach ((array) $path as $pathChoice)
            {
                if (realpath($searchForPath) == realpath(base_path().'/'.$pathChoice)) return $namespace;
            }
        }
        throw new \RuntimeException("Unable to detect application namespace.");
    }


}
